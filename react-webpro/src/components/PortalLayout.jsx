import { NavLink } from 'react-router-dom';
import { useContext } from 'react';
import { AuthContext } from '../context/AuthContext';
import AppIcon from './AppIcon';

const menuItems = [
  { to: '/dashboard', label: 'Beranda', icon: 'home' },
  { to: '/timeline', label: 'Timeline', icon: 'timeline' },
  { to: '/notifications', label: 'Notifikasi', icon: 'bell' },
  { to: '/account', label: 'Akun', icon: 'user' },
];

const featureItems = [
  { label: 'Pusat Edukasi', icon: 'book' },
  { label: 'Pelaporan Insiden Digital', icon: 'alert' },
  { label: 'Pemantau Privasi Digital', icon: 'shield' },
  { label: 'Pusat Informasi & Peringatan', icon: 'article' },
  { label: 'Pusat Pembelajaran CSIRT', icon: 'graduation' },
  { label: 'Sertifikat dan Penilaian', icon: 'certificate' },
  { label: 'Forum Kesadaran Digital', icon: 'profile' },
  { label: 'Asesmen Keamanan Digital', icon: 'settings' },
];

function PortalLayout({ children, notificationCount = 0 }) {
  const { user, logout } = useContext(AuthContext);
  const initials = (user?.name || 'CV')
    .split(' ')
    .map((part) => part[0])
    .join('')
    .slice(0, 2)
    .toUpperCase();

  return (
    <div className="portal-shell">
      <header className="portal-topbar">
        <div className="portal-topbar__left">
          <button type="button" className="icon-button">
            <AppIcon name="menu" className="icon-svg" />
          </button>
          <div className="portal-logo">
            <div className="portal-logo__mark">CV</div>
            <span>CyberVault</span>
          </div>
        </div>

        <div className="portal-search">
          <AppIcon name="search" className="icon-svg portal-search__icon" />
          <input type="text" placeholder="Cari Sesuatu" />
          <span className="portal-search__mic">o</span>
        </div>

        <div className="portal-userbar">
          <NavLink to="/notifications" className="icon-button icon-button--notify">
            <AppIcon name="bell" className="icon-svg" />
            {notificationCount > 0 ? <span className="notify-badge">{notificationCount}</span> : null}
          </NavLink>
          <div className="portal-userchip">
            <div className="portal-avatar">{initials}</div>
            <div>
              <strong>{user?.name || 'Pengguna'}</strong>
            </div>
          </div>
        </div>
      </header>

      <div className="portal-main">
        <aside className="portal-sidebar">
          <div className="portal-sidebar__welcome">
            <h2>Welcome</h2>
            <p>{user?.name || 'Member'}</p>
          </div>

          <nav className="portal-nav">
            {menuItems.map((item) => (
              <NavLink key={item.to} to={item.to} className={({ isActive }) => `portal-nav__item${isActive ? ' is-active' : ''}`}>
                <AppIcon name={item.icon} className="icon-svg" />
                <span>{item.label}</span>
                {item.label === 'Notifikasi' && notificationCount > 0 ? <em>{notificationCount}</em> : null}
              </NavLink>
            ))}
          </nav>

          <div className="portal-sidebar__section">
            <span>FITUR APLIKASI</span>
            {featureItems.map((item) => (
              <div key={item.label} className="portal-sidebar__feature">
                <AppIcon name={item.icon} className="icon-svg" />
                <span>{item.label}</span>
              </div>
            ))}
          </div>

          <div className="portal-sidebar__section portal-sidebar__section--bottom">
            <div className="portal-sidebar__feature">
              <AppIcon name="settings" className="icon-svg" />
              <span>Pengaturan</span>
            </div>
            <div className="portal-sidebar__feature">
              <AppIcon name="article" className="icon-svg" />
              <span>Pusat Bantuan</span>
            </div>
            <button type="button" className="portal-sidebar__logout" onClick={logout}>
              <AppIcon name="logout" className="icon-svg" />
              <span>Keluar</span>
            </button>
          </div>
        </aside>

        <div className="portal-content">
          <main>{children}</main>
          <footer className="portal-footer">
            <span>@ 2026 CyberVault dilindungi Hak Cipta</span>
            <div>
              <a href="#privacy">Kebijakan Privasi</a>
              <a href="#terms">Syarat & Ketentuan</a>
              <a href="#help">Bantuan</a>
            </div>
          </footer>
        </div>
      </div>
    </div>
  );
}

export default PortalLayout;
