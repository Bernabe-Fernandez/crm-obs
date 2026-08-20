<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Lead;

class MetaController extends Controller
{
    public function getLeads()
    {
        try {

            //  Obtener token desde .env
            $token = env('META_PAGE_TOKEN');


            //  ID del formulario (el que probaste en Postman)
            $formId = '1198054145629534';


            // Consumir la API de Meta Graph API
            $response = Http::get("https://graph.facebook.com/v26.0/$formId/leads", [
                'access_token' => $token
            ]);


            // 4. Convertir respuesta a JSON
            $data = $response->json()['data'];


            // 5. Recorrer cada lead y guardarlo
            foreach ($data as $lead) {


                // Extraer campos del arreglo field_data
                $fields = collect($lead['field_data']);

                
                // Obtener valores específicos
                $fullName   = $fields->where('name', 'full_name')->first()['values'][0] ?? null;
                $phone      = $fields->where('name', 'phone_number')->first()['values'][0] ?? null;
                $email      = $fields->where('name', 'email')->first()['values'][0] ?? null;
                $city       = $fields->where('name', 'city')->first()['values'][0] ?? null;
                $interest   = $fields->where('name', '¿en_qué_estás_interesado?')->first()['values'][0] ?? null;
                $inboxUrl   = $fields->where('name', 'inbox_url')->first()['values'][0] ?? null;



                // Evitar duplicados usando el ID del lead de Meta
                $exists = Lead::where('lead_id', $lead['id'])->first();



                if (!$exists) {
                    Lead::create([
                        'lead_id'      => $lead['id'],
                        'full_name'    => $fullName, 
                        'phone_number' => $phone,
                        'email'        => $email,
                        'city'         => $city,
                        'interest'     => $interest,
                        'inbox_url'    => $inboxUrl,
                        'created_time' => $lead['created_time']
                    ]);
                }
            }

            return response()->json([
                'message' => 'Leads procesados correctamente',
                'count'   => count($data)
            ]);

        } catch (\Exception $e) {


            //  Manejo de errores
            return response()->json([
                'error' => 'Error al obtener los leads',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}

