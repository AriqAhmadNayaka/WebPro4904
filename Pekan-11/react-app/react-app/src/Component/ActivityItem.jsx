import React from "react";

function ActivityItem(props) {
  return (
    <li className="activity-item">
      <span>{props.activity}</span>

      <button
        className="delete-btn"
        onClick={props.onDelete}
      >
        Hapus
      </button>
    </li>
  );
}

export default ActivityItem;