/* eslint-disable react-hooks/set-state-in-effect */
// useCallback menjaga function fetch stabil untuk useEffect, useEffect menjalankan fetch saat halaman dibuka.
import { useCallback, useEffect, useState } from 'react'
// postsAPI berisi request ke endpoint api/post pada backend CI3.
import { postsAPI } from '../services/api'
// Navbar tampil pada bagian atas halaman.
import Navbar from '../components/Navbar'
// Footer tampil pada bagian bawah halaman.
import Footer from '../components/Footer'
// PostCard merender setiap item post.
import PostCard from '../components/PostCard'

// PostList menampilkan semua artikel yang dikirim backend.
const PostList = () => {
  // State posts menyimpan array hasil response API.
  const [posts, setPosts] = useState([])
  // Loading dipakai untuk menampilkan spinner saat data belum datang.
  const [loading, setLoading] = useState(true)
  // Error dipakai untuk menampilkan pesan gagal fetch.
  const [error, setError] = useState('')

  // fetchPosts mengambil daftar post dari endpoint api/post.
  const fetchPosts = useCallback(async () => {
    try {
      setLoading(true)
      // Backend CI3 mengirim data pada response.data.data.
      const response = await postsAPI.getAll()
      setPosts(response.data?.data || [])
      setError('')
    } catch (err) {
      // Pesan error dibuat jelas untuk user praktikum.
      setError('Gagal memuat posts. Pastikan XAMPP dan API CodeIgniter Pekan-09 sudah berjalan.')
      console.error('Error fetching posts:', err)
    } finally {
      // Loading dimatikan setelah request selesai.
      setLoading(false)
    }
  }, [])

  // Effect ini menjalankan fetchPosts sekali saat komponen mount.
  useEffect(() => {
    fetchPosts()
  }, [fetchPosts])

  return (
    <div className="min-h-screen bg-gray-100 flex flex-col">
      <Navbar />
      <main className="flex-1">
        <div className="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
          {/* Judul halaman seperti contoh output modul. */}
          <div className="mx-auto max-w-screen-sm text-center lg:mb-16 mb-8">
            <h2 className="mb-4 text-3xl lg:text-4xl tracking-tight font-extrabold text-gray-900">Our Blog</h2>
            <p className="font-light text-gray-500 sm:text-xl">
              Explore our collection of articles from the CodeIgniter 3 REST API.
            </p>
          </div>

          {/* Kondisi loading, error, kosong, dan sukses dipisahkan agar UI mudah dipahami. */}
          {loading ? (
            <div className="flex justify-center items-center py-20">
              <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            </div>
          ) : error ? (
            <div className="text-center py-20">
              <p className="text-red-500 mb-4">{error}</p>
              <button
                type="button"
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
  )
}

// Export default agar route /posts dapat merender halaman ini.
export default PostList
