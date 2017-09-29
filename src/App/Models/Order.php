<?php

namespace CoreCMF\Omnipay\App\Models;

use Schema;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $table = 'omnipay_orders';

    protected $fillable = ['order_id','name','fee','gateway'];

}
