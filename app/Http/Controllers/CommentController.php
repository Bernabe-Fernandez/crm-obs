<?php

namespace App\Http\Controllers;

use App\Models\LeadComment;
use App\Models\FacebookLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CommentController extends Controller
{


    /**
     * Obtener comentarios de un lead
     */
    public function index($lead_id)
    {
        try {
            $comentarios = LeadComment::where('lead_id', $lead_id)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $comentarios
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al obtener los comentarios',
                'details' => $e->getMessage()
            ], 500);
        }
    }



    /**
     * Agregar comentario a un lead
     */
    public function store(Request $request, $lead_id)
    {
        // Validación profesional
        $request->validate([
            'comentario' => 'required|string|max:500',
            'usuario'    => 'required|string|max:100'
        ]);

        DB::beginTransaction();

        try {


            // Validar que el lead exista
            $lead = FacebookLead::findOrFail($lead_id);



            // Crear comentario
            $comentario = LeadComment::create([
                'lead_id'    => $lead_id,
                'comentario' => $request->comentario,
                'usuario'    => $request->usuario,   //quien escribio el comentario
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Comentario agregado correctamente',
                'data' => $comentario
            ], 201);

        } catch (ModelNotFoundException $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'error' => 'Lead no encontrado'
            ], 404);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'error' => 'Error al agregar el comentario',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}


