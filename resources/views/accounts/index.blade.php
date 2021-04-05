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
            <div class="col-md-4">

                <h1>Tous les comptes</h1>

                <table class="table table-hover table-striped">
                    <thead>
                    <tr>
                        <th>#id</th>
                        <th>Identifiant</th>
                        <th>Adresse e-mail</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach( $accounts as $account )
                            <tr>
                                <td>{{ $account['id'] }}</td>
                                <td>{{ $account['name'] }}</td>
                                <td>{{ $account['email'] }}</td>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
            <div class="col-md-8">

                <h1>Créer un compte</h1>

                @if( isset($result) )
                    <div class="alert alert-{{ $result['result'] ? 'success' : 'danger' }} text-center">{{ $result['message'] }}</div>
                @endif

                <form action="{{ @route('accounts.index') }}" method="POST">
                    <input type="hidden" name="action" value="account_create" />
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                    <div class="form-group">
                        <label for="user_id">Utilisateur</label>
                        <select name="user_data_id" class="form-control" id="user_id">
                            <option value="" disabled selected>Choisir un utilisateur</option>
                            @foreach( $users as $user )
                                <option value="{{ $user['id'] }}">#{{ $user['id'] }}. {{ $user['firstname'] }} {{ $user['lastname'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="user_name">Identifiant</label>
                        <input type="text" name="name" class="form-control" id="user_name">
                    </div>

                    <div class="form-group">
                        <label for="user_email">Adresse e-mail</label>
                        <input type="email" name="email" class="form-control" id="user_email">
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Créer le compte
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</body>
</html>
