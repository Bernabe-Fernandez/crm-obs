<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\FacebookLead;
use App\Models\FacebookForm;

class MetaController extends Controller
{
    public function getLeads()
    {
        try {


            // Token desde .env
            $token = env('META_PAGE_TOKEN');


            // ID del formulario REAL de Meta
            $formIdMeta = '1198054145629534';


            // Buscar el formulario en tu BD por su ID de Meta
            $form = FacebookForm::where('form_id', $formIdMeta)->first();

            if (!$form) {
                return response()->json([
                    'error' => 'El formulario no existe en vt_facebook_forms',
                    'details' => $formIdMeta
                ], 400);
            }


            // Llamada correcta a Meta Graph API
            $response = Http::get("https://graph.facebook.com/v26.0/$formIdMeta/leads", [
                'access_token' => $token
            ]);


            // Convertir JSON
            $data = $response->json()['data'];

            foreach ($data as $lead) {

                $fields = collect($lead['field_data']);

                // Cam
                // pos de BD 
                $nombre   = $fields->where('name', 'full_name')->first()['values'][0] ?? null;
                $correo   = $fields->where('name', 'email')->first()['values'][0] ?? null;
                $telefono = $fields->where('name', 'phone_number')->first()['values'][0] ?? null;
                $ciudad   = $fields->where('name', 'city')->first()['values'][0] ?? null;
                $interest = $fields->where('name', '¿en_qué_estás_interesado?')->first()['values'][0] ?? null;
                $inboxUrl = $fields->where('name', 'inbox_url')->first()['values'][0] ?? null;


                // Evitar duplicados
                $exists = FacebookLead::where('facebook_lead_id', $lead['id'])->first();

                if (!$exists) {

                    FacebookLead::create([
                        'form_id'            => $form->id, // ID interno correcto
                        'facebook_lead_id'   => $lead['id'],
                        'nombre'             => $nombre,
                        'correo'             => $correo,
                        'telefono'           => $telefono,
                        'ciudad'             => $ciudad,
                        'plataforma'         => 'Facebook',
                        'estatus'            => 'nuevo',
                        'fecha_lead'         => date('Y-m-d H:i:s', strtotime($lead['created_time'])),
                        'interest'           => $interest,
                        'inbox_url'          => $inboxUrl,
                    ]);
                }
            }

            
            return response()->json([
                'message' => 'Leads guardados correctamente',
                'count'   => count($data)
            ]);


        } catch (\Exception $e) {

            return response()->json([
                'error' => 'Error al obtener los leads',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}

