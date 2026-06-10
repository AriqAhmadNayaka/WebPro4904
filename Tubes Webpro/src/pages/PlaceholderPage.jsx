function PlaceholderPage({ title, description }) {
  return (
    <section className="container-fluid px-0">
      <div className="cv-card cv-card-dark">
        <p className="cybervault-section-label">CyberVault</p>
        <h1 className="cv-section-title mb-3">{title}</h1>
        <p className="cybervault-card__text">
          {description}
        </p>
      </div>
    </section>
  )
}

export default PlaceholderPage
