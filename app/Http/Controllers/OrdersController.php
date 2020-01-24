<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\ProductOrder;
use App\Client;
use App\Product;
use Cart;
use DB;
use App\Payment;
use App\Loan;
class OrdersController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth');
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$orders = DB::table('clients')->join('orders','orders.client_id','clients.id')->where('orders.deleted','0')->get();
		// return json_encode($orders);
		// $payments = DB::table('payments')
    // ->select(DB::raw('sum(amount) as total, order_id'))
    // ->groupBy('order_id')
    // ->get();	
		foreach($orders as $order){
			if('1' == $order->new && 'unpaid' == $order->paid){
				$check = Order::find($order->id);
				$this->checkOrder($check);
			}
		}
		$orders = DB::table('clients')->join('orders','orders.client_id','clients.id')->where('orders.deleted','0')->get();
		return view('orders.index',compact('orders'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$clients = Client::where('deleted','0')->get();
		return view('orders.create',compact('clients'));
	}

	// products
	public function products($id)
	{
		$products = Product::where('deleted','0')->where('quantity','>=','50')->get();
		$order = Order::find($id);
		// return json_encode($order);
		return view('orders.products',compact('products','order'));
	}

	// cart
	public function cart($id)
	{
		$cart = DB::table('products')->join('product_orders','product_orders.product_id','products.id')->where('product_orders.order_id',$id)->where('product_orders.deleted','0')->get();
		// return json_encode($cart);
		return view('orders.cart',compact('cart'));
	}

	//

	// increase/decrease cart quantity
	public function editQuantity(Request $request)
	{
		
		$item = ProductOrder::find($request->item_id);
		$qty = intval($request->quantity);
		$product = Product::find($item->product_id);
		$subtotal = $product->price * $qty;
		$order = Order::find($item->order_id);
		
		if('add' == $request->type){
			if($qty > $product->quantity){
				return response()->json([
					'status' => 'error',
					'message' => 'Not enough in stock. Only '.$product->quantity.' available.'
				]);
			}
			$item->quantity += $qty;
			$item->subtotal += $subtotal;
			$product->quantity -= $qty;
			$order->total_amount += $subtotal;
			$order->balance += $subtotal;
		}else{
			if($qty > $item->quantity){
				return response()->json([
					'status' => 'error',
					'message' => 'Only '.$item->quantity.' '. $product->name .' ordered.'
				]);
			}
			$item->quantity -= $qty;
			$item->subtotal -= $subtotal;
			$product->quantity += $qty;
			$order->total_amount -= $subtotal;
			$order->balance -= $subtotal;
		}
		$item->save();
		$product->save();
		$order->save();
		return response()->json([
			'status' => 'success',
			'message' => 'Saved changes',
			'item_qty' => $item->quantity,
			'product_qty' => $product->quantity,
			'order_total' => $order->total_amount,
			'item_total' => $item->subtotal,
		]);
	}

	// add to cart
	public function addToCart(Request $request)
	{
		// return json_encode($request->all());
		$product = Product::find($request->product_id);
		$qty = intval($request->quantity);
		if($qty < 50){
			return response()->json([
				'status' => 'error',
				'message' => 'Minimum number of items should be 50!',
			]);
		}

		if($qty > $product->quantity){
			return response()->json([
				'status' => 'error',
				'message' => 'Not enough in stock. Only '. $product->quantity.' available.',
			]);
		}


		$order = Order::find($request->order_id);
		
		$subtotal = $product->price * $qty;
		$item = ProductOrder::where('deleted','0')->where('order_id',$request->order_id)->where('product_id',$request->product_id)->first();


		if(null == $item){
			$item = new ProductOrder;
			$item->product_id = $request->product_id;
			$item->order_id = $request->order_id;
			$item->quantity = 0;
			$item->subtotal = 0;
		}
	
		$item->quantity += $qty;
		$item->subtotal += $subtotal;
		
		
		$order->total_amount += $subtotal;
		$order->balance += $subtotal;
		$order->num_products += 1;
		
		
		$product->quantity -= $qty;
		$item->save();
		$product->save();
		$order->save();
		return response()->json([
			'status' => 'success',
			'message' => 'Added to cart!',
			'product_qty' => $product->quantity
		]);
	}
	public function itemDestroy($id)
	{
		$item = ProductOrder::find($id);
		$order = Order::find($item->order_id);
		if('pending' == $order->status){
			$product = Product::find($item->product_id);
			
			$product->quantity += $item->quantity;
			
			$order->total_amount -= $item->subtotal;
			
			$order->num_products -= 1;
			
			$product->save();
			$order->save();
		}
		$item->delete();
		return response()->json([
			'status' => 'success',
			'message' => 'Item successfully deleted!',
			'order' => $order
		]);
	}

	// ship order
	public function ship(Request $request)
	{

		$order = Order::find($request->id);
		if($order->num_products < 5){
			return response()->json([
				'status' => 'error',
				'message' => 'Minimum number of products should be 5!',
			]);
		}
		$order->status = $request->type;
		
		$order->save();
		if('received' == $request->type && '1' == $order->new && 'unpaid' == $order->paid){
			$this->checkOrder($order);
		}
		$client = Client::find($order->client_id);
		return response()->json([
			'status' => 'success',
			'message' => 'Order '.ucfirst($request->type).'!',
			'order' => $order,
			'client' => $client
		]);
	}
	// check balances
	private function checkOrder($order)
	{
		// $order = Order::find($id);
		$payments = Payment::where('order_id',$order->id)->get();
		$paid = 0;
		foreach($payments as $payment)
		{
			$paid += $payment->amount;
		}
		$datenow = strtotime(date('Y-m-d'));
		$dateship = strtotime($order->delivery_date);
		$diff = ( $datenow - $dateship) / (60 * 60 * 24);
		if($diff > 7){
			$diff = $diff / 7;
			$balance = $order->total_amount;
			for($x = 0; $x < $diff; $x++){
				$balance += ($balance * 0.01);
			}
			$balance -= $paid;
			if(number_format($balance,2) != number_format($order->balance,2)){
				$order->balance = $balance;
				$order->paid = 'unpaid';
				$order->save();
			}
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$rules = Order::$rules;
		$validator = \Validator::make($request->all(),$rules);
			
		if($validator->fails())
		{
			return response()->json([
				'status' => 'invalid',
				'errors' => $validator->errors()->all()
			]);
		}
		$order = new Order;
		if('new' == $request->type){
			$order->new = '1';
		}
		$this->populateData($order,$request);
		$client = Client::find($order->client_id);
		return response()->json([
			'status' => 'success',
			'message' => 'Order saved!',
			'id' => $order->id,
			'client' => $client
		]);
	}

	private function populateData($order,$request)
	{
		$order->delivery_date = $request->delivery_date;
		$order->client_id = $request->client_id;
		$order->remarks = $request->remarks;
		$order->save();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$order = DB::table('clients')->join('orders','orders.client_id','clients.id')->where('orders.id',$id)->first();
		$cart = DB::table('products')->join('product_orders','product_orders.product_id','products.id')->where('product_orders.order_id',$id)->where('product_orders.deleted','0')->get();
		$paid = DB::table('payments')->where('order_id',$id)->sum('amount');
		return view('orders.show',compact('order','cart','paid'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$order = Order::find($id);
		$clients = Client::where('deleted','0')->get();
		return view('orders.create',compact('order','clients'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$rules = Order::$rules;
		$validator = \Validator::make($request->all(),$rules);
			
		if($validator->fails())
		{
			return response()->json([
				'status' => 'invalid',
				'errors' => $validator->errors()->all()
			]);
		}
		$order = Order::find($id);
		$this->populateData($order,$request);
		$client = Client::find($order->client_id);
		return response()->json([
			'status' => 'success',
			'message' => 'Order saved!',
			'id' => $order->id,
			'client' => $client->store
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$order = Order::find($id);
		$cart = ProductOrder::where('order_id',$id)->get();
		foreach($cart as $item){
			$this->itemDestroy($item->id);
		}
		$order->delete();
		return response()->json([
			'status' => 'success',
			'message' => 'Successfully deleted from database!'
		]);
	}

	public function delete($id)
	{
		$order = Order::find($id);
		$cart = ProductOrder::where('order_id',$id)->get();
		foreach($cart as $item){
			$this->itemDelete($item->id);
		}
		$order->deleted = '1';
		$order->save();
		return response()->json([
			'status' => 'success',
			'message' => 'Successfully deleted from database!'
		]);
	}

	private function itemDelete($id)
	{
		$item = ProductOrder::find($id);
		$order = Order::find($item->order_id);
		if('received' != $order->status ){
			$product = Product::find($item->product_id);
			
			$product->quantity += $item->quantity;
			
			$order->total_amount -= $item->subtotal;
			
			$order->num_products -= 1;
			
			$product->save();
			$order->save();
		}
		$item->deleted = '1';
		$item->save();
		return response()->json([
			'status' => 'success',
			'message' => 'Order successfully deleted from the list!'
		]);
	}
}
