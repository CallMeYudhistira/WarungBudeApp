<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function index()
    {
        $products = Product::join('categories', 'products.category_id', '=', 'categories.category_id')->join('product_details', 'product_details.product_id', '=', 'products.product_id')->join('units', 'units.unit_id', '=', 'product_details.unit_id')->where('stock', '>', '0')->simplePaginate(4);
        $carts = Cart::join('product_details', 'product_details.product_detail_id', '=', 'carts.product_detail_id')->join('products', 'product_details.product_id', '=', 'products.product_id')->join('categories', 'products.category_id', '=', 'categories.category_id')->join('users', 'users.user_id', '=', 'carts.user_id')->get();

        return view('transaksi.index', compact('products', 'carts'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        if ($keyword === "" || $keyword == null) {
            return redirect('/transaksi');
        }

        $products = Product::join('categories', 'products.category_id', '=', 'categories.category_id')->join('product_details', 'product_details.product_id', '=', 'products.product_id')->join('units', 'units.unit_id', '=', 'product_details.unit_id')->where('product_name', 'like', '%' . $keyword . '%')->where('stock', '>', '0')->simplePaginate(4);
        $carts = Cart::join('product_details', 'product_details.product_detail_id', '=', 'carts.product_detail_id')->join('products', 'product_details.product_id', '=', 'products.product_id')->join('categories', 'products.category_id', '=', 'categories.category_id')->join('users', 'users.user_id', '=', 'carts.user_id')->get();

        return view('transaksi.index', compact('products', 'keyword', 'carts'));
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

        Transaction::create([
            'date' => now(),
            'total' => $request->total,
            'pay' => $request->pay,
            'change' => $request->change,
            'user_id' => Auth::user()->user_id,
        ]);

        $newTransaction = Transaction::latest()->first();
        $transaction_id = $newTransaction->transaction_id;

        if ($request->payment === 'kredit') {
            $validator->after(function ($validator) use ($request) {
                if (!$request->pay || $request->pay == 0 || ($request->pay - $request->total) < 0) {
                    $validator->errors()->add('pay', 'Uang pembayaran kurang dan tidak boleh 0.');
                }
            });

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }

        $carts = Cart::all();

        foreach ($carts as $cart) {
            TransactionDetail::create([
                'transaction_id' => $transaction_id,
                'product_id' => $cart->product_id,
                'selling_price' => $cart->selling_price,
                'quantity' => $cart->quantity,
                'subtotal' => $cart->subtotal,
            ]);

            $product = Product::find($cart->product_id);

            Product::where('product_id', $cart->product_id)->update([
                'stock' => $product->stock - $cart->quantity,
            ]);
        }

        DB::statement('DELETE FROM carts WHERE user_id = ' . Auth::user()->user_id);

        return redirect('/transaksi')->with('success', 'Terimakasih Telah Berbelanja!');
    }
}
