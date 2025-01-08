<?php

class Lib
{

	public function __construct()
	{
		$this->CI = &get_instance();
	}

	function parameter()
	{
		$data = array(
			'title' => "Sistem Informasi Manajemen Inventaris Aset",
			'title-sm' => "SIMIA",
			'footer' => $this->tanggal_9t(date("Y-m-d H:i:s")) . " | &copy; 2023 PT BPRS Mustaqim Aceh (Perseroda). All Right Reserved.",
			'footer-login' => "<a href=\"https://bprsmustaqimaceh.co.id\" target=\"_blank\">www.bprsmustaqimaceh.co.id</a><br>&copy; 2023 PT BPRS Mustaqim Aceh (Perseroda)<br>All Right Reserved.",
			'session' => "bprsma_simia_logged_in",
			'key1_url' => "Bprsm@",
			'key2_url' => "H1rS",
			'dana-limit' => 3000000,
			'class' => $this->CI->router->fetch_class(),
			'function' => $this->CI->router->fetch_method(),
		);
		return $data;
	}

	function check_logged_in()
	{
		$this->param = $this->parameter();
		if (!isset($this->CI->session->userdata[$this->param['session']])) {
			redirect('login', 'refresh');
			exit;
		}
	}

	function check_method($method)
	{
		if ($_SERVER['REQUEST_METHOD'] != $method) {
			$this->CI->session->set_flashdata("pesan", "ada");
			$this->CI->session->set_flashdata("pesan_isi", "Mohon maaf request anda tidak sesuai!");
			$this->CI->session->set_flashdata("pesan_tipe", "warning");
			echo "<script>self.history.back();</script>";
			exit;
		}
	}

	function check_access()
	{
		$this->param = $this->parameter();
		$grup_pengguna = $this->CI->session->userdata[$this->param['session']]['grup_pengguna'];

		$class = $this->slug($this->param['class']);
		$function = $this->slug($this->param['function']);
		$access = array();
		/*
		1: Karyawan
    11: Pengurus
		90: SDI / Electronic Data Processing (EDP)
		99: Administrator
		*/


		// Menu Pengguna
		if (($class == "pengguna") && (($function == "index") || ($function == ""))) {
			$access = array(90, 99);
		} else if (($class == "pengguna") && ($function == "get-data")) {
			$access = array(90, 99);
		} else if (($class == "pengguna") && ($function == "add")) {
			$access = array(90, 99);
		} else if (($class == "pengguna") && ($function == "edit")) {
			$access = array(90, 99);
		} else if (($class == "pengguna") && ($function == "nonaktif")) {
			$access = array(90, 99);
		} else if (($class == "pengguna") && ($function == "detail")) {
			$access = array(90, 99);
		} else if (($class == "pengguna") && ($function == "get-data-log")) {
			$access = array(90, 99);
		}

		//Menu Master
		if (($class == "master") && ($function == "kategori")) {
			$access = array(90, 99);
		} else if (($class == "master") && ($function == "get-data-kategori")) {
			$access = array(90, 99);
		} else if (($class == "master") && ($function == "get-kategori")) {
			$access = array(90, 99);
		} else if (($class == "master") && ($function == "kategori-aksi")) {
			$access = array(90, 99);
		} else if (($class == "master") && ($function == "generate")) {
			$access = array(90, 99);
		} else if (($class == "master") && ($function == "get-data-generate")) {
			$access = array(90, 99);
		} else if (($class == "master") && ($function == "generate-aksi")) {
			$access = array(90, 99);
		} else if (($class == "master") && ($function == "jenis")) {
			$access = array(90, 99);
		} else if (($class == "master") && ($function == "get-data-jenis")) {
			$access = array(90, 99);
		} else if (($class == "master") && ($function == "get-jenis")) {
			$access = array(90, 99);
		} else if (($class == "master") && ($function == "jenis-aksi")) {
			$access = array(90, 99);
		} else if (($class == "master") && ($function == "kondisi")) {
			$access = array(90, 99);
		} else if (($class == "master") && ($function == "get-data-kondisi")) {
			$access = array(90, 99);
		} else if (($class == "master") && ($function == "get-kondisi")) {
			$access = array(90, 99);
		} else if (($class == "master") && ($function == "kondisi-aksi")) {
			$access = array(90, 99);
		} else if (($class == "master") && ($function == "penyusutan")) {
			$access = array(90, 99);
		} else if (($class == "master") && ($function == "get-data-penyusutan")) {
			$access = array(90, 99);
		} else if (($class == "master") && ($function == "get-penyusutan")) {
			$access = array(90, 99);
		} else if (($class == "master") && ($function == "penyusutan-aksi")) {
			$access = array(90, 99);
		} else if (($class == "master") && ($function == "lokasi")) {
			$access = array(90, 99);
		} else if (($class == "master") && ($function == "get-data-lokasi")) {
			$access = array(90, 99);
		} else if (($class == "master") && ($function == "get-lokasi")) {
			$access = array(90, 99);
		} else if (($class == "master") && ($function == "lokasi-aksi")) {
			$access = array(90, 99);
		} else if (($class == "master") && ($function == "generate-aksi-auto")) {
			$access = array(90, 99);
		}

		// Menu Aset
		if (($class == "aset") && ($function == "aktif")) {
			$access = array(90, 99);
		} else if (($class == "aset") && ($function == "tidak-aktif")) {
			$access = array(90, 99);
		} else if (($class == "aset") && ($function == "hapus")) {
			$access = array(90, 99);
		} else if (($class == "aset") && ($function == "get-data-aset")) {
			$access = array(90, 99);
		} else if (($class == "aset") && ($function == "get-aset")) {
			$access = array(90, 99);
		} else if (($class == "aset") && ($function == "add-aset")) {
			$access = array(90, 99);
		} else if (($class == "aset") && ($function == "edit-aset")) {
			$access = array(90, 99);
		} else if (($class == "aset") && ($function == "delete")) {
			$access = array(90, 99);
		} else if (($class == "aset") && ($function == "get-data-detail-lokasi")) {
			$access = array(90, 99);
		} else if (($class == "aset") && ($function == "detail-aset")) {
			$access = array(90, 99);
		} else if (($class == "aset") && ($function == "detail-aset-qr")) {
			$access = array(90, 99);
		}

		// Menu Core Aset
		if (($class == "core-aset") && ($function == "permohonan-aset")) {
			$access = array(90, 99, 41, 42, 51, 52, 11, 1);
		} else if (($class == "core-aset") && ($function == "add-permohonan-aset")) {
			$access = array(90, 99, 41, 51);
		} else if (($class == "core-aset") && ($function == "get-permohonan-aset")) {
			$access = array(90, 99, 41, 42, 51, 52, 1);
		} else if (($class == "core-aset") && ($function == "edit-permohonan-aset")) {
			$access = array(90, 99, 41, 51);
		} else if (($class == "core-aset") && ($function == "get-data-permohonan-aset")) {
			$access = array(90, 99, 41, 42, 51, 52, 53, 11, 1);
		} else if (($class == "core-aset") && ($function == "delete-permohonan-aset")) {
			$access = array(90, 99, 41, 51);
		} else if (($class == "core-aset") && ($function == "terima-permohonan-aset")) {
			$access = array(90, 99, 42, 52, 53);
		} else if (($class == "core-aset") && ($function == "tolak-permohonan-aset")) {
			$access = array(90, 99, 42, 52, 53);
		} else if (($class == "core-aset") && ($function == "detail-permohonan-aset")) {
			$access = array(90, 99, 41, 42, 51, 52, 11, 1);
		} else if (($class == "core-aset") && ($function == "finish-permohonan-aset")) {
			$access = array(90, 99, 41, 42, 51, 52, 53, 1, 11);
		} else if (($class == "core-aset") && ($function == "get-data-finish-permohonan-aset")) {
			$access = array(90, 99, 41, 42, 51, 52, 53, 1, 11);
		} else if (($class == "core-aset") && ($function == "get-finish-permohonan-aset")) {
			$access = array(90, 99, 41, 42, 51, 52, 53, 1, 11);
		} else if (($class == "core-aset") && ($function == "mutasi")) {
			$access = array(90, 99);
		} else if (($class == "core-aset") && ($function == "get-data-mutasi")) {
			$access = array(90, 99);
		} else if (($class == "core-aset") && ($function == "get-mutasi")) {
			$access = array(90, 99);
		} else if (($class == "core-aset") && ($function == "get-data-aset")) {
			$access = array(90, 99);
		} else if (($class == "core-aset") && ($function == "get-data-lokasi")) {
			$access = array(90, 99);
		} else if (($class == "core-aset") && ($function == "add-mutasi")) {
			$access = array(90, 99);
		} else if (($class == "core-aset") && ($function == "get-data-mutasi-aset-edit")) {
			$access = array(90, 99);
		} else if (($class == "core-aset") && ($function == "get-data-lokasi-edit")) {
			$access = array(90, 99);
		} else if (($class == "core-aset") && ($function == "edit-mutasi")) {
			$access = array(90, 99);
		} else if (($class == "core-aset") && ($function == "delete-mutasi")) {
			$access = array(90, 99);
		} else if (($class == "core-aset") && ($function == "pemeliharaan")) {
			$access = array(90, 99);
		} else if (($class == "core-aset") && ($function == "get-data-pemeliharaan")) {
			$access = array(90, 99);
		} else if (($class == "core-aset") && ($function == "get-pemeliharaan")) {
			$access = array(90, 99);
		} else if (($class == "core-aset") && ($function == "permohonan-perbaikan")) {
			$access = array(90, 99, 41, 42, 51, 52, 11, 1);
		} else if (($class == "core-aset") && ($function == "get-permohonan-perbaikan")) {
			$access = array(90, 99, 41, 42, 51, 52, 11, 1);
		} else if (($class == "core-aset") && ($function == "add-permohonan-perbaikan")) {
			$access = array(90, 99, 41, 51);
		} else if (($class == "core-aset") && ($function == "edit-permohonan-perbaikan")) {
			$access = array(90, 99, 41, 51);
		} else if (($class == "core-aset") && ($function == "get-data-permohonan-perbaikan")) {
			$access = array(90, 99, 41, 42, 51, 52, 11, 1);
		} else if (($class == "core-aset") && ($function == "delete-permohonan-perbaikan")) {
			$access = array(90, 99, 41, 51);
		} else if (($class == "core-aset") && ($function == "terima-permohonan-perbaikan")) {
			$access = array(90, 99, 42, 52, 53);
		} else if (($class == "core-aset") && ($function == "tolak-permohonan-perbaikan")) {
			$access = array(90, 99, 42, 52, 53);
		} else if (($class == "core-aset") && ($function == "detail-permohonan-perbaikan")) {
			$access = array(90, 99, 41, 42, 51, 52, 11, 1);
		} else if (($class == "core-aset") && ($function == "tambah-aset")) {
			$access = array(51, 52, 53, 99);
		} else if (($class == "core-aset") && ($function == "pengajuan-pusat")) {
			$access = array(51, 52, 53, 99);
		} else if (($class == "core-aset") && ($function == "permohonan-perbaikan-pusat")) {
			$access = array(51, 52, 53, 99);
		} else if (($class == "core-aset") && ($function == "get-data-permohonan-perbaikan-pusat")) {
			$access = array(51, 52, 53, 99);
		} else if (($class == "core-aset") && ($function == "get-permohonan-perbaikan-pusat")) {
			$access = array(51, 52, 53, 99);
		}




		// Menu Laporan
		if (($class == "laporan") && ($function == "aset")) {
			$access = array(90, 99, 41, 42, 51, 52, 53, 11, 1);
		} else if (($class == "laporan") && ($function == "get-data-kondisi")) {
			$access = array(90, 99, 41, 42, 51, 52, 53, 11, 1);
		} else if (($class == "laporan") && ($function == "mutasi")) {
			$access = array(90, 99, 41, 42, 51, 52, 53, 11, 1);
		}

		// Menu Riwayat
		if (($class == "riwayat") && ($function == "pengguna")) {
			$access = array(90, 99);
		} else if (($class == "riwayat") && ($function == "get-data-log-pengguna")) {
			$access = array(90, 99);
		} else if (($class == "riwayat") && ($function == "riw-permohonan-aset")) {
			$access = array(90, 99, 41, 42, 51, 52, 53, 11, 1);
		} else if (($class == "riwayat") && ($function == "get-data-riw-permohonan-aset")) {
			$access = array(90, 99, 41, 42, 51, 52, 53, 11, 1);
		} else if (($class == "riwayat") && ($function == "get-riw-permohonan-aset")) {
			$access = array(90, 99, 41, 42, 51, 52, 53, 11, 1);
		}


		if (!in_array($grup_pengguna, $access)) {
			$this->CI->session->set_flashdata("pesan", "ada");
			$this->CI->session->set_flashdata("pesan_isi", "Mohon maaf anda tidak memiliki akses!");
			$this->CI->session->set_flashdata("pesan_tipe", "warning");
			echo "<script>self.history.back();</script>";
			exit;
		}
	}

	function active_menu_check($urimenu1 = "", $urimenu2 = "", $urimenu3 = "")
	{
		$uri1 = $this->slug($this->CI->uri->segment(1));
		$uri2 = $this->slug($this->CI->uri->segment(2));
		$uri3 = $this->slug($this->CI->uri->segment(3));

		if (($urimenu1 != "") && ($urimenu2 == "") && ($urimenu3 == "")) {
			if ($uri1 == $urimenu1) {
				echo 'active';
			}
		} else if (($urimenu1 != "") && ($urimenu2 != "") && ($urimenu3 == "")) {
			if (($uri1 == $urimenu1) && ($uri2 == $urimenu2)) {
				echo 'active';
			}
		} else {
			if (($uri1 == $urimenu1) && ($uri2 == $urimenu2) && ($uri3 == $urimenu3)) {
				echo 'active';
			}
		}
	}

	function nama_pengguna($id_pengguna)
	{
		$nama_petugas = "";
		$tabel = "pengguna";
		$select = "nama";
		$where = "id_pengguna = '" . $id_pengguna . "'";
		$nama_petugas = $this->CI->apps_m->get_data($tabel, $select, $where)->result_array();
		if (count($nama_petugas) == 0) {
			$nama_petugas = "";
		} else {
			$nama_petugas = $nama_petugas[0]['nama'];
		}
		return $nama_petugas;
	}
	//51 : staff umum
	//52 : kasie
	//53 : pincab
	//54 : kadiv
	function grup_pengguna($level)
	{
		$temp = "";
		switch ($level) {
			case 1:
				$temp = "Karyawan";
				break;
			case 11:
				$temp = "Direksi";
				break;
			case 41:
				$temp = "Kasie";
				break;
			case 42:
				$temp = "Pincab";
				break;
			case 51:
				$temp = "Staff Umum";
				break;
			case 52:
				$temp = "Kasie Umum";
				break;
			case 53:
				$temp = "Kadiv";
				break;
			case 99:
				$temp = "Administrator";
				break;
			default:
				$temp = "-";
				break;
		}
		return $temp;
	}

	function status($status)
	{
		$temp = "";
		if ($status == 0) {
			$temp = "Tidak Aktif";
		} elseif ($status == 1) {
			$temp = "Aktif";
		} else {
			$temp = "-";
		}
		return $temp;
	}

	function jenis_kelamin($jk)
	{
		$temp = "";
		switch ($jk) {
			case "L":
				$temp = "Laki-laki";
				break;
			case "P":
				$temp = "Perempuan";
				break;
			default:
				$temp = "-";
				break;
		}
		return $temp;
	}

	function hitung_umur($tgl_lahir)
	{
		$tanggallahir = new DateTime($tgl_lahir);
		$today = new DateTime("today");
		if ($tanggallahir > $today) {
			exit("0 Tahun 0 Bulan");
		}
		$y = $today->diff($tanggallahir)->y;
		$m = $today->diff($tanggallahir)->m;
		$d = $today->diff($tanggallahir)->d;
		return $y . " Tahun " . $m . " Bulan ";
	}

	function status_pernikahan($status_pernikahan)
	{
		$temp = "";
		if ($status_pernikahan == 1) {
			$temp = "Belum Menikah";
		} else if ($status_pernikahan == 2) {
			$temp = "Menikah";
		} else if ($status_pernikahan == 3) {
			$temp = "Cerai Hidup";
		} else if ($status_pernikahan == 4) {
			$temp = "Cerai Mati";
		} else {
			$temp = "";
		}
		return $temp;
	}

	function status_presensi($status_presensi)
	{
		$temp = "";
		if ($status_presensi == "H") {
			$temp = "Hadir";
		} else if ($status_presensi == "I") {
			$temp = "Izin";
		} else if ($status_presensi == "C") {
			$temp = "Cuti";
		} else if ($status_presensi == "S") {
			$temp = "Sakit";
		} else if ($status_presensi == "TK") {
			$temp = "Tanpa Keterangan";
		} else {
			$temp = "";
		}
		return $temp;
	}

	function jabatan_bagian($jabatan, $bagian)
	{
		$temp = "";
		if (($jabatan == "") && ($bagian == "")) {
			$temp = "-";
		} else if (($jabatan == "-") && ($bagian != "")) {
			$temp = $bagian;
		} else if (($jabatan != "") && ($bagian == "-")) {
			$temp = $jabatan;
		} else if (($jabatan != "") && ($bagian != "")) {
			$temp = $jabatan . " " . $bagian;
		} else {
			$temp = $jabatan . " " . $bagian;
		}

		return $temp;
	}

	function masa_kerja($tgl_masuk, $tgl_berhenti)
	{
		$tanggalmasuk = new DateTime($tgl_masuk);
		if ($tgl_berhenti == "0000-00-00") {
			$tanggalberhenti = new DateTime("today");
		} else {
			$tanggalberhenti = new DateTime($tgl_berhenti);
		}
		if ($tanggalmasuk > $tanggalberhenti) {
			exit("0 Tahun 0 Bulan 0 Hari");
		}
		$y = $tanggalberhenti->diff($tanggalmasuk)->y;
		$m = $tanggalberhenti->diff($tanggalmasuk)->m;
		$d = $tanggalberhenti->diff($tanggalmasuk)->d;
		return $y . " Tahun " . $m . " Bulan " . $d . " Hari";
	}

	function tgl_pensiun($tgl_lahir)
	{
		$dt = new DateTime($tgl_lahir);
		$dt->modify('+56 year');
		$tgl_pensiun = $dt->format('Y-m-d');

		return $tgl_pensiun;
	}

	function sisa_masa_kerja($tgl_lahir, $tgl_masuk)
	{
		$tanggalpensiun = new DateTime($this->tgl_pensiun($tgl_lahir));
		$tanggalmasuk = new DateTime($tgl_masuk);
		if ($tanggalmasuk > $tanggalpensiun) {
			exit("0 Tahun 0 Bulan 0 Hari");
		}
		$interval = $tanggalmasuk->diff($tanggalpensiun);
		$y = $interval->y - 4;
		$m = $interval->m;
		$d = $interval->d;
		return $y . " Tahun " . $m . " Bulan " . $d . " Hari";
	}

	function kategori_aksi($kategori)
	{
		$temp = "";
		switch ($kategori) {
			case 1:
				$temp = 'Mutasi';
				break;
			case 2:
				$temp = 'Pengakatan';
				break;
			case 3:
				$temp = 'Promosi';
				break;
			case 4:
				$temp = 'Demosi';
				break;
			default:
				$temp = '';
				break;
		}
		return $temp;
	}

	function upload_pdf($upload_file)
	{
		// the user id contain dot, so we must remove it
		$path_file = strtolower("assets/lampiran/upload/");
		$config['upload_path']          = "./" . $path_file;
		$config['allowed_types']        = 'pdf';
		$config['file_name']            = $upload_file;
		$config['max_size']             = 10000; // ~10MB

		return $config;
	}

	function generate_qrcode($data)
	{
		/* Load QR Code Library */
		$this->CI->load->library('ciqrcode');

		/* Data */
		$hex_data   = bin2hex($data);
		$save_name  = $hex_data . '.png';

		/* QR Code File Directory Initialize */
		$dir = 'assets/media/qrcode/';
		if (!file_exists($dir)) {
			mkdir($dir, 0775, true);
		}

		/* QR Configuration  */
		$config['cacheable']    = true;
		$config['imagedir']     = $dir;
		$config['quality']      = true;
		$config['size']         = '1024';
		$config['black']        = array(255, 255, 255);
		$config['white']        = array(255, 255, 255);
		$this->CI->ciqrcode->initialize($config);

		/* QR Data  */
		$params['data']     = $data;
		$params['level']    = 'L';
		$params['size']     = 10;
		$params['savename'] = FCPATH . $config['imagedir'] . $save_name;

		$this->CI->ciqrcode->generate($params);

		/* Return Data */
		$return = array(
			'content' => $data,
			'file'    => $dir . $save_name
		);
		return $return;
	}

	function config_file($configfile, $configname)
	{

		if ($configfile == "karyawan") {
			$path = strtolower("assets/img/photo/");
			$config['upload_path'] = "./" . $path;
			$config['allowed_types'] = "jpg|png|jpeg";
			$config['max_size']  = "10000";
			$config['file_name'] = $configname;
		} else if ($configfile == "karyawan-thumbnail") {
			$path = strtolower("assets/img/photo/");
			$config['image_library'] = 'gd2';
			$config['source_image'] = './' . $path . $configname;
			$config['create_thumb'] = FALSE;
			$config['maintain_ratio'] = TRUE;
			// $config['quality']= '50%';
			$config['height'] = 300;
			$config['new_image'] = './' . $path . "thumbnail/" . $configname;
		} else if ($configfile == "karyawan-aksi") {
			$path = strtolower("assets/lampiran/karyawan-aksi/");
			$config['upload_path'] = "./" . $path;
			$config['allowed_types'] = "jpg|png|jpeg|pdf";
			$config['max_size']  = "10000";
			$config['file_name'] = $configname;
		}
		if ($configfile == "pengurus") {
			$path = strtolower("assets/img/photo/");
			$config['upload_path'] = "./" . $path;
			$config['allowed_types'] = "jpg|png|jpeg";
			$config['max_size']  = "10000";
			$config['file_name'] = $configname;
		} else if ($configfile == "pengurus-thumbnail") {
			$path = strtolower("assets/img/photo/");
			$config['image_library'] = 'gd2';
			$config['source_image'] = './' . $path . $configname;
			$config['create_thumb'] = FALSE;
			$config['maintain_ratio'] = TRUE;
			// $config['quality']= '50%';
			$config['height'] = 300;
			$config['new_image'] = './' . $path . "thumbnail/" . $configname;
		}

		return $config;
	}

	function encrypt_password($password)
	{
		$key1 = md5("BPrsMA");
		$key2 = md5("hR1$");
		$passworduser = md5($password);
		return $hasilpassword = md5($key1 . $passworduser . $key2);
	}

	function encrypt_url($url)
	{
		$this->param = $this->parameter();
		$key1 = sha1($this->param['key1_url']);
		$key2 = sha1($this->param['key2_url']);
		$urlbrowser = sha1($url);
		return $hasilurl = sha1($key1 . $urlbrowser . $key2);
	}

	function sql_encrypt_url($key, $value)
	{
		$this->param = $this->parameter();
		$key1_url = $this->param['key1_url'];
		$key2_url = $this->param['key2_url'];
		$temp = "sha1(CONCAT(sha1('" . $key1_url . "'),sha1(" . $key . "),sha1('" . $key2_url . "'))) = '" . $value . "'";

		return $temp;
	}

	function sql_select_encrypt($key, $value)
	{
		$this->param = $this->parameter();
		$key1_url = $this->param['key1_url'];
		$key2_url = $this->param['key2_url'];
		$temp = "sha1(CONCAT(sha1('" . $key1_url . "'),sha1(" . $key . "),sha1('" . $key2_url . "'))) as '" . $value . "'";

		return $temp;
	}

	function slug($temp)
	{
		$string = preg_replace("/[^a-zA-Z0-9 &%|{.}=,?!*()-_+$@;<>']/", '', $temp);
		$trim = trim($string);
		$slug = strtolower(str_replace(" ", "-", $trim));
		$slug = strtolower(str_replace("_", "-", $slug));
		return $slug;
	}

	function aksi_log($pengguna, $aksi, $ket = "")
	{
		$tabellog = "pengguna_log";

		$keterangan = "";
		if ($ket == "") {
			$keterangan = "-";
		} else {
			$keterangan = $ket;
		}
		$datalog = array(
			'pengguna' => $pengguna,
			'aksi' => $aksi,
			'keterangan' => $keterangan,
			'tanggal' => date("Y-m-d H:i:s"),
			'ip_address' => $this->CI->input->ip_address()
		);
		$this->CI->apps_m->insert_data($tabellog, $datalog);
	}

	function hitung_last_login($last_login)
	{
		$date1 = $last_login;
		$date2 = date('Y-m-d H:i:s');

		//Convert them to timestamps.
		$date1Timestamp = strtotime($date1);
		$date2Timestamp = strtotime($date2);

		$selisih = $date2Timestamp - $date1Timestamp;

		if (($selisih >= 0) and ($selisih < 60)) {
			echo $selisih . " detik yang lalu";
		} elseif (($selisih >= 60) and ($selisih < 3600)) {
			echo round($selisih / 60) . " menit yang lalu";
		} elseif (($selisih >= 3600) and ($selisih < 86400)) {
			echo round($selisih / 3600) . " jam yang lalu";
		} elseif ($selisih >= 86400) {
			echo round($selisih / 86400) . " hari yang lalu";
		} else {
		}
	}

	function tanggal_1($tgl)
	{
		$tahun = substr($tgl, 0, 4);
		$bulan = substr($tgl, 5, 2);
		$tanggal = substr($tgl, 8, 2);

		return $tanggal . "-" . $bulan . "-" . $tahun;
	}

	function tanggal_1t($tgl)
	{
		$tahun = substr($tgl, 0, 4);
		$bulan = substr($tgl, 5, 2);
		$tanggal = substr($tgl, 8, 2);

		return $tanggal . "-" . $bulan . "-" . $tahun . " " . substr($tgl, 11);
	}

	function tanggal_2($tgl)
	{
		$tahun = substr($tgl, 0, 4);
		$bulan = substr($tgl, 5, 2);
		$tanggal = substr($tgl, 8, 2);

		return $tanggal . "/" . $bulan . "/" . $tahun;
	}

	function tanggal_2t($tgl)
	{
		$tahun = substr($tgl, 0, 4);
		$bulan = substr($tgl, 5, 2);
		$tanggal = substr($tgl, 8, 2);

		return $tanggal . "/" . $bulan . "/" . $tahun . " " . substr($tgl, 11);
	}

	function tanggal_3($tgl)
	{
		$tahun = substr($tgl, 0, 4);
		$bulan = substr($tgl, 5, 2);
		$tanggal = substr($tgl, 8, 2);

		return $tanggal . " " . $bulan . " " . $tahun;
	}

	function tanggal_3t($tgl)
	{
		$tahun = substr($tgl, 0, 4);
		$bulan = substr($tgl, 5, 2);
		$tanggal = substr($tgl, 8, 2);

		return $tanggal . " " . $bulan . " " . $tahun . " " . substr($tgl, 11);
	}

	function tanggal_4($tgl)
	{
		$tahun = substr($tgl, 0, 4);
		$bulan = substr($tgl, 5, 2);
		$tanggal = substr($tgl, 8, 2);

		return $tanggal . "-" . $this->bulan_1($bulan) . "-" . $tahun;
	}

	function tanggal_4t($tgl)
	{
		$tahun = substr($tgl, 0, 4);
		$bulan = substr($tgl, 5, 2);
		$tanggal = substr($tgl, 8, 2);

		return $tanggal . "-" . $this->bulan_1($bulan) . "-" . $tahun . " " . substr($tgl, 11);
	}

	function tanggal_5($tgl)
	{
		$tahun = substr($tgl, 0, 4);
		$bulan = substr($tgl, 5, 2);
		$tanggal = substr($tgl, 8, 2);

		return $tanggal . "/" . $this->bulan_1($bulan) . "/" . $tahun;
	}

	function tanggal_5t($tgl)
	{
		$tahun = substr($tgl, 0, 4);
		$bulan = substr($tgl, 5, 2);
		$tanggal = substr($tgl, 8, 2);

		return $tanggal . "/" . $this->bulan_1($bulan) . "/" . $tahun . " " . substr($tgl, 11);
	}

	function tanggal_6($tgl)
	{
		$tahun = substr($tgl, 0, 4);
		$bulan = substr($tgl, 5, 2);
		$tanggal = substr($tgl, 8, 2);

		return $tanggal . " " . $this->bulan_1($bulan) . " " . $tahun;
	}

	function tanggal_6t($tgl)
	{
		$tahun = substr($tgl, 0, 4);
		$bulan = substr($tgl, 5, 2);
		$tanggal = substr($tgl, 8, 2);

		return $tanggal . " " . $this->bulan_1($bulan) . " " . $tahun . " " . substr($tgl, 11);
	}

	function tanggal_7($tgl)
	{
		$tahun = substr($tgl, 0, 4);
		$bulan = substr($tgl, 5, 2);
		$tanggal = substr($tgl, 8, 2);

		return $tanggal . "-" . $this->bulan_2($bulan) . "-" . $tahun;
	}

	function tanggal_7t($tgl)
	{
		$tahun = substr($tgl, 0, 4);
		$bulan = substr($tgl, 5, 2);
		$tanggal = substr($tgl, 8, 2);

		return $tanggal . "-" . $this->bulan_2($bulan) . "-" . $tahun . " " . substr($tgl, 11);
	}

	function tanggal_8($tgl)
	{
		$tahun = substr($tgl, 0, 4);
		$bulan = substr($tgl, 5, 2);
		$tanggal = substr($tgl, 8, 2);

		return $tanggal . "/" . $this->bulan_2($bulan) . "/" . $tahun;
	}

	function tanggal_8t($tgl)
	{
		$tahun = substr($tgl, 0, 4);
		$bulan = substr($tgl, 5, 2);
		$tanggal = substr($tgl, 8, 2);

		return $tanggal . "/" . $this->bulan_2($bulan) . "/" . $tahun . " " . substr($tgl, 11);
	}

	function tanggal_9($tgl)
	{
		$tahun = substr($tgl, 0, 4);
		$bulan = substr($tgl, 5, 2);
		$tanggal = substr($tgl, 8, 2);

		return $tanggal . " " . $this->bulan_2($bulan) . " " . $tahun;
	}

	function tanggal_9t($tgl)
	{
		$tahun = substr($tgl, 0, 4);
		$bulan = substr($tgl, 5, 2);
		$tanggal = substr($tgl, 8, 2);

		return $tanggal . " " . $this->bulan_2($bulan) . " " . $tahun . " " . substr($tgl, 11);
	}

	function jam($jam)
	{
		return substr($jam, 11, 5);
	}

	function hari($hari)
	{
		$temp = "";
		switch ($hari) {
			case '1':
				$temp = 'Senin';
				break;
			case '2':
				$temp = 'Selasa';
				break;
			case '3':
				$temp = 'Rabu';
				break;
			case '4':
				$temp = 'Kamis';
				break;
			case '5':
				$temp = "Jum'at";
				break;
			case '6':
				$temp = 'Sabtu';
				break;
			case '7':
				$temp = 'Minggu';
				break;
			default:
				$temp = '';
				break;
		}
		return $temp;
	}

	function bulan_1($bulan)
	{
		$temp = "";
		switch ($bulan) {
			case '01':
				$temp = 'Jan';
				break;
			case '02':
				$temp = 'Feb';
				break;
			case '03':
				$temp = 'Mar';
				break;
			case '04':
				$temp = 'Apr';
				break;
			case '05':
				$temp = 'Mei';
				break;
			case '06':
				$temp = 'Jun';
				break;
			case '07':
				$temp = 'Jul';
				break;
			case '08':
				$temp = 'Ags';
				break;
			case '09':
				$temp = 'Sep';
				break;
			case '10':
				$temp = 'Okt';
				break;
			case '11':
				$temp = 'Nov';
				break;
			case '12':
				$temp = 'Des';
				break;
			default:
				$temp = '';
				break;
		}
		return $temp;
	}

	function bulan_2($bulan)
	{
		$namabulan = "";
		switch ($bulan) {
			case '01':
				$namabulan = 'Januari';
				break;
			case '02':
				$namabulan = 'Februari';
				break;
			case '03':
				$namabulan = 'Maret';
				break;
			case '04':
				$namabulan = 'April';
				break;
			case '05':
				$namabulan = 'Mei';
				break;
			case '06':
				$namabulan = 'Juni';
				break;
			case '07':
				$namabulan = 'Juli';
				break;
			case '08':
				$namabulan = 'Agustus';
				break;
			case '09':
				$namabulan = 'September';
				break;
			case '10':
				$namabulan = 'Oktober';
				break;
			case '11':
				$namabulan = 'November';
				break;
			case '12':
				$namabulan = 'Desember';
				break;
			default:
				$namabulan = '';
				break;
		}
		return $namabulan;
	}

	function foto_pengguna($foto = "")
	{
		$temp = "";
		if ($foto == "") {
			$temp = "default.png";
		} else {
			$temp = $foto;
		}
		return $temp;
	}

	function username_nama($username, $nama = "")
	{
		$temp = "";
		if ($nama != "") {
			$temp = $nama;
		} else {
			$temp = $username;
		}
		return $temp;
	}
}
