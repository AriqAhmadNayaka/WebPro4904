import logo from '../assets/logo.png'

function Navbar({ onToggleSidebar }) {
  return (
    <header className="cv-navbar">
      <div className="cv-navbar-left">
        <button className="cv-menu-button" onClick={onToggleSidebar}>
          <i className="bi bi-list" />
        </button>

        <img src={logo} alt="CyberVault Logo" className="cv-navbar-logo-img" />

        <span className="cv-navbar-brand">CyberVault</span>
      </div>

      <div className="cv-navbar-search">
        <i className="bi bi-search" />
        <input type="text" placeholder="Cari Sesuatu" />
        <i className="bi bi-mic" />
      </div>

      <div className="cv-navbar-right">
        <button type="button" className="cv-navbar-icon-btn" aria-label="Notifikasi">
          <i className="bi bi-bell" />
        </button>

        <div className="cv-navbar-profile">
          <div className="cv-navbar-profile__avatar">MV</div>
          <span className="cv-navbar-profile__name">Maman Vyndy</span>
          <i className="bi bi-chevron-down cv-navbar-profile__chevron" />
        </div>
      </div>
    </header>
  )
}

export default Navbar
