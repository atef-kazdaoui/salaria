<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Connexion - salairia</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- FontAwesome -->
    <script defer src="{{ asset('assets/plugins/fontawesome/js/all.min.js') }}"></script>
    <!-- App CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/portal.css') }}">
</head>

<body class="app app-login p-0">
<div class="row g-0 app-auth-wrapper">
    <div class="col-12 col-md-7 col-lg-6 auth-main-col text-center p-5">
        <div class="d-flex flex-column align-content-end">
            <div class="app-auth-body mx-auto">
                <div class="app-auth-branding mb-4">
                    <a class="app-logo" href="/">
                        <img class="logo-icon me-2" src="{{ asset('assets/images/cover.png') }}" alt="logo">
                    </a>
                </div>

                <h2 class="auth-heading text-center mb-4">Connexion</h2>

                {{-- ✅ Messages flash --}}
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                {{--  Erreurs de validation --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{--  Formulaire de connexion --}}
                <div class="auth-form-container text-start">
                    <form method="POST" action="{{ route('api.login') }}">
                        @csrf

                        <div class="email mb-3">
                            <label for="email">Adresse email</label>
                            <input id="email" name="email" type="email" class="form-control"
                                   value="{{ old('email') }}" required placeholder="Adresse email">
                        </div>

                        <div class="password mb-3">
                            <label for="password">Mot de passe</label>
                            <input id="password" name="password" type="password" class="form-control"
                                   required placeholder="Mot de passe">
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn app-btn-primary w-100">Se connecter</button>
                        </div>
                    </form>

                    <div class="auth-option text-center pt-5">
                        Pas encore de compte ? <a class="text-link" href="{{ route('signup') }}">S'inscrire</a>
                    </div>
                </div><!-- //auth-form-container -->
            </div><!-- //app-auth-body -->
        </div><!-- //flex-column -->
    </div><!-- //auth-main-col -->

    <div class="col-12 col-md-5 col-lg-6 h-100 auth-background-col">
        <div class="auth-background-holder"></div>
        <div class="auth-background-mask"></div>
        <div class="auth-background-overlay p-3 p-lg-5"></div>
    </div><!-- //auth-background-col -->
</div><!-- //row -->
</body>
</html>
