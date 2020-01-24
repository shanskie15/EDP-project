<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Division;
use DB;
use App\Employee;
use App\Client;
class DivisionsController extends Controller
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
		$divisions = DB::table('employees')->join('divisions','divisions.agent_id','employees.id')->where('divisions.deleted','0')->get();
		return view('divisions.index',compact('divisions'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$employees = Employee::where('deleted','0')->get();
		return view('divisions.create',compact('employees'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$rules = Division::$rules;
		$validator = \Validator::make($request->all(),$rules);
		if($validator->fails())
		{
			return response()->json([
				'status' => 'invalid',
				'errors' => $validator->errors()->all()
			]);
		}

		$division = new Division;
		$this->populateData($division,$request);
		$employee = Employee::find($request->agent_id);
		return response()->json([
			'status' => 'success',
			'message' => 'Division successfully saved!',
			'id' => $division->id,
			'agent' => $employee->firstname . ' ' . $employee->lastname
		]);

	}

	// populate division data
	private function populateData($division,$request)
	{
		$division->name = $request->name;
		$division->area = $request->area;
		$division->agent_id = $request->agent_id;
		$division->save();
	}
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$division = DB::table('employees')->join('divisions','divisions.agent_id','employees.id')->where('divisions.deleted','0')->where('divisions.id',$id)->first();
		return view('divisions.show',compact('division'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$division = Division::find($id);
		$employees = Employee::where('deleted','0')->get();
		return view('divisions.edit',compact('division','employees'));
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
		$rules = Division::$rules;
		$rules['name'] .= ',id,' . $id;
		
		$validator = \Validator::make($request->all(),$rules);
		if($validator->fails())
		{
			return response()->json([
				'status' => 'invalid',
				'errors' => $validator->errors()->all()
			]);
		}

		$division = Division::find($id);
		$this->populateData($division,$request);
		$employee = Employee::find($request->agent_id);
		return response()->json([
			'status' => 'success',
			'message' => 'Division successfully saved!',
			'id' => $division->id,
			'agent' => $employee->firstname . ' ' . $employee->lastname
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
		$check = Client::where('division_id',$id)->first();
		if(null == $check){
			$division = Division::find($id);
			$division->delete();
			return response()->json([
				'status' => 'success',
				'message' => 'Division successfully deleted from the database!',
			]);
		}
		return response()->json([
			'status' => 'error',
			'message' => 'Division cannot be deleted from the database!',
		]);
	}

	//soft delete
	public function delete($id)
	{
		$check = Client::where('deleted','0')->where('division_id',$id)->first();
		if(null == $check){
			$division = Division::find($id);
			$division->deleted = '1';
			$division->save();
			return response()->json([
				'status' => 'success',
				'message' => 'Division successfully removed from the list!',
			]);
		}
		
	}

}
