import { ActivityItem } from './ActivityItem.jsx'

export function ActivityList({ activities, onDeleteActivity }) {
  // Tampilkan pesan khusus jika belum ada data aktivitas.
  if (activities.length === 0) {
    return <p className="empty-message">Belum ada aktivitas</p>
  }

  return (
    <ul className="activity-list">
      {/* Ubah setiap data aktivitas menjadi komponen ActivityItem. */}
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
