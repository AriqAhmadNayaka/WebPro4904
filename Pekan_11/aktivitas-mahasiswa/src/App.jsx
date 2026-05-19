import { useState } from 'react'
import ActivityItem from './components/ActivityItem'
import './App.css'

function App() {
  // State utama untuk menyimpan daftar aktivitas mahasiswa.
  const [activities, setActivities] = useState([
    'Makan Bergizi Gratis',
    'PUBG with Teman',
    'gak diajarin download minicraft',
    'cape kelas pengganti',
    'Makan Bersama Ronaldo'
  ])
  // State untuk menangkap nilai input sebelum ditambahkan ke list.
  const [newActivity, setNewActivity] = useState('')

  const handleAddActivity = (event) => {
    event.preventDefault()
    const cleanActivity = newActivity.trim()

    if (!cleanActivity) {
      return
    }

    setActivities((prevActivities) => [...prevActivities, cleanActivity])
    setNewActivity('')
  }

  const handleDeleteActivity = (indexToDelete) => {
    // Hapus aktivitas berdasarkan index item yang dipilih.
    setActivities((prevActivities) =>
      prevActivities.filter((_, index) => index !== indexToDelete),
    )
  }

  return (
    <main className="app-container">
      <header className="app-header">
        <p className="eyebrow">Pekan 11 · React JS</p>
        <h1>Daftar Aktivitas Mahasiswa</h1>
        <p className="subtitle">
          Catat agenda kuliah harianmu biar tetap terorganisir.
        </p>
      </header>

      <form className="activity-form" onSubmit={handleAddActivity}>
        <input
          type="text"
          value={newActivity}
          onChange={(event) => setNewActivity(event.target.value)}
          placeholder="Masukkan aktivitas baru"
        />
        <button type="submit">Tambah</button>
      </form>

      <p className="summary">
        Total aktivitas: <strong>{activities.length}</strong>
      </p>

      {activities.length === 0 ? (
        // Conditional rendering saat list belum memiliki data.
        <p className="empty-message">Belum ada aktivitas</p>
      ) : (
        <ul className="activity-list">
          {activities.map((activity, index) => (
            <ActivityItem
              key={`${activity}-${index}`}
              activity={activity}
              onDelete={() => handleDeleteActivity(index)}
            />
          ))}
        </ul>
      )}
    </main>
  )
}

export default App
