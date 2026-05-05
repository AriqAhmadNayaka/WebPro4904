<?php

// Helper ini isinya kumpulan function kecil yang bisa dipakai berulang.
// Jadi kalau butuh combobox, hitung data, atau cek nilai, tidak perlu nulis ulang dari awal.

// Function ini membuat dropdown/select secara dinamis dari isi tabel database.
// Parameter dibuat banyak karena nama tabel, field, primary key, dan pilihan aktifnya bisa beda-beda.
function cmb_dinamis($name, $table, $field, $pk, $selected = null, $extra = null) {
    $ci = & get_instance();
    $cmb = "<select name='$name' class='form-control' $extra>";
    $data = $ci->db->get($table)->result();

    // Data dari database diputar satu-satu untuk dijadikan option di dalam select.
    foreach ($data as $row) {
        $cmb .= "<option value='" . $row->$pk . "'";
        $cmb .= $selected == $row->$pk ? 'selected' : '';
        $cmb .= ">" . $row->$field . "</option>";
    }

    $cmb .= "</select>";
    return $cmb;
}

// Function ini menghitung jumlah semua data di tabel tertentu.
// Biasanya dipakai untuk dashboard atau info singkat yang butuh total data.
function hitungdata($table) {
    $ci = & get_instance();
    $hitung = $ci->db->from($table)->get()->num_rows();
    return $hitung;
}

// Function ini mirip cmb_dinamis, tapi ada filter where.
// Jadi dropdown-nya tidak ambil semua data, hanya data yang sesuai kondisi tertentu saja.
function cmb_dinamis_where($name, $table, $field, $pk, $selected = null, $extra = null, $where = null, $nilaiwhere=null) {
    $ci = & get_instance();
    $cmb = "<select name='$name' class='form-control' $extra>";
    $data = $ci->db->where($where,$nilaiwhere)->get($table)->result();

    // Bagian ini menyusun option dari hasil query yang sudah difilter tadi.
    foreach ($data as $row) {
        $cmb .= "<option value='" . $row->$pk . "'";
        $cmb .= $selected == $row->$pk ? 'selected' : '';
        $cmb .= ">" . $row->$field . "</option>";
    }

    $cmb .= "</select>";
    return $cmb;
}

// Function ini mengambil data tahun akademik yang sedang aktif.
// Field yang mau diambil dikirim lewat parameter, jadi lebih fleksibel walau agak rawan kalau field salah ketik.
function get_tahun_akademik_aktif($field) {
    $ci = & get_instance();
    $ci->db->where('is_aktif', 'y');
    $tahun = $ci->db->get('tbl_tahun_akademik')->row_array();
    return $tahun[$field];
}

// Function ini mengecek nilai mahasiswa berdasarkan nim dan jadwal.
// Kalau datanya ada, nilai dikembalikan. Kalau tidak ada, dianggap 0 dulu biar tidak error.
function chek_nilai($nim, $id_jadwal) {
    $ci = & get_instance();
    $nilai = $ci->db->get_where('tbl_nilai', array('nim' => $nim, 'id_jadwal' => $id_jadwal));

    if ($nilai->num_rows() > 0) {
        $row = $nilai->row_array();
        return $row['nilai'];
    } else {
        return 0;
    }
}

// Function ini mengecek komponen biaya berdasarkan jenis pembayaran dan semester aktif.
// Kalau biaya ditemukan, yang dikembalikan jumlah biayanya. Kalau tidak ada, baliknya 0.
function chek_komponen_biaya($id_jenis_pembayaran) {
    $ci = & get_instance();
    $where = array(
        'id_jenis_pembayaran' => $id_jenis_pembayaran,
        'id_tahun_akademik' => get_tahun_akademik_aktif('semester_aktif'));
    $biaya = $ci->db->get_where('tbl_biaya_sekolah', $where);

    if ($biaya->num_rows() > 0) {
        $row = $biaya->row_array();
        return $row['jumlah_biaya'];
    } else {
        return 0;
    }
}

// Function ini mengecek apakah user boleh membuka module tertentu.
// Alurnya ambil controller dan method dari URL, lalu dicocokkan dengan hak akses user di database.
function chekAksesModule() {
    $ci = & get_instance();

    // Bagian ini mengambil nama controller dan method dari URL yang sedang dibuka.
    $controller = $ci->uri->segment(1);
    $method = $ci->uri->segment(2);

    // Kalau method kosong, berarti URL cuma controller saja. Kalau ada method, digabung jadi controller/method.
    if (empty($method)) {
        $url = $controller;
    } else {
        $url = $controller . '/' . $method;
    }

    // Setelah URL dapat, program cari menu yang link-nya sama, lalu ambil level user dari session.
    $menu = $ci->db->get_where('tabel_menu', array('link' => $url))->row_array();
    $level_user = $ci->session->userdata('id_level_user');

    if (!empty($level_user)) {
        // Di sini dicek apakah level user punya aturan akses untuk menu tersebut.
        // Beberapa method seperti data, add, edit, dan delete dibuat pengecualian sesuai kode awalnya.
        $chek = $ci->db->get_where('tbl_user_rule', array('id_level_user' => $level_user, 'id_menu' => $menu['id']));
        if ($chek->num_rows() < 1 and $method != 'data' and $method != 'add' and $method != 'edit' and $method != 'delete') {
            echo "ANDA TIDAK BOLEH MENGAKSES MODUL INI";
            die;
        }
    } else {
        // Kalau level user tidak ada, berarti session belum siap atau belum login, jadi dilempar ke auth.
        redirect('auth');
    }
}

// Function ini mengubah angka menjadi tulisan bahasa Indonesia.
// Contohnya 125 bisa dibaca menjadi seratus dua puluh lima, meskipun penulisannya masih gaya rekursif yang agak muter.
function Terbilang($x) {
    $abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");

    // Bagian if-elseif ini membagi angka berdasarkan tingkatannya: belas, puluh, ratus, ribu, dan juta.
    if ($x < 12)
        return " " . $abil[$x];
    elseif ($x < 20)
        return Terbilang($x - 10) . "belas";
    elseif ($x < 100)
        return Terbilang($x / 10) . " puluh" . Terbilang($x % 10);
    elseif ($x < 200)
        return " seratus" . Terbilang($x - 100);
    elseif ($x < 1000)
        return Terbilang($x / 100) . " ratus" . Terbilang($x % 100);
    elseif ($x < 2000)
        return " seribu" . Terbilang($x - 1000);
    elseif ($x < 1000000)
        return Terbilang($x / 1000) . " ribu" . Terbilang($x % 1000);
    elseif ($x < 1000000000)
        return Terbilang($x / 1000000) . " juta" . Terbilang($x % 1000000);
}