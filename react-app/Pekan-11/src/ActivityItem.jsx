export function ActivityItem({ activity, onDeleteActivity }) {
  return (
    <li className="activity-item">
      <span>{activity.name}</span>
      {/* Kirim id aktivitas ke App saat tombol Hapus ditekan. */}
      <button type="button" onClick={() => onDeleteActivity(activity.id)}>
        Hapus
      </button>
    </li>
  )
}
