<?php

// Este modelo representara la tabla vt_facebook_page

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacebookPage extends Model
{

    use HasFactory; 

    // Nombre exacto de la tabla
    protected $table = 'vt_facebook_page';

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'nombre',
        'facebook_id',
        'token',
    ];


    // Relación: una página tiene muchos formularios

    public function forms()
    {
        return $this->hasMany(FacebookForm::class, 'page_id');
    }

}
