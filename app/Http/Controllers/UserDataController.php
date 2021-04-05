<?php

namespace App\Http\Controllers;

use App\Models\UserData;
use Illuminate\Http\Request;

class UserDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return UserData::orderByDesc('created_at')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Test de vérification des données entrantes
        $test = $this->checkEntry( $request );
        if( $test !== true ) {
            return $test;
        }
        else {
            // Tous les champs sont valides
            try {
                UserData::create( $request->all() );
                return $this->returnSuccess( $this->errorMessage("create_success") );
            }
            catch( \RuntimeException $e ) {
                return $this->returnError( $e->getMessage() );
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserData  $userData
     * @return \Illuminate\Http\Response
     */
//    public function show(UserData $userData)
//    {
//        //
//    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserData  $userData
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserData $userData)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserData  $userData
     * @return \Illuminate\Http\Response
     */
//    public function destroy($id)
//    {
//        // Si l'id est invalide
//        if( empty($id) || ! is_numeric($id) || $id < 0 ) {
//            return $this->returnError( $this->errorMessage("invalid_id") );
//        }
//        else {
//            $one = UserData::where('id', $id)->first();
//            if( ! $one ) {
//                return $this->returnError( $this->errorMessage("not_found_one") );
//            }
//            else {
//                try {
//                    $one->delete();
//                    return $this->returnSuccess( $this->errorMessage("delete_success") );
//                }
//                catch( \RuntimeException $e ) {
//                    return $this->returnError( $e->getMessage() );
//                }
//            }
//        }
//    }

    private function checkEntry( Request $request )
    {
        $post     = $request->all(); // Récupération des données post
        $data     = [];              // Préparation du tableau à insérer
        $userData = new UserData();  // Création d'une instange de UserData

        // Pour chaque champ remplissable du modèle
        foreach( $userData->getFillable() as $field ) {
            // Si le champ n'est pas présent dans la requête ou que la valeur renseignée est nulle
            if( ! array_key_exists( $field, $post) || empty($post[$field]) ) {
                // Renvoie d'une erreur
                return $this->returnError(
                    $this->errorMessage( "empty_field", ['field'=>$field] ) ,
                    $field
                );
            }
        }

        return true;
    }

    private function returnSuccess( string $msg )
    {
        return response()->json( [
            'status'  => "success",
            'message' => $msg
        ], 200 );
    }

    private function returnError( string $msg , ?string $fieldName = NULL , ?int $httpCode = 500 )
    {
        $data = [
            'status'  => "error",
            'message' => $msg
        ];

        if( ! empty($fieldName) ) {
            $data['field'] = $fieldName;
        }

        return response()->json( $data , $httpCode );
    }

    private function errorMessage( string $key , array $data = [] ) : string
    {
        switch( $key ) {
            case "create_success" : return "L'utilisateur a bien été ajouté";
            case "invalid_id"     : return "L'id saisie est invalide";
            case "not_found_one"  : return "L'utilisateur demandé n'a pas été trouvé";
            case "delete_success" : return "L'utilisateur a bien été supprimé";
            case "empty_field"    : return "Champ {$data['field']} invalide";
            default               : return "";
        }
    }
}
