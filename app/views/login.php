<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="/style/app.css">
    <link rel="icon" type="image/svg+xml" href="/icon/logo.svg">
</head>
<body>
    <main>
        <img class="logo" src="/icon/logo.svg" alt="Logo">
        <?php if(isset($data['error'])): ?>
            <div class="error-alert"><?= $data['error'] ?></div>
        <?php endif; ?>
        <form action="/login" method="post">
            <input type="text" name="username" placeholder="Username">
            <input type="password" name="password" placeholder="Password">
            <button type="submit">Login</button>
        </form>
    </main>
</body>
</html>