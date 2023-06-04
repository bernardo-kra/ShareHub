<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
</head>

<body>
    <h2>Login</h2>

    <form method="POST" action="/login">
        @csrf

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Senha</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Entrar</button>
    </form>
</body>

</html>
