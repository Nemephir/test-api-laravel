<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class Dashboard extends Controller
{

    public function index()
    {
        // Récupération des utilisateurs via l'API
        $all = $this->requestApi( "GET" , "/user" );

        // Affichage de la liste des utilisateurs
        return view('dashboard.index', [
            'users' => $all
        ]);
    }

    public function user( Request $request , $id )
    {
        // Mises à jour de l'utilisateur si formulaire validé
        if( $request->post('action') == "update_user" ) {
            $result = $this->requestApi("PATCH", "/user/$id", $request->all());
        }

        // Récupération de l'utilisateur
        $one = $this->requestApi( "GET" , "/user/$id" );

        return view('dashboard.user', [
            'result' => isset($result) ? $result : null ,
            'user'   => $one
        ]);
    }

    /**
     * Effectue une requête à l'API
     * @param string     $type
     * @param string     $route
     * @param array|null $params
     * @return mixed
     */
    private function requestApi( string $type , string $route , ?array $params = [] )
    {
        $url = "http://testlaravelapi.jeremyzunino.fr/api{$route}";
        $ch = curl_init();

        $params['api_token'] = "CdQxJ9844bc3rw284baw8QMgQA4jjNEx3yGJZ27LxG9FTa43MVrBiFSerVV6T8p2KvLxG2e84562gbuQ5PDEd9947r8MsG8ghmXeN4Mp6s9yja36NM73Mj3U";

        switch( $type ) {
            case "GET":
                $url .= "?" . http_build_query($params);
                break;

            case "PATCH":
                $url .= "?" . http_build_query($params);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
                break;
        }

        curl_setopt($ch, CURLOPT_URL           , $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);

        $rst = json_decode( $output , true );
        if( json_last_error() ) {
            throw new \RuntimeException( json_last_error_msg() );
        }

        return $rst;
    }

}
