<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecordsController extends Controller
{
//  функция получения токена из класса ExecutiveController ----------------------------------------
    public function success_token()
    {
        $ExecutiveController = new ExecutiveController();
        $success_token = $ExecutiveController->generate_access_token();
        $success_token = $success_token['access_token'];
        return $success_token;
    }

//  функция получения списка всех сделок ----------------------------------------------------------
    public function get_deals()
    {
        $success_token = $this->success_token();

        $post = [
            'sort_order ' => 'desc',
            'sort_by' => 'Created_Time'
        ];

        return $this->curl($post, $success_token, 'GET', 'https://www.zohoapis.com/crm/v2/Deals');
    }

//  функция получения полей для модулей --------------------------------------------------------------
    public function get_fields(){
        $success_token = $this->success_token();

        $post = [
            'module' => 'Tasks'
        ];

        return $this->curl($post, $success_token, 'GET', 'https://www.zohoapis.com/crm/v2/settings/fields?module=Tasks');
    }

//  функция создания сделки -----------------------------------------------------------------------------
    public function add_deals(Request $request){

        $success_token = $this->success_token();

        $post = [
            'data' => [[
                'Deal_Name' => $request->get('Deal_Name'), // Сделка - имя
                'Stage' => 'Needs Analysis', // Стадия
                'Account_Name' => [ // Контрагент - имя
                    "name" => "King (Sample)",
                    "id" => "4877310000000322099"
                ]
            ]],
            'trigger' => [
                'approval',
                'workflow',
                'blueprint'
            ]
        ];

        $this->curl($post, $success_token, 'POST', 'https://www.zohoapis.com/crm/v2/Deals');

        return redirect()->route('index');

    }

//  функция создания задачи под определенную сделку ----------------------------------------------------------------
    public function add_task(Request $request){

        $success_token = $this->success_token();

        $post = [
            'data' => [[
                'Subject' => $request->get('name_task'),
                'What_Id' => [
                    'name' => $request->get('name_deal'),
                    'id' => $request->get('id_deal')
                ],
//                "Who_Id" => [
//                    "name" => "golovin.andrey.27",
//                    "id" => "4877310000000309001"
//                ],
                '$se_module' => "Deals"
            ]],
            'trigger' => [
                'approval',
                'workflow',
                'blueprint'
            ]
        ];

        $this->curl($post, $success_token, 'POST', 'https://www.zohoapis.com/crm/v2/Tasks');

        return redirect()->route('index');

    }

//  функция отправки запроса на сервер ------------------------------------------------------------------------------------
    public function curl($post,$success_token, $method, $url = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        if($method == 'POST'){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post) );
        }else{
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query( $post ) );
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Zoho-oauthtoken ' . $success_token,
            'Content-Type: application/x-www-form-urlencoded'
        ));

        $response = curl_exec($ch);

        $response = json_decode($response, true);

        return $response;
    }
}
