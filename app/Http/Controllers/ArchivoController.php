<?php

//controlador de archivos

namespace App\Http\Controllers;

use App\Models\LeadArchivo;
use App\Models\FacebookLead;
use Illuminate\Http\Request;

class ArchivoController extends Controller
{


    /**
     * Obtener archivos de un lead
     */
    public function index($lead_id)
    {
        $archivos = LeadArchivo::where('lead_id', $lead_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($archivos);
    }


    /**
     * Subir archivo a un lead
     */
    public function store(Request $request, $lead_id)
    {
        // Validar que el lead exista
        $lead = FacebookLead::findOrFail($lead_id);

        // Validar archivo
        $request->validate([
            'archivo' => 'required|file|max:10240', // 10 MB
        ]);

        // Guardar archivo en storage
        $file = $request->file('archivo');
        $ruta = $file->store("leads/$lead_id", 'public');

        // Crear registro en BD
        $archivo = LeadArchivo::create([
            'lead_id' => $lead_id,
            'nombre_archivo' => $file->getClientOriginalName(), 
            'ruta' => $ruta,
            'tipo' => $file->getClientMimeType(),
            'tamano' => $file->getSize(),
        ]);

        return response()->json([
            'message' => 'Archivo subido correctamente',
            'archivo' => $archivo
        ]);
    }


    /**
     * Eliminar archivo
     */
    public function destroy($id)
    {
        $archivo = LeadArchivo::findOrFail($id);

        // Eliminar archivo físico
        if (file_exists(storage_path("app/public/" . $archivo->ruta))) {
            unlink(storage_path("app/public/" . $archivo->ruta));
        }


        // Eliminar registro en BD
        $archivo->delete();

        return response()->json([
            'message' => 'Archivo eliminado correctamente'
        ]);
    }
}