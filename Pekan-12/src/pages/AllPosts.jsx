import { useEffect, useMemo, useState } from 'react';
import { Link } from 'react-router-dom';

import { postsAPI } from '../service/Api';

import Navbar from '../components/Navbar';
import Footer from '../components/Footer';
import PostCard from '../components/PostCard';

const AllPosts = () => {
    const [posts, setPosts] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const [search, setSearch] = useState('');

    useEffect(() => {
        fetchPosts();
    }, []);

    const fetchPosts = async () => {
        try {
            setLoading(true);

            // Semua post dari CI3 ditarik sekali, lalu diolah di sisi frontend.
            const response = await postsAPI.getAll();

            setPosts(response.data.data || []);
            setError('');

        } catch (err) {
            setError('Gagal memuat semua post. Silakan coba lagi.');
            console.error('Error fetching all posts:', err);

        } finally {
            setLoading(false);
        }
    };

    const filteredPosts = useMemo(() => {
        // Search dibuat lokal supaya user bisa mencari tanpa request ulang ke server.
        const keyword = search.trim().toLowerCase();

        if (!keyword) {
            return posts;
        }

        return posts.filter((post) => {
            const title = post.title || '';
            const author = post.author || '';
            const article = post.article || '';

            return `${title} ${author} ${article}`
                .toLowerCase()
                .includes(keyword);
        });
    }, [posts, search]);

    const totalAuthors = useMemo(() => {
        // Author dihitung unik, jadi nama yang sama tidak dihitung dua kali.
        const authors = posts
            .map((post) => post.author)
            .filter(Boolean);

        return new Set(authors).size;
    }, [posts]);

    const latestPost = posts[0];

    return (
        <div className="min-h-screen bg-gray-100 flex flex-col">
            <Navbar />

            <main className="flex-1">
                <div className="py-8 px-4 mx-auto max-w-screen-xl lg:py-12 lg:px-6">

                    <div className="mb-8 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                        <div>
                            <p className="mb-2 text-sm font-semibold uppercase text-blue-600">
                                Dashboard
                            </p>

                            <h1 className="text-3xl font-extrabold tracking-tight text-gray-900 lg:text-4xl">
                                All Post
                            </h1>

                            <p className="mt-3 max-w-2xl text-gray-500">
                                Semua postingan yang dibuat di website CI3 ditampilkan di sini.
                            </p>
                        </div>

                        <Link
                            to="/posts"
                            className="inline-flex w-fit items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition-colors hover:bg-blue-700"
                        >
                            Kembali ke Dashboard
                        </Link>
                    </div>

                    <div className="mb-8 grid gap-4 md:grid-cols-3">
                        <section className="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                            <p className="text-sm font-medium text-gray-500">
                                Total Post
                            </p>

                            <p className="mt-2 text-3xl font-bold text-gray-900">
                                {posts.length}
                            </p>
                        </section>

                        <section className="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                            <p className="text-sm font-medium text-gray-500">
                                Total Author
                            </p>

                            <p className="mt-2 text-3xl font-bold text-gray-900">
                                {totalAuthors}
                            </p>
                        </section>

                        <section className="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                            <p className="text-sm font-medium text-gray-500">
                                Post Terbaru
                            </p>

                            <p className="mt-2 truncate text-lg font-semibold text-gray-900">
                                {latestPost?.title || '-'}
                            </p>
                        </section>
                    </div>

                    <div className="mb-6 rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                        <label
                            htmlFor="search-post"
                            className="mb-2 block text-sm font-medium text-gray-900"
                        >
                            Cari Post
                        </label>

                        <input
                            id="search-post"
                            type="search"
                            value={search}
                            onChange={(event) => setSearch(event.target.value)}
                            className="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                            placeholder="Cari berdasarkan judul, author, atau isi artikel"
                        />
                    </div>

                    {/* Bagian daftar post mengikuti hasil search dan kondisi request API. */}
                    {loading ? (
                        <div className="flex justify-center items-center py-20">
                            <div className="h-12 w-12 animate-spin rounded-full border-b-2 border-blue-600"></div>
                        </div>

                    ) : error ? (
                        <div className="rounded-lg border border-red-200 bg-white py-16 text-center shadow-sm">
                            <p className="mb-4 text-red-500">
                                {error}
                            </p>

                            <button
                                onClick={fetchPosts}
                                className="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition-colors hover:bg-blue-700"
                            >
                                Coba Lagi
                            </button>
                        </div>

                    ) : filteredPosts.length === 0 ? (
                        <div className="rounded-lg border border-gray-200 bg-white py-16 text-center shadow-sm">
                            <p className="text-gray-500">
                                Tidak ada post yang cocok.
                            </p>
                        </div>

                    ) : (
                        <div className="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                            {filteredPosts.map((post) => (
                                <PostCard
                                    key={post.id}
                                    post={post}
                                />
                            ))}
                        </div>
                    )}
                </div>
            </main>

            <Footer />
        </div>
    );
};

export default AllPosts;
