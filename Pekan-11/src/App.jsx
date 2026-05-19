import React, { useState } from 'react';
import ActivityList from './components/ActivityList';

function App() {
  // State untuk menyimpan daftar aktivitas mahasiswa dalam bentuk array
  const [activities, setActivities] = useState([
    'Mengerjakan tugas praktikum React',
    'Membaca materi pemrograman web',
  ]);

  // State untuk menyimpan isi input aktivitas baru
  const [newActivity, setNewActivity] = useState('');

  const handleAddActivity = () => {
    const trimmedActivity = newActivity.trim();

    if (trimmedActivity === '') {
      return;
    }

    setActivities([...activities, trimmedActivity]);
    setNewActivity('');
  };

  const handleDeleteActivity = (indexToDelete) => {
    setActivities(activities.filter((_, index) => index !== indexToDelete));
  };

  return (
    <div className="app-container">
      <div className="card">
        <h1>Aplikasi Daftar Aktivitas Mahasiswa</h1>
        <p className="description">
          Tambahkan aktivitas kuliah atau kegiatan harian mahasiswa dengan
          React dasar.
        </p>

        <div className="input-group">
          <input
            type="text"
            placeholder="Masukkan aktivitas baru"
            value={newActivity}
            onChange={(event) => setNewActivity(event.target.value)}
          />
          <button onClick={handleAddActivity}>Tambah</button>
        </div>

        {}
        <ActivityList
          activities={activities}
          onDeleteActivity={handleDeleteActivity}
        />
      </div>
    </div>
  );
}

export default App;
