import PusatEdukasi from './PusatEdukasi.jsx'

function PlaceholderPage({ title, description }) {
  if (
    title === 'Pusat Edukasi' ||
    description === 'Konten edukasi keamanan digital akan ditampilkan di sini.'
  ) {
    return <PusatEdukasi />
  }

  return (
    <section className="container-fluid px-0">
      <div className="cv-placeholder-card">
        <p className="cv-section-kicker">CyberVault</p>
        <h1 className="cv-placeholder-card__title">{title}</h1>
        <p className="cv-placeholder-card__text">{description}</p>
      </div>
    </section>
  )
}

export default PlaceholderPage
