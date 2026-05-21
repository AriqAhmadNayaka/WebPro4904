import { useState } from 'react'
import './App.css'

function ActivityForm({ activityName, onActivityNameChange, onAddActivity }) {
  return (
    <form className="activity-form" onSubmit={onAddActivity}>
      <label htmlFor="activityName">Nama aktivitas</label>
      <div className="form-row">
        <input
          id="activityName"
          type="text"
          value={activityName}
          onChange={(event) => onActivityNameChange(event.target.value)}
          placeholder="Contoh: Mengerjakan laporan praktikum"
        />
        <button type="submit">Tambah</button>
      </div>
    </form>
  )
}

function ActivityItem({ activity, onDeleteActivity }) {
  return (
    <li className="activity-item">
      <span>{activity.name}</span>
      <button type="button" onClick={() => onDeleteActivity(activity.id)}>
        Hapus
      </button>
    </li>
  )
}

function ActivityList({ activities, onDeleteActivity }) {
  if (activities.length === 0) {
    return <p className="empty-message">Belum ada aktivitas</p>
  }

  return (
    <ul className="activity-list">
      {activities.map((activity) => (
        <ActivityItem
          key={activity.id}
          activity={activity}
          onDeleteActivity={onDeleteActivity}
        />
      ))}
    </ul>
  )
}

function App() {
  const [activityName, setActivityName] = useState('')
  const [activities, setActivities] = useState([
    { id: 1, name: 'Mengikuti kuliah Pemrograman Web' },
    { id: 2, name: 'Membaca modul Pengenalan React JS' },
    { id: 3, name: 'Membuat komponen React sederhana' },
  ])

  function handleAddActivity(event) {
    event.preventDefault()

    const trimmedActivityName = activityName.trim()
    if (trimmedActivityName === '') {
      return
    }

    const newActivity = {
      id: Date.now(),
      name: trimmedActivityName,
    }

    setActivities([...activities, newActivity])
    setActivityName('')
  }

  function handleDeleteActivity(id) {
    setActivities(activities.filter((activity) => activity.id !== id))
  }

  return (
    <main className="app-shell">
      <section className="hero-section">
        <h1>Daftar Aktivitas Mahasiswa</h1>
        <p>
          Aplikasi sederhana untuk menampilkan, menambah, dan menghapus data
          aktivitas menggunakan component, props, state, rendering list, dan
          conditional rendering.
        </p>
      </section>

      <section className="content-grid">
        <div className="panel">
          <h2>Tambah Aktivitas</h2>
          <ActivityForm
            activityName={activityName}
            onActivityNameChange={setActivityName}
            onAddActivity={handleAddActivity}
          />
        </div>

        <div className="panel">
          <div className="panel-header">
            <div>
              <h2>Aktivitas Saat Ini</h2>
              <p>Total aktivitas: {activities.length}</p>
            </div>
          </div>
          <ActivityList
            activities={activities}
            onDeleteActivity={handleDeleteActivity}
          />
        </div>
      </section>
    </main>
  )
}

export default App
