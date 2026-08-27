<?php

namespace App\Http\Controllers;

use App\Models\LeadArchivo;
use App\Models\FacebookLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ArchivoController extends Controller
{
    /**
     * Obtener archivos de un lead
     */
    public function index($lead_id)
    {

        try {
            
            $archivos = LeadArchivo::where('lead_id', $lead_id)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $archivos
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al obtener los archivos',
                'details' => $e->getMessage()
            ], 500);
        }
    }



    /**
     * Subir archivo a un lead
     */
    public function store(Request $request, $lead_id)
    {

        // Validación profesional
        $request->validate([
            'archivo' => 'required|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx,xlsx,xls'
        ]);

        DB::beginTransaction();



        try {

            // Validar que el lead exista
            $lead = FacebookLead::findOrFail($lead_id);


            // Guardar archivo en storage
            $file = $request->file('archivo');
            $ruta = $file->store("leads/$lead_id", 'public');


            // Crear registro en BD
            $archivo = LeadArchivo::create([
                'lead_id'        => $lead_id,
                'nombre_archivo' => $file->getClientOriginalName(),
                'ruta'           => $ruta,
                'tipo'           => $file->getClientMimeType(),
                'tamano'         => $file->getSize(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Archivo subido correctamente',
                'data' => $archivo
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
                'error' => 'Error al subir el archivo',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
