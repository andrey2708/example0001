<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ExecutiveController extends Controller
{
    public function index()
    {

        $RecordsController = new RecordsController();

//        получения списка полей
//        $items = $RecordsController->get_fields();
//        $mas_field = [];
//        foreach ($items['fields'] as $field){
//            $mas_field[$field['api_name']] = 'null';
//        }
//        dd($mas_field);

        $success_token = $this->generate_access_token();

        if((count($success_token) == 1) && ($success_token['error'] == 'invalid_code')){
            $status_token = 'error: invalid_code';
            $status = false;
        }else{
            $status_token = 'Status true, token: "'.$success_token['access_token'].'"';
            $status = true;
        }

        dd($get_records_list = $RecordsController->get_deals());

        return view('page_api', [
            'status_token' => $status_token,
            'status' => $status,
            'list_deals' => $get_records_list['data']
        ]);

    }

//  Создание токена доступа ----------------------------------------------------------
    public function generate_refresh_token()
    {
         $post = [
         	'code' => '1000.dc85b6851d67546d470596d08ee5ebda.fd2619d9438c511591d8ef760a2dd5a2',
         	'redirect_uri' => 'http://example.com/callbackurl',
         	'client_id' => '1000.1C7CFUVUQOGDM1671E0ICDY1RT785X',
         	'client_secret' => '3459da13669530fe9e2d9abbd9e2936707ddfd0ab1',
         	'grant_type' => 'authorization_code',
         ];

        $response = $this->curl($post);

        if(count($response) == 1){

            dd($response);

        }else{

            $refresh_token = $response['refresh_token'];

            Storage::disk('public')->put("token.txt", $refresh_token);

            return redirect()->route('index');
        }
    }

//  Обновление токена доступа ---------------------------------------------------------
    public function generate_access_token($refresh_token = null)
    {
        $refresh_token = !is_null($refresh_token) ? $refresh_token : Storage::disk('public')->get("token.txt");

        $post = [
            'refresh_token' => $refresh_token,
            'client_id' => '1000.1C7CFUVUQOGDM1671E0ICDY1RT785X',
            'client_secret' => '3459da13669530fe9e2d9abbd9e2936707ddfd0ab1',
            'grant_type' => 'refresh_token',
        ];

        return $this->curl($post);
    }

//  функция передачи данных на сервер -----------------------------------------------------------
    public function curl($post, $url = null)
    {
        $url = !is_null($url) ? $url : 'https://accounts.zoho.com/oauth/v2/token'; // если url не передан, то подставляем url авторизации

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query( $post ) );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/x-www-form-urlencoded' ));

        $response = curl_exec($ch);

        $response = json_decode($response, true);

        return $response;
    }

}
