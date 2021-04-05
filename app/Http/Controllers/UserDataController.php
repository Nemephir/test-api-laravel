<?php

namespace App\Http\Controllers;

use App\Models\UserData;
use Illuminate\Http\Request;

class UserDataController extends Controller
{
    /**
     * [API] Lister les utilisateurs
     * @return mixed
     */
    public function index()
    {
        return UserData::orderByDesc('created_at')->get();
    }

    /**
     * [API] Créer un utilisateur
     * @param Request $request
     * @return bool|\Illuminate\Http\JsonResponse
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
     * [API] Afficher un utilisateur
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $one = UserData::where('id', $id)->first();
        if( ! $one ) {
            return $this->returnError( $this->errorMessage("not_found_one") );
        }
        else {
            return $one->toJson();
        }
    }

    /**
     * [API] Modifier un utilisateur
     * @param Request $request
     * @param         $id
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Test de vérification des données entrantes
        $test = $this->checkEntry( $request );
        if( $test !== true ) {
            return $test;
        }
        else
        {
            $one = UserData::where('id', $id)->first();
            if( ! $one ) {
                return $this->returnError( $this->errorMessage("not_found_one") );
            }
            else {
                try {
                    $one->update( $request->all() );
                    return $this->returnSuccess( $this->errorMessage("update_success") );
                }
                catch( \RuntimeException $e ) {
                    return $this->returnError( $e->getMessage() );
                }
            }
        }
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

    /**
     * Vérifie les données saisie pour la création et la modification d'un utilisateur
     * @param Request $request
     * @return bool|\Illuminate\Http\JsonResponse
     */
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

    /**
     * Renvoie une notification de succès
     * @param string $msg
     * @return \Illuminate\Http\JsonResponse
     */
    private function returnSuccess( string $msg )
    {
        return response()->json( [
            'status'  => "success",
            'message' => $msg
        ], 200 );
    }

    /**
     * Renvoie une  notification d'erreur d'exécution personnalisée
     * @param string      $msg
     * @param string|null $fieldName
     * @param int|null    $httpCode
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Permet de récupérer un message de notification à partir d'une clé
     * Permet également de centraliser tous les messages au même endroit.
     * @param string $key
     * @param array  $data
     * @return string
     */
    private function errorMessage( string $key , array $data = [] ) : string
    {
        switch( $key ) {
            case "create_success" : return "L'utilisateur a bien été ajouté";
            case "update_success" : return "L'utilisateur a bien été modifié";
            case "delete_success" : return "L'utilisateur a bien été supprimé";
            case "invalid_id"     : return "L'id saisie est invalide";
            case "not_found_one"  : return "L'utilisateur demandé n'a pas été trouvé";
            case "empty_field"    : return "Champ {$data['field']} invalide";
            default               : return "";
        }
    }
}
