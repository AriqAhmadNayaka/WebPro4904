import ActivityItem from "./ActivityItem"
export default function ActivityList({ activities, onDelete }) {
  if (activities.length === 0) {
    return (
      <div className="empty-message">
        <strong>Belum ada aktivitas</strong>
        <span>Tulis aktivitas pertama untuk mengisi halaman ini.</span>
      </div>
    )
  }

  return (
    <ul className="activity-list">
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