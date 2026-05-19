function ActivityItem({ activity, onDelete }) {
  return (
    <li>
      {activity.text}
      <button onClick={() => onDelete(activity.id)}>Hapus</button>
    </li>
  );
}

export default ActivityItem;