import { useEffect, useState } from 'react';
import PortalLayout from '../components/PortalLayout';
import AppIcon from '../components/AppIcon';
import { portalService } from '../services/api';

function Timeline() {
  const [data, setData] = useState(null);
  const [notificationCount, setNotificationCount] = useState(0);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    const loadData = async () => {
      try {
        setLoading(true);
        const [timelineResult, notificationResult] = await Promise.all([
          portalService.getTimeline(),
          portalService.getNotifications(),
        ]);
        setData(timelineResult);
        setNotificationCount(notificationResult.unread_count || 0);
      } catch (err) {
        setError(err.response?.data?.message || 'Gagal memuat timeline.');
      } finally {
        setLoading(false);
      }
    };

    loadData();
  }, []);

  if (loading) {
    return <div className="screen-loader"><div className="loader-orb" /><p>Memuat timeline...</p></div>;
  }

  return (
    <PortalLayout notificationCount={notificationCount}>
      {error ? <div className="portal-error">{error}</div> : null}

      {data ? (
        <div className="portal-page">
          <section className="timeline-hero">
            <div>
              <h1>{data.hero.title}</h1>
              <p>{data.hero.description}</p>
            </div>
            <div className="timeline-hero__art">
              <div className="timeline-hero__illustration">
                <div className="timeline-hero__screen" />
                <div className="timeline-hero__person timeline-hero__person--left" />
                <div className="timeline-hero__person timeline-hero__person--right" />
              </div>
            </div>
          </section>

          <section className="stats-grid">
            {[
              { label: 'Total Waktu Belajar', value: `${Math.floor(data.stats.total_minutes / 60)} Jam ${data.stats.total_minutes % 60} Menit`, sublabel: 'Waktu Keseluruhan Anda', icon: 'timeline' },
              { label: 'Waktu Minggu Ini', value: `${Math.floor(data.stats.weekly_minutes / 60)} Jam ${data.stats.weekly_minutes % 60} Menit`, sublabel: '+16% dari minggu lalu', icon: 'article' },
              { label: 'Materi Selesai', value: `${data.stats.completed_modules} / 24 Materi`, sublabel: '50% penyelesaian', icon: 'alert' },
              { label: 'Level Saat Ini', value: data.stats.level_name, sublabel: `${data.stats.xp} / 1000 XP`, icon: 'star' },
            ].map((item) => (
              <article key={item.label} className="stat-card">
                <div className="stat-card__icon">
                  <AppIcon name={item.icon} className="icon-svg" />
                </div>
                <div>
                  <span>{item.label}</span>
                  <strong>{item.value}</strong>
                  <small>{item.sublabel}</small>
                </div>
              </article>
            ))}
          </section>

          <section className="feature-panel">
            <div className="panel-header">
              <h3>Roadmap pembelajaran</h3>
              <button type="button" className="pill-button">
                Lihat Semua Materi
              </button>
            </div>

            <div className="roadmap-grid">
              {data.roadmap.map((item) => (
                <article key={item.id} className="roadmap-card">
                  <div className="roadmap-card__icon">
                    <AppIcon name={item.icon} className="icon-svg" />
                  </div>
                  <strong>{item.title}</strong>
                  <p>{item.summary}</p>
                  <div className="roadmap-card__footer">
                    <span className={`badge badge--${item.status === 'completed' ? 'success' : item.status === 'active' ? 'warning' : 'muted'}`}>{item.status_label}</span>
                    <small>{item.duration_label}</small>
                  </div>
                </article>
              ))}
            </div>
          </section>

          <section className="dashboard-bottom">
            <article className="chart-panel">
              <h3>Statik Belajar Anda</h3>
              <div className="study-chart">
                <div className="study-chart__header">
                  <span>Jam Belajar (30 Hari Terakhir)</span>
                  <strong>18 jam 45 menit</strong>
                </div>
                <div className="study-chart__graph">
                  <div className="study-chart__line" />
                  {data.chart.map((item, index) => (
                    <div key={item.label} className="study-chart__point" style={{ left: `${14 + index * 20}%`, bottom: `${20 + item.minutes / 6}px` }}>
                      <span>{item.label}</span>
                    </div>
                  ))}
                </div>
              </div>
            </article>

            <article className="alerts-panel">
              <div className="panel-header">
                <h3>Pencapaian Terbaru</h3>
                <button type="button" className="pill-button">
                  Lihat Semua
                </button>
              </div>
              <div className="alerts-list">
                {data.achievements.map((item) => (
                  <div key={item.id} className="alert-item">
                    <div className="alert-item__icon">
                      <AppIcon name={item.icon} className="icon-svg" />
                    </div>
                    <div className="alert-item__content">
                      <strong>{item.title}</strong>
                      <p>{item.description}</p>
                    </div>
                    <div className="achievement-meta">
                      <span>{item.date_label}</span>
                      <strong>+{item.xp} XP</strong>
                    </div>
                  </div>
                ))}
              </div>
            </article>
          </section>
        </div>
      ) : null}
    </PortalLayout>
  );
}

export default Timeline;
