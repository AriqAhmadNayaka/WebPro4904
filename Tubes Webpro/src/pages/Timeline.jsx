import timelineHero from '../../WhatsApp Image 2026-06-10 at 16.50.02.jpeg'
import timelineChart from '../../WhatsApp Image 2026-06-10 at 16.52.06.jpeg'

function Timeline() {
  const summary = [
    {
      icon: 'bi-clock',
      title: 'Total Waktu Belajar',
      value: '18 Jam 12 Menit',
      note: 'Waktu Keseluruhan Anda',
    },
    {
      icon: 'bi-calendar-event',
      title: 'Waktu Minggu Ini',
      value: '4 Jam 12 Menit',
      note: '+16% dari minggu lalu',
    },
    {
      icon: 'bi-target',
      title: 'Materi Selesai',
      value: '12 / 24 Materi',
      note: '50% penyelesaian',
    },
    {
      icon: 'bi-trophy',
      title: 'Level Saat Ini',
      value: 'Level 2 - Waspada',
      note: '500 / 1000 XP',
    },
  ]

  const roadmap = [
    {
      title: 'Dasar Keamanan Digital',
      status: 'Selesai',
      description: 'Pahami konsep dasar keamanan digital dan ancaman umum.',
      duration: '2 Jam 30 Menit',
      badge: 'success',
      icon: 'bi-shield-lock',
    },
    {
      title: 'Phishing & Social Engineering',
      status: 'Sekarang',
      description: 'Kenali berbagai kejadian phishing dan cara menghindarinya.',
      duration: '3 Jam 15 Menit',
      badge: 'current',
      icon: 'bi-exclamation-triangle',
    },
    {
      title: 'Password & Autentikasi',
      status: 'Belum',
      description: 'Cara membuat password yang kuat dan melakukan autentikasi 2 faktor.',
      duration: '2 Jam 15 Menit',
      badge: 'muted',
      icon: 'bi-key',
    },
    {
      title: 'Privasi Data Pribadi',
      status: 'Belum',
      description: 'Lindungi data pribadi Anda di dunia digital.',
      duration: '2 Jam 30 Menit',
      badge: 'muted',
      icon: 'bi-person-check',
    },
    {
      title: 'Keamanan di Smart City',
      status: 'Belum',
      description: 'Keamanan digital dalam ekosistem smart city.',
      duration: '3 Jam 30 Menit',
      badge: 'muted',
      icon: 'bi-building',
    },
  ]

  const achievements = [
    {
      title: 'Pahlawan Phishing',
      detail: 'Menyelesaikan Materi Phishing & Social Engineering',
      date: '4 Mei 2026',
      xp: '+70 XP',
    },
    {
      title: 'Akun Lebih Aman',
      detail: 'Melakukan autentikasi 2 faktor',
      date: '3 Mei 2026',
      xp: '+100 XP',
    },
    {
      title: 'Pembelajar Aktif',
      detail: 'Belajar selama 7 hari berturut-turut',
      date: '1 Mei 2026',
      xp: '+120 XP',
    },
    {
      title: 'Penjaga Privasi',
      detail: 'Menyelesaikan materi Privasi Data Pribadi',
      date: '29 April 2026',
      xp: '+90 XP',
    },
    {
      title: 'Anti Penipuan Digital',
      detail: 'Menyelesaikan kuis Penipuan Online',
      date: '27 April 2026',
      xp: '+80 XP',
    },
    {
      title: 'Siap Smart City',
      detail: 'Menyelesaikan modul Keamanan di Smart City',
      date: '25 April 2026',
      xp: '+110 XP',
    },
  ]

  return (
    <div className="cv-dashboard-container cv-timeline-page">
      <section className="cv-timeline-hero">
        <div className="cv-hero-text">
          <div className="cv-hero-greeting">Timeline Belajar</div>
          <h1>Timeline Belajar</h1>
          <p>
            Ikuti roadmap pembelajaran yang terstruktur untuk meningkatkan literasi keamanan digital Anda. Terus belajar, raih sertifikat, dan jadi bagian dari masyarakat digital yang aman.
          </p>
        </div>

        <div className="cv-timeline-hero-image">
          <img src={timelineHero} alt="Ilustrasi Timeline Belajar" />
        </div>
      </section>

      <section className="cv-timeline-summary-grid">
        {summary.map((item) => (
          <article key={item.title} className="cv-timeline-summary-card">
            <div className="cv-icon-box">
              <i className={`bi ${item.icon}`} />
            </div>
            <div>
              <p className="cv-stat-card__title">{item.title}</p>
              <h3 className="cv-stat-card__value">{item.value}</h3>
              <p className="cv-stat-card__note">{item.note}</p>
            </div>
          </article>
        ))}
      </section>

      <section className="cv-roadmap-section">
        <div className="cv-section-head">
          <h3>Roadmap Pembelajaran</h3>
          <button type="button" className="cv-chip-button">
            Lihat Semua Materi
          </button>
        </div>

        <div className="cv-roadmap-grid">
          {roadmap.map((item) => (
            <article key={item.title} className="cv-roadmap-card">
              <div className="cv-roadmap-card__top">
                <div className="cv-roadmap-icon">
                  <i className={`bi ${item.icon}`} />
                </div>
                <span className={`cv-badge-${item.badge}`}>
                  {item.status}
                </span>
              </div>
              <h4>{item.title}</h4>
              <p>{item.description}</p>
              <div className="cv-roadmap-card__footer">
                <small>Durasi: {item.duration}</small>
              </div>
            </article>
          ))}
        </div>
      </section>

      <section className="cv-timeline-bottom-grid">
        <article className="cv-dashboard-panel cv-timeline-stat-card">
          <div className="cv-dashboard-panel__header">
            <h3>Statistik Belajar Anda</h3>
          </div>

          <div className="cv-chart-placeholder">
            <img src={timelineChart} alt="Statistik Belajar Anda" />
          </div>
        </article>

        <article className="cv-dashboard-panel cv-timeline-achievement-card">
          <div className="cv-dashboard-panel__header">
            <h3>Pencapaian Terbaru</h3>
          </div>

          <div className="cv-achievement-list">
            {achievements.map((item) => (
              <div key={item.title} className="cv-achievement-item">
                <div className="cv-icon-box cv-achievement-icon">
                  <i className="bi bi-award" />
                </div>
                <div>
                  <h4>{item.title}</h4>
                  <p>{item.detail}</p>
                </div>
                <span>{item.xp}</span>
              </div>
            ))}
          </div>
        </article>
      </section>
    </div>
  )
}

export default Timeline
