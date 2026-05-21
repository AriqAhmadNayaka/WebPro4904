import { useState, useEffect } from 'react';
import { postsAPI } from '../services/api';
import Navbar from '../components/Navbar';
import Footer from '../components/Footer';
import PostCard from '../components/PostCard';

// Halaman Daftar Postingan (PostList)
// Halaman utama setelah login yang memuat seluruh artikel dari REST API backend.
const PostList = () => {
  const [posts, setPosts] = useState([]); // State untuk menyimpan array daftar artikel
  const [loading, setLoading] = useState(true); // State untuk melacak status pemuatan data dari API
  const [error, setError] = useState(''); // State untuk menyimpan pesan error jika pemanggilan API gagal

  // Memanggil fungsi fetchPosts secara otomatis saat halaman pertama kali dimuat
  useEffect(() => {
    fetchPosts();
  }, []);

  // Fungsi asinkron untuk mengambil data daftar artikel dari backend
  const fetchPosts = async () => {
    try {
      setLoading(true); // Aktifkan indikator loading
      const response = await postsAPI.getAll(); // Memanggil endpoint postsAPI.getAll()
      setPosts(response.data); // Simpan data postingan ke state posts
      setError(''); // Hapus pesan error sebelumnya jika ada
    } catch (err) {
      setError('Gagal memuat posts. Silakan coba lagi.'); // Set pesan kegagalan untuk ditampilkan ke user
      console.error('Error fetching posts:', err);
    } finally {
      setLoading(false); // Matikan indikator loading
    }
  };

  return (
    <div className="min-h-screen bg-gray-100 flex flex-col">
      {/* Navbar Atas */}
      <Navbar />
      
      {/* Konten Utama Aplikasi */}
      <main className="flex-1">
        <div className="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
          
          {/* Header/Judul Beranda Blog */}
          <div className="mx-auto max-w-screen-sm text-center lg:mb-16 mb-8">
            <h2 className="mb-4 text-3xl lg:text-4xl tracking-tight font-extrabold text-gray-900">Our Blog</h2>
            <p className="font-light text-gray-500 sm:text-xl">
              Explore our collection of articles about React, web development, and more.
            </p>
          </div>
          
          {/* 
            Kondisional Rendering berdasarkan status pemuatan:
            1. Jika Loading: Tampilkan Animasi Spinner Putar
          */}
          {loading ? (
            <div className="flex justify-center items-center py-20">
              <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            </div>
          ) : 
          /* 
            2. Jika Terjadi Error: Tampilkan Pesan Error & Tombol Coba Lagi 
          */
          error ? (
            <div className="text-center py-20">
              <p className="text-red-500 mb-4">{error}</p>
              <button
                onClick={fetchPosts}
                className="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors"
              >
                Coba Lagi
              </button>
            </div>
          ) : 
          /* 
            3. Jika Daftar Artikel Kosong: Tampilkan Info Kosong 
          */
          posts.length === 0 ? (
            <div className="text-center py-20">
              <p className="text-gray-500">Belum ada posts.</p>
            </div>
          ) : 
          /* 
            4. Jika Data Ada: Render dalam bentuk Grid Responsif berisi komponen PostCard 
          */
          (
            <div className="grid gap-8 lg:grid-cols-2">
              {posts.map((post) => (
                <PostCard key={post.id} post={post} />
              ))}
            </div>
          )}
        </div>
      </main>
      
      {/* Footer Bawah */}
      <Footer />
    </div>
  );
};

export default PostList;
