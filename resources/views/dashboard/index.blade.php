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

                <a href="{{ @route('accounts.index')  }}" class="btn btn-secondary float-right mt-2">
                    <i class="fas fa-user-lock"></i> Gestion des comptes
                </a>
                <h1>Tous les utilisateurs</h1>

                <table class="table table-hover table-striped">
                    <thead>
                    <tr>
                        <th>#id</th>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Date de naissance</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach( $users as $user )
                            <tr>
                                <td>{{ $user['id'] }}</td>
                                <td>{{ $user['firstname'] }}</td>
                                <td>{{ $user['lastname'] }}</td>
                                <td>{{ $user['birthday'] }}</td>
                                <td class="text-right">
                                    <a class="btn btn-primary btn-sm" href="{{ @route('dashboard.user', ['id'=>$user['id']]) }}">
                                        <i class="fas fa-pen"></i>
                                        Editer
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>

</body>
</html>
