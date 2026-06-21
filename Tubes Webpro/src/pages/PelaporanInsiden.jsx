import { useState } from 'react'
import '../styles/IncidentReportPage.css'

const incidentTypes = [
  {
    id: 'phishing',
    title: 'Phising',
    description: 'Upaya penipuan untuk mendapatkan data pribadi',
    icon: 'bi-envelope-paper',
  },
  {
    id: 'online-fraud',
    title: 'Penipuan Online',
    description: 'Penipuan jual beli, investasi, atau jasa',
    icon: 'bi-eyeglasses',
  },
  {
    id: 'account-hijack',
    title: 'Akun Diretas',
    description: 'Akun media sosial, email, atau aplikasi',
    icon: 'bi-lock-fill',
  },
  {
    id: 'data-breach',
    title: 'Kebocoran Data',
    description: 'Data pribadi Anda tersebar atau bocor',
    icon: 'bi-file-earmark-lock2',
  },
  {
    id: 'malware',
    title: 'Malware atau Virus',
    description: 'Perangkat terinfeksi malware atau virus',
    icon: 'bi-cpu-fill',
  },
  {
    id: 'other',
    title: 'Lainnya',
    description: 'Jenis insiden lainnya',
    icon: 'bi-three-dots',
  },
]

const steps = [
  'Jenis & Detail Insiden',
  'Informasi Tambahan',
  'Tinjau & Kirim',
]

function PelaporanInsiden() {
  const [selectedType, setSelectedType] = useState('phishing')

  return (
    <section className="cv-incident-page">
      <header className="cv-incident-page__hero">
        <h1>Pelaporan Insiden Digital</h1>
        <p>
          Laporkan kejadian atau ancaman keamanan digital yang Anda alami. Laporan
          Anda akan membantu menciptakan ruang digital yang lebih aman.
        </p>
      </header>

      <section className="cv-incident-steps" aria-label="Tahapan pelaporan">
        {steps.map((step, index) => (
          <div key={step} className="cv-incident-steps__item">
            <span className="cv-incident-steps__number">{index + 1}</span>
            <span>{step}</span>
          </div>
        ))}
      </section>

      <div className="cv-incident-layout">
        <div className="cv-incident-main">
          <section className="cv-incident-card cv-incident-form">
            <div className="cv-incident-form__section-head">
              <div>
                <h2>Jenis Insiden</h2>
                <p>Pilih jenis insiden yang anda alami</p>
              </div>
              <button type="button" className="cv-incident-next-button">
                Lanjutkan &gt;
              </button>
            </div>

            <div className="cv-incident-type-grid">
              {incidentTypes.map((item) => (
                <button
                  key={item.id}
                  type="button"
                  className={`cv-incident-type-card${
                    selectedType === item.id ? ' is-active' : ''
                  }`}
                  onClick={() => setSelectedType(item.id)}
                >
                  <span className="cv-incident-type-card__icon">
                    <i className={`bi ${item.icon}`} />
                  </span>
                  <span className="cv-incident-type-card__body">
                    <strong>{item.title}</strong>
                    <small>{item.description}</small>
                  </span>
                </button>
              ))}
            </div>

            <div className="cv-incident-form__details">
              <div className="cv-incident-form__section-title">
                <h3>Detail Insiden</h3>
              </div>

              <label className="cv-incident-field">
                <span>Judul Laporan</span>
                <input
                  type="text"
                  defaultValue="Contoh: Saya menerima email phishing yang mengatasnamakan bank"
                />
              </label>

              <label className="cv-incident-field">
                <span>Deskripsi Kejadian</span>
                <textarea
                  rows="4"
                  defaultValue="Jelaskan secara detail kronologi kejadian yang anda alami..."
                />
              </label>

              <div className="cv-incident-form__grid">
                <label className="cv-incident-field">
                  <span>Waktu Kejadian</span>
                  <div className="cv-incident-field__with-icon">
                    <input type="text" defaultValue="Pilih tanggal & waktu" />
                    <i className="bi bi-calendar3" />
                  </div>
                </label>

                <label className="cv-incident-field">
                  <span>Lampiran Bukti (Opsional)</span>
                  <button type="button" className="cv-incident-upload">
                    <i className="bi bi-upload" />
                    Upload File
                    <small>PNG, JPG, PDF (maks. 10MB)</small>
                  </button>
                </label>

                <label className="cv-incident-field">
                  <span>Tempat Kejadian (Opsional)</span>
                  <input type="text" defaultValue="Contoh: Email, Whatsapp, Instagram, dll" />
                </label>
              </div>
            </div>
          </section>
        </div>

        <aside className="cv-incident-side">
          <section className="cv-incident-card cv-incident-help-card">
            <h2>Sebelum Melaporkan</h2>
            <ul>
              <li>Pastikan informasi yang Anda berikan benar dan lengkap.</li>
              <li>Lampirkan bukti jika tersedia (screenshot, email, dll).</li>
              <li>Jangan bagikan informasi sensitif di deskripsi.</li>
            </ul>
          </section>

          <section className="cv-incident-card cv-incident-help-card">
            <h2>Butuh Bantuan Mendesak?</h2>
            <p>
              Jika Anda sedang mengalami situasi darurat atau kerugian besar, segera
              hubungi:
            </p>

            <div className="cv-incident-contact-box">
              <div className="cv-incident-contact-item">
                <span className="cv-incident-contact-item__dot" />
                <div>
                  <strong>CSIRT Kota Bandung</strong>
                  <p>(022) 1234 5678</p>
                </div>
              </div>

              <div className="cv-incident-contact-item">
                <span className="cv-incident-contact-item__dot" />
                <div>
                  <strong>csirt@bandung.go.id</strong>
                  <p>Layanan 24/7</p>
                </div>
              </div>
            </div>
          </section>
        </aside>
      </div>
    </section>
  )
}

export default PelaporanInsiden
