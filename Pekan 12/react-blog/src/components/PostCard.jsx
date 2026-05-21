import { Link } from 'react-router-dom';
import DefaultAvatar from './DefaultAvatar';

// Komponen PostCard.
// Menampilkan kartu ringkasan artikel di halaman utama.
const PostCard = ({ post }) => {
  
  // Fungsi pembantu untuk memotong teks artikel agar tidak terlalu panjang di halaman utama.
  // Default pemotongan adalah 150 karakter.
  const truncateText = (text, maxLength = 150) => {
    if (!text) return '';
    if (text.length <= maxLength) return text;
    return text.substring(0, maxLength) + '...';
  };

  // Fungsi pembantu untuk memformat tanggal secara relatif (Hari ini, Kemarin, X hari yang lalu)
  const formatDate = (dateString) => {
    if (!dateString) return '14 days ago';
    const date = new Date(dateString);
    const now = new Date();
    // Menghitung selisih waktu dalam milidetik dan mengonversinya menjadi hari
    const diffTime = now - date;
    const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
    
    if (diffDays === 0) return 'Today';
    if (diffDays === 1) return 'Yesterday';
    if (diffDays < 0) return 'Just now'; // Menangani jika ada selisih zona waktu kecil
    return `${diffDays} days ago`;
  };

  return (
    <article className="p-6 bg-white rounded-lg border border-gray-200 shadow-md hover:shadow-lg transition-shadow">
      
      {/* Bagian Atas Kartu: Kategori & Tanggal Pembuatan */}
      <div className="flex justify-between items-center mb-5 text-gray-500">
        {/* Label Tag Kategori */}
        <span className="bg-blue-100 text-blue-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded">
          {/* Ikon Kertas/Artikel SVG */}
          <svg className="mr-1 w-3 h-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fillRule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clipRule="evenodd"></path>
            <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"></path>
          </svg>
          Article
        </span>
        
        {/* Tanggal Relatif Pembuatan */}
        <span className="text-sm flex items-center">
          {/* Ikon Kalender SVG */}
          <svg className="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fillRule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clipRule="evenodd"></path>
          </svg>
          {formatDate(post.created_at)}
        </span>
      </div>
      
      {/* Bagian Tengah Kartu: Judul Artikel (Klik untuk ke Halaman Detail) */}
      <h2 className="mb-2 text-2xl font-bold tracking-tight text-gray-900">
        <Link to={`/posts/${post.id}`} className="hover:text-blue-600 transition-colors">
          {post.title}
        </Link>
      </h2>
      
      {/* Cuplikan Teks Artikel yang Telah Dipotong */}
      <p className="mb-5 font-light text-gray-500">
        {truncateText(post.article)}
      </p>
      
      {/* Bagian Bawah Kartu: Info Penulis & Tautan "Read more" */}
      <div className="flex justify-between items-center">
        {/* Identitas Penulis */}
        <div className="flex items-center space-x-3">
          <DefaultAvatar />
          <span className="font-medium text-gray-900">{post.author}</span>
        </div>
        
        {/* Tautan Selengkapnya */}
        <Link
          to={`/posts/${post.id}`}
          className="inline-flex items-center font-medium text-blue-600 hover:text-blue-500 transition-colors"
        >
          Read more
          {/* Ikon Panah Kanan SVG */}
          <svg className="ml-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fillRule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clipRule="evenodd"></path>
          </svg>
        </Link>
      </div>
    </article>
  );
};

export default PostCard;
