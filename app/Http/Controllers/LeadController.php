<?php

//es nuestro controlador principal

namespace App\Http\Controllers;
namespace App\Http\Controllers;

use App\Models\FacebookLead;
use App\Models\LeadComment;
use App\Models\LeadArchivo;
use Illuminate\Http\Request;

class LeadController extends Controller
{


    /**
     * Listar todos los leads
     */
    public function index()
    {
        $leads = FacebookLead::with(['form.page'])->orderBy('fecha_lead', 'desc')->get();

        return response()->json($leads);
    }


    /**
     * Mostrar un lead específico
     */
    public function show($id)
    {
        $lead = FacebookLead::with(['comentarios', 'archivos', 'form.page'])->findOrFail($id);

        return response()->json($lead);
    }


    /**
     * Actualizar estatus del lead
     */
    public function updateStatus(Request $request, $id)
    {
        $lead = FacebookLead::findOrFail($id);

        $lead->estatus = $request->estatus;
        $lead->save();

        return response()->json([
            'message' => 'Estatus actualizado correctamente',
            'lead' => $lead
        ]);
    }


    /**
     * Asignar vendedor al lead
     */
    public function assignSeller(Request $request, $id)
    {
        $lead = FacebookLead::findOrFail($id);

        $lead->vendedor = $request->vendedor;
        $lead->save();

        return response()->json([
            'message' => 'Vendedor asignado correctamente',
            'lead' => $lead
        ]);
    }


    /**
     * Eliminar lead (opcional)
     */
    public function destroy($id)
    {
        $lead = FacebookLead::findOrFail($id);
        $lead->delete();

        return response()->json([
            'message' => 'Lead eliminado correctamente'
        ]);
    }
}
