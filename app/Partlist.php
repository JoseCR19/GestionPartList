<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Partlist extends Model 
{
    

  public $timestamps = false;
   protected $table = 'partlist';
   protected $primaryKey = 'intIdPartList';
   protected $fillable = [
      'intIdProy',
      'intIdTipoProducto',
      'varDescripcion',
      'varArchivo',
      'boolNuevo',
      'boolActu',
      'vartipoDocu',
      'acti_usua',
      'acti_hora'
     
  ];
}
