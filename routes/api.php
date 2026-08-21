<?php

use App\Http\Controllers\ArchivoController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\LeadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MetaController; //  Importa tu controlador
use App\Http\Controllers\PageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'La API está funcionando correctamente.',
        'data' => [
            'application' => config('app.name'),
            'environment' => app()->environment(),
            'timestamp' => now()->toISOString(),
        ],
    ]);
});



//Ruta para obtener los leads desde Meta Graph API

Route::get('/prospectos', [MetaController::class, 'getLeads']);
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



        /////           RUTAS API     ///////////


// LEADS
Route::get('/leads', [LeadController::class, 'index']);
Route::get('/leads/{id}', [LeadController::class, 'show']);
Route::put('/leads/{id}/estatus', [LeadController::class, 'updateStatus']);
Route::put('/leads/{id}/vendedor', [LeadController::class, 'assignSeller']);
Route::delete('/leads/{id}', [LeadController::class, 'destroy']);



// COMENTARIOS
Route::get('/leads/{lead_id}/comentarios', [CommentController::class, 'index']);
Route::post('/leads/{lead_id}/comentarios', [CommentController::class, 'store']);
Route::put('/comentarios/{id}', [CommentController::class, 'update']);
Route::delete('/comentarios/{id}', [CommentController::class, 'destroy']);



// ARCHIVOS
Route::get('/leads/{lead_id}/archivos', [ArchivoController::class, 'index']);
Route::post('/leads/{lead_id}/archivos', [ArchivoController::class, 'store']);
Route::delete('/archivos/{id}', [ArchivoController::class, 'destroy']);



// FORMULARIOS
Route::get('/formularios', [FormController::class, 'index']);
Route::get('/formularios/{id}', [FormController::class, 'show']);
Route::get('/formularios/{id}/leads', [FormController::class, 'leads']);
Route::put('/formularios/{id}/estatus', [FormController::class, 'updateStatus']);
  


// PÁGINAS
Route::get('/pages', [PageController::class, 'index']);
Route::get('/pages/{id}', [PageController::class, 'show']);
Route::get('/pages/{id}/forms', [PageController::class, 'forms']);
Route::put('/pages/{id}/token', [PageController::class, 'updateToken']);
