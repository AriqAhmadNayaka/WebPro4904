// Digunakan untuk mengimport useState dari React, 
// serta mengimport file CSS dan komponen ActivityList yang akan digunakan dalam aplikasi.
import { useState } from "react";
import "./App.css";
import ActivityList from "./ActivityList";

// Komponen utama aplikasi yang menampilkan daftar aktivitas mahasiswa, 
// serta menyediakan fitur untuk menambahkan dan menghapus aktivitas.
function App() {
  // State untuk menyimpan daftar aktivitas mahasiswa, yang diinisialisasi dengan beberapa aktivitas awal.
  const [dataaktifitas, setDataAktivitas] = useState([
    "Belajar React",
    "Mengerjakan tugas",
    "Review materi"
  ]);

  // State untuk menyimpan nilai input baru yang akan ditambahkan ke daftar aktivitas.
  const [newActivity, setNewActivity] = useState("");

  // Fungsi untuk menambahkan aktivitas baru ke dalam daftar. 
  // Fungsi ini akan memeriksa apakah input tidak kosong sebelum menambahkannya ke state dataaktifitas, 
  // dan kemudian mengosongkan input setelah penambahan.
  const addActivity = () => {
    // Memeriksa apakah input baru tidak kosong setelah di-trim. 
    // Jika kosong, fungsi akan berhenti dan tidak menambahkan aktivitas.
    if (newActivity.trim() === "") return;

    // Menambahkan aktivitas baru ke dalam daftar dengan menggunakan spread operator 
    // untuk membuat array baru yang berisi semua aktivitas sebelumnya ditambah aktivitas baru.
    setDataAktivitas([...dataaktifitas, newActivity.trim()]);
    // Mengosongkan input setelah menambahkan aktivitas baru.
    setNewActivity("");
  };

  // Fungsi untuk menghapus aktivitas dari daftar berdasarkan indeksnya. 
  // Fungsi ini akan membuat array baru yang berisi semua aktivitas kecuali yang memiliki indeks yang diberikan, 
  // dan kemudian memperbarui state dataaktifitas dengan array baru tersebut.
  const deleteActivity = (index) => {
    const updatedActivities = dataaktifitas.filter((_, i) => i !== index);
    // Memperbarui state dataaktifitas dengan array yang sudah dihapus aktivitasnya.
    setDataAktivitas(updatedActivities);
  };

  // Fungsi untuk menangani event keydown pada input. 
  // Jika tombol yang ditekan adalah "Enter", maka fungsi addActivity akan dipanggil untuk menambahkan aktivitas baru.
  const handleKeyDown = (e) => {
    if (e.key === "Enter") addActivity();
  };

  // Render komponen yang menampilkan judul, input untuk menambahkan aktivitas, 
  // dan daftar aktivitas yang sudah ada. Jika tidak ada aktivitas, akan ditampilkan pesan bahwa belum ada aktivitas.
  return (
    <div>
      <h1>Daftar Aktivitas Mahasiswa</h1>

      {/* Bagian input untuk menambahkan aktivitas baru. Terdiri dari sebuah input teks dan tombol "Tambah". 
      Input akan memperbarui state newActivity saat pengguna mengetik, dan tombol "Tambah" akan memanggil fungsi addActivity saat diklik. 
      Selain itu, pengguna juga dapat menekan tombol "Enter" untuk menambahkan aktivitas baru. */}
      <div className="input-row">
        <input
          className="activity-input"
          type="text"
          placeholder="Masukkan aktivitas"
          value={newActivity}
          onChange={(e) => setNewActivity(e.target.value)}
          onKeyDown={handleKeyDown}
        />
        <button className="btn-add" onClick={addActivity}>
          Tambah
        </button>
      </div>

      <hr />

      {/* Bagian untuk menampilkan daftar aktivitas. Jika dataaktifitas kosong, 
      akan ditampilkan pesan "Belum ada aktivitas". 
      Jika tidak kosong, akan ditampilkan daftar aktivitas menggunakan komponen ActivityList untuk setiap aktivitas, 
      dengan opsi untuk menghapus aktivitas tersebut. */}
      {dataaktifitas.length === 0 ? (
        <p className="empty-text">Belum ada aktivitas</p>
      ) : (
        <ul className="activity-list">
          {dataaktifitas.map((activity, index) => (
            <ActivityList
              key={index}
              activity={activity}
              onDelete={() => deleteActivity(index)}
            />
          ))}
        </ul>
      )}
    </div>
  );
}

// Mengekspor komponen App sebagai default export, sehingga dapat diimpor dan digunakan di file lain dalam aplikasi.
export default App;