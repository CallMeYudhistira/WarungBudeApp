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
        $products = Product::join('categories', 'products.category_id', '=', 'categories.category_id')->join('product_details', 'product_details.product_id', '=', 'products.product_id')->join('units', 'units.unit_id', '=', 'product_details.unit_id')->join('refill_stocks', 'refill_stocks.product_detail_id', '=', 'product_details.product_detail_id')->where('refill_stocks.status', 'baik')->where('stock', '>', '0')->where('product_details.deleted_at', '=', NULL)->simplePaginate(5);
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

        $products = Product::join('categories', 'products.category_id', '=', 'categories.category_id')->join('product_details', 'product_details.product_id', '=', 'products.product_id')->join('units', 'units.unit_id', '=', 'product_details.unit_id')->join('refill_stocks', 'refill_stocks.product_detail_id', '=', 'product_details.product_detail_id')->where('refill_stocks.status', 'baik')->where('product_name', 'like', '%' . $keyword . '%')->where('stock', '>', '0')->where('product_details.deleted_at', '=', NULL)->simplePaginate(5);
        $carts = Cart::join('product_details', 'product_details.product_detail_id', '=', 'carts.product_detail_id')->join('products', 'product_details.product_id', '=', 'products.product_id')->join('categories', 'products.category_id', '=', 'categories.category_id')->join('units', 'units.unit_id', '=', 'product_details.unit_id')->join('users', 'users.user_id', '=', 'carts.user_id')->where('carts.user_id', Auth::user()->user_id)->get(['carts.*', 'products.product_name', 'products.pict', 'categories.category_name', 'units.unit_name', 'users.name']);
        $customers = Customer::all();

        return view('transaksi.index', compact('products', 'keyword', 'carts', 'customers'));
    }

    public function cartStore(Request $request)
    {
        $product = ProductDetail::find($request->id);
        $cekCart = Cart::where('product_detail_id', $request->id)->where('user_id', Auth::user()->user_id)->first();

        $request->validate([
            'id' => 'required',
            'quantity' => 'numeric|required|max:' . $product->stock,
            'selling_price' => 'required',
            'purchase_price' => 'required',
        ]);

        if ($cekCart || $cekCart != null) {
            $newQuantity = $cekCart->quantity + $request->quantity;
            if ($newQuantity > $product->stock) {
                Cart::where('product_detail_id', $request->id)->update([
                    'quantity' => $product->stock,
                    'subtotal' => ($product->stock * $request->selling_price),
                ]);

                return redirect('/transaksi#cart');
            }
            Cart::where('product_detail_id', $request->id)->update([
                'quantity' => $newQuantity,
                'subtotal' => ($newQuantity * $request->selling_price),
            ]);
            return redirect('/transaksi#cart');
        }

        Cart::create([
            'product_detail_id' => $request->id,
            'purchase_price' => $request->purchase_price,
            'selling_price' => $request->selling_price,
            'quantity' => $request->quantity,
            'subtotal' => ($request->quantity * $request->selling_price),
            'user_id' => Auth::user()->user_id,
        ]);

        return redirect('/transaksi#cart');
    }

    public function cartPlus($id)
    {
        $cart = Cart::where('cart_id', $id)->first();
        $stock = ProductDetail::where('product_detail_id', $cart->product_detail_id)->first()->stock;

        if (($cart->quantity + 1) > $stock) {
            return redirect('/transaksi#cart');
        }

        $cart->update([
            'quantity' => $cart->quantity + 1,
            'subtotal' => ($cart->quantity + 1) * $cart->selling_price,
        ]);

        return redirect('/transaksi#cart');
    }

    public function cartMinus($id)
    {
        $cart = Cart::where('cart_id', $id)->first();
        $cart->update([
            'quantity' => $cart->quantity - 1,
            'subtotal' => ($cart->quantity - 1) * $cart->selling_price,
        ]);

        return redirect('/transaksi#cart');
    }

    public function cartDelete($id)
    {
        Cart::where('cart_id', $id)->delete();

        return redirect('/transaksi#cart');
    }

    public function transactionStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'total' => 'required',
            'change' => 'required',
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->total == 0 || $request->total < 0) {
                $validator->errors()->add('total', 'Keranjang masih kosong!.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->pay < 0) {
            $validator->errors()->add('pay', 'Pembayaran minus!.');

            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->payment == 'kredit') {
            $error = 0;
            if (!$request->customer_name) {
                $validator->errors()->add('payment', 'Nama pelanggan tidak boleh kosong!');
                $error++;
            }
            if (!$request->address) {
                $validator->errors()->add('payment', 'Alamat pelanggan tidak boleh kosong!');
                $error++;
            }
            if (!$request->phone_number) {
                $validator->errors()->add('payment', 'Nomor telepon pelanggan tidak boleh kosong!');
                $error++;
            }

            if ($error > 0) {
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
                    'phone_number' => $request->phone_number,
                    'address' => $request->address,
                    'amount_of_debt' => $total_of_debt,
                    'status' => 'belum lunas',
                ]);

                $customer = Customer::latest()->first();
            }

            Credit::create([
                'transaction_id' => $transaction_id,
                'customer_id' => $customer->customer_id,
                'total' => $request->change * -1,
            ]);
        }

        $carts = Cart::where('user_id', Auth::user()->user_id)->get();

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

        DB::statement('DELETE FROM carts WHERE user_id = ' . Auth::user()->user_id);

        return redirect('/transaksi')->with('id', $transaction_id);
    }

    public function history()
    {
        $transactions = Transaction::join('users', 'users.user_id', '=', 'transactions.user_id', 'inner')->join('credits', 'transactions.transaction_id', '=', 'credits.transaction_id', 'left')->join('customers', 'customers.customer_id', '=', 'credits.customer_id', 'left')->orderBy('transactions.created_at', 'desc')->get(['transactions.transaction_id', 'transactions.date', 'transactions.total', 'transactions.pay', 'transactions.change', 'transactions.payment', 'users.name', 'customers.customer_name']);

        return view('transaksi.history', compact('transactions'));
    }

    public function exportExcelHistory(Request $request)
    {
        $fileName = 'transaksi_' . date('Y-m-d_H-i-s') . '.xls';
        $first = $request->first;
        $second = $request->second;

        if (!$first && !$second) {
            $transactions = Transaction::join('users', 'users.user_id', '=', 'transactions.user_id', 'inner')->join('credits', 'transactions.transaction_id', '=', 'credits.transaction_id', 'left')->join('customers', 'customers.customer_id', '=', 'credits.customer_id', 'left')->orderBy('transactions.created_at', 'desc')->get(['transactions.transaction_id', 'transactions.date', 'transactions.total', 'transactions.pay', 'transactions.change', 'transactions.payment', 'users.name', 'customers.customer_name']);
        } else {
            $transactions = Transaction::join('users', 'users.user_id', '=', 'transactions.user_id', 'inner')->join('credits', 'transactions.transaction_id', '=', 'credits.transaction_id', 'left')->join('customers', 'customers.customer_id', '=', 'credits.customer_id', 'left')->whereBetween('date', [$first, $second])->orderBy('transactions.created_at', 'desc')->get(['transactions.transaction_id', 'transactions.date', 'transactions.total', 'transactions.pay', 'transactions.change', 'transactions.payment', 'users.name', 'customers.customer_name']);
        }

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $callback = function () use ($transactions) {
            echo "<table border='1'>";
            echo "<tr>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Bayar</th>
                <th>Kembali</th>
                <th>Metode Pembayaran</th>
                <th>Nama Kasir</th>
                <th>Nama Pelanggan</th>
            </tr>";
            foreach ($transactions as $t) {
                echo "<tr>
                    <td>" . Carbon::parse($t->date)->translatedFormat('l, d/F/Y') . "</td>
                    <td>{$t->total}</td>
                    <td>{$t->pay}</td>
                    <td>{$t->change}</td>
                    <td>{$t->payment}</td>
                    <td>" . ($t->name ?? '-') . "</td>
                    <td>" . ($t->customer_name ?? '-') . "</td>
                </tr>";
            }
            echo "</table>";
        };

        return response()->stream($callback, 200, $headers);
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
        $transaction = Transaction::join('users', 'users.user_id', '=', 'transactions.user_id', 'inner')->join('credits', 'transactions.transaction_id', '=', 'credits.transaction_id', 'left')->join('customers', 'customers.customer_id', '=', 'credits.customer_id', 'left')->where('transactions.transaction_id', $id)->first(['transactions.transaction_id', 'transactions.date', 'transactions.payment', 'users.name', 'customers.customer_name', 'transactions.total', 'transactions.pay', 'transactions.change']);
        $transaction_details = Transaction::join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.transaction_id')->join('product_details', 'product_details.product_detail_id', '=', 'transaction_details.product_detail_id')->join('products', 'product_details.product_id', '=', 'products.product_id')->join('categories', 'products.category_id', '=', 'categories.category_id')->join('units', 'units.unit_id', 'product_details.unit_id')->where('transaction_details.transaction_id', $id)->get(['products.product_name', 'products.pict', 'categories.category_name', 'units.unit_name', 'transaction_details.selling_price', 'transaction_details.quantity', 'transaction_details.subtotal']);

        return view('transaksi.detail', compact('transaction', 'transaction_details'));
    }

    public function print($id)
    {
        $transaction = Transaction::join('users', 'users.user_id', '=', 'transactions.user_id', 'inner')->join('credits', 'transactions.transaction_id', '=', 'credits.transaction_id', 'left')->join('customers', 'customers.customer_id', '=', 'credits.customer_id', 'left')->where('transactions.transaction_id', $id)->first(['transactions.transaction_id', 'transactions.date', 'transactions.payment', 'users.name', 'customers.customer_name', 'transactions.total', 'transactions.pay', 'transactions.change']);
        $transaction_details = Transaction::join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.transaction_id')->join('product_details', 'product_details.product_detail_id', '=', 'transaction_details.product_detail_id')->join('products', 'product_details.product_id', '=', 'products.product_id')->join('categories', 'products.category_id', '=', 'categories.category_id')->join('units', 'units.unit_id', 'product_details.unit_id')->where('transaction_details.transaction_id', $id)->get(['products.product_name', 'products.pict', 'categories.category_name', 'units.unit_name', 'transaction_details.selling_price', 'transaction_details.quantity', 'transaction_details.subtotal']);

        $pdf = Pdf::loadView('transaksi.print.detail', compact('transaction', 'transaction_details'))->setPaper('a4', 'landscape');
        return $pdf->stream($transaction->transaction_id . '_' . $transaction->customer_name . '_' . $transaction->date . '_print.pdf');
    }

    public function income()
    {
        $transactions = DB::select("SELECT * FROM RekapHari ORDER BY date DESC");

        return view('transaksi.income', compact('transactions'));
    }

    public function incomeFilter(Request $request)
    {
        $first = $request->first;
        $second = $request->second;

        if (!$first && !$second) {
            return redirect('/transaksi/pendapatan');
        }

        $transactions = DB::select("SELECT * FROM RekapHari WHERE date BETWEEN '$first' AND '$second' ORDER BY date DESC");

        return view('transaksi.income', compact('transactions', 'first', 'second'));
    }

    public function exportExcelIncome(Request $request)
    {
        $fileName = 'pendapatan_' . date('Y-m-d_H-i-s') . '.xls';
        $first = $request->first;
        $second = $request->second;

        if (!$first && !$second) {
            $transactions = DB::select("SELECT * FROM RekapHari ORDER BY date DESC");
        } else {
            $transactions = DB::select("SELECT * FROM RekapHari WHERE date BETWEEN '$first' AND '$second' ORDER BY date DESC");
        }

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $callback = function () use ($transactions) {
            echo "<table border='1'>";
            echo "<tr>
                <th>Tanggal</th>
                <th>Modal</th>
                <th>Omset</th>
                <th>Omset Tunai</th>
                <th>Omset Kredit</th>
                <th>Laba</th>
                <th>Laba Tunai</th>
                <th>Laba Kredit</th>
            </tr>";
            foreach ($transactions as $t) {
                echo "<tr>
                    <td>" . Carbon::parse($t->date)->translatedFormat('l, d/F/Y') . "</td>
                    <td>" . ($t->Modal ?? '-') . "</td>
                    <td>" . ($t->Omset ?? '-') . "</td>
                    <td>" . ($t->OmsetTunai ?? '-') . "</td>
                    <td>" . ($t->OmsetKredit ?? '-') . "</td>
                    <td>" . ($t->Laba ?? '-') . "</td>
                    <td>" . ($t->LabaTunai ?? '-') . "</td>
                    <td>" . ($t->LabaKredit ?? '-') . "</td>
                </tr>";
            }
            echo "</table>";
        };

        return response()->stream($callback, 200, $headers);
    }
}
