<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  public static $rules = [
		'delivery_date' => 'required|date_format:Y-m-d',
		'client_id' => 'required'
	];
}
