<!DOCTYPE html>
<html>
<head>
<title>Tambah Pasien</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
<h3>Tambah Data</h3>

<form method="POST" action="../controllers/PatientController.php" enctype="multipart/form-data">
<input name="nama" class="form-control mb-2" placeholder="Nama" required>
<input name="umur" type="number" class="form-control mb-2" placeholder="Umur" required>
<input name="penyakit" class="form-control mb-2" placeholder="Penyakit" required>
<input type="file" name="file" class="form-control mb-3">
<img id="preview" width="120" class="mt-2"/>

<button name="simpan" class="btn btn-primary">Simpan</button>
</form>
</div>
<script>
document.querySelector('input[name="file"]').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('preview');

    if (file) {
        preview.src = URL.createObjectURL(file);
    }
});
</script>
</body>
</html>