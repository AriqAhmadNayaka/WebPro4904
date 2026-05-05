<?php

// Ini function buat ngecek user sudah login apa belum.
// Jadi sebelum masuk halaman tertentu, program lihat dulu session login_status-nya ada atau kosong.
function is_login(){
      $CI     =   & get_instance();
      if($CI->session->userdata('login_status')==''){
          // Kalau ternyata masih kosong, user dilempar ke halaman auth supaya login dulu.
          redirect('auth');
      }
}

// Function ini saya pakai buat mengubah format tanggal dari database jadi format Indonesia.
// Misalnya tanggal yang awalnya tahun-bulan-tanggal nanti dibaca jadi tanggal nama_bulan tahun.
function tgl_indo($tanggal){
	$bulan = array (
		1 =>   'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember'
	);

	// Bagian ini memecah tanggal berdasarkan tanda strip, jadi tiap bagian tanggal bisa dipakai sendiri-sendiri.
	$pecahkan = explode('-', $tanggal);
	
	// variabel pecahkan 0 = tahun
	// variabel pecahkan 1 = bulan
	// variabel pecahkan 2 = tanggal
 
	// Di sini tanggal disusun ulang agar lebih enak dibaca manusia Indonesia, bukan format database mentah.
	return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}

?>