// Import hook React, API post, dan komponen layout untuk halaman daftar artikel.
import { useState, useEffect } from 'react';
import { postsAPI } from '../services/api';
import Navbar from '../components/Navbar';
import Footer from '../components/Footer';
import PostCard from '../components/PostCard';

// PostList mengambil kumpulan post dari API lalu menampilkannya dalam grid kartu.
const PostList = () => {
    // State posts menyimpan daftar artikel yang berhasil diambil dari API.
    const [posts, setPosts] = useState([]);
    // State loading dan error mengatur kondisi tampilan saat request berlangsung atau gagal.
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');

    // Effect ini memuat daftar post sekali saat halaman pertama kali dibuka.
    useEffect(() => {
        fetchPosts();
    }, []);

    // fetchPosts mengambil data post dari API dan memperbarui state sesuai hasil request.
    const fetchPosts = async () => {
        try {
            setLoading(true);
            const response = await postsAPI.getAll();
            setPosts(response.data.data);
            setError('');
        } catch (err) {
            setError('Gagal memuat posts. Silakan coba lagi.');
            console.error('Error fetching posts:', err);
        } finally {
            setLoading(false);
        }
    };

    return (
        // Container utama menjaga struktur halaman dengan navbar di atas dan footer di bawah.
        <div className="min-h-screen bg-gray-100 flex flex-col">
            <Navbar />

            {/* Main memakai flex-1 agar area konten memenuhi ruang kosong di antara navbar dan footer. */}
            <main className="flex-1">
                <div className="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
                    {/* Header halaman memberi konteks singkat tentang kumpulan artikel. */}
                    <div className="mx-auto max-w-screen-sm text-center lg:mb-16 mb-8">
                        <h2 className="mb-4 text-3xl lg:text-4xl tracking-tight font-extrabold text-gray-900">
                            Our Blog
                        </h2>
                        <p className="font-light text-gray-500 sm:text-xl">
                            Explore our collection of articles about React, web development, and more.
                        </p>
                    </div>

                    {/* Blok kondisi ini memilih tampilan loading, error, kosong, atau grid post. */}
                    {loading ? (
                        <div className="flex justify-center items-center py-20">
                            <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                        </div>
                    ) : error ? (
                        // Saat request gagal, tombol Coba Lagi memanggil fetchPosts ulang.
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
                        // Empty state muncul ketika API berhasil tetapi tidak mengembalikan data post.
                        <div className="text-center py-20">
                            <p className="text-gray-500">Belum ada posts.</p>
                        </div>
                    ) : (
                        // Grid kartu merender setiap post menggunakan komponen PostCard.
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

// Export default agar halaman daftar post bisa dipasang pada route /posts.
export default PostList;
