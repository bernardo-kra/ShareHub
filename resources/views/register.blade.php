<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
</head>
<body>
    <h2>Registro</h2>

    <form method="POST" action="/register">
        @csrf

        <label for="name">Nome</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Senha</label>
        <input type="password" id="password" name="password" required>

        <label for="password_confirmation">Confirme a senha</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required>

        <button type="submit">Registrar</button>
    </form>
</body>
</html>
