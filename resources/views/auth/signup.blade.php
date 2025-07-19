<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Inscription - Mon App</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- FontAwesome -->
    <script defer src="{{ asset('assets/plugins/fontawesome/js/all.min.js') }}"></script>
    <!-- App CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/portal.css') }}">
</head>

<body class="app app-signup p-0">
<div class="row g-0 app-auth-wrapper">
    <div class="col-12 col-md-7 col-lg-6 auth-main-col text-center p-5">
        <div class="d-flex flex-column align-content-end">
            <div class="app-auth-body mx-auto">
                <div class="app-auth-branding mb-4">
                    <a class="app-logo" href="/">
                        <img class="logo-icon me-2" src="{{ asset('assets/images/cover.png') }}" alt="logo">
                    </a>
                </div>

                <h2 class="auth-heading text-center mb-4">Créer un compte</h2>

                {{-- Message de succès --}}
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Erreurs de validation --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Formulaire d'inscription --}}
                <div class="auth-form-container text-start">
                    <form method="POST" action="{{ route('api.register') }}" class="auth-form auth-signup-form">
                        @csrf

                        <div class="mb-3">
                            <label for="name">Nom complet</label>
                            <input id="name" name="name" type="text" class="form-control"
                                   value="{{ old('name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email">Adresse email</label>
                            <input id="email" name="email" type="email" class="form-control"
                                   value="{{ old('email') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="password">Mot de passe</label>
                            <input id="password" name="password" type="password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation">Confirmation du mot de passe</label>
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                   class="form-control" required>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn app-btn-primary w-100">S'inscrire</button>
                        </div>
                    </form>

                    <div class="auth-option text-center pt-5">
                        Vous avez déjà un compte ?
                        <a class="text-link" href="{{ route('login') }}">Se connecter</a>
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
