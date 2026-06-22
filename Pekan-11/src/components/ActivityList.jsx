import ActivityItem from './ActivityItem'

function ActivityList({ activities, onDeleteActivity }) {
  if (activities.length === 0) {
    return <p className="empty-state">Belum ada aktivitas</p>
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

export default ActivityList
