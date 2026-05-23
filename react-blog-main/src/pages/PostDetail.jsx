import { useState, useEffect } from 'react'; //untuk mengelola state lokal dalam komponen PostDetail,
//  seperti menyimpan data post yang diambil dari API, status loading, dan pesan error. State ini akan diperbarui
//  sesuai dengan hasil dari proses pengambilan data dan digunakan untuk menentukan apa yang ditampilkan kepada pengguna.
import { useParams, Link } from 'react-router-dom'; //untuk mengakses parameter id dari URL, yang digunakan
//  untuk mengambil data post yang sesuai dari API. Selain itu, Link digunakan untuk membuat tautan yang memungkinkan
//  pengguna kembali ke halaman daftar postingan setelah melihat detail sebuah post.
import { postsAPI } from '../services/api'; //untuk mengimpor fungsi-fungsi yang berhubungan dengan pengambilan data 
// post dari API, seperti getById, yang akan digunakan untuk mengambil data post berdasarkan id yang diperoleh dari 
// parameter URL.
import Navbar from '../components/Navbar'; //untuk mengimpor komponen Navbar yang akan digunakan untuk menampilkan 
// navigasi di bagian atas halaman detail post. Navbar ini biasanya berisi tautan ke halaman lain dalam aplikasi,
//  seperti daftar postingan, halaman login, atau halaman profil pengguna.
import Footer from '../components/Footer'; //untuk mengimpor komponen Footer yang akan digunakan untuk menampilkan
//  bagian bawah halaman detail post
import DefaultAvatar from '../components/DefaultAvatar'; //untuk mengimpor komponen DefaultAvatar yang akan digunakan
//  untuk menampilkan avatar default di halaman detail post, terutama jika data post tidak menyertakan informasi tentang
//  avatar penulis. Kom

const PostDetail = () => { //Komponen PostDetail yang bertanggung jawab untuk menampilkan detail sebuah post berdasarkan 
// id yang diperoleh dari parameter URL. Komponen ini akan mengambil data post dari API, mengelola status loading
    const { id } = useParams(); //untuk mengakses parameter id dari URL, yang digunakan untuk mengambil data post
    //  yang sesuai dari API. Nilai id ini akan digunakan dalam fungsi fetchPost untuk memanggil API dan mendapatkan
    //  data post yang akan ditampilkan di halaman detail.
    const [post, setPost] = useState(null); //untuk menyimpan data post yang diambil dari API. State ini akan
    //  diperbarui dengan data post yang berhasil diambil, dan nilai post akan digunakan untuk menampilkan informasi detail
    //  tentang post tersebut di halaman.
    const [loading, setLoading] = useState(true); //untuk menyimpan status loading selama proses pengambilan data post
    //  berlangsung. State ini akan diatur ke true saat proses pengambilan data dimulai dan kembali ke false setelah
    //  proses selesai, baik berhasil maupun gagal. Status loading ini dapat digunakan untuk menampilkan indikator loading 
    // kepada pengguna, seperti spinner atau pesan "Loading...", selama data post sedang diambil dari API.
    const [error, setError] = useState(''); //untuk menyimpan pesan error yang mungkin terjadi selama proses
    //  pengambilan data post. Jika terjadi kesalahan saat mencoba mengambil data dari API, pesan error akan diperbarui
    //  dengan informasi yang relevan, dan pesan ini akan ditampilkan kepada pengguna untuk memberi tahu mereka tentang 
    // masalah yang terjadi saat mencoba memuat detail post.

    useEffect(() => {
        fetchPost();
    }, [id]);

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

    const formatDate = (dateString) => {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    };

    if (loading) {
        return (
            <div className="min-h-screen bg-gray-100 flex items-center justify-center">
                <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            </div>
        );
    }

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
                                <path fillRule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 
                                1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clipRule="evenodd"></path>
                            </svg>
                            Back to Posts
                        </Link>
                    </div>

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
                                        <path fillRule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 
                                        1h6v4H5V6zm6 6H5v2h6v-2z" clipRule="evenodd"></path>
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
                                            <path fillRule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 
                                            2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clipRule="evenodd"></path>
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

export default PostDetail; //untuk mengekspor komponen PostDetail agar dapat digunakan di bagian lain dari aplikasi,
//  terutama untuk digunakan sebagai rute detail post dalam aplikasi React. Komponen ini bertanggung jawab untuk 
// menampilkan informasi detail tentang sebuah post, termasuk judul, artikel, penulis, tanggal pembuatan, dan gambar (jika tersedia).
