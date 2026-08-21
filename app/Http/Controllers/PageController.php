<?php

namespace App\Http\Controllers;

use App\Models\FacebookPage;
use App\Models\FacebookForm;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Listar todas las páginas
     */
    public function index()
    {
        $pages = FacebookPage::with('forms')->orderBy('id', 'desc')->get();

        return response()->json($pages);
    }

    /**
     * Mostrar una página específica
     */
    public function show($id)
    {
        $page = FacebookPage::with('forms')->findOrFail($id);

        return response()->json($page);
    }

    /**
     * Obtener formularios de una página
     */
    public function forms($id)
    {
        $forms = FacebookForm::where('page_id', $id)->get();

        return response()->json($forms);
    }

    /**
     * Actualizar token de la página
     */
    public function updateToken(Request $request, $id)
    {
        $page = FacebookPage::findOrFail($id);

        $page->token = $request->token;
        $page->save();

        return response()->json([
            'message' => 'Token actualizado correctamente',
            'page' => $page
        ]);
    }
}