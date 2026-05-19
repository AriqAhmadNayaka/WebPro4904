import React from 'react';

function ActivityItem({ activity, index, onDeleteActivity }) {
  return (
    <li className="activity-item">
      {}
      <span>{activity}</span>
      <button
        className="delete-button"
        onClick={() => onDeleteActivity(index)}
      >
        Hapus
      </button>
    </li>
  );
}

export default ActivityItem;
