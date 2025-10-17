<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\CreditDetail;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CreditController extends Controller
{
    public function index()
    {
        $customers = Customer::simplePaginate(15);

        return view('kredit.index', compact('customers'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        if ($keyword === "" || $keyword == null) {
            return redirect('/kredit');
        }

        $customers = Customer::where('customer_name', 'LIKE', '%' . $keyword . '%')->simplePaginate(15);

        return view('kredit.index', compact('customers', 'keyword'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required',
            'customer_name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $customer = Customer::where('customer_name', $request->customer_name)->first();

        if ($customer && $request->customer_name == $customer->customer_name) {
            $validator->errors()->add('customer_name', 'Nama customer tidak boleh sama!');

            return redirect()->back()->withErrors($validator)->withInput();
        }

        Customer::where('customer_id', $request->customer_id)->update([
            'customer_name' => $request->customer_name,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
        ]);

        return redirect('/kredit')->with('success', 'Pelanggan Berhasil Diedit!');
    }

    public function pay(Request $request, $id)
    {
        $request->validate([
            'amount_of_debt' => 'required|numeric',
            'amount_of_paid' => 'required|numeric',
            'remaining_debt' => 'required|numeric',
            'change' => 'required|numeric',
        ]);

        if($request->amount_of_paid === 0 || !$request->amount_of_paid){
            return redirect()->back()->with('error', 'Pembayaran Tidak Boleh 0 atau Kosong');
        }

        $credit = Credit::where('customer_id', $id)->latest()->first();

        CreditDetail::create([
            'credit_id' => $credit->credit_id,
            'amount_of_paid' => intval($request->amount_of_paid),
            'remaining_debt' => $request->remaining_debt,
            'change' => $request->change,
            'payment_date' => now()->format('Y-m-d'),
        ]);

        Customer::where('customer_id', $id)->update([
            'amount_of_debt' => $request->remaining_debt,
        ]);

        if ($request->remaining_debt == 0 && $request->change >= 0) {
            Customer::where('customer_id', $id)->update([
                'status' => 'lunas'
            ]);
        }

        return redirect('/kredit')->with('success', 'Hutang Berhasil Dibayar!');
    }

    public function history(){
        $customers = Customer::join('credits', 'customers.customer_id', '=', 'credits.customer_id')->join('credit_details', 'credits.credit_id', '=', 'credit_details.credit_id')->orderBy('credit_details.created_at', 'desc')->get(['customers.customer_name', 'credits.total', 'credit_details.amount_of_paid', 'credit_details.remaining_debt', 'credit_details.change', 'customers.status', 'credit_details.payment_date']);

        return view('kredit.history', compact('customers'));
    }

    public function filter(Request $request)
    {
        $first = $request->first;
        $second = $request->second;

        if (!$first && !$second) {
            return redirect('/kredit/history');
        }

        $customers = Customer::join('credits', 'customers.customer_id', '=', 'credits.customer_id')->join('credit_details', 'credits.credit_id', '=', 'credit_details.credit_id')->whereBetween('credit_details.payment_date', [$first, $second])->orderBy('credit_details.created_at', 'desc')->get(['customers.customer_name', 'credits.total', 'credit_details.amount_of_paid', 'credit_details.remaining_debt', 'credit_details.change', 'customers.status', 'credit_details.payment_date']);

        return view('kredit.history', compact('customers', 'first', 'second'));
    }

    public function exportExcelHistory(Request $request)
    {
        $fileName = 'kredit_' . date('Y-m-d_H-i-s') . '.xls';
        $first = $request->first;
        $second = $request->second;

        if (!$first && !$second) {
            $customers = Customer::join('credits', 'customers.customer_id', '=', 'credits.customer_id')->join('credit_details', 'credits.credit_id', '=', 'credit_details.credit_id')->orderBy('credit_details.created_at', 'desc')->get(['customers.customer_name', 'credits.total', 'credit_details.amount_of_paid', 'credit_details.remaining_debt', 'credit_details.change', 'customers.status', 'credit_details.payment_date']);
        } else {
            $customers = Customer::join('credits', 'customers.customer_id', '=', 'credits.customer_id')->join('credit_details', 'credits.credit_id', '=', 'credit_details.credit_id')->whereBetween('credit_details.payment_date', [$first, $second])->orderBy('credit_details.created_at', 'desc')->get(['customers.customer_name', 'credits.total', 'credit_details.amount_of_paid', 'credit_details.remaining_debt', 'credit_details.change', 'customers.status', 'credit_details.payment_date']);
        }

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $callback = function () use ($customers) {
            echo "<table border='1'>";
            echo "<tr>
                <th>Tanggal Bayar</th>
                <th>Nama Pelanggan</th>
                <th>Total Hutang</th>
                <th>Bayar</th>
                <th>Sisa</th>
                <th>Kembali</th>
            </tr>";
            foreach ($customers as $c) {
                echo "<tr>
                    <td>" . Carbon::parse($c->payment_date)->translatedFormat('l, d/F/Y') . "</td>
                    <td>" . ($c->customer_name ?? '-') . "</td>
                    <td>{$c->total}</td>
                    <td>{$c->amount_of_paid}</td>
                    <td>{$c->remaining_debt}</td>
                    <td>{$c->change}</td>
                </tr>";
            }
            echo "</table>";
        };

        return response()->stream($callback, 200, $headers);
    }
}
