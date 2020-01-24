<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $total = DB::table('payments')->sum('amount');
        $payments = DB::table('payments')
        ->select(DB::raw('sum(amount) as total, date'))
        ->groupBy('date')->orderBy('date')
        ->paginate(5);
        return view('home',compact('total','payments'));
    }

    public function sales()
    {
        
        return json_encode($orders) . json_encode($loans) . $order_total . $loan_total;
    }
}
