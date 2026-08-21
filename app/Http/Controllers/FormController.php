<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers;

use App\Models\FacebookForm;
use App\Models\FacebookLead;
use Illuminate\Http\Request;

class FormController extends Controller
{
    /**
     * Listar todos los formularios
     */
    public function index()
    {
        $formularios = FacebookForm::with('page')->orderBy('id', 'desc')->get();

        return response()->json($formularios);
    }

    /**
     * Mostrar un formulario específico
     */
    public function show($id)
    {
        $formulario = FacebookForm::with(['page', 'leads'])->findOrFail($id);

        return response()->json($formulario);
    }

    /**
     * Obtener los leads de un formulario
     */
    public function leads($id)
    {
        $leads = FacebookLead::where('form_id', $id)
            ->orderBy('fecha_lead', 'desc')
            ->get();

        return response()->json($leads);
    }

    /**
     * Actualizar estatus del formulario
     */
    public function updateStatus(Request $request, $id)
    {
        $formulario = FacebookForm::findOrFail($id);

        $formulario->estatus = $request->estatus;
        $formulario->save();

        return response()->json([
            'message' => 'Estatus del formulario actualizado correctamente',
            'formulario' => $formulario
        ]);
    }
}
