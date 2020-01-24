<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
	public static $rules = [
		'store' => 'required',
		'owner' => 'required',
		'contact' => 'sometimes|required|numeric|digits_between:7,11|unique:employees,contact|unique:clients,contact',
		'email' => 'sometimes|required|email|unique:employees,email|unique:clients,email',
		'city' => 'required',
		'province' => 'required',
		'zipcode' => 'required|numeric|digits:4',
		'contact_person' => 'required',
		'division_id' => 'required'
	];
}
