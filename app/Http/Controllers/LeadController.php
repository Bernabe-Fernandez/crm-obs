<?php

namespace App\Http\Controllers;

use App\Models\FacebookLead;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LeadController extends Controller
{
    /**
     * Listar todos los leads con relaciones
     */
    public function index()
    {
        try {
            $leads = FacebookLead::with(['form.page'])
                ->orderBy('fecha_lead', 'desc')
                ->get();

            return response()->json($leads);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener los leads'
            ], 500);
        }
    }


    /**
     * Actualizar estatus del lead
     */
    public function updateStatus(Request $request, $id)
    {
        
        // Validación de campo obligatorio
        $request->validate([
            'estatus' => 'required|string'
        ]);

        try {
            $lead = FacebookLead::findOrFail($id);
            $lead->estatus = $request->estatus;
            $lead->save();

            return response()->json([
                'message' => 'Estatus actualizado correctamente',
                'lead' => $lead
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Lead no encontrado'
            ], 404);
        }
    }



    /**
     * Asignar vendedor al lead
     */
    public function assignSeller(Request $request, $id)
    {
        // Validación de campo obligatorio
        $request->validate([
            'vendedor' => 'required|string'
        ]);

        try {
            $lead = FacebookLead::findOrFail($id);
            $lead->vendedor = $request->vendedor;
            $lead->save();

            return response()->json([
                'message' => 'Vendedor asignado correctamente',
                'lead' => $lead
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Lead no encontrado'
            ], 404);
        }
    }

}
