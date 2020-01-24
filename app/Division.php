<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
	public static $rules = [
		'name' => 'sometimes|required|unique:divisions',
		'area' => 'required',
		'agent_id' => 'required',
	];
}
