<?php

namespace App\Http\Controllers;

use App\Models\CreditDetail;
use App\Models\Customer;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    public function index()
    {
        $customers = Customer::all();

        return view('kredit.index', compact('customers'));
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

        return view('kredit.bayar', compact('customer'));
    }

    public function pay(Request $request, $id)
    {
        $request->validate([
            'amount_of_debt' => 'required',
            'amount_of_paid' => 'required',
            'remaining_debt' => 'required',
            'change' => 'required',
        ]);

        CreditDetail::create([
            'customer_id' => $id,
            'amount_of_paid' => $request->amount_of_paid,
            'remaining_debt' => $request->remaining_debt,
            'change' => $request->change,
            'payment_date' => now()->format('Y-m-d'),
        ]);

        Customer::where('customer_id', $id)->update([
            'amount_of_debt' => $request->remaining_debt,
        ]);

        if ($request->remaining_debt === 0) {
            Customer::where('customer_id', $id)->update([
                'status' => 'lunas'
            ]);
        }

        return redirect('/kredit')->with('success', 'Hutang Berhasil Dibayar!');
    }
}
