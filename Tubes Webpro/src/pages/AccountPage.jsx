import { useMemo, useState } from 'react'
import profileIllustration from '../assets/hero_gaia-1.png'
import '../styles/AccountPage.css'

const profile = {
  name: 'Maman Vyndy',
  email: 'maman.vyndy@gmail.com',
  role: 'Member Aktif',
  joinedAt: 'Bergabung sejak 24 Desember 2024',
}

const securitySummary = [
  {
    title: 'Password',
    status: 'Kuat',
    detail: 'Terakhir diubah 27 Mei 2024',
    action: 'Ubah Password',
    icon: 'bi-key',
  },
  {
    title: 'Two-Factor Authentication',
    status: 'Aktif',
    detail: 'Google Authenticator',
    action: 'Kelola 2FA',
    icon: 'bi-shield-lock',
  },
  {
    title: 'Email Pemulihan',
    status: 'Terverifikasi',
    detail: 'maman.vyndy@gmail.com',
    action: 'Ubah Email',
    icon: 'bi-envelope-check',
  },
  {
    title: 'Nomor Pemulihan',
    status: 'Terverifikasi',
    detail: '+62 812-3456-7890',
    action: 'Ubah Nomor',
    icon: 'bi-phone',
  },
]

const identityItems = [
  {
    id: 1,
    category: 'Email',
    type: 'Email Utama',
    detail: 'maman.vyndy@gmail.com',
    relatedTo: 'Akun CyberVault',
    status: 'Terverifikasi',
    checkedAt: '29 Apr 2026',
    icon: 'bi-envelope',
  },
  {
    id: 2,
    category: 'Email',
    type: 'Email Cadangan',
    detail: 'maman.backup@gmail.com',
    relatedTo: 'Pemulihan Akun',
    status: 'Terverifikasi',
    checkedAt: '26 Apr 2026',
    icon: 'bi-envelope-paper',
  },
  {
    id: 3,
    category: 'Nomor Telepon',
    type: 'Nomor Telepon Utama',
    detail: '+62 812-3456-7890',
    relatedTo: 'WhatsApp',
    status: 'Terverifikasi',
    checkedAt: '23 Apr 2026',
    icon: 'bi-phone',
  },
  {
    id: 4,
    category: 'Nomor Telepon',
    type: 'Nomor Telepon Cadangan',
    detail: '+62 856-0000-0000',
    relatedTo: 'Pemulihan Akun',
    status: 'Pending',
    checkedAt: '22 Apr 2026',
    icon: 'bi-phone-vibrate',
  },
  {
    id: 5,
    category: 'Akun Online',
    type: 'Instagram',
    detail: '@maman.vyndy',
    relatedTo: 'Media Sosial',
    status: 'Aktif',
    checkedAt: '18 Apr 2026',
    icon: 'bi-instagram',
  },
  {
    id: 6,
    category: 'Dokumen Penting',
    type: 'KTP',
    detail: '3271 xxxx xxxx xxxx',
    relatedTo: 'Verifikasi Identitas',
    status: 'Terverifikasi',
    checkedAt: '14 Apr 2026',
    icon: 'bi-file-earmark-lock',
  },
]

const activityItems = [
  { title: 'Email Utama Diperbarui', time: '26 Mei 2026, 10:30' },
  { title: '2FA Diaktifkan', time: '25 Mei 2026, 15:00' },
  { title: 'Password Diubah', time: '20 Mei 2026, 13:45' },
]

const filterIcons = {
  'Semua Identitas': 'bi-grid',
  Email: 'bi-envelope',
  'Nomor Telepon': 'bi-phone',
  'Akun Online': 'bi-globe2',
  'Dokumen Penting': 'bi-file-earmark-text',
  'Kontak Darurat': 'bi-person-heart',
}

const statusClassNames = {
  Terverifikasi: 'is-success',
  Aktif: 'is-info',
  Pending: 'is-warning',
  'Perlu Dicek': 'is-danger',
}

function AccountPage() {
  const [activeCategory, setActiveCategory] = useState('Semua Identitas')

  const categories = useMemo(() => {
    const baseCategories = [
      'Semua Identitas',
      'Email',
      'Nomor Telepon',
      'Akun Online',
      'Dokumen Penting',
      'Kontak Darurat',
    ]

    return baseCategories.map((name) => ({
      name,
      icon: filterIcons[name],
      count:
        name === 'Semua Identitas'
          ? identityItems.length
          : identityItems.filter((item) => item.category === name).length,
    }))
  }, [])

  const filteredItems = useMemo(() => {
    if (activeCategory === 'Semua Identitas') {
      return identityItems
    }

    return identityItems.filter((item) => item.category === activeCategory)
  }, [activeCategory])

  return (
    <section className="cv-account-page">
      <header className="cv-account-page__header">
        <div>
          <p className="cv-account-page__eyebrow">Akun</p>
          <h1>Akun Saya</h1>
          <p className="cv-account-page__subtitle">
            Kelola profil, keamanan akun, dan data identitas digital anda
          </p>
        </div>
      </header>

      <div className="cv-account-overview">
        <article className="cv-account-card cv-account-profile">
          <div className="cv-account-profile__avatar">
            <img src={profileIllustration} alt={profile.name} />
          </div>

          <div className="cv-account-profile__body">
            <div className="cv-account-profile__heading">
              <div>
                <h2>{profile.name}</h2>
                <p>{profile.email}</p>
              </div>
              <span className="cv-account-pill">{profile.role}</span>
            </div>

            <p className="cv-account-profile__meta">{profile.joinedAt}</p>

            <button type="button" className="cv-account-inline-button">
              Edit Profil
            </button>
          </div>
        </article>

        <article className="cv-account-card cv-account-security">
          <div className="cv-account-card__heading">
            <div>
              <p className="cv-account-card__kicker">Proteksi</p>
              <h2>Ringkasan Keamanan Akun</h2>
            </div>
          </div>

          <div className="cv-account-security__grid">
            {securitySummary.map((item) => (
              <div key={item.title} className="cv-account-security__item">
                <div className="cv-account-security__icon">
                  <i className={`bi ${item.icon}`} />
                </div>
                <div className="cv-account-security__content">
                  <span className="cv-account-security__label">{item.title}</span>
                  <strong>{item.status}</strong>
                  <p>{item.detail}</p>
                  <button type="button" className="cv-account-text-button">
                    {item.action}
                  </button>
                </div>
              </div>
            ))}
          </div>
        </article>
      </div>

      <section className="cv-account-vault">
        <div className="cv-account-vault__header">
          <div>
            <p className="cv-account-vault__kicker">Brankas Identitas</p>
            <h2>Digital Identity Vault</h2>
            <p>Kelola dan pantau identitas digital anda dengan aman</p>
          </div>

          <button type="button" className="cv-account-primary-button">
            <i className="bi bi-plus-lg" />
            Tambah Identitas
          </button>
        </div>

        <div className="cv-account-vault__content">
          <aside className="cv-account-card cv-account-filters">
            <div className="cv-account-filter-list">
              {categories.map((category) => (
                <button
                  key={category.name}
                  type="button"
                  className={`cv-account-filter-item${
                    activeCategory === category.name ? ' is-active' : ''
                  }`}
                  onClick={() => setActiveCategory(category.name)}
                >
                  <span className="cv-account-filter-item__left">
                    <i className={`bi ${category.icon}`} />
                    {category.name}
                  </span>
                  <span className="cv-account-filter-item__count">{category.count}</span>
                </button>
              ))}
            </div>

            <div className="cv-account-filter-note">
              <div className="cv-account-filter-note__icon">
                <i className="bi bi-shield-fill-check" />
              </div>
              <p>
                Semua data disimpan secara aman dan hanya dapat diakses oleh pemilik
                akun.
              </p>
            </div>
          </aside>

          <article className="cv-account-card cv-account-table-card">
            <div className="cv-account-card__heading">
              <div>
                <h3>Daftar Identitas Digital</h3>
              </div>
              <div className="cv-account-table-toolbar">
                <button type="button" className="cv-account-table-tab is-active">
                  Semua Identitas
                </button>
                <span className="cv-account-table-card__count">
                  {filteredItems.length} data
                </span>
              </div>
            </div>

            <div className="cv-account-table-wrap">
              <table className="cv-account-table">
                <thead>
                  <tr>
                    <th>Jenis Identitas</th>
                    <th>Detail</th>
                    <th>Terkait Dengan</th>
                    <th>Status</th>
                    <th>Terakhir Diperiksa</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  {filteredItems.map((item) => (
                    <tr key={item.id}>
                      <td>
                        <div className="cv-account-table__identity">
                          <span className="cv-account-table__icon">
                            <i className={`bi ${item.icon}`} />
                          </span>
                          <span>{item.type}</span>
                        </div>
                      </td>
                      <td>{item.detail}</td>
                      <td>{item.relatedTo}</td>
                      <td>
                        <span
                          className={`cv-account-status ${
                            statusClassNames[item.status] ?? 'is-info'
                          }`}
                        >
                          {item.status}
                        </span>
                      </td>
                      <td>{item.checkedAt}</td>
                      <td>
                        <div className="cv-account-table__actions">
                          <button type="button" aria-label={`Edit ${item.type}`}>
                            <i className="bi bi-pencil" />
                          </button>
                          <button type="button" aria-label={`Hapus ${item.type}`}>
                            <i className="bi bi-trash3" />
                          </button>
                        </div>
                      </td>
                    </tr>
                  ))}
                  {filteredItems.length === 0 ? (
                    <tr>
                      <td colSpan="6" className="cv-account-table__empty">
                        Belum ada data pada kategori ini.
                      </td>
                    </tr>
                  ) : null}
                </tbody>
              </table>
            </div>

            <div className="cv-account-table-pagination">
              <button type="button" aria-label="Halaman 1" className="is-active">
                1
              </button>
              <button type="button" aria-label="Halaman 2">
                2
              </button>
              <button type="button" aria-label="Halaman selanjutnya">
                <i className="bi bi-chevron-right" />
              </button>
            </div>
          </article>

          <aside className="cv-account-sidepanels">
            <article className="cv-account-card cv-account-score">
              <div className="cv-account-card__heading">
                <div>
                  <p className="cv-account-card__kicker">Insight</p>
                  <h3>Skor Keamanan Identitas</h3>
                </div>
              </div>

              <div className="cv-account-score__badge">
                <i className="bi bi-shield-check" />
              </div>
              <div className="cv-account-score__value">80/100</div>
              <span className="cv-account-score__label">Baik</span>
              <p>
                Tingkatkan keamanan identitas anda dengan memperbarui informasi secara
                berkala dan aktifkan 2FA di semua akun.
              </p>
              <button type="button" className="cv-account-primary-button is-secondary">
                Lihat Rekomendasi
              </button>
            </article>

            <article className="cv-account-card cv-account-activity">
              <div className="cv-account-card__heading">
                <div>
                  <p className="cv-account-card__kicker">Timeline</p>
                  <h3>Aktivitas Terbaru</h3>
                </div>
              </div>

              <div className="cv-account-activity__list">
                {activityItems.map((item) => (
                  <div key={`${item.title}-${item.time}`} className="cv-account-activity__item">
                    <span className="cv-account-activity__dot" aria-hidden="true" />
                    <div>
                      <strong>{item.title}</strong>
                      <p>{item.time}</p>
                    </div>
                  </div>
                ))}
              </div>

              <button type="button" className="cv-account-text-button">
                Lihat Semua Aktivitas
              </button>
            </article>
          </aside>
        </div>
      </section>
    </section>
  )
}

export default AccountPage
