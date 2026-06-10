import { NavLink, Outlet } from 'react-router-dom'

const menuUtama = [
  { label: 'Beranda', icon: 'bi-house-door', to: '/' },
  { label: 'Edukasi', icon: 'bi-mortarboard', to: '/' },
  { label: 'Pelaporan', icon: 'bi-shield-exclamation', to: '/' },
  { label: 'Pusat Bantuan', icon: 'bi-life-preserver', to: '/' },
]

function CyberVaultLayout() {
  return (
    <div className="cybervault-shell">
      <aside className="cybervault-sidebar">
        <div>
          <div className="cybervault-brand">
            <div className="cybervault-brand__icon">
              <i className="bi bi-shield-lock" />
            </div>
            <div>
              <p className="cybervault-brand__eyebrow">Platform Keamanan</p>
              <h1 className="cybervault-brand__title">CyberVault</h1>
            </div>
          </div>

          <nav className="cybervault-nav">
            {menuUtama.map((menu) => (
              <NavLink
                key={`${menu.label}-${menu.to}`}
                to={menu.to}
                className={({ isActive }) =>
                  `cybervault-nav__link${isActive ? ' active' : ''}`
                }
              >
                <i className={`bi ${menu.icon}`} />
                <span>{menu.label}</span>
              </NavLink>
            ))}
          </nav>
        </div>

        <div className="cybervault-sidebar__card">
          <i className="bi bi-bell-fill" />
          <div>
            <p className="mb-1 fw-semibold">Peringatan Privasi</p>
            <p className="mb-0 small text-white-50">
              Setup awal frontend CyberVault sudah aktif.
            </p>
          </div>
        </div>
      </aside>

      <div className="cybervault-main">
        <header className="cybervault-navbar">
          <div>
            <p className="cybervault-navbar__eyebrow">Dashboard Awal</p>
            <h2 className="cybervault-navbar__title">Pusat Kesadaran Digital</h2>
          </div>

          <div className="cybervault-user">
            <div className="cybervault-user__avatar">CV</div>
            <div>
              <p className="mb-0 fw-semibold">Pengguna CyberVault</p>
              <small className="text-secondary">Frontend React + Vite</small>
            </div>
          </div>
        </header>

        <main className="cybervault-content">
          <Outlet />
        </main>

        <footer className="cybervault-footer">
          <span>CyberVault</span>
          <span>Setup frontend dasar untuk project Tubes Webpro.</span>
        </footer>
      </div>
    </div>
  )
}

export default CyberVaultLayout
