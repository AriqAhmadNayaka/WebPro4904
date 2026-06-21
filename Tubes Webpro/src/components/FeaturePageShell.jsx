function FeaturePageShell({
  eyebrow,
  title,
  description,
  stats = [],
  highlights = [],
  timeline = [],
  tips = [],
}) {
  return (
    <div className="cv-feature-page">
      <section className="cv-feature-hero">
        <div className="cv-feature-hero__copy">
          <p className="cv-section-kicker">{eyebrow}</p>
          <h1>{title}</h1>
          <p>{description}</p>
        </div>

        <div className="cv-feature-hero__visual" aria-hidden="true">
          <div className="cv-feature-hero__visual-grid">
            <span />
            <span />
            <span />
            <span />
          </div>
          <div className="cv-feature-hero__badge">CyberVault Workspace</div>
        </div>
      </section>

      <section className="cv-feature-stats">
        {stats.map((item) => (
          <article key={item.title} className="cv-feature-stat-card">
            <div className="cv-feature-stat-card__icon">
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

      <section className="cv-feature-grid">
        <article className="cv-feature-panel">
          <div className="cv-feature-panel__head">
            <div>
              <p className="cv-section-kicker">Ringkasan</p>
              <h2>Area Utama</h2>
            </div>
          </div>

          <div className="cv-feature-highlight-list">
            {highlights.map((item) => (
              <article key={item.title} className="cv-feature-highlight-card">
                <div className="cv-feature-highlight-card__icon">
                  <i className={`bi ${item.icon}`} />
                </div>
                <div>
                  <h3>{item.title}</h3>
                  <p>{item.description}</p>
                </div>
              </article>
            ))}
          </div>
        </article>

        <article className="cv-feature-panel">
          <div className="cv-feature-panel__head">
            <div>
              <p className="cv-section-kicker">Aktivitas</p>
              <h2>Pembaruan Terbaru</h2>
            </div>
          </div>

          <div className="cv-feature-timeline">
            {timeline.map((item, index) => (
              <article key={`${item.title}-${item.time}`} className="cv-feature-timeline__item">
                <div className="cv-feature-timeline__rail">
                  <div className="cv-feature-timeline__dot">
                    <i className={`bi ${item.icon}`} />
                  </div>
                  {index < timeline.length - 1 ? (
                    <span className="cv-feature-timeline__line" aria-hidden="true" />
                  ) : null}
                </div>
                <div className="cv-feature-timeline__content">
                  <h3>{item.title}</h3>
                  <p>{item.description}</p>
                  <span>{item.time}</span>
                </div>
              </article>
            ))}
          </div>
        </article>
      </section>

      <section className="cv-feature-panel cv-feature-panel--tips">
        <div className="cv-feature-panel__head">
          <div>
            <p className="cv-section-kicker">Catatan</p>
            <h2>Checklist Cepat</h2>
          </div>
        </div>

        <div className="cv-feature-tips">
          {tips.map((tip) => (
            <div key={tip} className="cv-feature-tip">
              <i className="bi bi-stars" />
              <p>{tip}</p>
            </div>
          ))}
        </div>
      </section>
    </div>
  )
}

export default FeaturePageShell
