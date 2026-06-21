import { NavLink } from 'react-router-dom'

const menuUtama = [
  { label: 'Beranda', icon: 'bi-house-door', to: '/dashboard' },
  { label: 'Timeline', icon: 'bi-card-list', to: '/timeline' },
  { label: 'Notifikasi', icon: 'bi-bell', to: '/notifikasi', badge: '3' },
  { label: 'Akun', icon: 'bi-person', to: '/akun' },
]

const menuFitur = [
  { label: 'Pusat Edukasi', icon: 'bi-journal-text', to: '/pusat-edukasi' },
  { label: 'Pelaporan Insiden Digital', icon: 'bi-bug', to: '/pelaporan-insiden' },
  { label: 'Pemantau Privasi Digital', icon: 'bi-exclamation-triangle', to: '/pemantau-privasi' },
  { label: 'Pusat Informasi & Peringatan', icon: 'bi-building-exclamation', to: '/informasi-peringatan' },
  { label: 'Pusat Pembelajaran CSIRT', icon: 'bi-arrow-repeat', to: '/pembelajaran-csirt' },
  { label: 'Sertifikat dan Penilaian', icon: 'bi-chat-square-text', to: '/sertifikat-penilaian' },
  { label: 'Forum Kesadaran Digital', icon: 'bi-chat-left-dots', to: '/forum' },
  { label: 'Asesmen Keamanan Digital', icon: 'bi-person-workspace', to: '/asesmen' },
]

const menuBawah = [
  { label: 'Pengaturan', icon: 'bi-gear', to: '/pengaturan' },
  { label: 'Pusat Bantuan', icon: 'bi-info-circle', to: '/pusat-bantuan' },
  { label: 'Keluar', icon: 'bi-box-arrow-right', to: '/keluar' },
]

function SidebarMenu({ items }) {
  return items.map((item) => (
    <NavLink
      key={item.to}
      to={item.to}
      title={item.label}
      className={({ isActive }) => `cv-sidebar-menu-item${isActive ? ' active' : ''}`}
    >
      <i className={`bi ${item.icon}`} />
      <span className="cv-menu-text">{item.label}</span>
      {item.badge ? <span className="cv-sidebar-badge">{item.badge}</span> : null}
    </NavLink>
  ))
}

function Sidebar({ collapsed = false }) {
  return (
    <aside className={`cv-sidebar ${collapsed ? 'collapsed' : ''}`}>
      <div className="cv-sidebar-welcome">
        <h4>Welcome</h4>
        <p>Maman</p>
      </div>

      <nav className="cv-sidebar-nav" aria-label="Menu utama">
        <SidebarMenu items={menuUtama} />
      </nav>

      <div className="cv-sidebar-section">
        <p className="cv-sidebar-section__label cv-sidebar-section-title">
          Fitur Aplikasi
        </p>
        <nav className="cv-sidebar-nav" aria-label="Fitur aplikasi">
          <SidebarMenu items={menuFitur} />
        </nav>
      </div>

      <div className="cv-sidebar-bottom">
        <nav className="cv-sidebar-nav cv-sidebar-nav--secondary" aria-label="Menu bawah">
          <SidebarMenu items={menuBawah} />
        </nav>
      </div>
    </aside>
  )
}

export default Sidebar
