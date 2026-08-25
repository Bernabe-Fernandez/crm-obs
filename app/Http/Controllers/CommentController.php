<?php

//controlador de comentarios

namespace App\Http\Controllers;

use App\Models\LeadComment;
use App\Models\FacebookLead;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    /**
     * Obtener comentarios de un lead
     */
    public function index($lead_id)
    {
        $comentarios = LeadComment::where('lead_id', $lead_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($comentarios);
    }


    /**
     * Agregar comentario a un lead
     */
    public function store(Request $request, $lead_id)
    {
        // Validar que el lead exista
        $lead = FacebookLead::findOrFail($lead_id);

        $comentario = LeadComment::create([
            'lead_id' => $lead_id,
            'comentario' => $request->comentario,
            'usuario' => $request->usuario,
        ]);

        return response()->json([
            'message' => 'Comentario agregado correctamente',
            'comentario' => $comentario
        ]);
    }


    /**
     * Editar comentario (opcional) quitar
     */
    public function update(Request $request, $id)
    {
        $comentario = LeadComment::findOrFail($id);

        $comentario->comentario = $request->comentario;
        $comentario->save();

        return response()->json([
            'message' => 'Comentario actualizado correctamente',
            'comentario' => $comentario
        ]);
    }


    /**
     * Eliminar comentario (opcional)
     */
    public function destroy($id)
    {
        $comentario = LeadComment::findOrFail($id);
        $comentario->delete();

        return response()->json([
            'message' => 'Comentario eliminado correctamente'
        ]);
    }
}

