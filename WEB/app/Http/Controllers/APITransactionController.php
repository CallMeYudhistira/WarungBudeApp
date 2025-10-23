<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Credit;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\RefillStock;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class APITransactionController extends Controller
{
    public function show_products(Request $request)
    {
        $user_id = $request->user()->user_id;
        $products = Product::join('categories', 'products.category_id', '=', 'categories.category_id')->join('product_details', 'product_details.product_id', '=', 'products.product_id')->join('units', 'units.unit_id', '=', 'product_details.unit_id')->where('stock', '>', '0')->where('product_details.deleted_at', '=', NULL)->where('products.product_name', 'LIKE', '%' . $request->product_name . '%')->select('product_details.product_detail_id', 'products.product_name', 'products.pict', 'categories.category_name', 'units.unit_name', 'product_details.purchase_price', 'product_details.selling_price', 'product_details.stock')->get();
        $cart = Cart::where('user_id', $user_id)->count();

        return response()->json(['status' => 'success', 'products' => $products, 'cart' => $cart], 200);
    }

    public function show_carts(Request $request)
    {
        $user_id = $request->user()->user_id;
        $carts = Cart::join('product_details', 'product_details.product_detail_id', '=', 'carts.product_detail_id')->join('products', 'product_details.product_id', '=', 'products.product_id')->join('categories', 'products.category_id', '=', 'categories.category_id')->join('units', 'units.unit_id', '=', 'product_details.unit_id')->join('users', 'users.user_id', '=', 'carts.user_id')->where('carts.user_id', $user_id)->select('carts.*', 'products.product_name', 'products.pict', 'categories.category_name', 'units.unit_name', 'users.name', 'product_details.stock', DB::raw('(SELECT SUM(subtotal) FROM carts WHERE user_id = ' . $user_id . ') AS total'))->get();
        $customers = Customer::get(['customer_id', 'customer_name']);

        return response()->json(['status' => 'success', 'carts' => $carts, 'customers' => $customers], 200);
    }

    public function cartStore(Request $request)
    {
        $user_id = $request->user()->user_id;
        $product = ProductDetail::find($request->product_detail_id);
        $cekCart = Cart::where('product_detail_id', $request->product_detail_id)->first();

        $validation = Validator::make($request->all(), [
            'product_detail_id' => 'required',
            'quantity' => 'numeric|required|max:' . $product->stock,
            'selling_price' => 'required',
            'purchase_price' => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json(['status' => 'error', 'message' => $validation->errors()->all()], 400);
        }

        if ($cekCart || $cekCart != null) {
            $newQuantity = $cekCart->quantity + $request->quantity;
            if ($newQuantity > $product->stock) {
                Cart::where('product_detail_id', $request->product_detail_id)->update([
                    'quantity' => $product->stock,
                    'subtotal' => ($product->stock * $request->selling_price),
                ]);
                return response()->json(['status' => 'error', 'message' => 'Kuantitas sudah maksimal!'], 400);
            }
            Cart::where('product_detail_id', $request->product_detail_id)->update([
                'quantity' => $newQuantity,
                'subtotal' => ($newQuantity * $request->selling_price),
            ]);
            return response()->json(['status' => 'success', 'message' => 'Kuantitas bertambah +' . $request->quantity], 200);
        }

        Cart::create([
            'product_detail_id' => $request->product_detail_id,
            'purchase_price' => $request->purchase_price,
            'selling_price' => $request->selling_price,
            'quantity' => $request->quantity,
            'subtotal' => ($request->quantity * $request->selling_price),
            'user_id' => $user_id,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Produk ditambahkan ke keranjang'], 201);
    }

    public function cartPlus(Request $request)
    {
        $id = $request->cart_id;
        $cart = Cart::where('cart_id', $id)->first();
        $stock = ProductDetail::where('product_detail_id', $cart->product_detail_id)->first()->stock;

        if ($cart->quantity < $stock) {
            $cart->update([
                'quantity' => $cart->quantity + 1,
                'subtotal' => ($cart->quantity + 1) * $cart->selling_price,
            ]);

            return response()->json(['status' => 'success', 'message' => 'Kuantitas +1'], 200);
        } else {
            return response()->json(['status' => 'success', 'message' => null], 200);
        }
    }

    public function cartMinus(Request $request)
    {
        $id = $request->cart_id;
        $cart = Cart::where('cart_id', $id)->first();

        if ($cart->quantity > 1) {
            $cart->update([
                'quantity' => $cart->quantity - 1,
                'subtotal' => ($cart->quantity - 1) * $cart->selling_price,
            ]);

            return response()->json(['status' => 'success', 'message' => 'Kuantitas -1'], 200);
        } else {
            return response()->json(['status' => 'success', 'message' => null], 200);
        }
    }

    public function cartDelete(Request $request)
    {
        $id = $request->cart_id;
        Cart::where('cart_id', $id)->delete();

        return response()->json(['status' => 'deleted', 'message' => 'Produk dihapus dari keranjang'], 200);
    }

    public function transactionStore(Request $request)
    {
        $user_id = $request->user()->user_id;
        $validator = Validator::make($request->all(), [
            'total' => 'required',
            'change' => 'required',
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->total == 0 || $request->total < 0) {
                $validator->errors()->add('total', 'Keranjang masih kosong!.');
            }
            if ($request->pay < 0) {
                $validator->errors()->add('pay', 'Pembayaran minus!.');
            }

            if ($request->pay < 0 && !$request->customer_name) {
                $validator->errors()->add('payment', 'Nama pelanggan tidak boleh kosong');
            }

            if ($request->payment == 'kredit' && !$request->customer_name) {
                $validator->errors()->add('payment', 'Nama pelanggan tidak boleh kosong');
            }
        });

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);
        }

        if ($request->payment !== 'tunai' && $request->payment !== 'kredit') {
            $validator->errors()->add('payment', 'Metode pembayaran tidak valid!');

            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);
        } else {
            Transaction::create([
                'date' => now(),
                'total' => $request->total,
                'pay' => $request->pay,
                'change' => $request->change,
                'payment' => $request->payment,
                'user_id' => $user_id,
            ]);
        }

        $newTransaction = Transaction::latest()->first();
        $transaction_id = $newTransaction->transaction_id;

        if ($request->payment === 'kredit') {
            $customer = Customer::where('customer_name', $request->customer_name)->first();
            $total_of_debt = $request->change * -1;

            if ($customer && $customer !== null) {
                $total_of_debt = $customer->amount_of_debt + $total_of_debt;

                Customer::where('customer_id', $customer->customer_id)->update([
                    'amount_of_debt' => $total_of_debt,
                    'status' => 'belum lunas',
                ]);
            } else {
                Customer::create([
                    'customer_name' => $request->customer_name,
                    'amount_of_debt' => $total_of_debt,
                    'status' => 'belum lunas',
                ]);

                $customer = Customer::latest()->first();
            }

            Credit::create([
                'transaction_id' => $transaction_id,
                'customer_id' => $customer->customer_id,
                'total' => $total_of_debt,
            ]);
        }

        $carts = Cart::all();

        foreach ($carts as $cart) {
            TransactionDetail::create([
                'transaction_id' => $transaction_id,
                'product_detail_id' => $cart->product_detail_id,
                'purchase_price' => $cart->purchase_price,
                'selling_price' => $cart->selling_price,
                'quantity' => $cart->quantity,
                'subtotal' => $cart->subtotal,
            ]);

            $refill = RefillStock::where('product_detail_id', $cart->product_detail_id)->where('status', 'baik')->where('updated_stock', '>', '0')->orderBy('expired_date', 'asc')->first();
            $refill->update([
                'updated_stock' => $refill->updated_stock - $cart->quantity,
            ]);

            DB::statement("UPDATE product_details SET stock = (SELECT SUM(updated_stock) FROM refill_stocks WHERE product_detail_id = '$cart->product_detail_id') WHERE product_detail_id = '$cart->product_detail_id'");
        }

        DB::statement('DELETE FROM carts WHERE user_id = ' . $user_id);

        return response()->json(['status' => 'success', 'message' => 'Transaksi Berhasil!'], 201);
    }

    public function invoice(Request $request)
    {
        $user_id = $request->user()->user_id;
        $id = Transaction::where('user_id', $user_id)->latest()->first()->transaction_id;
        $transaction = Transaction::join('users', 'users.user_id', '=', 'transactions.user_id', 'inner')->join('credits', 'transactions.transaction_id', '=', 'credits.transaction_id', 'left')->join('customers', 'customers.customer_id', '=', 'credits.customer_id', 'left')->where('transactions.transaction_id', $id)->first(['transactions.date', 'transactions.payment', 'users.name', 'customers.customer_name', 'transactions.total', 'transactions.pay', 'transactions.change']);
        $transaction_details = Transaction::join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.transaction_id')->join('product_details', 'product_details.product_detail_id', '=', 'transaction_details.product_detail_id')->join('products', 'product_details.product_id', '=', 'products.product_id')->join('categories', 'products.category_id', '=', 'categories.category_id')->join('units', 'units.unit_id', 'product_details.unit_id')->where('transaction_details.transaction_id', $id)->get(['products.product_name', 'transaction_details.selling_price', 'transaction_details.quantity', 'transaction_details.subtotal']);

        return response()->json(['status' => 'success', 'message' => 'Detail Transaksi Berhasil Dikirim!', 'transaction' => $transaction, 'transaction_details' => $transaction_details, 'transaction_id' => $id], 200);
    }
}