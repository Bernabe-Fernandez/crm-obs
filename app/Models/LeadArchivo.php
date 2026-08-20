<?php


// Este modelo representara la tabla vt_facebook_parchivos

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadArchivo extends Model
{
    use HasFactory;


    // Nombre exacto de la tabla
    protected $table = 'vt_facebook_lead_archivos';


    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'lead_id',
        'nombre_archivo',
        'ruta',
        'tipo',
        'tamano',
    ];


    // Relación: un archivo pertenece a un lead
    public function lead()
    {
        return $this->belongsTo(FacebookLead::class, 'lead_id');
    }
}

