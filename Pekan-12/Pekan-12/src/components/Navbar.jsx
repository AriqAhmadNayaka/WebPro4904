// Komponen Navbar yang menyediakan navigasi utama untuk situs web. Ini mencakup tautan ke halaman utama, tentang, dan kontak, serta menampilkan nama pengguna dan tombol logout jika pengguna sudah masuk. 
// Navbar ini dirancang dengan Tailwind CSS untuk memastikan responsivitas dan konsistensi gaya di seluruh situs. 
// Selain itu, Navbar ini juga memiliki menu hamburger untuk tampilan mobile, yang memungkinkan pengguna mengakses tautan navigasi dengan mudah di perangkat kecil. 
// Komponen ini menggunakan konteks autentikasi untuk mengelola status login pengguna dan menyediakan fungsi logout.
import { useState } from 'react';
import { Link } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import DefaultAvatar from './DefaultAvatar';

//Di dalam komponen Navbar, kita menggunakan useState untuk mengelola status menu mobile (apakah terbuka atau tertutup). 
// Kita juga menggunakan useAuth untuk mendapatkan informasi pengguna saat ini dan fungsi logout. 
// Navbar ini dirancang untuk memberikan pengalaman pengguna yang baik di berbagai perangkat dengan menggunakan Tailwind CSS untuk styling dan responsivitas.
const Navbar = () => {
    const { user, logout } = useAuth();
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

    // Fungsi handleLogout dipanggil ketika pengguna mengklik tombol logout. 
    // Fungsi ini memanggil fungsi logout dari konteks autentikasi untuk menghapus sesi pengguna dan mengembalikan mereka ke halaman login atau halaman utama, tergantung pada implementasi routing aplikasi.
    const handleLogout = async () => {
        await logout();
    };

    // Bagian return dari komponen Navbar berisi struktur JSX yang mendefinisikan tampilan dan tata letak navbar. 
    // Ini mencakup logo situs, tautan navigasi, informasi pengguna, tombol logout, dan menu hamburger untuk tampilan mobile. Navbar ini dirancang untuk tetap berada di bagian atas halaman (sticky) dan memiliki bayangan untuk memberikan efek visual yang menarik.
    return (
        <nav className="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
            <div className="max-w-screen-xl mx-auto px-4">
                <div className="flex items-center justify-between h-16">
                    <Link to="/posts" className="flex items-center text-xl font-bold text-gray-900 flex-shrink-0">
                        <svg className="w-8 h-8 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM14 11a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z"></path>
                        </svg>
                        Blog Posts
                    </Link>

                    <div className="hidden md:flex items-center space-x-8 absolute left-1/2 transform -translate-x-1/2">
                        <Link to="/posts" className="text-blue-600 font-medium hover:text-blue-500 transition-colors">Home</Link>
                        <a href="#" className="text-gray-600 font-medium hover:text-gray-900 transition-colors">About</a>
                        <a href="#" className="text-gray-600 font-medium hover:text-gray-900 transition-colors">Contact</a>
                    </div>

                    <div className="flex items-center space-x-4">
                        <div className="hidden sm:flex items-center space-x-3">
                            <DefaultAvatar className="w-8 h-8" />
                            <span className="text-sm font-medium text-gray-900">
                                {user?.name || 'User'}
                            </span>
                        </div>
                        <button
                            onClick={handleLogout}
                            className="text-sm text-white bg-red-500 hover:bg-red-600 font-medium rounded-lg px-4 py-2 transition-colors"
                        >
                            Logout
                        </button>

                        <button
                            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                            className="md:hidden p-2 text-gray-600 hover:text-gray-900"
                        >
                            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                {/* Menu mobile yang muncul ketika tombol hamburger diklik. 
                Menu ini hanya terlihat pada perangkat dengan ukuran layar kecil (mobile) dan memberikan akses ke tautan navigasi yang sama seperti pada tampilan desktop. 
                Menu ini dirancang untuk memberikan pengalaman pengguna yang baik di perangkat mobile dengan menggunakan Tailwind CSS untuk styling dan responsivitas. */}
                {mobileMenuOpen && (
                    <div className="md:hidden pb-4 border-t border-gray-200 mt-2 pt-4">
                        <div className="flex flex-col space-y-3">
                            <Link to="/posts" className="text-blue-600 font-medium">Home</Link>
                            <a href="#" className="text-gray-600 hover:text-gray-900">About</a>
                            <a href="#" className="text-gray-600 hover:text-gray-900">Contact</a>
                        </div>
                    </div>
                )}
            </div>
        </nav>
    );
};

// Ekspor komponen Navbar agar dapat digunakan di bagian lain aplikasi, seperti di App.jsx untuk ditampilkan di seluruh halaman situs web.
export default Navbar;