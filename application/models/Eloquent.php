<?php

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model;

class EloquentModel extends Model
{

   public static function getInstance()
    {
        return Util_Factory::create(static::class);
    }
}