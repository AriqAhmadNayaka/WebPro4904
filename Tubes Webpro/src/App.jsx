import { BrowserRouter, Navigate, Outlet, Route, Routes } from 'react-router-dom'
import AuthLayout from './layouts/AuthLayout.jsx'
import DashboardLayout from './layouts/DashboardLayout.jsx'
import BerandaPage from './pages/BerandaPage.jsx'
import Login from './pages/Login.jsx'
import Logout from './pages/Logout.jsx'
import PlaceholderPage from './pages/PlaceholderPage.jsx'
import Register from './pages/Register.jsx'
import ResetPassword from './pages/ResetPassword.jsx'
import Timeline from './pages/Timeline.jsx'
import TidakDitemukanPage from './pages/TidakDitemukanPage.jsx'

const dashboardRoutes = [
  {
    path: '/dashboard',
    element: <BerandaPage />,
  },
  {
    path: '/timeline',
    element: <Timeline />,
  },
  {
    path: '/pelaporan-insiden',
    element: (
      <PlaceholderPage
        title="Pelaporan Insiden"
        description="Halaman ini disiapkan untuk alur pelaporan insiden digital secara bertahap."
      />
    ),
  },
  {
    path: '/notifikasi',
    element: <PlaceholderPage title="Notifikasi" description="Pusat notifikasi CyberVault." />,
  },
  {
    path: '/akun',
    element: <PlaceholderPage title="Akun" description="Kelola informasi akun pengguna CyberVault." />,
  },
  {
    path: '/pusat-edukasi',
    element: <PlaceholderPage title="Pusat Edukasi" description="Konten edukasi keamanan digital akan ditampilkan di sini." />,
  },
  {
    path: '/pelaporan-insiden-digital',
    element: <Navigate to="/pelaporan-insiden" replace />,
  },
  {
    path: '/pemantau-privasi-digital',
    element: <PlaceholderPage title="Pemantau Privasi Digital" description="Area pemantauan privasi digital pengguna." />,
  },
  {
    path: '/pusat-informasi-dan-peringatan',
    element: <PlaceholderPage title="Pusat Informasi & Peringatan" description="Informasi dan peringatan keamanan digital akan ditampilkan di sini." />,
  },
  {
    path: '/pusat-pembelajaran-csirt',
    element: <PlaceholderPage title="Pusat Pembelajaran CSIRT" description="Materi pembelajaran CSIRT akan dikembangkan di halaman ini." />,
  },
  {
    path: '/sertifikat-dan-penilaian',
    element: <PlaceholderPage title="Sertifikat dan Penilaian" description="Ringkasan sertifikat dan hasil penilaian pengguna." />,
  },
  {
    path: '/forum-kesadaran-digital',
    element: <PlaceholderPage title="Forum Kesadaran Digital" description="Forum diskusi kesadaran digital akan hadir di halaman ini." />,
  },
  {
    path: '/asesmen-keamanan-digital',
    element: <PlaceholderPage title="Asesmen Keamanan Digital" description="Area asesmen keamanan digital pengguna." />,
  },
  {
    path: '/pengaturan',
    element: <PlaceholderPage title="Pengaturan" description="Pengaturan aplikasi CyberVault." />,
  },
  {
    path: '/pusat-bantuan',
    element: <PlaceholderPage title="Pusat Bantuan" description="Pusat bantuan pengguna CyberVault." />,
  },
  {
    path: '/keluar',
    element: <Logout />,
  },
]

function isAuthenticated() {
  return sessionStorage.getItem('cv-authenticated') === 'true'
}

function ProtectedRoutes() {
  return isAuthenticated() ? <Outlet /> : <Navigate to="/login" replace />
}

function GuestRoutes() {
  return isAuthenticated() ? <Navigate to="/dashboard" replace /> : <Outlet />
}

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<Navigate to="/login" replace />} />

        <Route element={<GuestRoutes />}>
          <Route element={<AuthLayout />}>
            <Route path="/login" element={<Login />} />
            <Route path="/register" element={<Register />} />
            <Route path="/reset-password" element={<ResetPassword />} />
          </Route>
        </Route>

        <Route element={<ProtectedRoutes />}>
          <Route element={<DashboardLayout />}>
            {dashboardRoutes.map((route) => (
              <Route key={route.path} path={route.path} element={route.element} />
            ))}
          </Route>
        </Route>

        <Route path="*" element={<TidakDitemukanPage />} />
      </Routes>
    </BrowserRouter>
  )
}

export default App
