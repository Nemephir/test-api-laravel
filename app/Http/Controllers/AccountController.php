<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    private $userDataId, $username, $email;

    public function index(Request $request)
    {
        // Mises à jour de l'utilisateur si formulaire validé
        if( $request->post('action') == "account_create" ) {
            // Récupération des données post
            $this->parsePost( $request );
            // Vérifie les données post
            $result = $this->checkDatas();

            // Si les données saisies sont correctes
            if( $result['result'] ) {
                // Génération du mot de passe
                $password = Str::random(12);

                // Création de l'utilisateur
                $user = new User;
                $user->name     = $this->username;
                $user->email    = $this->email;
                $user->password = Hash::make( $password );
                $user->data_id  = $this->userDataId;
                if( $action = $user->save() ) {
                    $result['message'] = "L'utilisateur a bien été créé";
                }
                else {
                    $result['result']  = false;
                    $result['message'] = "Une erreur est survenue lors de la création de l'utilisateur";
                }
            }
        }

        // Récupération de tous les comptes utilisateur existant
        $existingAccounts = User::select('id', 'email', 'name', 'data_id')
            ->get()
            ->toArray();

        // Extraction des ids des comptes déjà existant
        $existingAccountsIds = [];
        foreach( $existingAccounts as $item ) {
            if( $item['data_id'] > 0 ) {
                $existingAccountsIds[] = $item['data_id'];
            }
        }

        // Récupération de la liste de tous les ids qui ne sont pas associés à un compte
        $usersWithoutAccount = UserData::select(['id', 'firstname', 'lastname'])
            ->whereNotIn( 'id' , $existingAccountsIds )
            ->get()
            ->toArray();

        // Affichage de la liste des utilisateurs
        return view('accounts.index', [
            'result'   => isset($result) ? $result : null,
            'accounts' => $existingAccounts,
            'users'    => $usersWithoutAccount
        ]);

    }

    /**
     * Récupère les données post en les nettoyant
     * @param $request
     */
    private function parsePost( $request )
    {
        $this->userDataId = trim( $request->post('user_data_id') );
        $this->username   = trim( $request->post('name') );
        $this->email      = trim( $request->post('email') );
    }

    /**
     * Vérifie les données post avant de créer l'utilisateur
     * @return array
     */
    private function checkDatas()
    {
        $result = [
            'result'  => false,
            'message' => "",
        ];

        // Vérification de l'existance de l'utilisateur
        if( ! $this->checkUserDataExists($this->userDataId) )
            $result['message'] = "L'utilisateur demandé n'a pas été trouvé";

        // Vérification sur le fait que l'utilisateur ne soit pas encore utilisé pour un compte
        elseif( ! $this->checkUserNotAlreadyUsed($this->userDataId) )
            $result['message'] = "L'utilisateur est déjà utilisé pour un compte";

        // Vérificaton du nom d'utilisateur
        elseif( ! strlen($this->username) > 3 )
            $result['message'] = "Le nom d'utilisateur est trop court (min. 3 caractères)";

        // Vérificaton du nom d'utilisateur bis
        elseif( ! $this->isUniqueUsername($this->username) )
            $result['message'] = "Le nom d'utilisateur est déjà utilisé";

        // Vérificaton de l'adresse e-mail
        elseif( ! filter_var($this->email, FILTER_VALIDATE_EMAIL) )
            $result['message'] = "L'adresse e-mail est déjà utiliséz";

        // Vérificaton de l'adresse e-mail bis
        elseif( ! $this->isUniqueEmail($this->email) )
            $result['message'] = "L'adresse e-mail est déjà utiliséz";

        else
            $result['result'] = true;

        return $result;
    }


    private function checkUserDataExists( $userDataId )
    {
        return UserData::where('id', '=', $userDataId)->get()->count() > 0;
    }

    private function checkUserNotAlreadyUsed( $userDataId )
    {
        return User::where('data_id', '=', $userDataId)->get()->count() == 0;
    }

    private function isUniqueUsername( $username )
    {
        return User::where('name', '=', $username)->get()->count() == 0;
    }

    private function isUniqueEmail( $email )
    {
        return User::where('email', '=', $email)->get()->count() == 0;
    }
}
