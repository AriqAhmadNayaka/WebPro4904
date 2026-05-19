function ActivityItem({ activity, onDelete }) {
  // Child component untuk menampilkan 1 aktivitas + aksi hapus.
  return (
    <li className="activity-item">
      <span>{activity}</span>
      <button type="button" onClick={onDelete}>
        Hapus
      </button>
    </li>
  )
}

export default ActivityItem
