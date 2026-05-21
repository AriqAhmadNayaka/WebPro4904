import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

const Register = () => {
    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [confirmPassword, setConfirmPassword] = useState('');
    const [error, setError] = useState('');
    const [loading, setLoading] = useState(false);

    const { register } = useAuth();
    const navigate = useNavigate();

    const handleSubmit = async (e) => {
        e.preventDefault();
        setError('');

        if (password !== confirmPassword) {
            setError('Konfirmasi password tidak sama.');
            return;
        }

        if (password.length < 6) {
            setError('Password minimal 6 karakter.');
            return;
        }

        setLoading(true);

        try {
            const result = await register(name, email, password);

            if (result.success) {
                navigate('/posts');
            } else {
                setError(result.message);
            }
        } catch (err) {
            console.error('Register error:', err);
            setError(err.message || 'Terjadi kesalahan saat register.');
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="min-h-screen bg-gray-100 flex flex-col">
            <div className="flex-1 flex flex-col items-center justify-center px-6 py-8">
                <Link to="/posts" className="flex items-center mb-8 text-2xl font-semibold text-gray-900">
                    <svg className="w-10 h-10 mr-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM14 11a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z"></path>
                    </svg>
                    Blog Posts
                </Link>

                <div className="w-full max-w-md bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                    <h1 className="text-2xl font-bold text-gray-900 mb-6">
                        Create your account
                    </h1>

                    {error && (
                        <div className="mb-6 p-4 text-sm text-red-700 bg-red-100 rounded-lg border border-red-200" role="alert">
                            {error}
                        </div>
                    )}

                    <form className="space-y-6" onSubmit={handleSubmit}>
                        <div>
                            <label htmlFor="name" className="block mb-2 text-sm font-medium text-gray-900">
                                Name
                            </label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                value={name}
                                onChange={(e) => setName(e.target.value)}
                                className="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                placeholder="Your name"
                                required
                            />
                        </div>

                        <div>
                            <label htmlFor="email" className="block mb-2 text-sm font-medium text-gray-900">
                                Email
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
                                placeholder="Minimum 6 characters"
                                className="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                required
                            />
                        </div>

                        <div>
                            <label htmlFor="confirmPassword" className="block mb-2 text-sm font-medium text-gray-900">
                                Confirm password
                            </label>
                            <input
                                type="password"
                                name="confirmPassword"
                                id="confirmPassword"
                                value={confirmPassword}
                                onChange={(e) => setConfirmPassword(e.target.value)}
                                placeholder="Repeat password"
                                className="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                required
                            />
                        </div>

                        <button
                            type="submit"
                            disabled={loading}
                            className="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors focus:ring-4 focus:ring-blue-300 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {loading ? 'Creating account...' : 'Create account'}
                        </button>

                        <p className="text-sm text-gray-600 text-center">
                            Already have an account?{' '}
                            <Link to="/login" className="font-medium text-blue-600 hover:text-blue-500">
                                Sign in
                            </Link>
                        </p>
                    </form>
                </div>
            </div>

            <footer className="bg-white border-t border-gray-200 py-4">
                <p className="text-center text-sm text-gray-500">
                    Copyright 2024 Blog Posts. All rights reserved.
                </p>
            </footer>
        </div>
    );
};

export default Register;
