<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\FacebookLead;

class MetaController extends Controller
{
    public function getLeads()
    {


        try {


            // Token desde .env
            $token = env('META_PAGE_TOKEN');


            // ID del formulario
            $formId = '1198054145629534';


            // // Llamada correcta a Meta (la que sí funcionaba)
            // $response = Http::get("https://graph.facebook.com/v26.0/$formId/leads", [
            //     'access_token' => $token
            // ]);

            $response = Http::get("https://graph.facebook.com/v26.0/1198054145629534/leads?access_token=EAAMbfY3jKiwBSKDm6vzsplxniqQNIaSSdMsaKoAztZCLvv5oZCjkBRJTuwso1sjsYRPZBYeDk2OfKlrO96rLHIyyRe1aAWN99ZCas8IVpSXP8k6q7dnrI5mceii7PczdbOjW8lNDdXh5zlmxxWMrP6s3sEkUKExkSbCk1ZAf7mkVVTGI9tlbXOSScaCdW7DRgP8JXKVnl");





             //convertir JSON en BD 
            $data = $response->json()['data'];

            foreach ($data as $lead) {

                $fields = collect($lead['field_data']);


                // Campos de BD 
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
                        'form_id'            => $formId,
                        'facebook_lead_id'   => $lead['id'],
                        'nombre'             => $nombre,
                        'correo'             => $correo,
                        'telefono'           => $telefono,
                        'ciudad'             => $ciudad,
                        'plataforma'         => 'Facebook',
                        'estatus'            => 'nuevo',
                        'fecha_lead'         => $lead['created_time'],
                        'interest'         => $interest,
                        'inbox_url'        => $inboxUrl,
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

