// import ini digunakan untuk mengimpor beberapa hook dari React, layanan API untuk posts, dan beberapa komponen seperti Navbar, Footer, dan PostCard. 
// Hook useState digunakan untuk mengelola state lokal dalam komponen, sementara useEffect digunakan untuk melakukan side effect seperti fetching data saat komponen pertama kali dirender. 
// postsAPI adalah objek yang berisi metode untuk berinteraksi dengan API backend untuk mendapatkan data posts. Navbar dan Footer adalah komponen yang digunakan untuk menampilkan navigasi dan footer pada halaman, sedangkan PostCard adalah komponen yang
import { useState, useEffect } from 'react';
import { postsAPI } from '../services/api';
import Navbar from '../components/Navbar';
import Footer from '../components/Footer';
import PostCard from '../components/PostCard';

// Komponen PostList adalah halaman yang menampilkan daftar posts yang diambil dari API. Komponen ini menggunakan state untuk menyimpan data posts, status loading, dan pesan error. 
// Ketika komponen pertama kali dirender, useEffect akan memanggil fungsi fetchPosts untuk mengambil data posts dari API. 
// Fungsi fetchPosts menggunakan try-catch untuk menangani proses fetching data, mengatur status loading, dan menangani error jika terjadi masalah saat mengambil data. 
// Dalam bagian return, komponen ini menampilkan Navbar di bagian atas, kemudian menampilkan judul dan deskripsi singkat tentang blog. Selanjutnya, berdasarkan status loading dan error, komponen ini akan menampilkan spinner loading, pesan error, atau daftar posts menggunakan komponen PostCard. 
// Jika tidak ada posts yang tersedia, akan ditampilkan pesan yang menyatakan bahwa belum ada posts. Di bagian bawah halaman, Footer akan ditampilkan untuk memberikan informasi tambahan atau navigasi. Halaman ini dirancang dengan menggunakan Tailwind CSS untuk memberikan tampilan yang menarik dan responsif.
const PostList = () => {
    const [posts, setPosts] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');

    // useEffect digunakan untuk memanggil fungsi fetchPosts saat komponen pertama kali dirender. Ini memastikan bahwa data posts diambil dari API segera setelah halaman dimuat. 
    // Dengan memberikan array kosong sebagai dependensi, kita memastikan bahwa efek ini hanya dijalankan sekali saat komponen pertama kali dirender.
    useEffect(() => {
        fetchPosts();
    }, []);

    // Fungsi fetchPosts adalah fungsi asinkron yang digunakan untuk mengambil data posts dari API. 
    // Fungsi ini menggunakan try-catch untuk menangani proses fetching data. 
    // Pertama, kita mengatur status loading menjadi true untuk menunjukkan bahwa data sedang dimuat. 
    // Kemudian, kita memanggil metode getAll dari postsAPI untuk mendapatkan data posts. Jika permintaan berhasil, kita menyimpan data posts ke dalam state dan menghapus pesan error. 
    // Jika terjadi error selama proses fetching, kita menangkap error tersebut dan menyimpan pesan error yang sesuai ke dalam state. Setelah proses selesai, kita mengatur status loading menjadi false untuk menunjukkan bahwa proses fetching telah selesai.
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

    // Bagian return dari komponen PostList berisi struktur JSX yang mendefinisikan tampilan halaman daftar posts. 
    // Ini mencakup Navbar di bagian atas, judul dan deskripsi singkat tentang blog, serta kondisi untuk menampilkan spinner loading, pesan error, atau daftar posts. 
    // Jika status loading true, akan ditampilkan spinner animasi. Jika ada pesan error, itu akan ditampilkan dengan tombol untuk mencoba lagi. Jika tidak ada posts yang tersedia, akan ditampilkan pesan yang menyatakan bahwa belum ada posts. 
    // Jika data posts berhasil diambil, kita menggunakan metode map untuk merender setiap post menggunakan komponen PostCard. Di bagian bawah halaman, Footer akan ditampilkan untuk memberikan informasi tambahan atau navigasi. 
    // Halaman ini dirancang untuk memberikan pengalaman pengguna yang baik dengan menggunakan Tailwind CSS untuk styling dan responsivitas.
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

// Terakhir, kita mengekspor komponen PostList agar dapat digunakan di bagian lain aplikasi. 
// Dengan menggunakan export default, kita memungkinkan komponen ini untuk diimpor dengan nama apa pun di file lain, meskipun biasanya kita akan mengimpor dengan nama yang sama untuk menjaga konsistensi.
export default PostList;