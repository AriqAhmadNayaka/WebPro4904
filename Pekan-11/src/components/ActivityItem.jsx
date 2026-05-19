// Component ini menampilkan satu aktivitas dan tombol untuk menghapusnya.
function ActivityItem({ activity, onDelete }) {
  return (
    <li className="activity-item">
      <span>{activity.name}</span>
      {/* Saat tombol ditekan, id aktivitas dikirim ke fungsi onDelete. */}
      <button type="button" onClick={() => onDelete(activity.id)}>
        Hapus
      </button>
    </li>
  )
}

export default ActivityItem
