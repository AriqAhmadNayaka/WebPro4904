import { useEffect, useState } from 'react';
import PortalLayout from '../components/PortalLayout';
import AppIcon from '../components/AppIcon';
import { portalService } from '../services/api';

function Notifications() {
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    const loadData = async () => {
      try {
        setLoading(true);
        const result = await portalService.getNotifications();
        setData(result);
      } catch (err) {
        setError(err.response?.data?.message || 'Gagal memuat notifikasi.');
      } finally {
        setLoading(false);
      }
    };

    loadData();
  }, []);

  if (loading) {
    return <div className="screen-loader"><div className="loader-orb" /><p>Memuat notifikasi...</p></div>;
  }

  return (
    <PortalLayout notificationCount={data?.unread_count || 0}>
      <div className="portal-page">
        <section className="feature-panel">
          <div className="panel-header">
            <h3>Notifikasi</h3>
            <span className="pill-button">{data?.unread_count || 0} belum dibaca</span>
          </div>
          {error ? <div className="portal-error">{error}</div> : null}
          <div className="notifications-stack">
            {(data?.items || []).map((item) => (
              <article key={item.id} className="notification-card">
                <div className="notification-card__icon">
                  <AppIcon name={item.type === 'warning' ? 'alert' : item.type === 'success' ? 'star' : 'bell'} className="icon-svg" />
                </div>
                <div className="notification-card__body">
                  <div className="notification-card__head">
                    <strong>{item.title}</strong>
                    {!item.is_read ? <span className="badge badge--warning">Baru</span> : null}
                  </div>
                  <p>{item.body}</p>
                  <small>{item.time_label}</small>
                </div>
              </article>
            ))}
          </div>
        </section>
      </div>
    </PortalLayout>
  );
}

export default Notifications;
