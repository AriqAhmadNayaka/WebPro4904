<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-primary d-flex justify-content-center align-items-center vh-100">

<div class="card p-4 shadow" style="width: 350px;">
    <h3 class="text-center mb-3">Register</h3>

    <form method="POST" action="proses_register.php">
        <input type="text" name="username" class="form-control mb-2" placeholder="Username" required>
        <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

        <button type="submit" class="btn btn-success w-100">Daftar</button>
    </form>

    <p class="text-center mt-3">
        Sudah punya akun? <a href="login.php">Login</a>
    </p>
</div>

</body>
</html>