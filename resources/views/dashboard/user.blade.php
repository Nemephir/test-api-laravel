<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
</head>
<body>

    <div class="container mt-5">
        <div class="row">
            <div class="col-12">

                <h1>Modifier un utilisateur</h1>
                <a href="{{ @route('dashboard.index') }}">
                    <i class="fas fa-angle-left"></i> Revenir à la liste
                </a>

                <div class="mb-4"></div>

                @if( isset($result) )
                    <div class="alert alert-{{ $result['status'] ? 'success' : 'danger' }} text-center">{{ $result['message'] }}</div>
                @endif

                @if( isset($user['status']) && $user['status'] == "error" )
                    <div class="alert alert-danger text-center">{{ $user['message'] }}</div>
                @else

                    <form action="{{ @route('dashboard.user', ['id'=>$user['id']]) }}" method="POST">
                        <input type="hidden" name="action" value="update_user" />
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                        <div class="form-group">
                            <label for="user_firstname">Prénom</label>
                            <input type="text" name="firstname" class="form-control" id="user_firstname" value="{{ $user['firstname'] }}">
                        </div>
                        <div class="form-group">
                            <label for="user_lastname">Nom</label>
                            <input type="text" name="lastname" class="form-control" id="user_lastname" value="{{ $user['lastname'] }}">
                        </div>
                        <div class="form-group">
                            <label for="user_birthday">Date de naissance</label>
                            <input type="date" name="birthday" class="form-control" id="user_birthday" value="{{ $user['birthday'] }}">
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Valider et enregistrer
                            </button>
                        </div>
                    </form>

                @endif


            </div>
        </div>
    </div>

</body>
</html>
