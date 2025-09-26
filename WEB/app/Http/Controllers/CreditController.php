<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\CreditDetail;
use App\Models\Customer;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    public function index()
    {
        $customers = Customer::where('status', 'belum lunas')->get();

        return view('kredit.index', compact('customers'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        if ($keyword === "" || $keyword == null) {
            return redirect('/kredit');
        }

        $customers = Customer::where('status', 'belum lunas')->where('customer_name', 'LIKE', '%' . $keyword . '%')->get();

        return view('kredit.index', compact('customers', 'keyword'));
    }

    public function edit($id)
    {
        $customer = Customer::where('customer_id', $id)->first();

        return view('kredit.update', compact('customer'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'customer_name' => 'required',
            'phone_number' => 'required',
            'address' => 'required',
        ]);

        Customer::where('customer_id', $request->customer_id)->update([
            'customer_name' => $request->customer_name,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
        ]);

        return redirect('/kredit')->with('success', 'Pelanggan Berhasil Diedit!');
    }

    public function payment($id)
    {
        $customer = Customer::where('customer_id', $id)->first();

        return view('kredit.pay', compact('customer'));
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
        $customers = Customer::join('credits', 'customers.customer_id', '=', 'credits.customer_id')->join('credit_details', 'credits.credit_id', '=', 'credit_details.credit_id')->get(['customers.customer_name', 'credits.total', 'credit_details.amount_of_paid', 'credit_details.remaining_debt', 'credit_details.change', 'customers.status', 'credit_details.payment_date']);

        return view('kredit.history', compact('customers'));
    }
}
