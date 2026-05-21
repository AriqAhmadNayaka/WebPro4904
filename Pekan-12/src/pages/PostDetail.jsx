/* eslint-disable react-hooks/set-state-in-effect */
// useCallback menjaga function fetch stabil, useEffect mengambil data saat ID berubah.
import { useCallback, useEffect, useState } from 'react'
// useParams membaca ID dari URL, Link membuat tombol kembali tanpa reload.
import { Link, useParams } from 'react-router-dom'
// postsAPI berisi request detail post ke backend CI3.
import { postsAPI } from '../services/api'
// Navbar digunakan di bagian atas halaman.
import Navbar from '../components/Navbar'
// Footer digunakan di bagian bawah halaman.
import Footer from '../components/Footer'
// DefaultAvatar menampilkan ikon author.
import DefaultAvatar from '../components/DefaultAvatar'

// PostDetail menampilkan satu artikel berdasarkan ID route.
const PostDetail = () => {
  // ID berasal dari route /posts/:id.
  const { id } = useParams()
  // State post menyimpan object detail dari response API.
  const [post, setPost] = useState(null)
  // Loading menampilkan spinner saat request berjalan.
  const [loading, setLoading] = useState(true)
  // Error menampilkan pesan jika post tidak ditemukan atau server gagal.
  const [error, setError] = useState('')

  // fetchPost mengambil satu post dari endpoint api/post/:id.
  const fetchPost = useCallback(async () => {
    try {
      setLoading(true)
      // Backend CI3 mengirim detail pada response.data.data.
      const response = await postsAPI.getById(id)
      setPost(response.data?.data || null)
      setError('')
    } catch (err) {
      // Status 404 diberi pesan khusus agar user tahu ID tidak ada.
      if (err.response?.status === 404) {
        setError('Post tidak ditemukan.')
      } else {
        setError('Gagal memuat post. Pastikan API CodeIgniter Pekan-09 sudah berjalan.')
      }
      console.error('Error fetching post:', err)
    } finally {
      // Loading selalu dimatikan setelah request selesai.
      setLoading(false)
    }
  }, [id])

  // Fetch detail dijalankan setiap ID berubah.
  useEffect(() => {
    fetchPost()
  }, [fetchPost])

  // formatDate mengubah created_at menjadi format tanggal Indonesia.
  const formatDate = (dateString) => {
    if (!dateString) return ''

    // Date dibuat dari string timestamp database.
    const date = new Date(dateString)
    if (Number.isNaN(date.getTime())) return ''

    // Format Indonesia membuat tanggal lebih mudah dibaca.
    return date.toLocaleDateString('id-ID', {
      day: 'numeric',
      month: 'long',
      year: 'numeric',
    })
  }

  // Tampilan loading awal saat detail post sedang dimuat.
  if (loading) {
    return (
      <div className="min-h-screen bg-gray-100 flex items-center justify-center">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
      </div>
    )
  }

  // Tampilan error tetap membawa Navbar dan Footer agar layout konsisten.
  if (error) {
    return (
      <div className="min-h-screen bg-gray-100 flex flex-col">
        <Navbar />
        <div className="flex-1 flex items-center justify-center py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
          <div className="text-center py-20">
            <p className="text-red-500 mb-4">{error}</p>
            <Link to="/posts" className="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">
              Kembali ke Daftar Posts
            </Link>
          </div>
        </div>
        <Footer />
      </div>
    )
  }

  return (
    <div className="min-h-screen bg-gray-100 flex flex-col">
      <Navbar />
      <main className="flex-1">
        <div className="py-8 px-4 mx-auto max-w-screen-lg lg:py-16 lg:px-6">
          {/* Link kembali ke halaman daftar post. */}
          <div className="mb-6">
            <Link to="/posts" className="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-500 transition-colors">
              <svg className="mr-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path
                  fillRule="evenodd"
                  d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                  clipRule="evenodd"
                ></path>
              </svg>
              Back to Posts
            </Link>
          </div>

          {/* Article detail menampilkan data lengkap dari field title, author, article, created_at. */}
          <article className="bg-white rounded-lg border border-gray-200 shadow-md overflow-hidden">
            <div className="p-6 lg:p-10">
              <div className="flex flex-wrap items-center gap-4 mb-6">
                <span className="bg-blue-100 text-blue-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded">
                  <svg className="mr-1 w-3 h-3" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path
                      fillRule="evenodd"
                      d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z"
                      clipRule="evenodd"
                    ></path>
                    <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"></path>
                  </svg>
                  Article
                </span>

                <div className="flex items-center space-x-2">
                  <DefaultAvatar />
                  <span className="font-medium text-sm text-gray-700">{post?.author}</span>
                </div>

                {post?.created_at && (
                  <span className="text-sm text-gray-500 flex items-center">
                    <svg className="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                      <path
                        fillRule="evenodd"
                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                        clipRule="evenodd"
                      ></path>
                    </svg>
                    {formatDate(post.created_at)}
                  </span>
                )}
              </div>

              <h1 className="mb-6 text-3xl lg:text-4xl font-bold tracking-tight text-gray-900">{post?.title}</h1>

              {/* Artikel dipecah per baris agar newline dari database tetap terbaca sebagai paragraf. */}
              <div className="text-gray-600 leading-relaxed text-lg">
                {(post?.article || '').split('\n').map((paragraph, index) => (
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
  )
}

// Export default agar route /posts/:id dapat merender halaman ini.
export default PostDetail
