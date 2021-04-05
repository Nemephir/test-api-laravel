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
        // Récupération des données post
        $post     = $request->all();
        // Préparation du tableau à insérer
        $data     = [];
        // Création d'une instange de UserData
        $userData = new UserData();

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
            // Sinon, le champ est valide, on l'ajoute à la liste des données à faire entrer
            else
            {
                $data[$field] = $post[$field];
            }
        }

        // Tous les champs sont valides
        try {
            UserData::create( $request->all() );
            return $this->returnSuccess( $this->errorMessage("create_success") );
        }
        catch( \RuntimeException $e ) {
            return $this->returnError( $e->getMessage() );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserData  $userData
     * @return \Illuminate\Http\Response
     */
    public function show(UserData $userData)
    {
        //
    }

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
    public function destroy(UserData $userData)
    {
        //
    }

    private function errorMessage( string $key , array $data = [] ) : string
    {
        switch( $key ) {
            case "create_success" : return "L'utilisateur a bien été ajouté";
            case "empty_field"    : return "Champ {$data['field']} invalide";
            default               : return "";
        }
    }


    private function returnSuccess( string $msg )
    {
        return response()->json( [
            'status'  => "success",
            'message' => $msg
        ], 200 );
    }


    private function returnError( string $msg , ?string $fieldName , ?int $httpCode = 500 )
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
}
