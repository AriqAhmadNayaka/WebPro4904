import { useEffect, useState, useContext } from 'react';
import { Link } from 'react-router-dom';
import { postService } from '../services/api';
import { AuthContext } from '../context/AuthContext';

const Dashboard = () => {
  const { user } = useContext(AuthContext);
  const [posts, setPosts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    const loadPosts = async () => {
      try {
        setLoading(true);
        const response = await postService.getPosts();
        setPosts(response.data || []);
      } catch (err) {
        console.error('Error loading posts:', err);
        setError('Gagal memuat data post.');
      } finally {
        setLoading(false);
      }
    };

    loadPosts();
  }, []);

  return (
    <div className="min-h-screen bg-gray-900 text-white py-12">
      <div className="container mx-auto px-4">
        <section className="hero__inner rounded-3xl bg-slate-950/80 border border-blue-400/20 p-8 mb-10 shadow-2xl">
          <div className="grid gap-8 lg:grid-cols-[1.6fr_1fr] items-center">
            <div>
              <p className="eyebrow">Dashboard</p>
              <h1 className="text-5xl font-bold leading-tight text-white mb-4">
                Selamat datang, {user?.name || 'Pengguna'}
              </h1>
              <p className="hero__text text-gray-300 mb-8">
                Akses semua post dari backend Pekan-09, lihat detail postingan, dan gunakan dashboard untuk informasi ringkas.
              </p>
              <div className="flex flex-wrap gap-4">
                <Link to="/posts" className="primary-button">Lihat Semua Post</Link>
                <Link to="/posts/new" className="ghost-button">Buat Post Baru</Link>
                {user?.role === 'admin' && (
                  <Link to="/users" className="ghost-button">Kelola User</Link>
                )}
              </div>
            </div>
            <div className="hero__card">
              <div className="panel__head">
                <div>
                  <h2>Ringkasan</h2>
                  <p className="text-gray-300">Total post dan informasi terbaru.</p>
                </div>
              </div>
              <div className="grid gap-4">
                <div className="panel p-4 bg-slate-900 border border-blue-400/10 rounded-3xl">
                  <h3 className="text-gray-400 text-sm">Total Post</h3>
                  <p className="text-4xl font-semibold text-white">{loading ? '...' : posts.length}</p>
                </div>
                <div className="panel p-4 bg-slate-900 border border-blue-400/10 rounded-3xl">
                  <h3 className="text-gray-400 text-sm">Post Terbaru</h3>
                  <p className="text-lg text-white">{posts[0]?.title || 'Belum ada post tersedia'}</p>
                </div>
              </div>
            </div>
          </div>
        </section>

        <section className="grid gap-6 lg:grid-cols-3">
          <div className="panel p-6 rounded-3xl border border-blue-400/10 bg-slate-900">
            <h2 className="text-xl font-bold mb-3">Tentang</h2>
            <p className="text-gray-300 leading-relaxed">
              Dashboard ini menampilkan data dari API Pekan-09 dan memberi akses ke halaman post serta detail blog.
            </p>
          </div>
          <div className="panel p-6 rounded-3xl border border-blue-400/10 bg-slate-900">
            <h2 className="text-xl font-bold mb-3">Pengguna</h2>
            <p className="text-gray-300">Nama: {user?.name}</p>
            <p className="text-gray-300">Email: {user?.email}</p>
            <p className="text-gray-300">Role: {user?.role || 'user'}</p>
          </div>
          <div className="panel p-6 rounded-3xl border border-blue-400/10 bg-slate-900">
            <h2 className="text-xl font-bold mb-3">Statistik</h2>
            {error ? (
              <p className="text-red-400">{error}</p>
            ) : (
              <p className="text-gray-300">Data post dimuat dari API backend.</p>
            )}
          </div>
        </section>
      </div>
    </div>
  );
};

export default Dashboard;
