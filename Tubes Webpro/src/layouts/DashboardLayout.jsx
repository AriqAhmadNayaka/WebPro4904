import { useState } from 'react'
import { Outlet } from 'react-router-dom'
import Sidebar from '../components/Sidebar.jsx'
import Navbar from '../components/Navbar.jsx'
import Footer from '../components/Footer.jsx'

function DashboardLayout() {
  const [sidebarCollapsed, setSidebarCollapsed] = useState(false)

  return (
    <div
      className={`cv-dashboard-shell ${sidebarCollapsed ? 'sidebar-collapsed' : ''}`}
    >
      <Sidebar collapsed={sidebarCollapsed} />

      <div className="cv-dashboard-main">
        <Navbar
          sidebarCollapsed={sidebarCollapsed}
          onToggleSidebar={() => setSidebarCollapsed(!sidebarCollapsed)}
        />

        <main className="cv-dashboard-scroll">
          <div className="cv-dashboard-content">
            <Outlet />
          </div>
          <Footer />
        </main>
      </div>
    </div>
  )
}

export default DashboardLayout
