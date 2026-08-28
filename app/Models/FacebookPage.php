<?php

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

    // Campos que se deben ocultar en las respuestas JSON
    protected $hidden = [
        'token',
    ];

    // Relación: una página tiene muchos formularios
    public function forms()
    {
        return $this->hasMany(FacebookForm::class, 'page_id');
    }
}
