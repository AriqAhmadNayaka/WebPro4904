import { useState } from "react";
import "./App.css";
import ActivityList from "./components/ActivityList";

// Komponen utama aplikasi yang mengatur data aktivitas dan tampilan keseluruhan halaman.
function App() {
  // State activities menyimpan daftar aktivitas yang akan ditampilkan di halaman.
  const [activities, setActivities] = useState([
    "Belajar React",
    "Mengerjakan Praktikum",
    "Review Materi",
    "Mengerjakan TuBes"
  ]);

  // State newActivity menyimpan teks yang sedang diketik pengguna pada input form.
  const [newActivity, setNewActivity] = useState("");

  // Fungsi ini menambahkan aktivitas baru ke dalam daftar jika input tidak kosong.
  const addActivity = () => {
    // trim menghapus spasi di awal dan akhir agar input kosong berisi spasi tidak ikut tersimpan.
    const activityName = newActivity.trim();

    // Jika input kosong, proses tambah aktivitas dihentikan.
    if (activityName === "") return;

    // Membuat array baru berisi aktivitas lama ditambah aktivitas baru.
    setActivities([...activities, activityName]);
    // Mengosongkan kembali input setelah aktivitas berhasil ditambahkan.
    setNewActivity("");
  };

  // Fungsi ini menghapus aktivitas berdasarkan index item yang dipilih.
  const deleteActivity = (index) => {
    // filter membuat daftar baru tanpa aktivitas yang index-nya sama dengan index yang dihapus.
    const updatedActivities = activities.filter(
      (_, i) => i !== index
    );

    // Memperbarui state activities dengan daftar yang sudah dikurangi.
    setActivities(updatedActivities);
  };

  return (
    // Container utama untuk menempatkan card aplikasi di tengah halaman.
    <div className="container">
      {/* Card berisi judul, form input, dan daftar aktivitas. */}
      <div className="card">
        <h1>Daftar Aktivitas Mahasiswa</h1>

        {/* Form digunakan agar pengguna bisa menambahkan aktivitas dengan tombol atau tombol Enter. */}
        <form
          className="input-group"
          onSubmit={(event) => {
            // Mencegah halaman refresh saat form dikirim.
            event.preventDefault();
            addActivity();
          }}
        >
          {/* Input terhubung dengan state newActivity sehingga nilainya selalu sinkron dengan React. */}
          <input
            type="text"
            placeholder="Masukkan aktivitas..."
            value={newActivity}
            onChange={(e) => setNewActivity(e.target.value)}
          />

          {/* Tombol submit menjalankan proses tambah aktivitas melalui event onSubmit form. */}
          <button type="submit">
            Tambah
          </button>
        </form>

        {/* Jika daftar kosong tampilkan pesan, jika ada data tampilkan komponen ActivityList. */}
        {activities.length === 0 ? (
          <p className="empty">
            Belum ada aktivitas
          </p>
        ) : (
          <ActivityList
            activities={activities}
            onDeleteActivity={deleteActivity}
          />
        )}
      </div>
    </div>
  );
}

export default App;
