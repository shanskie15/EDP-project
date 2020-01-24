<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Product;
use App\ProductOrder;
class ProductsController extends Controller
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
			$products = Product::where('deleted','0')->get();
			return view('products.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
			return view('products.create');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
			$rules = Product::$rules;
			$validator = \Validator::make($request->all(),$rules);

			if($validator->fails())
			{
				return response()->json([
					'status'=>'invalid',
					'errors'=> $validator->errors()->all()
				]);
			}
			
			$product = new Product;
			
			$product->barcode = $request->barcode;
			$product->name = ucwords($request->name);
			
			$product->price = $request->price;
			$product->quantity = $request->quantity;
			$product->category = ucwords($request->category);
			$product->brand = ucwords($request->brand);
			$product->save();
			return response()->json([
				'status' => 'success',
				'message' => 'Product saved successfully.',
				'id' => $product->id
			]);
		}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
			try{
				$product = Product::find($id);
				return view('products.show',compact('product'));
			}catch(Exception $e){
				return response()->json(['status'=>'error','message'=>$e->getMessage()]);
			}
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      
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
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $check = ProductOrder::where('product_id',$id)->first();
      if(null == $check){
        $product = Product::find($id);
        $product->delete();
        return response()->json(['status'=>'success','message'=>'Product deleted from database']);
      }
      return response()->json(['status'=>'error','message'=>'Product cannot be deleted from the database']); 
		}
		
		public function delete($id)
		{
      
     
        $product = Product::find($id);
        $product->deleted = '1';
        $product->save();
        return response()->json(['status'=>'success','message'=>'Product removed from the list']); 
      
		}

		// add items
		public function addItems(Request $request)
		{
			$id = $request->id;
			$product = Product::find($id);
			$product->quantity += $request->quantity;
			$product->save();
			return response()->json([
				'status' => 'success',
				'message' => 'Items added.',
				'quantity' => $product->quantity
			]);
		}

}
