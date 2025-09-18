<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    public function index(){
        $customers = Customer::all();

        return view('kredit.index', compact('customers'));
    }
}
