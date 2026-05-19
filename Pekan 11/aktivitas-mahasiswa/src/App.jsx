
import { useState } from "react";
import ActivityList from "./components/ActivityList";

function App() {
  // ===== STATE MANAGEMENT =====
  // State untuk menyimpan daftar aktivitas (array of objects)
  const [daftarAktivitas, setDaftarAktivitas] = useState([
    { id: 1, nama: "Mengerjakan tugas pemrograman web" },
    { id: 2, nama: "Belajar konsep React.js" },
    { id: 3, nama: "Membaca materi kuliah algoritma" },
  ]);

  // State untuk menyimpan nilai input teks yang sedang diketik
  const [inputAktivitas, setInputAktivitas] = useState("");

  // State untuk melacak id berikutnya (auto increment)
  const [nextId, setNextId] = useState(4);

  // ===== FUNGSI TAMBAH AKTIVITAS =====
  const tambahAktivitas = () => {
    // Validasi: jangan tambah jika input kosong/hanya spasi
    if (inputAktivitas.trim() === "") return;

    // Buat objek aktivitas baru
    const aktivitasBaru = {
      id: nextId,
      nama: inputAktivitas.trim(),
    };

    // Update state daftar aktivitas (tidak mutasi langsung, buat array baru)
    setDaftarAktivitas([...daftarAktivitas, aktivitasBaru]);

    // Reset input dan increment id
    setInputAktivitas("");
    setNextId(nextId + 1);
  };

  // ===== FUNGSI HAPUS AKTIVITAS =====
  const hapusAktivitas = (id) => {
    // Filter array: kembalikan semua item KECUALI yang id-nya cocok
    const daftarBaru = daftarAktivitas.filter((item) => item.id !== id);
    setDaftarAktivitas(daftarBaru);
  };

  // ===== HANDLE ENTER KEY =====
  const handleKeyDown = (e) => {
    if (e.key === "Enter") tambahAktivitas();
  };

  // ===== RENDER =====
  return (
    <div className="app-container">
      {/* Header */}
      <header className="app-header">
        <div className="header-icon">🎓</div>
        <h1>Daftar Aktivitas Mahasiswa</h1>
        <p className="header-subtitle">Catat dan kelola aktivitas harianmu</p>
      </header>

      <main className="app-main">
        {/* Form Tambah Aktivitas */}
        <section className="add-section">
          <h2 className="section-title">➕ Tambah Aktivitas</h2>
          <div className="input-group">
            <input
              type="text"
              className="input-aktivitas"
              placeholder="Masukkan nama aktivitas baru..."
              value={inputAktivitas}
              onChange={(e) => setInputAktivitas(e.target.value)}
              onKeyDown={handleKeyDown}
            />
            <button className="btn-tambah" onClick={tambahAktivitas}>
              Tambah
            </button>
          </div>
        </section>

        {/* Divider */}
        <hr className="divider" />

        {/* Daftar Aktivitas — dikirim ke child component melalui props */}
        <section className="list-section">
          <h2 className="section-title">
            📋 Daftar Aktivitas
            {/* Badge jumlah aktivitas */}
            {daftarAktivitas.length > 0 && (
              <span className="count-badge">{daftarAktivitas.length}</span>
            )}
          </h2>

          {/*
           * Props drilling: data dikirim dari parent (App) ke child (ActivityList)
           * - daftarAktivitas: array state aktivitas
           * - onHapus: fungsi untuk menghapus aktivitas
           */}
          <ActivityList
            daftarAktivitas={daftarAktivitas}
            onHapus={hapusAktivitas}
          />
        </section>
      </main>

      {/* Footer */}
      <footer className="app-footer">
        <p>Praktikum Pemrograman Web — React.js</p>
      </footer>
    </div>
  );
}

export default App;
