<?php

namespace Cardumen\ArgentinaProvinciasLocalidades\Models;

use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    protected $table = 'provincias';
    protected $guarded = [];

    public function pais(){
    	return $this->belongsTo(Pais::class);
    }
}
