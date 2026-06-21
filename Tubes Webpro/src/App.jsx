import { BrowserRouter, Navigate, Outlet, Route, Routes } from 'react-router-dom'
import AuthLayout from './layouts/AuthLayout.jsx'
import DashboardLayout from './layouts/DashboardLayout.jsx'
import Akun from './pages/Akun.jsx'
import Asesmen from './pages/Asesmen.jsx'
import Dashboard from './pages/Dashboard.jsx'
import Forum from './pages/Forum.jsx'
import InformasiPeringatan from './pages/InformasiPeringatan.jsx'
import Login from './pages/Login.jsx'
import Notifikasi from './pages/Notifikasi.jsx'
import Logout from './pages/Logout.jsx'
import PembelajaranCSIRT from './pages/PembelajaranCSIRT.jsx'
import PelaporanInsiden from './pages/PelaporanInsiden.jsx'
import PemantauPrivasi from './pages/PemantauPrivasi.jsx'
import Pengaturan from './pages/Pengaturan.jsx'
import PusatEdukasi from './pages/PusatEdukasi'
import PusatBantuan from './pages/PusatBantuan.jsx'
import Register from './pages/Register.jsx'
import ResetPassword from './pages/ResetPassword.jsx'
import SertifikatPenilaian from './pages/SertifikatPenilaian.jsx'
import Timeline from './pages/Timeline.jsx'
import TidakDitemukanPage from './pages/TidakDitemukanPage.jsx'

const dashboardRoutes = [
  {
    path: '/dashboard',
    element: <Dashboard />,
  },
  {
    path: '/timeline',
    element: <Timeline />,
  },
  {
    path: '/pelaporan-insiden',
    element: <PelaporanInsiden />,
  },
  {
    path: '/pemantau-privasi',
    element: <PemantauPrivasi />,
  },
  {
    path: '/akun',
    element: <Akun />,
  },
  {
    path: '/notifikasi',
    element: <Notifikasi />,
  },
  {
    path: '/informasi-peringatan',
    element: <InformasiPeringatan />,
  },
  {
    path: '/pembelajaran-csirt',
    element: <PembelajaranCSIRT />,
  },
  {
    path: '/sertifikat-penilaian',
    element: <SertifikatPenilaian />,
  },
  {
    path: '/forum',
    element: <Forum />,
  },
  {
    path: '/asesmen',
    element: <Asesmen />,
  },
  {
    path: '/pengaturan',
    element: <Pengaturan />,
  },
  {
    path: '/pusat-bantuan',
    element: <PusatBantuan />,
  },
  {
    path: '/pelaporan-insiden-digital',
    element: <Navigate to="/pelaporan-insiden" replace />,
  },
  {
    path: '/pemantau-privasi-digital',
    element: <Navigate to="/pemantau-privasi" replace />,
  },
  {
    path: '/pusat-informasi-dan-peringatan',
    element: <Navigate to="/informasi-peringatan" replace />,
  },
  {
    path: '/pusat-pembelajaran-csirt',
    element: <Navigate to="/pembelajaran-csirt" replace />,
  },
  {
    path: '/sertifikat-dan-penilaian',
    element: <Navigate to="/sertifikat-penilaian" replace />,
  },
  {
    path: '/forum-kesadaran-digital',
    element: <Navigate to="/forum" replace />,
  },
  {
    path: '/asesmen-keamanan-digital',
    element: <Navigate to="/asesmen" replace />,
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
            <Route path="/pusat-edukasi" element={<PusatEdukasi />} />
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
