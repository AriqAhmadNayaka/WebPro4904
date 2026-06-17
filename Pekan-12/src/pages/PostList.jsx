import { useState, useEffect } from 'react';

import { postsAPI } from '../service/Api';

import Navbar from '../components/Navbar';
import Footer from '../components/Footer';
import PostCard from '../components/PostCard';

const PostList = () => {

    const [posts, setPosts] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');

    useEffect(() => {
        fetchPosts();
    }, []);

    const fetchPosts = async () => {
        try {
            setLoading(true);

            // Data post diambil dari API CI3, lalu isi array data dipakai untuk card.
            const response = await postsAPI.getAll();

            setPosts(response.data.data || []);
            setError('');

        } catch (err) {

            setError(
                'Gagal memuat posts. Silakan coba lagi.'
            );

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

                    {/* Bagian pembuka dashboard post. */}
                    <div className="mx-auto max-w-screen-sm text-center lg:mb-16 mb-8">

                        <h2 className="mb-4 text-3xl lg:text-4xl tracking-tight font-extrabold text-gray-900">
                            Our Blog
                        </h2>

                        <p className="font-light text-gray-500 sm:text-xl">
                            Explore our collection of articles about React,
                            web development, and more.
                        </p>

                    </div>

                    {/* Tampilan berubah sesuai kondisi: loading, error, kosong, atau ada data. */}
                    {loading ? (

                        <div className="flex justify-center items-center py-20">

                            <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>

                        </div>

                    ) : error ? (

                        /* Error State */
                        <div className="text-center py-20">

                            <p className="text-red-500 mb-4">
                                {error}
                            </p>

                            <button
                                onClick={fetchPosts}
                                className="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5"
                            >
                                Coba Lagi
                            </button>

                        </div>

                    ) : posts.length === 0 ? (

                        /* Empty State */
                        <div className="text-center py-20">

                            <p className="text-gray-500">
                                Belum ada posts.
                            </p>

                        </div>

                    ) : (

                        /* Posts Grid */
                        <div className="grid gap-8 lg:grid-cols-2">

                            {posts.map((post) => (
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

export default PostList;
