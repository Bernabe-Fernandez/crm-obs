<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\FacebookLead;
use App\Models\FacebookForm;

class MetaController extends Controller
{
    public function getLeads()
    {
        DB::beginTransaction(); // TRANSACCIÓN

        try {


            // Token desde .env
            $token = env('META_PAGE_TOKEN');


            
            // ID del formulario REAL de Meta
            $formIdMeta = '1198054145629534';



            // Buscar el formulario en tu BD por su ID de Meta
            $form = FacebookForm::firstWhere('form_id', $formIdMeta);


            if (!$form) {
                DB::rollBack();
                return response()->json([
                    'error' => 'El formulario no existe en vt_facebook_forms',
                    'details' => $formIdMeta
                ], 400);
            }


            // Llamada a Meta Graph API
            $response = Http::get("https://graph.facebook.com/v26.0/$formIdMeta/leads", [
                'access_token' => $token
            ]);



            // Validar si Meta devolvió error
            if ($response->failed()) {
                DB::rollBack();    //Deshaz absolutamente todos los cambios que se hicieron en la base de dato
                return response()->json([
                    'error' => 'Meta Graph API devolvió un error',
                    'details' => $response->json()
                ], 500);
            }


            // Validar estructura
            $json = $response->json();

            if (!isset($json['data'])) {
                DB::rollBack();
                return response()->json([
                    'error' => 'Meta no devolvió leads',
                    'details' => $json
                ], 500);
            }


            $data = $json['data'];

            foreach ($data as $lead) {


                // Validar estructura del lead (se esta incmpleto un led saltalo )
                if (!isset($lead['field_data'])) {
                    continue;  // Evita errores si Meta manda un lead incompleto
                }

                

                // convertir campos de meta en una coleccion y poder filtrarlos con firstWhere.
                $fields = collect($lead['field_data']); 



                // optional() para evitar errores cuando Meta no envía un campo, firstWhere para ejecutar la consulta de un solo registro.

                $nombre   = optional($fields->firstWhere('name', 'full_name'))['values'][0] ?? null;
                $correo   = optional($fields->firstWhere('name', 'email'))['values'][0] ?? null;
                $telefono = optional($fields->firstWhere('name', 'phone_number'))['values'][0] ?? null;
                $ciudad   = optional($fields->firstWhere('name', 'city'))['values'][0] ?? null;
                $interest = optional($fields->firstWhere('name', '¿en_qué_estás_interesado?'))['values'][0] ?? null;
                $inboxUrl = optional($fields->firstWhere('name', 'inbox_url'))['values'][0] ?? null;


                
                // Evitar duplicados
                $exists = FacebookLead::firstWhere('facebook_lead_id', $lead['id']);



                //Guardar en la BD
                
                if (!$exists) {
                    FacebookLead::create([
                        'form_id'            => 1, 
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


            DB::commit(); // GUARDAR LOS CAMBIOS SI TODO SALE BIEN

            return response()->json([
                'message' => 'Leads guardados correctamente',
                'count'   => count($data)
            ], 200);



        } catch (\Exception $e) {


            DB::rollBack(); // SI ALGO SALE MAL DESHAZ TODO 

            return response()->json([
                'error' => 'Error al obtener los leads',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}


