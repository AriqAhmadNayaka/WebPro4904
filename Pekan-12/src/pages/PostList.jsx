//import yang berfungsi untuk mengelola state dan efek samping dalam komponen React
import { useState, useEffect } from 'react';
import { postsAPI } from '../services/api';
import Navbar from '../components/Navbar';
import Footer from '../components/Footer';
import PostCard from '../components/PostCard';

// Komponen PostList yang menampilkan daftar semua post yang diambil dari API
const PostList = () => {
    const [posts, setPosts] = useState([]); // State untuk menyimpan daftar post yang diambil dari API
    const [loading, setLoading] = useState(true); // State untuk menandakan apakah data masih dalam proses pengambilan
    const [error, setError] = useState(''); // State untuk menyimpan pesan error jika terjadi kesalahan saat pengambilan data

    // Menggunakan useEffect untuk memanggil fungsi fetchPosts saat komponen pertama kali dirender
    useEffect(() => {
        fetchPosts();
    }, []);

    // Fungsi untuk mengambil data post dari API
    const fetchPosts = async () => {
        // Set loading menjadi true sebelum memulai proses pengambilan data
        try {
            setLoading(true);
            const response = await postsAPI.getAll(); // Memanggil fungsi getAll dari postsAPI untuk mengambil semua post
            setPosts(response.data.data); // Menyimpan data post yang diambil ke dalam state posts
            setError('');
        // Menangani error yang mungkin terjadi selama proses pengambilan data
        } catch (err) {
            setError('Gagal memuat posts. Silakan coba lagi.');
            console.error('Error fetching posts:', err);
        // Set loading menjadi false setelah proses pengambilan data selesai, baik berhasil maupun gagal
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="min-h-screen bg-gray-100 flex flex-col">
            <Navbar />

            <main className="flex-1">
                <div className="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
                    <div className="mx-auto max-w-screen-sm text-center lg:mb-16 mb-8">
                        <h2 className="mb-4 text-3xl lg:text-4xl tracking-tight font-extrabold text-gray-900">
                            Our Blog
                        </h2>
                        <p className="font-light text-gray-500 sm:text-xl">
                            Explore our collection of articles about React, web development, and more.
                        </p>
                    </div>

                    {loading ? (
                        <div className="flex justify-center items-center py-20">
                            <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                        </div>
                    ) : error ? (
                        <div className="text-center py-20">
                            <p className="text-red-500 mb-4">{error}</p>
                            <button
                                onClick={fetchPosts}
                                className="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5"
                            >
                                Coba Lagi
                            </button>
                        </div>
                    ) : posts.length === 0 ? (
                        <div className="text-center py-20">
                            <p className="text-gray-500">Belum ada posts.</p>
                        </div>
                    ) : (
                        <div className="grid gap-8 lg:grid-cols-2">
                            {posts.map((post) => (
                                <PostCard key={post.id} post={post} />
                            ))}
                        </div>
                    )}
                </div>
            </main>

            <Footer />
        </div>
    );
};

export default PostList;
