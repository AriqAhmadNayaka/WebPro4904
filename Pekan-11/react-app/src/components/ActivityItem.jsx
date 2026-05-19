import supportAmount from "./SupportAmount"
export default function ActivityItem({ activity, onDelete }) {
  return (
    <li className="activity-item">
      <div className="activity-avatar">{activity.name.charAt(0)}</div>
      <div>
        <span>{activity.name}</span>
        <p>{supportAmount(activity)}</p>
      </div>
      <button type="button" onClick={() => onDelete(activity.id)}>
        Hapus
      </button>
    </li>
  )
}