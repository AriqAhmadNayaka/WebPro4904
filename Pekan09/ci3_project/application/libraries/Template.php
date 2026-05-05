<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Class Template ini dibuat supaya loading view bisa lebih rapi.
// Jadi isi halaman dimasukkan dulu ke template, bukan dipanggil acak-acakan di controller.
#[AllowDynamicProperties]
class Template {
        // Variabel ini menampung data yang nanti dikirim ke template utama.
		var $template_data = array();
		
        // Function ini menyimpan data ke array template_data.
        // Ibaratnya kita titip variabel dulu sebelum view benar-benar ditampilkan.
		function set($name, $value)
		{
			$this->template_data[$name] = $value;
		}
	
        // Function ini memuat view isi, lalu memasukkannya ke dalam template.
        // Parameter return dipakai kalau hasil view mau dikembalikan sebagai string, bukan langsung tampil.
		function load($template = '', $view = '' , $view_data = array(), $return = FALSE)
		{               
			$this->CI =& get_instance();
			$this->set('contents', $this->CI->load->view($view, $view_data, TRUE));			
			return $this->CI->load->view($template, $this->template_data, $return);
		}
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */