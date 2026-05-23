//import ini digunakan untuk membawa fungsi dan komponen yang diperlukan dari pustaka React, react-router-dom, dan layanan API yang telah dibuat sebelumnya.
import { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { postsAPI } from '../services/api';
import Navbar from '../components/Navbar';
import Footer from '../components/Footer';
import DefaultAvatar from '../components/DefaultAvatar';

// Komponen PostDetail adalah halaman yang menampilkan detail dari sebuah postingan artikel. 
// Komponen ini menggunakan useParams untuk mendapatkan ID artikel dari URL, dan useState serta useEffect untuk mengelola state dan efek samping dalam mengambil data artikel dari API.
// Ketika komponen dimuat, useEffect memanggil fungsi fetchPost untuk mengambil data artikel berdasarkan ID yang diperoleh dari URL. 
// Jika data berhasil diambil, state post akan diperbarui dengan data tersebut. Jika terjadi error, state error akan diperbarui dengan pesan error yang sesuai. 
// Komponen ini juga menyediakan fungsi formatDate untuk mengubah tanggal pembuatan artikel menjadi format yang lebih mudah dibaca. 
// Bagian return dari komponen ini berisi struktur JSX yang mendefinisikan tampilan halaman detail artikel, termasuk judul, isi, penulis, tanggal pembuatan, dan tautan untuk kembali ke daftar posts.
const PostDetail = () => {
    const { id } = useParams();
    const [post, setPost] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');

    // useEffect digunakan untuk memanggil fungsi fetchPost ketika komponen pertama kali dimuat atau ketika ID artikel berubah.
    useEffect(() => {
        fetchPost();
    }, [id]);

    // Fungsi fetchPost digunakan untuk mengambil data artikel dari API berdasarkan ID yang diperoleh dari URL. 
    // Fungsi ini mengatur status loading menjadi true sebelum memulai proses pengambilan data. 
    // Jika data berhasil diambil, state post akan diperbarui dengan data tersebut dan pesan error akan dikosongkan. 
    // Jika terjadi error, kita memeriksa apakah error tersebut adalah 404 (artikel tidak ditemukan) atau error lainnya, dan memperbarui state error dengan pesan yang sesuai. 
    // Setelah proses selesai, status loading akan diatur menjadi false.
    const fetchPost = async () => {
        try {
            setLoading(true);
            const response = await postsAPI.getById(id);
            setPost(response.data.data);
            setError('');
        } catch (err) {
            if (err.response?.status === 404) {
                setError('Post tidak ditemukan.');
            } else {
                setError('Gagal memuat post. Silakan coba lagi.');
            }
            console.error('Error fetching post:', err);
        } finally {
            setLoading(false);
        }
    };

    // Fungsi formatDate digunakan untuk mengubah tanggal pembuatan artikel menjadi format yang lebih mudah dibaca, seperti "14 June 2024".
    const formatDate = (dateString) => {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    };

    // Jika status loading true, kita menampilkan indikator loading kepada pengguna. Jika ada pesan error, kita menampilkan pesan error tersebut. 
    // Jika data artikel berhasil diambil, kita menampilkan detail artikel tersebut, termasuk judul, isi, penulis, tanggal pembuatan, dan tautan untuk kembali ke daftar posts. 
    // Halaman ini dirancang dengan menggunakan Tailwind CSS untuk memberikan tampilan yang menarik dan responsif.
    if (loading) {
        return (
            <div className="min-h-screen bg-gray-100 flex items-center justify-center">
                <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            </div>
        );
    }

    // Jika ada pesan error, kita menampilkan pesan error tersebut. Jika data artikel berhasil diambil, kita menampilkan detail artikel tersebut, termasuk judul, isi, penulis, tanggal pembuatan, dan tautan untuk kembali ke daftar posts. 
    // Halaman ini dirancang dengan menggunakan Tailwind CSS untuk memberikan tampilan yang menarik dan responsif. --- IGNORE --- 
    if (error) {
        return (
            <div className="min-h-screen bg-gray-100 flex flex-col">
                <Navbar />
                <div className="flex-1 flex items-center justify-center py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
                    <div className="text-center py-20">
                        <p className="text-red-500 mb-4">{error}</p>
                        <Link
                            to="/posts"
                            className="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5"
                        >
                            ← Kembali ke Daftar Posts
                        </Link>
                    </div>
                </div>
                <Footer />
            </div>
        );
    }

    // Jika data artikel berhasil diambil, kita menampilkan detail artikel tersebut, termasuk judul, isi, penulis, tanggal pembuatan, dan tautan untuk kembali ke daftar posts. 
    // Halaman ini dirancang dengan menggunakan Tailwind CSS untuk memberikan tampilan yang menarik dan responsif.
    return (
        <div className="min-h-screen bg-gray-100 flex flex-col">
            <Navbar />

            <main className="flex-1">
                <div className="py-8 px-4 mx-auto max-w-screen-lg lg:py-16 lg:px-6">
                    <div className="mb-6">
                        <Link
                            to="/posts"
                            className="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-500 transition-colors"
                        >
                            <svg className="mr-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fillRule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clipRule="evenodd"></path>
                            </svg>
                            Back to Posts
                        </Link>
                    </div>

                    {/* Jika data artikel berhasil diambil, kita menampilkan detail artikel tersebut, termasuk judul, isi, penulis, tanggal pembuatan, dan tautan untuk kembali ke daftar posts. 
                    Halaman ini dirancang dengan menggunakan Tailwind CSS untuk memberikan tampilan yang menarik dan responsif.*/}
                    <article className="bg-white rounded-lg border border-gray-200 shadow-md overflow-hidden">
                        {post.image_url && (
                            <img
                                src={post.image_url}
                                alt={post.title}
                                className="w-full h-64 lg:h-96 object-cover"
                                onError={(e) => {
                                    e.target.style.display = 'none';
                                }}
                            />
                        )}

                        <div className="p-6 lg:p-10">
                            <div className="flex flex-wrap items-center gap-4 mb-6">
                                <span className="bg-blue-100 text-blue-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded">
                                    <svg className="mr-1 w-3 h-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fillRule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clipRule="evenodd"></path>
                                        <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"></path>
                                    </svg>
                                    Article
                                </span>
                                <div className="flex items-center space-x-2">
                                    <DefaultAvatar />
                                    <span className="font-medium text-sm text-gray-700">{post.author}</span>
                                </div>
                                {post.created_at && (
                                    <span className="text-sm text-gray-500 flex items-center">
                                        <svg className="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fillRule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clipRule="evenodd"></path>
                                        </svg>
                                        {formatDate(post.created_at)}
                                    </span>
                                )}
                            </div>

                            <h1 className="mb-6 text-3xl lg:text-4xl font-bold tracking-tight text-gray-900">
                                {post.title}
                            </h1>

                            <div className="text-gray-600 leading-relaxed text-lg">
                                {post.article.split('\n').map((paragraph, index) => (
                                    <p key={index} className="mb-4">{paragraph}</p>
                                ))}
                            </div>
                        </div>
                    </article>
                </div>
            </main>

            <Footer />
        </div>
    );
};

// export default digunakan untuk mengekspor komponen PostDetail agar dapat digunakan di bagian lain aplikasi, seperti di App.jsx untuk ditampilkan ketika pengguna mengakses URL yang sesuai dengan detail artikel.
export default PostDetail;