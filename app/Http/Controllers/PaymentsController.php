<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Payment;
use App\Order;
use App\Loan;
use App\Client;
use DB;
class PaymentsController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	// ORDERS
	public function createOrder($id)
	{
		$payments = Payment::where('order_id',$id)->orderBy('date','desc')->get();
		$order = Order::find($id);
		return view('payments.create',compact('payments','order'));
	}
	
	public function payOrder(Request $request)
	{
		$order = Order::find($request->id);
		$payment = new Payment;
		$amount = floatval($request->amount); 
		$payment->order_id = $request->id;
		$payment->due = $order->balance;
		$payment->amount = $amount;
		$payment->method = $request->method;
		$payment->date = $request->date;
		if($amount > $order->balance){
			$order->balance = 0;
		}else{
			$order->balance -= $payment->amount;
		}
		
		
		$payment->comments = $request->comments;
		if(0 == $order->balance){
			$order->paid = 'full';
		}
		$payment->save();
		$order->save();
		$paid = DB::table('payments')->where('order_id',$request->id)->sum('amount');
		$client = Client::find($order->client_id);
		return response()->json([
			'status' => 'success',
			'message' => 'Payment success!',
			'order' => $order,
			'client' => $client
		]);
	}

	public function orderPayments($id)
	{
		$payments = Payment::where('order_id',$id)->orderBy('date','desc')->get();
		return view('payments.index',compact('payments'));
	}
}
