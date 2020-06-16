<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class TipoGrupo extends Model 
{
    
   public $timestamps = false;
   
   protected $table = 'tipo_grupo';
   protected $primaryKey = 'intIdTipoGrupo';
   protected $fillable = [
      'varCodiTipoGrupo',
      'varDescTipoGrupo',
      'intIdEsta',
      'acti_usua',
      'acti_hora',   
   
  ];
}
