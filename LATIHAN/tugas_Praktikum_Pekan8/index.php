<?php
// ============================================
// Aplikasi CRUD Sederhana - Konsep MVC
// ============================================

// ---------- MODEL ----------
class Model {
    private $file = 'data.json';
    private $data = [];

    public function __construct() {
        $this->loadData();
    }

    private function loadData() {
        if (file_exists($this->file)) {
            $json = file_get_contents($this->file);
            $this->data = json_decode($json, true) ?? [];
        }
    }

    private function saveData() {
        file_put_contents($this->file, json_encode($this->data, JSON_PRETTY_PRINT));
    }

    public function getAll() {
        return $this->data;
    }

    public function getById($id) {
        foreach ($this->data as $item) {
            if ($item['id'] == $id) {
                return $item;
            }
        }
        return null;
    }

    public function create($data) {
        $data['id'] = count($this->data) > 0 ? max(array_column($this->data, 'id')) + 1 : 1;
        $this->data[] = $data;
        $this->saveData();
        return $data['id'];
    }

    public function update($id, $data) {
        foreach ($this->data as &$item) {
            if ($item['id'] == $id) {
                $data['id'] = $id;
                $item = $data;
                $this->saveData();
                return true;
            }
        }
        return false;
    }

    public function delete($id) {
        foreach ($this->data as $key => $item) {
            if ($item['id'] == $id) {
                unset($this->data[$key]);
                $this->data = array_values($this->data);
                $this->saveData();
                return true;
            }
        }
        return false;
    }
}

// ---------- CONTROLLER ----------
$model = new Model();
$action = $_GET['action'] ?? 'index';
$message = '';

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $model->create([
            'nim' => $_POST['nim'],
            'nama' => $_POST['nama'],
            'jurusan' => $_POST['jurusan'],
            'nilai' => $_POST['nilai']
        ]);
        $message = 'Data berhasil ditambahkan!';
    } elseif (isset($_POST['update'])) {
        $model->update($_POST['id'], [
            'nim' => $_POST['nim'],
            'nama' => $_POST['nama'],
            'jurusan' => $_POST['jurusan'],
            'nilai' => $_POST['nilai']
        ]);
        $message = 'Data berhasil diperbarui!';
    } elseif (isset($_POST['delete'])) {
        $model->delete($_POST['id']);
        $message = 'Data berhasil dihapus!';
    }
    header('Location: index.php');
    exit;
}

// Handle GET actions
$editData = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $editData = $model->getById($_GET['id']);
} elseif ($action === 'delete' && isset($_GET['id'])) {
    $model->delete($_GET['id']);
    $message = 'Data berhasil dihapus!';
    header('Location: index.php');
    exit;
}

$dataMahasiswa = $model->getAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi CRUD Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .container { max-width: 900px; margin-top: 50px; }
        .card { border: none; shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .table-header { background-color: #0d6efd; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card p-4">
            <h2 class="text-center mb-4">📚 Aplikasi CRUD Mahasiswa</h2>
            
            <?php if ($message): ?>
                <div class="alert alert-success"><?= $message ?></div>
            <?php endif; ?>

            <!-- Form Tambah/Edit Mahasiswa -->
            <div class="card mb-4 p-3" style="background-color: #e9ecef;">
                <h5><?= $editData ? '✏️ Edit Mahasiswa' : '➕ Tambah Mahasiswa' ?></h5>
                <form method="POST" class="row g-3">
                    <?php if ($editData): ?>
                        <input type="hidden" name="id" value="<?= $editData['id'] ?>">
                        <input type="hidden" name="update" value="1">
                    <?php else: ?>
                        <input type="hidden" name="create" value="1">
                    <?php endif; ?>
                    
                    <div class="col-md-6">
                        <label class="form-label">NIM</label>
                        <input type="text" name="nim" class="form-control" required 
                               value="<?= $editData['nim'] ?? '' ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" required 
                               value="<?= $editData['nama'] ?? '' ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jurusan</label>
                        <select name="jurusan" class="form-select" required>
                            <option value="">-- Pilih Jurusan --</option>
                            <option value="Teknik Informatika" <?= ($editData['jurusan'] ?? '') == 'Teknik Informatika' ? 'selected' : '' ?>>Teknik Informatika</option>
                            <option value="Teknik Elektro" <?= ($editData['jurusan'] ?? '') == 'Teknik Elektro' ? 'selected' : '' ?>>Teknik Elektro</option>
                            <option value="Teknik Mesin" <?= ($editData['jurusan'] ?? '') == 'Teknik Mesin' ? 'selected' : '' ?>>Teknik Mesin</option>
                            <option value="Teknik Industri" <?= ($editData['jurusan'] ?? '') == 'Teknik Industri' ? 'selected' : '' ?>>Teknik Industri</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nilai</label>
                        <input type="number" name="nilai" class="form-control" min="0" max="100" required 
                               value="<?= $editData['nilai'] ?? '' ?>">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <?= $editData ? '💾 Perbarui' : '➕ Tambah' ?>
                        </button>
                        <?php if ($editData): ?>
                            <a href="index.php" class="btn btn-secondary">Batal</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Tabel Data Mahasiswa -->
            <h5>📋 Data Mahasiswa</h5>
            <table class="table table-striped table-hover">
                <thead class="table-header">
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Jurusan</th>
                        <th>Nilai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($dataMahasiswa)): ?>
                        <tr><td colspan="6" class="text-center">Data kosong</td></tr>
                    <?php else: ?>
                        <?php foreach ($dataMahasiswa as $index => $mhs): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($mhs['nim']) ?></td>
                                <td><?= htmlspecialchars($mhs['nama']) ?></td>
                                <td><?= htmlspecialchars($mhs['jurusan']) ?></td>
                                <td><?= htmlspecialchars($mhs['nilai']) ?></td>
                                <td>
                                    <a href="?action=edit&id=<?= $mhs['id'] ?>" class="btn btn-sm btn-warning">✏️ Edit</a>
                                    <a href="?action=delete&id=<?= $mhs['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">🗑️ Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>