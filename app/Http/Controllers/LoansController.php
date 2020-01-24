<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Loan;
use App\Client;
use App\Order;
use App\Payment;
use DB;
class LoansController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function create_payment($id)
	{
		
		$payments = Payment::where('loan_id',$id)->orderBy('date','desc')->get();
		
		$loan = Loan::find($id);
		return view('payments.loans',compact('payments','loan'));
	}

	public function payLoan(Request $request)
	{
		$loan = Loan::find($request->id);
		$amount = floatval($request->amount);	
		$payment = new Payment;
		$payment->loan_id = $request->id;
		$payment->order_id = $loan->order_id;
		
		$payment->due = $loan->balance;
		$payment->amount = $amount;
		$payment->method = $request->method;
		$payment->date = $request->date;
		$payment->comments = $request->comments;
		$loan->balance -= $amount;
		$loan->save();
		$payment->save();
		
		$client = Client::find($request->client_id);
		$paid = DB::table('payments')->where('loan_id',$request->id)->sum('amount');
		return response()->json([
			'status' => 'success',
			'message' => 'Payment success!',
			'loan' => $loan,
			'client' => $client,
			'paid' => $paid
		]);
	}
	
	public function index()
	{
		$loans = Loan::all();
		$payments = DB::table('payments')
    ->select(DB::raw('sum(amount) as total, loan_id'))
    ->groupBy('loan_id')
    ->get();
		foreach($loans as $loan){
			$check = Loan::find($loan->id);
			$this->checkLoan($check);
		}
		$loans = DB::table('clients')->join('orders','orders.client_id','clients.id')->join('loans','loans.order_id','orders.id')->where('loans.deleted','0')->get();
		return view('loans.index',compact('loans','payments'));
	}

	public function loanOrder(Request $request)
	{
		$order = Order::find($request->id);
		$loan = new Loan;
		$loan->order_id = $order->id;
		$loan->balance = $order->balance;
		$loan->comments = $request->comments;
		$loan->total = $order->balance;
		$loan->loan_date = $request->date;
		$this->checkLoan($loan);
		
		
		$order->paid = 'loan';
		$order->balance = 0;
		$order->save();
		$client = Client::find($order->client_id);
		return response()->json([
			'status' => 'success',
			'message' => 'Loan order successful!',
			'order' => $order,
			'client' => $client
		]);
	}

	private function checkLoan($loan)
	{
		$paid = DB::table('payments')->where('loan_id',$loan->id)->sum('amount');
		
		$datenow = strtotime(date('Y-m-d'));
		$dateloan = strtotime($loan->loan_date);
		
		$year2 = date('Y', $datenow);
		$year1 = date('Y', $dateloan);

		$month2 = date('m', $datenow);
		$month1 = date('m', $dateloan);	

		$diff = (($year2 - $year1) * 12) + ($month2 - $month1);
		
		
		$balance = $loan->total;
		for($x = 0; $x < $diff; $x++){
			$balance += ($balance * 0.05);
		}
		$balance -= $paid;
		if(number_format($balance,2) != number_format($loan->balance,2)){
			$loan->balance = $balance;
		}
		$loan->save();
	}

	public function delete($id)
	{
		$loan = Loan::find($id);
		$loan->deleted = '1';
		$loan->save();
		return response()->json([
			'status' => 'success',
			'message' => 'Loan deleted!',
		]);
	}

}
