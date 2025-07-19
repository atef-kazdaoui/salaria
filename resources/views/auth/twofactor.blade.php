<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Vérification en 2 étapes</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="{{ asset('assets/css/portal.css') }}">
</head>
<body>
<div class="container mt-5">
    <h2>Vérification par code</h2>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('2fa.store') }}">
        @csrf
        <div class="mb-3">
            <label for="two_factor_code">Code reçu par email</label>
            <input type="text" name="two_factor_code" class="form-control" required autofocus>
        </div>
        <button type="submit" class="btn btn-primary">Valider</button>
    </form>
</div>
</body>
</html>
