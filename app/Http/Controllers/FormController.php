<?php

namespace App\Http\Controllers;

use App\Models\FacebookForm;
use App\Models\FacebookLead;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FormController extends Controller
{
    /**
     * Listar todos los formularios
     */
    public function index()
    {
        try {
            $formularios = FacebookForm::with('page')
                ->orderBy('id', 'desc')
                ->get();

            return response()->json($formularios, 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener los formularios'
            ], 500);
        }
    }

    /**
     * Mostrar un formulario específico
     */
    public function show($id)
    {
        try {
            $formulario = FacebookForm::with(['page', 'leads'])
                ->findOrFail($id);

            return response()->json($formulario, 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Formulario no encontrado'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener el formulario'
            ], 500);
        }
    }

    /**
     * Obtener los leads de un formulario pendiente
     */
    public function leads($id)
    {
        try {
            $leads = FacebookLead::where('form_id', $id)
                ->orderBy('fecha_lead', 'desc')
                ->get();

            return response()->json($leads, 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener los leads del formulario'
            ], 500);
        }
    }

    /**
     * Actualizar estatus del formulario pendiente
     */
    public function updateStatus(Request $request, $id)
    {
        // Validación
        $request->validate([
            'estatus' => 'required|string|max:50'
        ]);

        try {
            $formulario = FacebookForm::findOrFail($id);

            $formulario->estatus = $request->estatus;
            $formulario->save();

            return response()->json([
                'message' => 'Estatus del formulario actualizado correctamente',
                'formulario' => $formulario
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Formulario no encontrado'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al actualizar el estatus del formulario'
            ], 500);
        }
    }
}

