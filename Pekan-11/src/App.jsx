import { useState } from 'react'
import ActivityList from './components/ActivityList'
import './App.css'

const initialActivities = [
  { id: 1, name: 'Mengikuti kuliah React' },
  { id: 2, name: 'Mengerjakan tugas praktikum' },
  { id: 3, name: 'Diskusi kelompok proyek web' },
]

function App() {
  const [activities, setActivities] = useState(initialActivities)
  const [activityName, setActivityName] = useState('')

  const handleSubmit = (event) => {
    event.preventDefault()

    const trimmedName = activityName.trim()
    if (!trimmedName) {
      return
    }

    const newActivity = {
      id: Date.now(),
      name: trimmedName,
    }

    setActivities([...activities, newActivity])
    setActivityName('')
  }

  const handleDeleteActivity = (activityId) => {
    setActivities(activities.filter((activity) => activity.id !== activityId))
  }

  return (
    <main className="app">
      <section className="activity-panel">
        <div className="panel-header">
          <p className="eyebrow">Praktikum React</p>
          <h1>Daftar Aktivitas Mahasiswa</h1>
          <p className="intro">
            Kelola aktivitas mahasiswa menggunakan state, props, rendering list,
            dan conditional rendering.
          </p>
        </div>

        <form className="activity-form" onSubmit={handleSubmit}>
          <label htmlFor="activityName">Nama aktivitas</label>
          <div className="form-row">
            <input
              id="activityName"
              type="text"
              value={activityName}
              onChange={(event) => setActivityName(event.target.value)}
              placeholder="Contoh: Membaca materi komponen"
            />
            <button type="submit">Tambah</button>
          </div>
        </form>

        <ActivityList
          activities={activities}
          onDeleteActivity={handleDeleteActivity}
        />
      </section>
    </main>
  )
}

export default App
