import { useState } from 'react'; // Mengimpor useState untuk mengelola state aktivitas dan input
import ActivityItem from './components/ActivityItem'; // Mengimpor komponen anak untuk menampilkan setiap aktivitas
import './App.css'; // Mengimpor file CSS untuk styling aplikasi

function App() { // Komponen utama aplikasi
  const [activities, setActivities] = useState([ // State untuk menyimpan daftar aktivitas, diinisialisasi dengan beberapa contoh aktivitas
    { id: 1, name: 'Belajar React' }, // Contoh aktivitas pertama dengan id unik dan nama aktivitas
    { id: 2, name: 'Mengerjakan Tugas Praktikum' }, // Contoh aktivitas kedua dengan id unik dan nama aktivitas
  ]);
  const [inputValue, setInputValue] = useState(''); // State untuk menyimpan nilai input dari pengguna

  function handleAdd() { // Fungsi untuk menambahkan aktivitas baru ke daftar
    const trimmed = inputValue.trim(); // Menghapus spasi di awal dan akhir input untuk memastikan tidak menambahkan aktivitas kosong
    if (!trimmed) return; // Jika input kosong setelah di-trim, keluar dari fungsi tanpa menambahkan aktivitas
    setActivities([...activities, { id: Date.now(), name: trimmed }]); // Menambahkan aktivitas baru ke state dengan id unik menggunakan timestamp dan nama dari input yang sudah di-trim
    setInputValue(''); // Mengosongkan input setelah menambahkan aktivitas baru ke daftar
  }

  function handleDelete(id) { // Fungsi untuk menghapus aktivitas dari daftar berdasarkan id
    setActivities(activities.filter((act) => act.id !== id)); // Memperbarui state dengan menyaring keluar aktivitas yang memiliki id yang sama dengan id yang ingin dihapus
  }

  function handleKeyDown(e) { // Fungsi untuk menangani event keydown pada input, memungkinkan pengguna menekan Enter untuk menambahkan aktivitas
    if (e.key === 'Enter') handleAdd(); // Jika tombol yang ditekan adalah Enter, panggil fungsi handleAdd untuk menambahkan aktivitas baru
  }

  return ( // JSX untuk merender tampilan aplikasi
    <div className="app-wrapper">
      <div className="card">
        <header className="app-header">
          <h1>Daftar Aktivitas Mahasiswa</h1>
          <p className="subtitle">Kelola aktivitas harianmu di sini</p>
        </header>

        <div className="input-group">
          <input
            type="text"
            className="activity-input"
            placeholder="Masukkan nama aktivitas..."
            value={inputValue}
            onChange={(e) => setInputValue(e.target.value)}
            onKeyDown={handleKeyDown}
          />
          <button className="btn-add" onClick={handleAdd}>
            + Tambah
          </button>
        </div>

        {activities.length === 0 ? (
          <div className="empty-state">
            <span className="empty-icon">🗂️</span>
            <p>Belum ada aktivitas</p>
          </div>
        ) : (
          <ul className="activity-list">
            {activities.map((activity) => (
              <ActivityItem
                key={activity.id}
                activity={activity}
                onDelete={handleDelete}
              />
            ))}
          </ul>
        )}

        <footer className="app-footer">
          Total: <strong>{activities.length}</strong> aktivitas
        </footer>
      </div>
    </div>
  );
}

export default App; // Mengekspor komponen App agar bisa digunakan di main.jsx untuk dirender ke DOM
