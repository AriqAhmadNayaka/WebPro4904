// Import hook React, navigasi route, dan helper autentikasi dari context.
import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

// Halaman Login menangani input credential dan proses masuk ke aplikasi.
const Login = () => {
    // State form menyimpan input email dan password dari user.
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    // State error dan loading mengatur feedback visual saat login diproses.
    const [error, setError] = useState('');
    const [loading, setLoading] = useState(false);

    // Ambil fungsi login dari AuthContext dan navigate dari React Router.
    const { login } = useAuth();
    const navigate = useNavigate();

    // Handler submit mencegah reload halaman lalu mencoba login melalui AuthContext.
    const handleSubmit = async (e) => {
        e.preventDefault();
        setError('');
        setLoading(true);

        try {
            const result = await login(email, password);

            // Jika login berhasil, user diarahkan ke halaman daftar post.
            if (result.success) {
                navigate('/posts');
            } else {
                // Jika API mengembalikan gagal, tampilkan pesan error dari hasil login.
                setError(result.message);
            }
        } catch (err) {
            // Error tidak terduga dicatat di console dan ditampilkan sebagai pesan ramah user.
            console.error('Login error:', err);
            setError(err.message || 'Terjadi kesalahan saat login. Pastikan server Laravel sudah berjalan.');
        } finally {
            // Loading dimatikan kembali setelah request selesai dalam kondisi apa pun.
            setLoading(false);
        }
    };

    return (
        // Container halaman membuat form login berada di tengah layar dan footer di bawah.
        <div className="min-h-screen bg-gray-100 flex flex-col">
            <div className="flex-1 flex flex-col items-center justify-center px-6 py-8">
                {/* Brand aplikasi ditampilkan di atas form sebagai identitas halaman login. */}
                <a href="#" className="flex items-center mb-8 text-2xl font-semibold text-gray-900">
                    <svg className="w-10 h-10 mr-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM14 11a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z"></path>
                    </svg>
                    Blog Posts
                </a>

                {/* Kartu form membungkus field credential, pilihan tambahan, dan tombol submit. */}
                <div className="w-full max-w-md bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                    <h1 className="text-2xl font-bold text-gray-900 mb-6">
                        Sign in to your account
                    </h1>

                    {/* Alert error hanya muncul ketika proses login menghasilkan pesan kesalahan. */}
                    {error && (
                        <div className="mb-6 p-4 text-sm text-red-700 bg-red-100 rounded-lg border border-red-200" role="alert">
                            {error}
                        </div>
                    )}

                    {/* Form login mengikat input ke state dan memanggil handleSubmit saat dikirim. */}
                    <form className="space-y-6" onSubmit={handleSubmit}>
                        {/* Field email memakai validasi bawaan browser dengan type email dan required. */}
                        <div>
                            <label htmlFor="email" className="block mb-2 text-sm font-medium text-gray-900">
                                Your email
                            </label>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                value={email}
                                onChange={(e) => setEmail(e.target.value)}
                                className="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                placeholder="name@company.com"
                                required
                            />
                        </div>

                        {/* Field password menyimpan nilai password dan menyembunyikan input user. */}
                        <div>
                            <label htmlFor="password" className="block mb-2 text-sm font-medium text-gray-900">
                                Password
                            </label>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                value={password}
                                onChange={(e) => setPassword(e.target.value)}
                                placeholder="••••••••"
                                className="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                required
                            />
                        </div>

                        {/* Bar opsi tambahan berisi remember me dan tautan lupa password. */}
                        <div className="flex items-center justify-between">
                            <div className="flex items-center">
                                <input
                                    id="remember"
                                    type="checkbox"
                                    className="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-2 focus:ring-blue-500"
                                />
                                <label htmlFor="remember" className="ml-2 text-sm text-gray-600">
                                    Remember me
                                </label>
                            </div>
                            <a href="#" className="text-sm font-medium text-blue-600 hover:text-blue-500">
                                Forgot password?
                            </a>
                        </div>

                        {/* Tombol submit dinonaktifkan saat loading agar request login tidak dobel. */}
                        <button
                            type="submit"
                            disabled={loading}
                            className="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors focus:ring-4 focus:ring-blue-300 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {loading ? 'Signing in...' : 'Sign in'}
                        </button>

                        {/* Tautan sign up disediakan sebagai placeholder menuju fitur registrasi. */}
                        <p className="text-sm text-gray-600 text-center">
                            Don't have an account yet?{' '}
                            <a href="#" className="font-medium text-blue-600 hover:text-blue-500">
                                Sign up
                            </a>
                        </p>
                    </form>
                </div>
            </div>

            {/* Footer sederhana menjaga informasi copyright tetap terlihat di halaman login. */}
            <footer className="bg-white border-t border-gray-200 py-4">
                <p className="text-center text-sm text-gray-500">
                    © 2024 Blog Posts. All rights reserved.
                </p>
            </footer>
        </div>
    );
};

// Export default agar halaman ini bisa dipasang pada route /login.
export default Login;
