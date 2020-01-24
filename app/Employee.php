<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
  public static $rules = [
		'email' => 'sometimes|required|email|unique:clients,email|unique:employees,email',
		'firstname' => 'required',
		'middlename' => 'required',
		'lastname' => 'required',
		'birth_date' => 'required|date_format:Y-m-d',
		'contact' => 'sometimes|required|numeric|digits:11|unique:clients,contact|unique:employees,contact',
		'address' => 'required',
		'salary' => 'required|numeric'
  ];
}
