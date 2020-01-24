<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	public static $rules =  [
		'barcode' => 'sometimes|required|numeric|digits:12|unique:products',
		'name' => 'required',
		'price' => 'required|numeric|min:0',
		'quantity' => 'required|min:0',
		'category' => 'required',
		'brand' => 'required'
	];
}
