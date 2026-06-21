import heroImage from '../assets/WhatsApp Image 2026-06-10 at 15.47.24.jpeg'

function PusatEdukasi() {
  const quickCategories = [
    'Keamanan Dasar',
    'Privasi Data',
    'Phishing',
    'Mobile Banking',
    'Smart City',
  ]

  const statistics = [
    {
      icon: 'bi-bar-chart-line',
      title: 'Progress Belajar',
      value: '68%',
      note: 'Naik 12% bulan ini',
      tone: 'blue',
    },
    {
      icon: 'bi-clock-history',
      title: 'Total Waktu Belajar',
      value: '18j 12m',
      note: '4j 12m minggu ini',
      tone: 'sky',
    },
    {
      icon: 'bi-journal-check',
      title: 'Materi Diselesaikan',
      value: '12 Modul',
      note: 'dari 24 materi aktif',
      tone: 'green',
    },
    {
      icon: 'bi-patch-check',
      title: 'Sertifikat',
      value: '6 Badge',
      note: '2 badge siap dibuka',
      tone: 'amber',
    },
    {
      icon: 'bi-trophy',
      title: 'Level',
      value: 'Level 2',
      note: 'Waspada Digital',
      tone: 'violet',
    },
  ]

  const featuredMaterials = [
    {
      title: 'Waspada Phishing',
      progress: '82%',
      duration: '28 menit',
      status: 'Sedang dipelajari',
      imageTone: 'blue',
      icon: 'bi-shield-exclamation',
    },
    {
      title: 'Password yang Kuat',
      progress: '100%',
      duration: '22 menit',
      status: 'Selesai',
      imageTone: 'green',
      icon: 'bi-key',
    },
    {
      title: 'Keamanan Media Sosial',
      progress: '56%',
      duration: '31 menit',
      status: 'Lanjutkan',
      imageTone: 'sky',
      icon: 'bi-instagram',
    },
    {
      title: 'Keamanan Mobile Banking',
      progress: '34%',
      duration: '26 menit',
      status: 'Baru dimulai',
      imageTone: 'amber',
      icon: 'bi-phone',
    },
    {
      title: 'Smart City & Keamanan Data',
      progress: '12%',
      duration: '35 menit',
      status: 'Direkomendasikan',
      imageTone: 'violet',
      icon: 'bi-buildings',
    },
  ]

  const categories = [
    {
      title: 'Keamanan Dasar',
      desc: 'Mulai dari fondasi keamanan akun dan perangkat.',
      icon: 'bi-shield-lock',
    },
    {
      title: 'Privasi Data',
      desc: 'Belajar mengelola izin, data pribadi, dan jejak digital.',
      icon: 'bi-file-earmark-lock',
    },
    {
      title: 'Phishing & Penipuan',
      desc: 'Kenali modus penipuan online dan serangan rekayasa sosial.',
      icon: 'bi-bug',
    },
    {
      title: 'Keamanan Akun',
      desc: 'Perkuat password, MFA, dan pengaturan akun penting.',
      icon: 'bi-person-lock',
    },
    {
      title: 'Perangkat & Jaringan',
      desc: 'Amankan laptop, ponsel, Wi-Fi, dan koneksi publik.',
      icon: 'bi-router',
    },
    {
      title: 'Smart City & IoT',
      desc: 'Pahami risiko data di layanan cerdas dan perangkat terhubung.',
      icon: 'bi-cpu',
    },
  ]

  const activities = [
    {
      title: 'Menyelesaikan modul Password yang Kuat',
      description: 'Anda berhasil menamatkan materi dan membuka ringkasan praktik terbaik.',
      time: 'Hari ini, 09:30',
      icon: 'bi-check2-circle',
    },
    {
      title: 'Melanjutkan kuis Waspada Phishing',
      description: 'Skor sementara 80%. Satu sesi lagi untuk menuntaskan evaluasi.',
      time: 'Kemarin, 16:45',
      icon: 'bi-lightning-charge',
    },
    {
      title: 'Membuka kategori Smart City & Keamanan Data',
      description: 'Kategori baru aktif berdasarkan progres dan minat pembelajaran Anda.',
      time: 'Kemarin, 11:20',
      icon: 'bi-grid-1x2',
    },
    {
      title: 'Meraih badge Akun Lebih Aman',
      description: 'Badge diberikan setelah menyelesaikan materi autentikasi dua faktor.',
      time: '2 hari lalu',
      icon: 'bi-award',
    },
  ]

  const tips = [
    'Gunakan password manager agar setiap akun memiliki password unik.',
    'Jangan pernah membagikan kode OTP, bahkan kepada pihak yang mengaku resmi.',
    'Periksa ulang URL sebelum login ke layanan finansial atau pemerintahan digital.',
  ]

  return (
    <div className="cv-dashboard-container cv-education-page">
      <section className="cv-education-hero-card">
        <div className="cv-education-hero-card__content">
          <p className="cv-section-kicker">Pusat Edukasi</p>
          <h1>Cyber Learning Center</h1>
          <p className="cv-education-hero-card__text">
            Pusat pembelajaran keamanan digital CyberVault untuk membantu Anda
            memahami ancaman siber, memperkuat privasi, dan membangun kebiasaan
            online yang lebih aman di era smart city.
          </p>

          <div className="cv-education-search">
            <i className="bi bi-search" />
            <input type="text" placeholder="Cari materi, topik, atau kategori..." />
          </div>

          <div className="cv-education-chip-row">
            {quickCategories.map((item) => (
              <button key={item} type="button" className="cv-education-chip">
                {item}
              </button>
            ))}
          </div>
        </div>

        <div className="cv-education-hero-card__visual">
          <div className="cv-education-hero-illustration">
            <img src={heroImage} alt="Cyber Learning Center" />
            <div className="cv-education-hero-illustration__overlay">
              <span>24 Modul Aktif</span>
              <strong>Belajar lebih aman, langkah demi langkah</strong>
            </div>
          </div>
        </div>
      </section>

      <section className="cv-education-stats-grid">
        {statistics.map((item) => (
          <article key={item.title} className="cv-education-stat-card">
            <div className={`cv-education-stat-card__icon is-${item.tone}`}>
              <i className={`bi ${item.icon}`} />
            </div>
            <div>
              <p>{item.title}</p>
              <h3>{item.value}</h3>
              <span>{item.note}</span>
            </div>
          </article>
        ))}
      </section>

      <section className="cv-education-section-card">
        <div className="cv-education-section-head">
          <div>
            <p className="cv-section-kicker">Materi Pilihan</p>
            <h2>Materi Pilihan Untuk Anda</h2>
          </div>
        </div>

        <div className="cv-education-material-grid">
          {featuredMaterials.map((item) => (
            <article key={item.title} className="cv-education-material-card">
              <div className={`cv-education-material-card__thumb is-${item.imageTone}`}>
                <i className={`bi ${item.icon}`} />
              </div>

              <div className="cv-education-material-card__body">
                <h3>{item.title}</h3>

                <div className="cv-education-material-card__meta">
                  <span>{item.duration}</span>
                  <span>{item.status}</span>
                </div>

                <div className="cv-education-material-card__progress">
                  <div className="cv-education-material-card__progress-bar">
                    <span style={{ width: item.progress }} />
                  </div>
                  <strong>{item.progress}</strong>
                </div>
              </div>
            </article>
          ))}
        </div>
      </section>

      <section className="cv-education-section-card">
        <div className="cv-education-section-head">
          <div>
            <p className="cv-section-kicker">Kategori</p>
            <h2>Kategori Materi</h2>
          </div>
        </div>

        <div className="cv-education-category-grid">
          {categories.map((item) => (
            <article key={item.title} className="cv-education-category-card">
              <div className="cv-education-category-card__icon">
                <i className={`bi ${item.icon}`} />
              </div>
              <h3>{item.title}</h3>
              <p>{item.desc}</p>
            </article>
          ))}
        </div>
      </section>

      <section className="cv-education-bottom-grid">
        <article className="cv-education-section-card">
          <div className="cv-education-section-head">
            <div>
              <p className="cv-section-kicker">Aktivitas</p>
              <h2>Aktivitas Belajar Terbaru</h2>
            </div>
          </div>

          <div className="cv-education-activity-timeline">
            {activities.map((item, index) => (
              <article key={`${item.title}-${item.time}`} className="cv-education-activity-row">
                <div className="cv-education-activity-row__rail">
                  <div className="cv-education-activity-row__icon">
                    <i className={`bi ${item.icon}`} />
                  </div>
                  {index < activities.length - 1 ? (
                    <span className="cv-education-activity-row__line" aria-hidden="true" />
                  ) : null}
                </div>

                <div className="cv-education-activity-row__content">
                  <h3>{item.title}</h3>
                  <p>{item.description}</p>
                  <span>{item.time}</span>
                </div>
              </article>
            ))}
          </div>
        </article>

        <article className="cv-education-section-card cv-education-tips-card">
          <div className="cv-education-section-head">
            <div>
              <p className="cv-section-kicker">Tips Hari Ini</p>
              <h2>Tetap Aman Saat Online</h2>
            </div>
          </div>

          <div className="cv-education-tips-list">
            {tips.map((item) => (
              <div key={item} className="cv-education-tip-item">
                <i className="bi bi-stars" />
                <p>{item}</p>
              </div>
            ))}
          </div>

          <div className="cv-education-tips-highlight">
            <strong>Fokus hari ini:</strong>
            <p>
              Selesaikan materi phishing sebelum membuka tautan yang tidak dikenal
              di pesan instan atau email kerja.
            </p>
          </div>
        </article>
      </section>
    </div>
  )
}

export default PusatEdukasi
