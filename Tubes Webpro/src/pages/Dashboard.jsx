import heroImage from '../assets/WhatsApp Image 2026-06-10 at 15.47.24.jpeg'

function Dashboard() {
  const statistik = [
    {
      icon: 'bi-shield-lock',
      title: 'Cyber Awareness Score',
      value: '80',
      suffix: '/100',
      note: 'Baik',
    },
    {
      icon: 'bi-mortarboard',
      title: 'Progres Belajar',
      value: '50%',
      note: '12 / 24 Materi Selesai',
    },
    {
      icon: 'bi-file-earmark-text',
      title: 'Laporan Keaktifan',
      value: '3',
      note: '2 Sedang Diproses',
    },
    {
      icon: 'bi-patch-check',
      title: 'Sertifikat Pembelajaran',
      value: '6',
      note: 'Lihat Semua',
    },
  ]

  const aksiCepat = [
    {
      icon: 'bi-book',
      title: 'Mulai Belajar',
      text: 'Akses materi & kuis keamanan digital',
    },
    {
      icon: 'bi-bell',
      title: 'Laporkan Insiden',
      text: 'Laporkan kejadian mencurigakan',
    },
    {
      icon: 'bi-person-badge',
      title: 'Cek Data Pribadi',
      text: 'Pantau & kelola data pribadi Anda',
    },
    {
      icon: 'bi-file-earmark-lock',
      title: 'Baca Cyber Alert',
      text: 'Dapatkan berita & peringatan terbaru',
    },
  ]

  const progresBelajar = [
    { label: 'Keamanan Akun & Password', value: '70%' },
    { label: 'Privasi Data Pribadi', value: '50%' },
    { label: 'Phising & Social Engineering', value: '35%' },
  ]

  const berita = [
    {
      icon: 'bi-exclamation-triangle-fill',
      title: 'Peringatan: Modus Penipuan QRIS Palsu Meningkat',
      text: 'Waspadai modus penipuan menggunakan QRIS palsu yang marak terjadi di berbagai kota.',
      tag: 'Peringatan',
      time: '2 Jam Lalu',
    },
    {
      icon: 'bi-lock-fill',
      title: 'Kebocoran Data di Platform E-Commerce',
      text: 'Beberapa data pengguna e-commerce dilaporkan bocor. Segera ubah password dan aktifkan autentikasi dua faktor.',
      tag: 'Informasi',
      time: '2 Jam Lalu',
    },
  ]

  const aktivitasTerbaru = [
    {
      icon: 'bi-book',
      title: 'Menyelesaikan materi',
      subtitle: 'Phishing: Kenali & Hindari',
      time: 'Hari ini, 09:30',
      tone: 'green',
    },
    {
      icon: 'bi-ui-checks-grid',
      title: 'Menyelesaikan kuis',
      subtitle: 'Keamanan Password',
      time: 'Kemarin, 16:45',
      tone: 'purple',
    },
    {
      icon: 'bi-file-earmark-text',
      title: 'Laporan #CV-2024-0017',
      subtitle: 'Diterima oleh sistem',
      time: 'Kemarin, 11:20',
      tone: 'red',
    },
    {
      icon: 'bi-clock-history',
      title: 'Laporan #CV-2024-0016',
      subtitle: 'Sedang diproses',
      time: '2 hari lalu',
      tone: 'orange',
    },
    {
      icon: 'bi-shield-check',
      title: 'Mengupdate recovery email',
      subtitle: 'Akun berhasil diperbarui',
      time: '3 hari lalu',
      tone: 'green',
    },
  ]

  const kategoriIdentitas = [
    'Semua Identitas (5)',
    'Email (2)',
    'Nomor Telepon (2)',
    'Akun Online (4)',
    'Dokumen Penting (2)',
    'Kontak Darurat (1)',
  ]

  const daftarIdentitas = [
    ['Email Utama', 'maman@example.com', 'Akun CyberVault', 'Terverifikasi', '23 Apr 2025'],
    ['Email Cadangan', 'maman.vyndy@gmail.com', 'Pemulihan Akun', 'Terverifikasi', '18 Apr 2025'],
    ['Nomor Telepon Utama', '+62 812 xxxx xxxx', 'Verifikasi & Notifikasi', 'Terverifikasi', '23 Apr 2025'],
    ['Nomor Telepon Cadangan', '+62 857 xxxx xxxx', 'Pemulihan Akun', 'Pending', '18 Apr 2025'],
    ['Google Account', 'maman.vyndy@gmail.com', 'Login & Sinkronisasi', 'Aktif', '20 Apr 2025'],
    ['Instagram', '@mamanyvndy', 'Media Sosial', 'Aktif', '15 Apr 2025'],
    ['KTP', '3273 xxxx xxxx', 'Verifikasi Identitas', 'Terverifikasi', '10 Apr 2025'],
  ]

  const aktivitasVault = [
    ['Email utama diperbarui', '23 Apr 2025'],
    ['2FA diaktifkan', '20 Apr 2025'],
    ['Password diubah', '18 Apr 2025'],
  ]

  return (
    <div className="cv-dashboard-container">
      <section className="cv-hero-panel cv-dashboard-hero">
        <div className="cv-dashboard-hero__content cv-hero-text">
          <div className="cv-hero-greeting">Hai, Maman!</div>
          <h1 className="cv-dashboard-hero__subtitle">Selamat Datang di CyberVault</h1>
          <p className="cv-dashboard-hero__text">
            CyberVault siap hadir untuk melindungi data pribadi anda dari kejahatan
            siber demi mendukung keamanan di era Smart-City.
          </p>

          <div className="cv-dashboard-hero__actions">
            <button type="button" className="cv-cta-button cv-cta-button--cyan">
              <i className="bi bi-book" />
              Mulai Belajar
            </button>
            <button type="button" className="cv-cta-button cv-cta-button--white">
              <i className="bi bi-bug" />
              Laporkan Insiden
            </button>
          </div>
        </div>

        <div className="cv-hero-image">
          <img src={heroImage} alt="CyberVault Smart City" />
        </div>
      </section>

      <section className="cv-stat-grid cv-dashboard-grid--stats">
        {statistik.map((item) => (
          <article key={item.title} className="cv-stat-card">
            <div className="cv-icon-box">
              <i className={`bi ${item.icon}`} />
            </div>
            <div className="cv-stat-card__body">
              <p className="cv-stat-card__title">{item.title}</p>
              <h3 className="cv-stat-card__value">
                {item.value}
                {item.suffix ? <span>{item.suffix}</span> : null}
              </h3>
              <p className="cv-stat-card__note">{item.note}</p>
            </div>
          </article>
        ))}
      </section>

      <section className="cv-action-section cv-dashboard-panel">
        <div className="cv-dashboard-panel__header">
          <h3>Aksi Cepat</h3>
        </div>
        <div className="cv-action-grid cv-dashboard-grid--actions">
          {aksiCepat.map((item, index) => (
            <article
              key={item.title}
              className={`cv-action-card${index % 2 === 1 ? ' is-alt' : ''}`}
            >
              <div className="cv-icon-box">
                <i className={`bi ${item.icon}`} />
              </div>
              <div className="cv-action-card__body">
                <h4>{item.title}</h4>
                <p>{item.text}</p>
              </div>
              <i className="bi bi-chevron-right cv-action-card__arrow" />
            </article>
          ))}
        </div>
      </section>

      <section className="cv-bottom-grid cv-dashboard-grid--bottom">
        <article className="cv-progress-card cv-dashboard-panel cv-dashboard-panel--progress">
          <div className="cv-dashboard-panel__header">
            <h3>Progres Belajar Anda</h3>
            <button type="button" className="cv-chip-button">
              Lihat Semua Materi
            </button>
          </div>

          <div className="cv-progress-layout">
            <div className="cv-progress-ring">
              <div className="cv-progress-ring__inner">50%</div>
            </div>

            <div className="cv-progress-list">
              {progresBelajar.map((item, index) => (
                <div key={item.label} className="cv-progress-item">
                  <div className="cv-progress-item__label">
                    <span>{item.label}</span>
                    <span>{item.value}</span>
                  </div>
                  <div className="cv-progress-bar">
                    <span
                      className="cv-progress-bar__fill"
                      style={{ width: item.value }}
                      data-index={index}
                    />
                  </div>
                </div>
              ))}
            </div>
          </div>
        </article>

        <article className="cv-alert-card cv-dashboard-panel cv-dashboard-panel--alerts">
          <div className="cv-dashboard-panel__header">
            <h3>Berita dan Peringatan</h3>
          </div>

          <div className="cv-alert-list">
            {berita.map((item) => (
              <article key={item.title} className="cv-alert-item">
                <div className="cv-icon-box">
                  <i className={`bi ${item.icon}`} />
                </div>
                <div className="cv-alert-item__body">
                  <h4>{item.title}</h4>
                  <p>{item.text}</p>
                  <div className="cv-alert-item__meta">
                    <span className="cv-alert-tag">{item.tag}</span>
                    <span>{item.time}</span>
                  </div>
                </div>
              </article>
            ))}
          </div>
        </article>
      </section>

      <section className="cv-recent-activity-card">
        <div className="cv-section-head">
          <h3>Aktivitas Terbaru Anda</h3>
          <button type="button" className="cv-text-link">
            Lihat Semua
          </button>
        </div>

        <div className="cv-recent-activity-timeline">
          {aktivitasTerbaru.map((item, index) => (
            <article key={`${item.title}-${item.time}`} className="cv-activity-item">
              <div className={`cv-activity-item__icon is-${item.tone}`}>
                <i className={`bi ${item.icon}`} />
              </div>
              <div className="cv-activity-item__content">
                <h4>{item.title}</h4>
                <p>{item.subtitle}</p>
                <span>{item.time}</span>
              </div>
              {index < aktivitasTerbaru.length - 1 ? (
                <div className="cv-activity-item__line" aria-hidden="true" />
              ) : null}
            </article>
          ))}
        </div>
      </section>

      <section className="cv-identity-vault-section">
        <aside className="cv-vault-sidebar">
          <div className="cv-vault-card">
            <h3>Digital Identity Vault</h3>
            <p>Kelola dan pantau identitas digital Anda dengan aman.</p>

            <div className="cv-vault-menu">
              {kategoriIdentitas.map((item, index) => (
                <button
                  key={item}
                  type="button"
                  className={`cv-vault-menu__item${index === 0 ? ' active' : ''}`}
                >
                  {item}
                </button>
              ))}
            </div>
          </div>

          <div className="cv-vault-help-card">
            Semua data disimpan secara aman dan hanya dapat diakses oleh pemilik akun.
          </div>
        </aside>

        <div className="cv-vault-table-card">
          <div className="cv-section-head">
            <div>
              <h3>Daftar Identitas Digital</h3>
            </div>
            <button type="button" className="cv-add-button">
              + Tambah Identitas
            </button>
          </div>

          <div className="cv-vault-table-wrap">
            <table className="cv-vault-table">
              <thead>
                <tr>
                  <th>Jenis Identitas</th>
                  <th>Detail</th>
                  <th>Terkait Dengan</th>
                  <th>Status</th>
                  <th>Terakhir Diperbarui</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                {daftarIdentitas.map((item) => (
                  <tr key={`${item[0]}-${item[1]}`}>
                    <td>{item[0]}</td>
                    <td>{item[1]}</td>
                    <td>{item[2]}</td>
                    <td>
                      <span
                        className={`cv-status-badge ${
                          item[3] === 'Terverifikasi'
                            ? 'is-success'
                            : item[3] === 'Aktif'
                              ? 'is-info'
                              : 'is-warning'
                        }`}
                      >
                        {item[3]}
                      </span>
                    </td>
                    <td>{item[4]}</td>
                    <td>
                      <div className="cv-table-actions">
                        <button type="button" aria-label="Edit">
                          <i className="bi bi-pencil" />
                        </button>
                        <button type="button" aria-label="Detail">
                          <i className="bi bi-eye" />
                        </button>
                        <button type="button" aria-label="Delete">
                          <i className="bi bi-trash" />
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>

        <aside className="cv-vault-sidepanels">
          <div className="cv-vault-info-card">
            <h3>Skor Keamanan Identitas</h3>
            <div className="cv-vault-score">
              <div className="cv-vault-score__ring">
                <span>84</span>
                <small>/ 100</small>
              </div>
            </div>
            <strong>Baik</strong>
            <p>
              Tingkatkan keamanan identitas Anda dengan memperbarui informasi secara
              berkala dan aktifkan 2FA di semua akun.
            </p>
            <button type="button" className="cv-add-button cv-add-button--secondary">
              Lihat Rekomendasi
            </button>
          </div>

          <div className="cv-vault-info-card">
            <h3>Aktivitas Terbaru</h3>
            <div className="cv-vault-mini-list">
              {aktivitasVault.map((item) => (
                <div key={item[0]} className="cv-vault-mini-list__item">
                  <p>{item[0]}</p>
                  <span>{item[1]}</span>
                </div>
              ))}
            </div>
            <button type="button" className="cv-text-link cv-text-link--left">
              Lihat Semua Aktivitas
            </button>
          </div>
        </aside>
      </section>
    </div>
  )
}

export default Dashboard
