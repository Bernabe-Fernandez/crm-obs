<?php

namespace App\Http\Controllers;

use App\Models\FacebookPage;
use App\Models\FacebookForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{


    /**
     * Listar todas las páginas
     */
    public function index()
    {
        try {
            $pages = FacebookPage::with('forms')
                ->orderBy('id', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $pages
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al obtener las páginas',
                'details' => $e->getMessage()
            ], 500);
        }
    }



    /**
     * Mostrar una página específica
     */
    public function show($id)
    {
        try {
            $page = FacebookPage::with('forms')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $page
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Página no encontrada',
                'details' => $e->getMessage()
            ], 404);
        }
    }


    /**
     * Obtener formularios de una página
     */
    public function forms($id)
    {
        try {
            $forms = FacebookForm::where('page_id', $id)->get();

            return response()->json([
                'success' => true,
                'data' => $forms
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al obtener los formularios',
                'details' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Actualizar token de la página para actualizar manualmente el token de la página en la base de datos
     */
    public function updateToken(Request $request, $id)
    {
        // Validación 
        $request->validate([
            'token' => 'required|string|max:255'
        ]);

        DB::beginTransaction();

        try {
            $page = FacebookPage::findOrFail($id);

            $page->token = $request->token;
            $page->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Token actualizado correctamente',
                'data' => $page
            ], 200);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'error' => 'Error al actualizar el token',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
