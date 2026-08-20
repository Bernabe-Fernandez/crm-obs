<?php

// Este modelo representara la tabla vt_facebook_comments

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadComment extends Model
{
    use HasFactory;

    // Nombre exacto de la tabla
    protected $table = 'vt_facebook_lead_comments';


    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'lead_id',
        'comentario',
        'usuario',
    ];

    // Relación: un comentario pertenece a un lead
    public function lead()
    {
        return $this->belongsTo(FacebookLead::class, 'lead_id');
    }
}
