<?php

namespace CoreCMF\Omnipay\App\Models;

use Schema;
use Illuminate\Database\Eloquent\Model;
use CoreCMF\Core\App\Models\Upload;

class Config extends Model
{
    public $table = 'omnipay_configs';

    protected $fillable = [];

}
