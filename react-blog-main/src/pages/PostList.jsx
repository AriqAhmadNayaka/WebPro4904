import { useState, useEffect } from 'react'; //untuk mengelola state dan efek samping dalam komponen PostList,
//  seperti menyimpan daftar posts, status loading, dan pesan error, serta untuk melakukan fetch data posts saat 
// komponen pertama kali dimuat.
import { postsAPI } from '../services/api'; //untuk mengimpor fungsi-fungsi yang berhubungan dengan 
// pengambilan data post dari API, seperti getAll, yang akan digunakan untuk mengambil daftar semua posts dari 
// server Laravel.
import Navbar from '../components/Navbar'; //untuk mengimpor komponen Navbar yang akan digunakan untuk 
// menampilkan navigasi di bagian atas halaman daftar postingan. Navbar ini biasanya berisi tautan ke 
// halaman lain dalam aplikasi, seperti halaman login, halaman profil pengguna, atau halaman detail post
import Footer from '../components/Footer'; //untuk mengimpor komponen Footer yang akan digunakan untuk
//  menampilkan bagian bawah halaman daftar postingan, biasanya berisi informasi hak cipta, tautan ke
//  kebijakan privasi, atau informasi kontak.
import PostCard from '../components/PostCard'; //untuk mengimpor komponen PostCard yang akan digunakan 
// untuk menampilkan setiap post dalam daftar postingan. Komponen PostCard biasanya berisi informasi singkat 
// tentang post, seperti judul, cuplikan artikel, nama penulis, dan tanggal pembuatan, serta tautan ke halaman
//  detail post

const PostList = () => { //Komponen PostList yang bertanggung jawab untuk menampilkan daftar semua posts
//  yang diambil dari API. Komponen ini akan mengelola status loading dan error selama proses pengambilan
//  data, serta menampilkan daftar postingan menggunakan komponen PostCard untuk setiap post yang berhasil diambil.
    const [posts, setPosts] = useState([]); //untuk menyimpan daftar posts yang diambil dari API. State ini
    //  akan diperbarui dengan data posts yang berhasil diambil, dan nilai posts akan digunakan untuk
    //  menampilkan daftar postingan di halaman menggunakan komponen PostCard untuk setiap post.
    const [loading, setLoading] = useState(true); //untuk menyimpan status loading selama proses
    //  pengambilan data posts berlangsung. State ini akan diatur ke true saat proses pengambilan
    //  data dimulai dan kembali ke false setelah proses selesai, baik berhasil maupun gagal.
    //  Status loading ini dapat digunakan untuk menampilkan indikator loading kepada pengguna,
    //  seperti spinner atau pesan "Loading...", selama data posts sedang diambil dari API.
    const [error, setError] = useState(''); //untuk menyimpan pesan error yang mungkin
    //  terjadi selama proses pengambilan data posts. Jika terjadi kesalahan saat mencoba
    //  mengambil data dari API, pesan error akan diperbarui dengan informasi yang relevan,
    //  dan pesan ini akan ditampilkan kepada pengguna untuk memberi tahu mereka tentang
    //  masalah yang terjadi saat mencoba memuat daftar postingan.

    useEffect(() => { //untuk melakukan fetch data posts saat komponen pertama kali dimuat.
    //  Fungsi fetchPosts akan dipanggil dalam useEffect dengan array dependensi kosong,
    //  sehingga hanya akan dijalankan sekali saat komponen pertama kali dirender.
    //  Fungsi fetchPosts akan mencoba mengambil data posts dari API, mengelola status loading,
    //  dan menangani hasilnya untuk memperbarui state posts atau error sesuai dengan hasil pengambilan data.
        fetchPosts(); //untuk melakukan fetch data posts saat komponen pertama kali dimuat.
        //  Fungsi fetchPosts akan dipanggil dalam useEffect dengan array dependensi kosong,
        //  sehingga hanya akan dijalankan sekali saat komponen pertama kali dirender.
        //  Fungsi fetchPosts akan mencoba mengambil data posts dari API, mengelola status loading,
        //  dan menangani hasilnya untuk memperbarui state posts atau error sesuai dengan hasil pengambilan data.
    }, []);

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

export default PostList; //untuk mengekspor komponen PostList 
// agar dapat digunakan di bagian lain dari aplikasi, terutama untuk digunakan 
// sebagai rute daftar postingan dalam aplikasi React. Komponen ini bertanggung 
// jawab untuk menampilkan daftar semua posts yang diambil dari API, serta 
// mengelola status loading dan error selama proses pengambilan data.
