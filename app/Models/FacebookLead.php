<?php

// Este modelo representara la tabla vt_facebook_leads

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacebookLead extends Model
{
    use HasFactory;
    // Nombre exacto de la tabla
    protected $table = 'vt_facebook_leads';

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'facebook_lead_id',
        'nombre',
        'correo',
        'telefono',
        'ciudad',
        'plataforma',
        'vendedor',
        'estatus',
        'fecha_lead',
        'interest',      
    ];

    // Relación: un lead pertenece a un formulario
    public function form()
    {
        return $this->belongsTo(FacebookForm::class, 'form_id');
    }

    // Relación: un lead tiene muchos comentarios
    public function comentarios()
    {
        return $this->hasMany(LeadComment::class, 'lead_id');
    }

    // Relación: un lead tiene muchos archivos
    public function archivos()
    {
        return $this->hasMany(LeadArchivo::class, 'lead_id');
    }
}
