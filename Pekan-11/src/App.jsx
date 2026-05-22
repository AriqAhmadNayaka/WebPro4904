// Parent Component utama aplikasi
// useState adalah React Hook untuk menyimpan data yang bisa berubah
// Setiap kali state berubah, React otomatis me-render ulang tampilan
import { useState } from "react";

import ActivityList from "./components/ActivityList";
import "./App.css";

function App() {
  // useState(initialValue) untuk mengembalikan [nilaiState, fungsiUbahState]
  // 'activities' = array data aktivitas yang tampil di layar
  // 'setActivities' = fungsi untuk mengubah data activities
  const [activities, setActivities] = useState([
    { id: 1, text: "Belajar React" },
    { id: 2, text: "Mengerjakan Praktikum" },
    { id: 3, text: "Review Materi" },
  ]);

  // State untuk menyimpan teks yang sedang diketik di input
  const [inputValue, setInputValue] = useState("");

  // Fungsi menambahkan aktivitas baru ke dalam list
  // Dipanggil saat user klik tombol "Tambah" atau tekan Enter
  const handleAdd = () => {
    // trim() menghapus spasi di awal/akhir; cegah aktivitas kosong ditambahkan
    if (inputValue.trim() === "") return;

    // Spread operator menyalin semua item lama, lalu tambah item baru
    // Ini cara React-friendly untuk update array 
    const newActivity = {
      id: Date.now(), // gunakan timestamp sebagai ID unik
      text: inputValue.trim(),
    };
    setActivities([...activities, newActivity]);

    // Reset input menjadi kosong setelah menambahkan
    setInputValue("");
  };

  // Fungsi menghapus aktivitas berdasarkan id-nya
  // Dikirim ke child component melalui props
  const handleDelete = (id) => {
    // filter() membuat array baru tanpa item yang id-nya cocok
    setActivities(activities.filter((activity) => activity.id !== id));
  };

  // Menangani event tekan tombol keyboard di input
  const handleKeyDown = (e) => {
    if (e.key === "Enter") handleAdd();
  };

  return (
    <div className="app-container">
      <div className="card">

        <div className="app-header">
          <div>
            <h1 className="app-title">Daftar Aktivitas</h1>
            <p className="app-subtitle">Mahasiswa Tracker</p>
          </div>
        </div>

        <div className="input-row">
          <input
            className="input-field"
            type="text"
            placeholder="Tambahkan aktivitas baru..."
            value={inputValue}
            // Setiap karakter diketik, state inputValue diperbarui
            onChange={(e) => setInputValue(e.target.value)}
            onKeyDown={handleKeyDown}
          />
          <button className="btn-add" onClick={handleAdd}>
            Tambah
          </button>
        </div>

        {activities.length > 0 && (
          <p className="activity-count">
            {activities.length} aktivitas tersimpan
          </p>
        )}

        {/* Child component ActivityList menerima data melalui props */}
        {/* 'activities' dan 'onDelete' dikirim sebagai props ke ActivityList */}
        <ActivityList activities={activities} onDelete={handleDelete} />
      </div>
    </div>
  );
}

export default App;