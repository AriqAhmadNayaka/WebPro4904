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

export default ActivityItem
