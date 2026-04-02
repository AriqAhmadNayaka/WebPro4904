<?php
/**
 * Mendapatkan tipe data untuk binding pada Prepared Statement MySQLi
 */
function getBindType($value)
{
    if (is_int($value)) return 'i';
    if (is_float($value) || is_double($value)) return 'd';
    return 's';
}

/**
 * 1. CREATE (Tambah Data)
 * Memasukkan data ke tabel tertentu secara dinamis.
 */
function insertData($conn, $table, $data)
{
    $columns = implode(', ', array_keys($data));
    $placeholders = implode(', ', array_fill(0, count($data), '?'));
    $values = array_values($data);

    $types = '';
    foreach ($values as $value) {
        $types .= getBindType($value);
    }

    $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$values);
    return $stmt->execute();
}

/**
 * 2. READ (Ambil Data)
 * Mengambil data dari tabel dengan filter kondisi opsional.
 */
function getData($conn, $table, $conditions = [])
{
    $sql = "SELECT * FROM $table";
    $types = '';
    $values = [];

    if (!empty($conditions)) {
        $clauses = [];
        foreach ($conditions as $key => $value) {
            $cleanKey = trim($key);
            if (strpos($cleanKey, ' ') !== false) {
                // Mendukung operator custom seperti 'id >'
                $clauses[] = "$cleanKey ?";
            } else {
                $clauses[] = "$cleanKey = ?";
            }
            $types .= getBindType($value);
            $values[] = $value;
        }
        $sql .= " WHERE " . implode(' AND ', $clauses);
    }

    $stmt = $conn->prepare($sql);
    if (!empty($values)) {
        $stmt->bind_param($types, ...$values);
    }
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * 3. UPDATE (Edit Data)
 * Memperbarui record berdasarkan ID secara dinamis.
 */
function updateData($conn, $table, $data, $id, $id_column = 'id')
{
    $cols = [];
    $types = '';
    $values = [];

    foreach ($data as $key => $value) {
        $cols[] = "$key = ?";
        $types .= getBindType($value);
        $values[] = $value;
    }

    $types .= getBindType($id);
    $values[] = $id;

    $setClause = implode(', ', $cols);
    $sql = "UPDATE $table SET $setClause WHERE $id_column = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$values);
    return $stmt->execute();
}

/**
 * 4. DELETE (Hapus Data)
 * Menghapus record berdasarkan ID.
 */
function deleteData($conn, $table, $id, $id_column = 'id')
{
    $sql = "DELETE FROM $table WHERE $id_column = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(getBindType($id), $id);
    return $stmt->execute();
}

/**
 * Fungsi untuk menangani File Upload
 * Memvalidasi ukuran, ekstensi, dan memindahkan file ke folder tujuan.
 */
function uploadFile($fileData, $uploadPath = 'uploads/')
{
    $fileName = $fileData['name'];
    $fileSize = $fileData['size'];
    $fileError = $fileData['error'];
    $tmpName = $fileData['tmp_name'];

    if ($fileError === 4) {
        return ['status' => 'error', 'msg' => 'Pilih file terlebih dahulu!'];
    }

    $ekstensiValid = ['jpg', 'jpeg', 'png', 'pdf', 'docx'];
    $ekstensiFile = explode('.', $fileName);
    $ekstensiFile = strtolower(end($ekstensiFile));

    if (!in_array($ekstensiFile, $ekstensiValid)) {
        return ['status' => 'error', 'msg' => 'Format file tidak didukung! (Hanya JPG, PNG, PDF, DOCX)'];
    }

    if ($fileSize > 2000000) {
        return ['status' => 'error', 'msg' => 'Ukuran file terlalu besar! (Maksimal 2MB)'];
    }

    // Generate nama unik untuk menghindari duplikasi
    $namaFileBaru = uniqid() . '.' . $ekstensiFile;

    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    move_uploaded_file($tmpName, $uploadPath . $namaFileBaru);

    return ['status' => 'success', 'filename' => $namaFileBaru];
}

/**
 * Fungsi untuk menghapus file fisik dari server
 */
function deletePhysicalFile($fileName, $folderPath = 'uploads/')
{
    $filePath = $folderPath . $fileName;
    if (!empty($fileName) && file_exists($filePath)) {
        return unlink($filePath); // Menghapus file
    }
    return false;
}

/**
 * Fungsi cerdas untuk menangani Update File
 * Mengecek apakah ada file baru. Jika ada, hapus file lama dan simpan yang baru.
 */
function handleFileUpdate($fileInput, $oldFileName, $uploadPath = 'uploads/', $defaultFile = 'default.png')
{
    // Jika ada file yang diunggah (error code 4 berarti tidak ada file)
    if ($fileInput['error'] !== 4) {
        $uploadResult = uploadFile($fileInput, $uploadPath);
        if ($uploadResult['status'] === 'success') {
            // Hapus file lama jika bukan file default
            if ($oldFileName !== $defaultFile && !empty($oldFileName)) {
                deletePhysicalFile($oldFileName, $uploadPath);
            }
            return ['status' => 'success', 'filename' => $uploadResult['filename']];
        } else {
            return ['status' => 'error', 'msg' => $uploadResult['msg']];
        }
    }
    // Jika tidak ada upload baru, gunakan file lama
    return ['status' => 'no_file', 'filename' => $oldFileName];
}
?>
