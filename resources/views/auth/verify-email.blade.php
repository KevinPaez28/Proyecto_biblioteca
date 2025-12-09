<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verifica tu correo</title>
</head>
<body>
    <h1>Verifica tu correo</h1>
    <p>Revisa tu correo para encontrar el enlace de verificación.</p>

    @if (session('message'))
        <p>{{ session('message') }}</p>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit">Reenviar correo</button>
    </form>
</body>
</html>
