<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Division;
use App\Order;
class ClientsController extends Controller
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
		$clients = Client::where('deleted','0')->get();
		$divisions = Division::where('deleted','0')->get();
		return view('clients.index',compact('clients','divisions'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$divisions = Division::where('deleted','0')->get();
		return view('clients.create',compact('divisions'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$rules = Client::$rules;
		$validator = \Validator::make($request->all(),$rules);
		if($validator->fails())
		{
			return response()->json([
				'status' => 'invalid',
				'errors' => $validator->errors()->all()
			]);
		}
		$client = new Client;
		$this->populateData($client,$request);
		$division = Division::find($client->division_id);
		$division->population += 1;
		$division->save();
		return response()->json([
			'status' => 'success',
			'message' => 'Client successfully saved!',
			'id' => $client->id,
		]);
	}

	// populate data
	private function populateData($client,$request)
	{
		$client->store = $request->store;
		$client->owner = $request->owner;
		$client->contact = $request->contact;
		$client->email = $request->email;
		$client->city = $request->city;
		$client->province = $request->province;
		$client->zipcode = $request->zipcode;
		$client->contact_person = $request->contact_person;
		$client->division_id = $request->division_id;
		$client->save();

	}
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$client = Client::find($id);
		$division = Division::find($client->division_id);
		$orders = Order::where('client_id',$id)->get();
		return view('clients.show',compact('client','division','orders'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$client = Client::find($id);
		$divisions = Division::where('deleted','0')->get();
		return view('clients.edit',compact('client','divisions'));
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
		$rules = Client::$rules;
		$rules['contact'] .= ',' . $id;
		$rules['email'] .= ',' . $id;
		$validator = \Validator::make($request->all(),$rules);
		
		if($validator->fails())
		{	
			return response()->json([
				'status' => 'invalid',
				'errors' => $validator->errors()->all()
			]);
		}

		$client = Client::find($id);
		$this->populateData($client,$request);
		return response()->json([
			'status' => 'success',
			'message' => 'Client updated!'
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
		$check = Order::where('client_id',$id)->first();
		if(null == $check){
			$client = Client::find($id);
			$client->delete();
			return response()->json([
				'status' => 'success',
				'message' => 'Client successfully deleted from database!'
			]);
		}
		return response()->json([
			'status' => 'error',
			'message' => 'Client cannot be deleted from database!'
		]);
	}
	public function delete($id)
	{
		$client = Client::find($id);
		$client->deleted = '1';
		$client->save();
		return response()->json([
			'status' => 'success',
			'message' => 'Client successfully deleted from database!'
		]);
	}
}
