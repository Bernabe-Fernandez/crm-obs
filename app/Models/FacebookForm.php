<?php

//Este modelo representara la tabla vt_facebook_foms

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacebookForm extends Model
{
    use HasFactory;

    
    protected $table = 'vt_facebook_forms';

    protected $fillable = [
        'page_id',
        'form_id',
        'nombre',
        'estatus',
    ];


    // Relación: un formulario pertenece a una página
    public function page()
    {
        return $this->belongsTo(FacebookPage::class, 'page_id');
    }


    // Relación: un formulario tiene muchos leads
    public function leads()
    {
        return $this->hasMany(FacebookLead::class, 'form_id');
    }
}
