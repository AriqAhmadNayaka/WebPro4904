import { useEffect, useState } from 'react';
import PortalLayout from '../components/PortalLayout';
import AppIcon from '../components/AppIcon';
import { portalService } from '../services/api';

const statIcons = ['shield', 'graduation', 'article', 'certificate'];

function Dashboard() {
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    const loadData = async () => {
      try {
        setLoading(true);
        const result = await portalService.getDashboard();
        setData(result);
      } catch (err) {
        setError(err.response?.data?.message || 'Gagal memuat dashboard.');
      } finally {
        setLoading(false);
      }
    };

    loadData();
  }, []);

  if (loading) {
    return <div className="screen-loader"><div className="loader-orb" /><p>Memuat dashboard...</p></div>;
  }

  return (
    <PortalLayout notificationCount={data?.summary?.notification_unread || 0}>
      {error ? <div className="portal-error">{error}</div> : null}

      {data ? (
        <div className="portal-page">
          <section className="hero-panel">
            <div className="hero-panel__content">
              <h1>{data.hero.title}</h1>
              <h2>{data.hero.subtitle}</h2>
              <p>{data.hero.description}</p>
              <div className="hero-panel__actions">
                <button type="button" className="cta-button cta-button--primary">
                  <AppIcon name="book" className="icon-svg" />
                  Mulai Belajar
                </button>
                <button type="button" className="cta-button cta-button--secondary">
                  <AppIcon name="alert" className="icon-svg" />
                  Laporkan Insiden
                </button>
              </div>
            </div>
            <div className="hero-panel__art">
              <div className="skyline-card">
                <div className="skyline-card__buildings" />
              </div>
            </div>
          </section>

          <section className="stats-grid">
            {[
              { label: 'Cyber Awareness Score', value: `${data.summary.awareness_score}/100`, sublabel: 'Baik' },
              { label: 'Progres Belajar', value: `${data.summary.learning_progress}%`, sublabel: '12 / 24 Materi Selesai' },
              { label: 'Laporan Keaktifan', value: data.summary.active_reports, sublabel: '2 Sedang Diproses' },
              { label: 'Sertifikat Pembelajaran', value: data.summary.certificates, sublabel: 'Lihat Semua' },
            ].map((item, index) => (
              <article key={item.label} className="stat-card">
                <div className="stat-card__icon">
                  <AppIcon name={statIcons[index]} className="icon-svg" />
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
            <h3>Aksi Cepat</h3>
            <div className="feature-grid">
              {data.quick_actions.map((item) => (
                <article key={item.title} className="feature-card">
                  <div className="feature-card__icon">
                    <AppIcon name={item.icon} className="icon-svg" />
                  </div>
                  <div>
                    <strong>{item.title}</strong>
                    <p>{item.description}</p>
                  </div>
                  <span>&gt;</span>
                </article>
              ))}
            </div>
          </section>

          <section className="dashboard-bottom">
            <article className="progress-panel">
              <div className="panel-header">
                <h3>Progres Belajar Anda</h3>
                <button type="button" className="pill-button">
                  Lihat Semua Materi
                </button>
              </div>

              <div className="progress-panel__body">
                <div className="progress-ring">
                  <div className="progress-ring__inner">{data.summary.learning_progress}%</div>
                </div>
                <div className="progress-list">
                  {data.progress_breakdown.map((item) => (
                    <div key={item.label} className="progress-item">
                      <div className="progress-item__labels">
                        <span>{item.label}</span>
                        <strong>{item.value}%</strong>
                      </div>
                      <div className="progress-bar">
                        <span style={{ width: `${item.value}%` }} />
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            </article>

            <article className="alerts-panel">
              <div className="panel-header">
                <h3>Berita dan Peringatan</h3>
              </div>

              <div className="alerts-list">
                {data.alerts.map((item) => (
                  <div key={item.id} className="alert-item">
                    <div className="alert-item__icon">
                      <AppIcon name={item.type === 'warning' ? 'alert' : 'lock'} className="icon-svg" />
                    </div>
                    <div className="alert-item__content">
                      <strong>{item.title}</strong>
                      <p>{item.summary}</p>
                      <div className="alert-item__meta">
                        <span className={`badge badge--${item.type}`}>{item.badge}</span>
                        <small>{item.time_label}</small>
                      </div>
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

export default Dashboard;
