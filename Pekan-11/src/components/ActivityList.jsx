import ActivityItem from './ActivityItem'

// Component ini menerima data activities dan fungsi onDelete dari parent melalui props.
function ActivityList({ activities, onDelete }) {
  // Conditional rendering: jika daftar kosong, tampilkan pesan berikut.
  if (activities.length === 0) {
    return <p className="empty-message">Belum ada aktivitas</p>
  }

  return (
    <ul className="activity-list">
      {/* Rendering list: menampilkan setiap aktivitas menggunakan map. */}
      {activities.map((activity) => (
        <ActivityItem
          key={activity.id}
          activity={activity}
          onDelete={onDelete}
        />
      ))}
    </ul>
  )
}

export default ActivityList
