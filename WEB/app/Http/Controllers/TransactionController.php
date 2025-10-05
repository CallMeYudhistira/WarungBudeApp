<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Credit;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function index()
    {
        $products = Product::join('categories', 'products.category_id', '=', 'categories.category_id')->join('product_details', 'product_details.product_id', '=', 'products.product_id')->join('units', 'units.unit_id', '=', 'product_details.unit_id')->where('stock', '>', '0')->where('product_details.deleted_at', '=', NULL)->simplePaginate(5);
        $carts = Cart::join('product_details', 'product_details.product_detail_id', '=', 'carts.product_detail_id')->join('products', 'product_details.product_id', '=', 'products.product_id')->join('categories', 'products.category_id', '=', 'categories.category_id')->join('units', 'units.unit_id', '=', 'product_details.unit_id')->join('users', 'users.user_id', '=', 'carts.user_id')->where('carts.user_id', Auth::user()->user_id)->get(['carts.*', 'products.product_name', 'products.pict', 'categories.category_name', 'units.unit_name', 'users.name']);
        $customers = Customer::all();

        return view('transaksi.index', compact('products', 'carts', 'customers'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        if ($keyword === "" || $keyword == null) {
            return redirect('/transaksi');
        }

        $products = Product::join('categories', 'products.category_id', '=', 'categories.category_id')->join('product_details', 'product_details.product_id', '=', 'products.product_id')->join('units', 'units.unit_id', '=', 'product_details.unit_id')->where('product_name', 'like', '%' . $keyword . '%')->where('stock', '>', '0')->where('product_details.deleted_at', '=', NULL)->simplePaginate(5);
        $carts = Cart::join('product_details', 'product_details.product_detail_id', '=', 'carts.product_detail_id')->join('products', 'product_details.product_id', '=', 'products.product_id')->join('categories', 'products.category_id', '=', 'categories.category_id')->join('units', 'units.unit_id', '=', 'product_details.unit_id')->join('users', 'users.user_id', '=', 'carts.user_id')->where('carts.user_id', Auth::user()->user_id)->get(['carts.*', 'products.product_name', 'products.pict', 'categories.category_name', 'units.unit_name', 'users.name']);
        $customers = Customer::all();

        return view('transaksi.index', compact('products', 'keyword', 'carts', 'customers'));
    }

    public function cartStore(Request $request)
    {
        $product = ProductDetail::find($request->id);

        $request->validate([
            'id' => 'required',
            'quantity' => 'numeric|required|max:' . $product->stock,
            'selling_price' => 'required',
        ]);

        $cekCart = Cart::where('product_detail_id', $request->id)->first();

        if (!$cekCart || $cekCart != null) {
            Cart::where('product_detail_id', $request->id)->delete();
        }

        Cart::create([
            'product_detail_id' => $request->id,
            'selling_price' => $request->selling_price,
            'quantity' => $request->quantity,
            'subtotal' => ($request->quantity * $request->selling_price),
            'user_id' => Auth::user()->user_id,
        ]);

        return redirect('/transaksi');
    }

    public function cartDelete($id)
    {
        Cart::where('cart_id', $id)->delete();

        return redirect('/transaksi');
    }

    public function transactionStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'total' => 'required',
            'change' => 'required',
        ]);

        $validator->after(function ($validator) use ($request) {
            if (!$request->payment || $request->payment == null) {
                $validator->errors()->add('payment', 'Metode pembayaran tidak valid!');
            }
            if ($request->total == 0 || $request->total < 0) {
                $validator->errors()->add('total', 'Keranjang masih kosong!.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->payment === 'tunai') {
            $validator->after(function ($validator) use ($request) {
                if (!$request->pay || $request->pay == 0 || ($request->pay - $request->total) < 0) {
                    $validator->errors()->add('pay', 'Uang pembayaran kurang dan tidak boleh 0.');
                }
            });

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }

        if ($request->payment !== 'tunai' && $request->payment !== 'kredit') {
            $validator->errors()->add('payment', 'Metode pembayaran tidak valid!');

            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            Transaction::create([
                'date' => now(),
                'total' => $request->total,
                'pay' => $request->pay,
                'change' => $request->change,
                'payment' => $request->payment,
                'user_id' => Auth::user()->user_id,
            ]);
        }

        $newTransaction = Transaction::latest()->first();
        $transaction_id = $newTransaction->transaction_id;

        if ($request->payment === 'kredit') {
            $customer = Customer::where('customer_name', $request->customer_name)->first();
            $total_of_debt = $request->total;

            if ($customer && $customer !== null) {
                $total_of_debt = $customer->amount_of_debt + $request->total;

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
                'selling_price' => $cart->selling_price,
                'quantity' => $cart->quantity,
                'subtotal' => $cart->subtotal,
            ]);

            $product = ProductDetail::find($cart->product_detail_id);

            ProductDetail::where('product_detail_id', $cart->product_detail_id)->update([
                'stock' => $product->stock - $cart->quantity,
            ]);
        }

        DB::statement('DELETE FROM carts WHERE user_id = ' . Auth::user()->user_id);

        return redirect('/transaksi')->with('success', 'Terimakasih Telah Berbelanja!');
    }

    public function history()
    {
        $transactions = Transaction::join('users', 'users.user_id', '=', 'transactions.user_id', 'inner')->join('credits', 'transactions.transaction_id', '=', 'credits.transaction_id', 'left')->join('customers', 'customers.customer_id', '=', 'credits.customer_id', 'left')->orderBy('transactions.created_at', 'desc')->get(['transactions.transaction_id', 'transactions.date', 'transactions.total', 'transactions.pay', 'transactions.change', 'transactions.payment', 'users.name', 'customers.customer_name']);

        return view('transaksi.history', compact('transactions'));
    }

    public function filter(Request $request)
    {
        $first = $request->first;
        $second = $request->second;

        if (!$first && !$second) {
            return redirect('/transaksi/history');
        }

        $transactions = Transaction::join('users', 'users.user_id', '=', 'transactions.user_id', 'inner')->join('credits', 'transactions.transaction_id', '=', 'credits.transaction_id', 'left')->join('customers', 'customers.customer_id', '=', 'credits.customer_id', 'left')->whereBetween('date', [$first, $second])->orderBy('transactions.created_at', 'desc')->get(['transactions.transaction_id', 'transactions.date', 'transactions.total', 'transactions.pay', 'transactions.change', 'transactions.payment', 'users.name', 'customers.customer_name']);

        return view('transaksi.history', compact('transactions', 'first', 'second'));
    }

    public function detail($id)
    {
        Carbon::setLocale('id');
        $transaction = Transaction::join('users', 'users.user_id', '=', 'transactions.user_id', 'inner')->join('credits', 'transactions.transaction_id', '=', 'credits.transaction_id', 'left')->join('customers', 'customers.customer_id', '=', 'credits.customer_id', 'left')->where('transactions.transaction_id', $id)->first(['transactions.transaction_id', 'transactions.date', 'transactions.payment', 'users.name', 'customers.customer_name', 'transactions.total', 'transactions.pay', 'transactions.change']);
        $transaction_details = Transaction::join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.transaction_id')->join('product_details', 'product_details.product_detail_id', '=', 'transaction_details.product_detail_id')->join('products', 'product_details.product_id', '=', 'products.product_id')->join('categories', 'products.category_id', '=', 'categories.category_id')->join('units', 'units.unit_id', 'product_details.unit_id')->where('transaction_details.transaction_id', $id)->get(['products.product_name', 'products.pict', 'categories.category_name', 'units.unit_name', 'transaction_details.selling_price', 'transaction_details.quantity', 'transaction_details.subtotal']);

        return view('transaksi.detail', compact('transaction', 'transaction_details'));
    }

    public function print($id)
    {
        Carbon::setLocale('id');
        $transaction = Transaction::join('users', 'users.user_id', '=', 'transactions.user_id', 'inner')->join('credits', 'transactions.transaction_id', '=', 'credits.transaction_id', 'left')->join('customers', 'customers.customer_id', '=', 'credits.customer_id', 'left')->where('transactions.transaction_id', $id)->first(['transactions.transaction_id', 'transactions.date', 'transactions.payment', 'users.name', 'customers.customer_name', 'transactions.total', 'transactions.pay', 'transactions.change']);
        $transaction_details = Transaction::join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.transaction_id')->join('product_details', 'product_details.product_detail_id', '=', 'transaction_details.product_detail_id')->join('products', 'product_details.product_id', '=', 'products.product_id')->join('categories', 'products.category_id', '=', 'categories.category_id')->join('units', 'units.unit_id', 'product_details.unit_id')->where('transaction_details.transaction_id', $id)->get(['products.product_name', 'products.pict', 'categories.category_name', 'units.unit_name', 'transaction_details.selling_price', 'transaction_details.quantity', 'transaction_details.subtotal']);

        $pdf = Pdf::loadView('transaksi.print.detail', compact('transaction', 'transaction_details'))->setPaper('a4', 'landscape');
        return $pdf->stream($transaction->transaction_id . '_' . $transaction->customer_name . '_' . $transaction->date . '_print.pdf');
    }
}
