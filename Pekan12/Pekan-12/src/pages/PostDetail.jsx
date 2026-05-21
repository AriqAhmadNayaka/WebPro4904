import { useEffect, useState } from "react";
import { Link, useParams } from "react-router-dom";
import DefaultAvatar from "../components/DefaultAvatar";
import Footer from "../components/Footer";
import Navbar from "../components/Navbar";
import { postsAPI } from "../services/api";

// Menyesuaikan bentuk response API agar komponen selalu menerima object post.
const getPostData = (payload) => payload?.data || payload || null;

const PostDetail = () => {
  const { id } = useParams();
  const [post, setPost] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  useEffect(() => {
    // Mengambil detail post berdasarkan id yang ada di URL.
    const loadPost = async () => {
      try {
        const response = await postsAPI.getById(id);
        setPost(getPostData(response.data));
        setError("");
      } catch (err) {
        if (err.response?.status === 404) {
          setError("Post tidak ditemukan.");
        } else {
          setError("Gagal memuat post. Silakan coba lagi.");
        }

        console.error("Error fetching post:", err);
      } finally {
        setLoading(false);
      }
    };

    loadPost();
  }, [id]);

  // Mengubah format tanggal dari database menjadi format Indonesia.
  const formatDate = (dateString) => {
    if (!dateString) return "";

    const date = new Date(dateString);
    return date.toLocaleDateString("id-ID", {
      day: "numeric",
      month: "long",
      year: "numeric",
    });
  };

  if (loading) {
    return (
      <div className="flex min-h-screen items-center justify-center bg-gray-100">
        <div className="h-12 w-12 animate-spin rounded-full border-b-2 border-blue-600"></div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="flex min-h-screen flex-col bg-gray-100">
        <Navbar />
        <div className="mx-auto flex max-w-screen-xl flex-1 items-center justify-center px-4 py-8 lg:px-6 lg:py-16">
          <div className="py-20 text-center">
            <p className="mb-4 text-red-500">{error}</p>
            <Link
              to="/posts"
              className="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700"
            >
              Kembali ke Daftar Posts
            </Link>
          </div>
        </div>
        <Footer />
      </div>
    );
  }

  return (
    <div className="flex min-h-screen flex-col bg-gray-100">
      <Navbar />

      <main className="flex-1">
        <div className="mx-auto max-w-screen-lg px-4 py-8 lg:px-6 lg:py-16">
          <div className="mb-6">
            <Link
              to="/posts"
              className="inline-flex items-center text-sm font-medium text-blue-600 transition-colors hover:text-blue-500"
            >
              <svg
                className="mr-2 h-4 w-4"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  fillRule="evenodd"
                  d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                  clipRule="evenodd"
                />
              </svg>
              Back to Posts
            </Link>
          </div>

          <article className="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-md">
            {post.image_url && (
              <img
                src={post.image_url}
                alt={post.title}
                className="h-64 w-full object-cover lg:h-96"
                onError={(event) => {
                  // Jika gambar dari API gagal dimuat, gambar disembunyikan.
                  event.currentTarget.style.display = "none";
                }}
              />
            )}

            <div className="p-6 lg:p-10">
              <div className="mb-6 flex flex-wrap items-center gap-4">
                <span className="inline-flex items-center rounded bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                  <svg
                    className="mr-1 h-3 w-3"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      fillRule="evenodd"
                      d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z"
                      clipRule="evenodd"
                    />
                    <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z" />
                  </svg>
                  Article
                </span>

                <div className="flex items-center space-x-2">
                  <DefaultAvatar />
                  <span className="text-sm font-medium text-gray-700">
                    {post.author}
                  </span>
                </div>

                {post.created_at && (
                  <span className="flex items-center text-sm text-gray-500">
                    <svg
                      className="mr-1 h-4 w-4"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        fillRule="evenodd"
                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                        clipRule="evenodd"
                      />
                    </svg>
                    {formatDate(post.created_at)}
                  </span>
                )}
              </div>

              <h1 className="mb-6 text-3xl font-bold tracking-tight text-gray-900 lg:text-4xl">
                {post.title}
              </h1>

              <div className="text-lg leading-relaxed text-gray-600">
                {/* Artikel dipisah per baris agar paragraf tetap rapi. */}
                {(post.article || "").split("\n").map((paragraph, index) => (
                  <p key={index} className="mb-4">
                    {paragraph}
                  </p>
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

export default PostDetail;
