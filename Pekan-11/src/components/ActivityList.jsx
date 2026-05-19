import React from 'react';
import ActivityItem from './ActivityItem';

function ActivityList({ activities, onDeleteActivity }) {
  return (
    <div>
      {activities.length === 0 ? (
        <p className="empty-text">Belum ada aktivitas</p>
      ) : (
        <ul className="activity-list">
          {activities.map((activity, index) => (
            <ActivityItem
              key={index}
              activity={activity}
              index={index}
              onDeleteActivity={onDeleteActivity}
            />
          ))}
        </ul>
      )}
    </div>
  );
}
  
export default ActivityList;
