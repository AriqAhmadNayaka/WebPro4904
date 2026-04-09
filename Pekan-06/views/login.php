<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-primary">

<div class="container vh-100 d-flex justify-content-center align-items-center">
<div class="card p-4 shadow" style="width:350px;">
<h3 class="text-center">Login</h3>

<form method="POST" action="../controllers/AuthController.php">
<input name="username" class="form-control mb-2" placeholder="Username" required>
<input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
<button name="login" class="btn btn-primary w-100">Login</button>
</form>

<p class="text-center mt-3">
<a href="register.php">Register</a>
</p>
</div>
</div>
</body>
</html>