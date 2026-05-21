<?php
include 'koneksi.php';

// Logika Simpan Data
if (isset($_POST['add'])) {
    $n = mysqli_real_escape_string($conn, $_POST['n']); 
    $e = mysqli_real_escape_string($conn, $_POST['e']); 
    $p = mysqli_real_escape_string($conn, $_POST['p']);
    
    // Gunakan query yang aman
    $query = "INSERT INTO users (nama, email, password) VALUES ('$n', '$e', '$p')";
    
    if ($conn->query($query)) {
        header("Location: CRUD_event.php");
        exit();
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User | WeBandoo+</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { 
            background: #F8FAFB; 
            background-image: radial-gradient(circle at 10% 20%, rgba(74, 134, 69, 0.05) 0%, transparent 50%);
            font-family: 'Plus Jakarta Sans', sans-serif; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0;
        }

        .form-card {
            background: white; 
            padding: 50px; 
            border-radius: 40px; 
            width: 100%;
            max-width: 420px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.06); 
            text-align: center;
            border: 1px solid #f0f0f0;
            position: relative;
        }

        /* Aksen Hijau di Atas Card */
        .form-card::before {
            content: '';
            position: absolute;
            top: 0; left: 50%;
            transform: translateX(-50%);
            width: 100px; height: 5px;
            background: #4A8645;
            border-radius: 0 0 10px 10px;
        }

        h2 { 
            font-weight: 800; 
            font-size: 28px; 
            color: #1a1a1a; 
            margin-bottom: 10px; 
        }

        h2 span { color: #4A8645; }

        p {
            color: #888;
            margin-bottom: 35px;
            font-size: 14px;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #4A8645;
            font-size: 18px;
        }

        input {
            width: 100%; 
            padding: 18px 18px 18px 55px; 
            border: 2px solid #f5f5f5; 
            border-radius: 18px; 
            box-sizing: border-box; 
            outline: none; 
            transition: 0.3s;
            background: #FAFAFA;
            font-size: 15px;
        }

        input:focus { 
            border-color: #4A8645; 
            background: white;
            box-shadow: 0 10px 20px rgba(74, 134, 69, 0.05);
        }

        .btn-save {
            width: 100%; 
            padding: 18px; 
            background: #4A8645; 
            color: white;
            border: none; 
            border-radius: 18px; 
            font-weight: 800; 
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 10px 25px rgba(74, 134, 69, 0.2);
            margin-top: 10px;
        }

        .btn-save:hover { 
            background: #3e703a; 
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(74, 134, 69, 0.3);
        }

        .back-link {
            display: block; 
            margin-top: 25px; 
            color: #AAA; 
            text-decoration: none; 
            font-size: 13px;
            font-weight: 600;
            transition: 0.3s;
        }

        .back-link:hover { color: #4A8645; }
    </style>
</head>
<body>

    <div class="form-card">
        <h2>Tambah <span>User</span></h2>
        <p>Silakan lengkapi data pengunjung WeBandoo+</p>
        
        <form method="POST">
            <div class="input-group">
                <i class="fa-solid fa-user"></i>
                <input type="text" name="n" placeholder="Nama Lengkap" required>
            </div>
            
            <div class="input-group">
                <i class="fa-solid fa-envelope"></i>
                <input type="email" name="e" placeholder="Email Address" required>
            </div>

            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="p" placeholder="Password Akun" required>
            </div>

            <button type="submit" name="add" class="btn-save">
                <i class="fa-solid fa-circle-check"></i> SIMPAN DATA
            </button>
        </form>

        <a href="index.php" class="back-link">
            <i class="fa-solid fa-arrow-left"></i> KEMBALI KE PANEL
        </a>
    </div>

</body>
</html>