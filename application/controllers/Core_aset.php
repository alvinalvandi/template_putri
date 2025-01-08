<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Core_aset extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('apps_m'));
		$this->param = $this->lib->parameter();
	}

	public function permohonan_aset()
	{

		$this->lib->check_logged_in();
		$this->lib->check_access();

		$data = array();
		$this->load->view('core-aset/permohonan-aset-v', $data);
	}

	function get_data_permohonan_aset()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();
		$this->lib->check_method("POST");
		$kantor_pengguna = $this->session->userdata[$this->param['session']]['kantor'];

		$tabel = "permohonan_aset";
		$select = "*";
		$param = array(
			'column_order' => array(),
			'column_search' => array('nama_permohonan_aset', 'tgl_pengajuan'),
			'order' => array('tgl_pengajuan'),
			'order2' => array('ASC'),
		);
		//terima 1, ketika 1 tidak akan ditampilkan lagi di menu permohonan aset karena statusnya sudah berubah
		$where = "status_terima = 0 AND kantor = '" . $kantor_pengguna . "'";

		$join = array(
			'table' => array('master_kategori', 'master_kantor', 'master_jenis'),
			'kondisi' => array('permohonan_aset.kategori_permohonan_aset = master_kategori.id_kategori', 'permohonan_aset.kantor = master_kantor.kode_ktr', 'permohonan_aset.jenis_permohonan_aset = master_jenis.id_jenis'),
			'posisi' => array('left', 'left', 'left'),
		);

		$list = $this->apps_m->get_datatables($tabel, $param, $select, $where, $join);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $field) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $field->nama_permohonan_aset;
			$row[] = $field->nama_jenis;
			$row[] = $field->nama_kategori;
			$row[] = $field->tgl_pengajuan;
			$row[] = $field->estimasi_biaya;
			$row[] = $field->keterangan_permohonan;
			if ($field->file_upload == "") {
				$row[] = "-";
			} else {
				$row[] = "<a href=\"" . base_url() . "/assets/lampiran/upload/" . $field->file_upload . "\" target=\"_blank\" class=\"btn btn-icon btn-sm btn-light\"><i class=\"fas fa-file\"></i></a>";
			}

			$urlubah = base_url() . "core-aset/edit_permohonan_aset/" . $this->lib->encrypt_url($field->id_permohonan_aset);
			$urlhapus = "onclick='return hapus(\"" . $this->lib->encrypt_url($field->id_permohonan_aset) . "\")'";
			$urlterima = base_url() . "core-aset/terima_permohonan_aset/" . $this->lib->encrypt_url($field->id_permohonan_aset);
			$urltolak = "onclick='return tolak(\"" . $this->lib->encrypt_url($field->id_permohonan_aset) . "\")'";
			$urldetail = base_url() . "core-aset/detail_permohonan_aset/" . $this->lib->encrypt_url($field->id_permohonan_aset);

			$grup_pengguna = $this->session->userdata[$this->param['session']]['grup_pengguna'];
			$menu_kasie = array(41, 51, 99);
			$menu_pincab = array(42, 52, 99);
			$temp = "";
			if (in_array($grup_pengguna, $menu_pincab)) {
				$temp .= " <a href=\"" . $urlterima . "\" class=\"btn btn-icon btn-sm btn-info\" data-toggle=\"tooltip\" data-original-title=\"Terima\"><i class=\"fa fa-check\"></i></a>";
				$temp .= " <a href=\"#\" " . $urltolak . " class=\"btn btn-icon btn-sm btn-danger\" data-toggle=\"tooltip\" data-original-title=\"Tolak\"><i class=\"fas fa-times\"></i></a>";
				$temp .= " <a href=\"" . $urldetail . "\" class=\"btn btn-icon btn-sm btn-secondary\" data-toggle=\"tooltip\" data-original-title=\"Detail\"><i class=\"fa fa-info\"></i></a>";
			} elseif (in_array($grup_pengguna, $menu_kasie)) {
				$temp .= " <a href=\"" . $urlubah . "\" class=\"btn btn-icon btn-sm btn-warning\" data-toggle=\"tooltip\" data-original-title=\"Ubah\"><i class=\"fas fa-edit\"></i></a>";
				$temp .= " <a href=\"#\" " . $urlhapus . " class=\"btn btn-icon btn-sm btn-danger\" data-toggle=\"tooltip\" data-original-title=\"Hapus\"><i class=\"fas fa-trash\"></i></a>";
				$temp .= " <a href=\"" . $urldetail . "\" class=\"btn btn-icon btn-sm btn-secondary\" data-toggle=\"tooltip\" data-original-title=\"Detail\"><i class=\"fa fa-info\"></i></a>";
			} else {
				$temp .= " <a href=\"" . $urldetail . "\" class=\"btn btn-icon btn-sm btn-secondary\" data-toggle=\"tooltip\" data-original-title=\"Detail\"><i class=\"fa fa-info\"></i></a>";
			}

			$row[] = $temp;

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->apps_m->count_all($tabel, $select, $where, $join),
			"recordsFiltered" => $this->apps_m->count_filtered($tabel, $param, $select, $where, $join),
			"data" => $data,
		);
		//output dalam format JSON
		echo json_encode($output);
	}

	public function get_permohonan_aset()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();

		$id = $this->uri->segment(3);
		$tabel = "permohonan_aset";

		$key = "id_permohonan_aset";
		$value = $id;
		$where = $this->lib->sql_encrypt_url($key, $value);

		$select = "*, " . $this->lib->sql_select_encrypt($key, "id_permohonan_aset");
		$cek = $this->apps_m->get_data($tabel, $select, $where)->result_array();

		if (count($cek) != 0) {
			$result = $this->apps_m->get_data($tabel, $select, $where)->row();
			echo json_encode($result);
		} else {
			$respone = array("status" => FALSE, "pesan" => "Data tidak ditemukan.", "tipe_pesan" => "warning");
			echo json_encode($respone);
			echo "<script>self.history.back();</script>";
		}
	}

	public function add_permohonan_aset()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();

		//0 = kasie
		//1 = pincab
		//2 = pusat
		$kantor_pengguna = $this->session->userdata[$this->param['session']]['kantor'];
		if (isset($_POST['btn-simpan'])) {

			$tabel = "permohonan_aset";
			$nama_aset = $this->input->post('nama_aset');
			$jenis_permohonan_aset = $this->input->post('jenis_permohonan_aset');
			$kategori_permohonan_aset = $this->input->post('kategori_permohonan_aset');
			$tgl_pengajuan = date("Y-m-d H:i:s");
			$keterangan_permohonan = $this->input->post('keterangan_permohonan');
			$estimasi_biaya = $this->input->post('estimasi_biaya');
			$status_terima = "0";
			$temp_pengguna = $this->session->userdata[$this->param['session']]['nama'];
			$grup_pengguna = $this->session->userdata[$this->param['session']]['grup_pengguna'];

			$data1 = array(
				'nama_permohonan_aset' => $nama_aset,
				'jenis_permohonan_aset' => $jenis_permohonan_aset,
				'kategori_permohonan_aset' => $kategori_permohonan_aset,
				'kantor' => $kantor_pengguna,
				'tgl_pengajuan' => $tgl_pengajuan,
				'keterangan_permohonan' => $keterangan_permohonan,
				'estimasi_biaya' => $estimasi_biaya,
				'status_terima' => $status_terima,
				'pengguna' => $temp_pengguna,
			);


			if ($_FILES['file_pdf']['name'] == "") {
				$data2 = array();
			} else {
				if ($_FILES['file_pdf']['type'] == "application/pdf") {
					$typefile = str_replace("application/", ".", $_FILES['file_pdf']['type']);
				}

				$tempfile = "permohonan-aset-" . $kantor_pengguna . "-" . substr($tgl_pengajuan, 0, 10) . "-" . date('His') . $typefile;
				$file = $this->lib->upload_pdf($tempfile);
				$this->load->library('upload', $file);

				if ($this->upload->do_upload('file_pdf')) {
					$file_info = $this->upload->data();
					$file_name = $file_info['file_name'];

					$data2 = array(
						'file_upload' => strtolower($file_name),
					);
				} else {
					// echo $this->upload->display_errors(); //untuk melihat penyebab eror
					$this->session->set_flashdata("pesan", "ada");
					$this->session->set_flashdata("pesan_isi", "Kesalahan Upload File.");
					$this->session->set_flashdata("pesan_tipe", "warning");
					echo "<script>self.history.back();</script>";
					exit;
				}
			}

			$komen_permohonan = $this->input->post('komen');
			$waktu = date("Y-m-d H:i:s");

			$data = array_merge($data1, $data2);
			$result = $this->apps_m->insert_data($tabel, $data);
			$temp_id_permohonan_aset = $this->db->insert_id();
			if ($result == TRUE) {

				$this->apps_m->insert_data("komen_permohonan_aset", array('permohonan_aset' => $temp_id_permohonan_aset, 'isi_komen' => $komen_permohonan, 'waktu' => $waktu, 'pengguna_komen' => $temp_pengguna, 'grup_jabatan' => $grup_pengguna));
				// Log
				$operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
				$pengguna = $operator;
				$aksi = "Tambah Permohonan Aset";
				$keterangan = "nama aset : <b>" . $nama_aset . "</b> dengan estimasi biaya <b>" . $estimasi_biaya . "</b>";
				$this->lib->aksi_log($pengguna, $aksi, $keterangan);
				// End Log

				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Data berhasil ditambahkan.");
				$this->session->set_flashdata("pesan_tipe", "success");
				redirect('/core-aset/permohonan-aset');
			} else {
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Terjadi kesalahan inputan data!");
				$this->session->set_flashdata("pesan_tipe", "warning");
				echo "<script>self.history.back();</script>";
			}
		} else {
			//data select option untuk ditampilkan di form
			$data = array(
				'master_jenis' => $this->apps_m->get_data("master_jenis", "*", "", "id_jenis ASC")->result(),
				'master_kategori' => $this->apps_m->get_data("master_kategori", "*", "", "id_kategori ASC")->result(),
			);
			$this->load->view('core-aset/add-permohonan-aset-v', $data);
		}
	}

	public function edit_permohonan_aset()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();
		$kantor_pengguna = $this->session->userdata[$this->param['session']]['kantor'];

		$tabel = "permohonan_aset";
		if (isset($_POST['btn-ubah'])) {

			$idpermohonan_aset = $this->input->post('idpermohonan_aset');

			$key = "id_permohonan_aset";
			$value = $idpermohonan_aset;
			$select = "*";
			$whereid = $this->lib->sql_encrypt_url($key, $value);
			$cek = $this->apps_m->get_data($tabel, $select, $whereid)->result_array();

			if (count($cek) == 0) {
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Data tidak ditemukan.");
				$this->session->set_flashdata("pesan_tipe", "warning");
				echo "<script>self.history.back();</script>";
				exit;
			}

			$id_permohonan_aset = $cek[0]['id_permohonan_aset'];
			$nama_aset = $this->input->post('nama_aset');
			$jenis_permohonan_aset = $this->input->post('jenis_permohonan_aset');
			$kategori_permohonan_aset = $this->input->post('kategori_permohonan_aset');
			$tgl_perubahan = date("Y-m-d H:i:s");
			$keterangan_permohonan = $this->input->post('keterangan_permohonan');
			$estimasi_biaya = $this->input->post('estimasi_biaya');
			$temp_pengguna = $this->session->userdata[$this->param['session']]['id_pengguna'];
			$pengguna_pengubah = $this->apps_m->get_data("pengguna", "nama", "id_pengguna = $temp_pengguna")->result_array();
			$komen = $this->input->post('komen');

			$datakomen = $this->apps_m->get_data("komen_permohonan_aset", "*", "permohonan_aset = '" . $id_permohonan_aset . "' AND status_komen = '1'", "")->result_array();

			$data1 = array(
				'nama_permohonan_aset' => $nama_aset,
				'jenis_permohonan_aset' => $jenis_permohonan_aset,
				'kategori_permohonan_aset' => $kategori_permohonan_aset,
				'tgl_perubahan' => $tgl_perubahan,
				'keterangan_permohonan' => $keterangan_permohonan,
				'estimasi_biaya' => $estimasi_biaya,
				'pengubah' => $pengguna_pengubah[0]['nama']
			);


			if ($_FILES['file_pdf']['name'] == "") {
				$data2 = array();
			} else {
				if ($_FILES['file_pdf']['type'] == "application/pdf") {
					$typefile = str_replace("application/", ".", $_FILES['file_pdf']['type']);
				}

				$tempfile = "permohonan-aset-" . $kantor_pengguna . "-" . substr($tgl_perubahan, 0, 10) . "-" . date('His') . $typefile;
				$file = $this->lib->upload_pdf($tempfile);
				$this->load->library('upload', $file);


				if ($this->upload->do_upload('file_pdf')) {
					$file_pdf_old = $cek[0]['file_upload'];
					@unlink("./assets/lampiran/upload/" . $file_pdf_old);

					$file_info = $this->upload->data();
					$file_name = $file_info['file_name'];

					$data2 = array(
						'file_upload' => strtolower($file_name),
					);
				} else {
					// echo $this->upload->display_errors(); //untuk melihat penyebab eror
					$this->session->set_flashdata("pesan", "ada");
					$this->session->set_flashdata("pesan_isi", "Kesalahan Upload File.");
					$this->session->set_flashdata("pesan_tipe", "warning");
					echo "<script>self.history.back();</script>";
					exit;
				}
			}

			$data = array_merge($data1, $data2);
			$where = "id_permohonan_aset = '" . $id_permohonan_aset . "'";
			$result = $this->apps_m->update_data($tabel, $data, $where);
			if ($result == TRUE) {
				if ($komen != $datakomen[0]['isi_komen']) {
					$this->apps_m->update_data("komen_permohonan_aset", array('isi_komen' => $komen, 'waktu' => date("Y-m-d H:i:s")), "permohonan_aset = '" . $id_permohonan_aset . "' AND grup_jabatan IN ('99', '41','51')");
				}
				// Log
				$operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
				$pengguna = $operator;
				$aksi = "Ubah Data Permohonan Aset";
				$keterangan = "ID aset : <b>" . $nama_aset . "</b> dengan estimasi biaya : <b>" . $estimasi_biaya . "</b>";
				$this->lib->aksi_log($pengguna, $aksi, $keterangan);
				// End Log

				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Data berhasil diubah.");
				$this->session->set_flashdata("pesan_tipe", "success");
				redirect('/core-aset/permohonan-aset');
			} else {
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Terjadi kesalahan inputan data!");
				$this->session->set_flashdata("pesan_tipe", "warning");
				echo "<script>self.history.back();</script>";
				exit;
			}
		} else {
			$id = $this->uri->segment(3);
			$key = "id_permohonan_aset";
			$value = $id;
			$select = "*";
			$where = $this->lib->sql_encrypt_url($key, $value);
			$join =  array(
				'table' => array('komen_permohonan_aset'),
				'kondisi' => array('permohonan_aset.id_permohonan_aset = komen_permohonan_aset.permohonan_aset AND komen_permohonan_aset.status_komen=1'),
				'posisi' => array('left'),
			);
			$result = $this->apps_m->get_data($tabel, $select, $where, '', $join)->result_array();
			$id_permohonan_aset = $result[0]['id_permohonan_aset'];

			if (count($result) != 0) {
				$data = array(
					'permohonan_aset' => $result,
					'master_jenis' => $this->apps_m->get_data("master_jenis", "*", "", "id_jenis ASC")->result(),
					'master_kategori' => $this->apps_m->get_data("master_kategori", "*", "", "id_kategori ASC")->result(),
				);
				$this->load->view('core-aset/edit-permohonan-aset-v', $data);
			} else {
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Data tidak ditemukan.");
				$this->session->set_flashdata("pesan_tipe", "warning");
				redirect('/core-aset/permohonan-aset');
			}
		}
	}

	public function terima_permohonan_aset()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();

		$tabel = "permohonan_aset";
		if (isset($_POST['btn-terima'])) {

			$idpermohonan_aset = $this->input->post('idpermohonan_aset');

			$key = "id_permohonan_aset";
			$value = $idpermohonan_aset;
			$select = "*";
			$whereid = $this->lib->sql_encrypt_url($key, $value);
			$result = $this->apps_m->get_data($tabel, $select, $whereid)->result_array();

			if (count($result) == 0) {
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Data tidak ditemukan.");
				$this->session->set_flashdata("pesan_tipe", "warning");
				echo "<script>self.history.back();</script>";
				exit;
			}

			$id_permohonan_aset = $result[0]['id_permohonan_aset'];
			$tgl_terima = date("Y-m-d H:i:s");
			$komen = $this->input->post('komen');
			$final_biaya_cabang = $this->input->post('final_biaya_cabang');
			//cek final biaya lbh dari 3 jt
			if ($final_biaya_cabang > $this->param['dana-limit']) {
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Melebihi Limit Cabang!");
				$this->session->set_flashdata("pesan_tipe", "warning");
				echo "<script>self.history.back();</script>";
				exit;
			}
			$waktu = date("Y-m-d H:i:s");
			$status_terima = "1";

			$temp_pengguna = $this->session->userdata[$this->param['session']]['id_pengguna'];
			$grup_pengguna = $this->session->userdata[$this->param['session']]['grup_pengguna'];
			$pengguna_penginput = $this->apps_m->get_data("pengguna", "nama", "id_pengguna = $temp_pengguna")->result_array();

			$data = array(
				'tgl_terima' => $tgl_terima,
				'final_biaya_cabang' => $final_biaya_cabang,
				'status_terima' => $status_terima,
			);

			$where = "id_permohonan_aset = '" . $id_permohonan_aset . "'";
			$result = $this->apps_m->update_data($tabel, $data, $where);
			if ($result == TRUE) {
				$this->apps_m->insert_data("komen_permohonan_aset", array('permohonan_aset' => $id_permohonan_aset, 'isi_komen' => $komen, 'waktu' => $waktu, 'pengguna_komen' => $pengguna_penginput[0]['nama'], 'grup_jabatan' => $grup_pengguna));
				// Log
				$operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
				$pengguna = $operator;
				$aksi = "Menerima Permohonan Aset";
				$keterangan = "a/n : <b>" . $pengguna_penginput[0]['nama'] . "</b> dengan id permohonan aset : <b>" . $id_permohonan_aset . "</b>";
				$this->lib->aksi_log($pengguna, $aksi, $keterangan);
				// End Log

				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Permohonan Telah Disetujui.");
				$this->session->set_flashdata("pesan_tipe", "success");
				redirect('/core-aset/permohonan-aset');
			} else {
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Terjadi kesalahan inputan data!");
				$this->session->set_flashdata("pesan_tipe", "warning");
				echo "<script>self.history.back();</script>";
				exit;
			}
		}

		if (isset($_POST['btn-ajukan'])) {

			//pengajuan ke pusat
			$idpermohonan_aset = $this->input->post('idpermohonan_aset');

			$key = "id_permohonan_aset";
			$value = $idpermohonan_aset;
			$select = "*";
			$whereid = $this->lib->sql_encrypt_url($key, $value);
			$result = $this->apps_m->get_data($tabel, $select, $whereid)->result_array();

			if (count($result) == 0) {
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Data tidak ditemukan.");
				$this->session->set_flashdata("pesan_tipe", "warning");
				echo "<script>self.history.back();</script>";
				exit;
			}

			$id_permohonan_aset = $result[0]['id_permohonan_aset'];
			$tgl_terima = date("Y-m-d H:i:s");
			$komen = $this->input->post('komen');
			$final_biaya_cabang = $this->input->post('final_biaya_cabang');
			//cek final biaya bila ajukan ke pusat namun kurang dari 3 jt
			if ($final_biaya_cabang < $this->param['dana-limit']) {
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Pengajuan ke Pusat harus melebihi atau sama dengan 3 Juta");
				$this->session->set_flashdata("pesan_tipe", "warning");
				echo "<script>self.history.back();</script>";
				exit;
			}
			$waktu = date("Y-m-d H:i:s");
			//ke pusat
			$status_terima = "3";

			$temp_pengguna = $this->session->userdata[$this->param['session']]['id_pengguna'];
			$grup_pengguna = $this->session->userdata[$this->param['session']]['grup_pengguna'];
			$pengguna_penginput = $this->apps_m->get_data("pengguna", "nama", "id_pengguna = $temp_pengguna")->result_array();

			$data = array(
				'tgl_terima' => $tgl_terima,
				'final_biaya_cabang' => $final_biaya_cabang,
				'status_terima' => $status_terima,
			);

			$where = "id_permohonan_aset = '" . $id_permohonan_aset . "'";
			$result = $this->apps_m->update_data($tabel, $data, $where);
			if ($result == TRUE) {
				$this->apps_m->insert_data("komen_permohonan_aset", array('permohonan_aset' => $id_permohonan_aset, 'isi_komen' => $komen, 'waktu' => $waktu, 'pengguna_komen' => $pengguna_penginput[0]['nama'], 'grup_jabatan' => $grup_pengguna));
				// Log
				$operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
				$pengguna = $operator;
				$aksi = "Mengajukan Permohonan Aset Pusat";
				$keterangan = "a/n : <b>" . $pengguna_penginput[0]['nama'] . "</b> dengan id permohonan aset : <b>" . $id_permohonan_aset . "</b>";
				$this->lib->aksi_log($pengguna, $aksi, $keterangan);
				// End Log

				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Permohonan Telah Disetujui.");
				$this->session->set_flashdata("pesan_tipe", "success");
				redirect('/core-aset/permohonan-aset');
			} else {
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Terjadi kesalahan inputan data!");
				$this->session->set_flashdata("pesan_tipe", "warning");
				echo "<script>self.history.back();</script>";
				exit;
			}
		} else {
			$kantor_pengguna = $this->session->userdata[$this->param['session']]['kantor'];
			$id = $this->uri->segment(3);
			$key = "id_permohonan_aset";
			$value = $id;
			$select = "*";
			$where = $this->lib->sql_encrypt_url($key, $value);
			$result = $this->apps_m->get_data($tabel, $select, $where . "AND kantor = '" . $kantor_pengguna . "'")->result_array();

			if (count($result) != 0) {
				$data = array(
					'permohonan_aset' => $result,
				);
				$this->load->view('core-aset/terima-permohonan-aset-v', $data);
			} else {
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Data tidak ditemukan.");
				$this->session->set_flashdata("pesan_tipe", "warning");
				redirect('/core-aset/permohonan-aset');
			}
		}
	}

	//status komen 1 terima, 2 tolak oleh pincab atau pusat, 99 dihapus oleh kasie
	//pincab
	public function tolak_permohonan_aset()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();
		$this->lib->check_method("POST");

		$temp_pengguna = $this->session->userdata[$this->param['session']]['id_pengguna'];
		$pengguna_penginput = $this->apps_m->get_data("pengguna", "nama", "id_pengguna = $temp_pengguna")->result_array();

		$tgl_tolak = date("Y-m-d H:i:s");
		$status_terima = 2;
		$tabel = "permohonan_aset";
		$id = $this->input->post('id_permohonan_aset');
		$key = "id_permohonan_aset";
		$value = $id;
		$where = $this->lib->sql_encrypt_url($key, $value);
		$select = "*";
		$cek = $this->apps_m->get_data($tabel, $select, $where)->result_array();

		$data = array(
			'status_terima' => $status_terima,
			'tgl_terima' => $tgl_tolak,
			'penolak' => $pengguna_penginput[0]['nama'],
			'file_upload' => ""
		);

		if (count($cek) != 0) {
			$result = $this->apps_m->update_data($tabel, $data, $where);
			$file_pdf = $cek[0]['file_upload'];
			@unlink("./assets/lampiran/upload/" . $file_pdf);

			if ($result == TRUE) {
				$this->apps_m->update_data("komen_permohonan_aset", array('status_komen' => 2), "permohonan_aset = '" . $cek[0]['id_permohonan_aset'] . "'");
				// Log
				$operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
				$pengguna = $operator;
				$aksi = "Tolak Data Permohonan Aset";
				$keterangan = "a/n <b>" . $pengguna . "</b> dengan id permohonan aset<b>" . $cek[0]['id_permohonan_aset'] . "</b>";
				$this->lib->aksi_log($pengguna, $aksi, $keterangan);
				// End Log
				$respone = array("status" => TRUE, "pesan" => "Data Permohonan sudah di tolak!", "tipe_pesan" => "success");
				echo json_encode($respone);
			} else {
				$respone = array("status" => FALSE, "pesan" => "Terjadi kesalahan!", "tipe_pesan" => "warning");
				echo json_encode($respone);
			}
		} else {
			$respone = array("status" => FALSE, "pesan" => "Data tidak ditemukan.", "tipe_pesan" => "warning");
			echo json_encode($respone);
		}
	}

	//kasie
	//status terima 99 untuk data permohonan aset yang sudah di delete
	public function delete_permohonan_aset()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();
		$this->lib->check_method("POST");

		$tabel = "permohonan_aset";
		$id = $this->input->post('id_permohonan_aset');
		$status_komen = "99";
		$status_terima = "99";
		$key = "id_permohonan_aset";
		$value = $id;
		$where = $this->lib->sql_encrypt_url($key, $value);
		$select = "*";
		$cek = $this->apps_m->get_data($tabel, $select, $where)->result_array();

		if (count($cek) != 0) {
			$file_pdf = $cek[0]['file_upload'];
			@unlink("./assets/lampiran/upload/" . $file_pdf);

			$result = $this->apps_m->update_data($tabel, array('status_terima' => $status_terima, 'file_upload' => ""), $where);
			if ($result == TRUE) {
				$this->apps_m->update_data("komen_permohonan_aset", array('status_komen' => $status_komen), "permohonan_aset = '" . $cek[0]['id_permohonan_aset'] . "'");
				// Log
				$operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
				$pengguna = $operator;
				$aksi = "Hapus Data Permohonan Aset";
				$keterangan = "a/n <b>" . $pengguna . "</b> dengan id permohonan aset<b>" . $id . "</b>";
				$this->lib->aksi_log($pengguna, $aksi, $keterangan);
				// End Log
				$respone = array("status" => TRUE, "pesan" => "Data Permohonan sudah di hapus!", "tipe_pesan" => "success");
				echo json_encode($respone);
			} else {
				$respone = array("status" => FALSE, "pesan" => "Terjadi kesalahan!", "tipe_pesan" => "warning");
				echo json_encode($respone);
			}
		} else {
			$respone = array("status" => FALSE, "pesan" => "Data tidak ditemukan.", "tipe_pesan" => "warning");
			echo json_encode($respone);
		}
	}

	public function detail_permohonan_aset()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();

		$tabel = "permohonan_aset";
		$id = $this->uri->segment(3);
		$key = "id_permohonan_aset";
		$value = $id;
		$select = "*";
		$where = $this->lib->sql_encrypt_url($key, $value);
		$join = array(
			'table' => array('master_kategori', 'master_jenis', 'master_kantor'),
			'kondisi' => array('permohonan_aset.kategori_permohonan_aset = master_kategori.id_kategori', 'permohonan_aset.jenis_permohonan_aset = master_jenis.id_jenis', 'permohonan_aset.kantor = master_kantor.kode_ktr'),
			'posisi' => array('left', 'left', 'left'),
		);
		$result = $this->apps_m->get_data($tabel, $select, $where, '', $join)->result_array();
		if (count($result) != 0) {
			$data = array(
				'permohonan_aset' => $result,
				'komen' => $this->apps_m->get_data("komen_permohonan_aset", "*", "permohonan_aset = '" . $result[0]['id_permohonan_aset'] . "' AND grup_jabatan IN('99','41', '42','51', '52', '53')", "")->result_array(),
			);
			$this->load->view('core-aset/detail-permohonan-aset-v', $data);
		} else {
			$this->session->set_flashdata("pesan", "ada");
			$this->session->set_flashdata("pesan_isi", "Data tidak ditemukan.");
			$this->session->set_flashdata("pesan_tipe", "warning");
			redirect('/core-aset/permohonan-aset');
		}
	}

	public function finish_permohonan_aset()
	{

		$this->lib->check_logged_in();
		$this->lib->check_access();

		$data = array();
		$this->load->view('core-aset/finish-permohonan-aset-v', $data);
	}

	function get_data_finish_permohonan_aset()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();
		$this->lib->check_method("POST");
		$kantor_pengguna = $this->session->userdata[$this->param['session']]['kantor'];

		$tabel = "permohonan_aset";
		$select = "*";
		$param = array(
			'column_order' => array(),
			'column_search' => array('nama_permohonan_aset'),
			'order' => array('tgl_pengajuan'),
			'order2' => array('ASC'),
		);
		//terima 1, ketika 1 tidak akan ditampilkan lagi di menu permohonan aset karena statusnya sudah berubah
		$where = "status_terima = 1 AND status_ditambahkan = 0 AND kantor = '" . $kantor_pengguna . "'";

		// $join = array();
		$join = array(
			'table' => array('master_kategori', 'master_kantor', 'master_jenis'),
			'kondisi' => array('permohonan_aset.kategori_permohonan_aset = master_kategori.id_kategori', 'permohonan_aset.kantor = master_kantor.kode_ktr', 'permohonan_aset.jenis_permohonan_aset = master_jenis.id_jenis'),
			'posisi' => array('left', 'left', 'left'),
		);

		$list = $this->apps_m->get_datatables($tabel, $param, $select, $where, $join);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $field) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $field->nama_permohonan_aset;
			$row[] = $field->nama_jenis;
			$row[] = $field->nama_kategori;
			$row[] = $field->tgl_pengajuan;
			$row[] = $field->final_biaya_cabang;
			$row[] = $field->keterangan_permohonan;

			$urltambah = base_url() . "core-aset/tambah_aset/" . $this->lib->encrypt_url($field->id_permohonan_aset);
			$urldetail = base_url() . "core-aset/detail_permohonan_aset/" . $this->lib->encrypt_url($field->id_permohonan_aset);

			$grup_pengguna = $this->session->userdata[$this->param['session']]['grup_pengguna'];
			//staff umum, kasie umum, admin
			$menu_bag_umum = array(51, 52, 99);
			$temp = "";
			if (in_array($grup_pengguna, $menu_bag_umum)) {
				$temp .= " <a href=\"" . $urltambah . "\" class=\"btn btn-icon btn-sm btn-primary\" data-toggle=\"tooltip\" data-original-title=\"Tambahkan Data Aset\"><i class=\"fa fa-plus\"></i></a>";
				$temp .= " <a href=\"" . $urldetail . "\" class=\"btn btn-icon btn-sm btn-secondary\" data-toggle=\"tooltip\" data-original-title=\"Detail\"><i class=\"fa fa-info\"></i></a>";
			} else {
				$temp .= " <a href=\"" . $urldetail . "\" class=\"btn btn-icon btn-sm btn-secondary\" data-toggle=\"tooltip\" data-original-title=\"Detail\"><i class=\"fa fa-info\"></i></a>";
			}

			$row[] = $temp;

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->apps_m->count_all($tabel, $select, $where, $join),
			"recordsFiltered" => $this->apps_m->count_filtered($tabel, $param, $select, $where, $join),
			"data" => $data,
		);
		//output dalam format JSON
		echo json_encode($output);
	}

	public function get_finish_permohonan_aset()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();

		$id = $this->uri->segment(3);
		$tabel = "permohonan_aset";

		$key = "id_permohonan_aset";
		$value = $id;
		$where = $this->lib->sql_encrypt_url($key, $value);

		$select = "*, " . $this->lib->sql_select_encrypt($key, "id_permohonan_aset");
		$cek = $this->apps_m->get_data($tabel, $select, $where)->result_array();

		if (count($cek) != 0) {
			$result = $this->apps_m->get_data($tabel, $select, $where)->row();
			echo json_encode($result);
		} else {
			$respone = array("status" => FALSE, "pesan" => "Data tidak ditemukan.", "tipe_pesan" => "warning");
			echo json_encode($respone);
			echo "<script>self.history.back();</script>";
		}
	}

	public function tambah_aset()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();

		$status_aset = "0";
		$status_ditambahkan = 1;
		$status_generate = "1";
		$kode_awal_aset = $this->apps_m->get_data("aset", "kode_aset", "", "id_aset DESC")->result_array();
		$last_id2 = $kode_awal_aset[0]['kode_aset'];
		$last_id1 = explode("/", $last_id2);
		$last_id = intval($last_id1[0]) + 1;

		$tabel = "permohonan_aset";
		if (isset($_POST['btn-simpan'])) {

			$id_permohonan_aset = $this->input->post('idpermohonan_aset');

			$key = "id_permohonan_aset";
			$value = $id_permohonan_aset;
			$select = "*";
			$whereid = $this->lib->sql_encrypt_url($key, $value);
			$result = $this->apps_m->get_data($tabel, $select, $whereid)->result_array();


			if (count($result) == 0) {
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Data tidak ditemukan.");
				$this->session->set_flashdata("pesan_tipe", "warning");
				echo "<script>self.history.back();</script>";
				exit;
			}

			$temp_id = $result[0]['id_permohonan_aset'];
			$nama_aset = $this->input->post('nama_aset');
			$kode_aset = $this->input->post('kode_aset');
			$jenis_aset = $this->input->post('jenis_aset');
			$kategori_aset = $this->input->post('kategori_aset');
			$kantor = $this->input->post('kantor');
			$lokasi = $this->input->post('lokasi');
			$kondisi_aset = $this->input->post('kondisi_aset');
			$tgl_perolehan = $this->input->post('tgl_perolehan');
			$harga_perolehan = $this->input->post('harga_perolehan');
			$status_kondisi = $this->apps_m->get_data("master_kondisi", "status_kondisi", "id_kondisi = $kondisi_aset")->result_array();

			if ($status_kondisi[0]['status_kondisi'] != 0) {
				$status_aset = $status_kondisi[0]['status_kondisi'];
			}

			$wherekodeaset = "kode_aset = '" . $kode_aset . "'";
			$cekkodeaset = $this->apps_m->get_data("aset", "*", $wherekodeaset)->result_array();
			if (count($cekkodeaset) != 0) {
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Kode Aset Sudah Digunakan");
				$this->session->set_flashdata("pesan_tipe", "warning");
				echo "<script>self.history.back();</script>";
				exit;
			}

			$data = array(
				'nama_aset' => $nama_aset,
				'kode_aset' => $kode_aset,
				'jenis_aset' => $jenis_aset,
				'kategori_aset' => $kategori_aset,
				'kantor' => $kantor,
				'lokasi' => $lokasi,
				'kondisi_aset' => $kondisi_aset,
				'tgl_perolehan' => $tgl_perolehan,
				'harga_perolehan' => $harga_perolehan,
				'status_aset' => $status_aset,
				'status_generate' => $status_generate,
			);

			$result = $this->apps_m->insert_data("aset", $data);
			if ($result == TRUE) {
				$inserted_id = $this->db->insert_id();
				//link_qr tu link detail yang nanti merujuk ke data detail asetnya
				$link_qr = base_url() . "aset/detail_aset_qr/" . $this->lib->encrypt_url($inserted_id);
				//content file tu letak file gambar qr di direktori
				$content = $this->lib->generate_qrcode($link_qr);
				$this->apps_m->insert_data("master_generate", array('aset' => $inserted_id, 'file_qr' => $link_qr, 'content' => $content['file']));
				$this->apps_m->update_data("permohonan_aset", array('status_ditambahkan' => $status_ditambahkan), "id_permohonan_aset = '" . $temp_id . "'");
				// Log
				$operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
				$pengguna = $operator;
				$aksi = "Tambah Data Aset Dari Permohonan Aset";
				$keterangan = "Nama aset : <b>" . $nama_aset . "</b> dengan harga perolehan : <b>" . $harga_perolehan . "</b>";
				$this->lib->aksi_log($pengguna, $aksi, $keterangan);
				// End Log

				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Data Aset berhasil ditambah.");
				$this->session->set_flashdata("pesan_tipe", "success");
				redirect('/aset/aktif');
			} else {
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Terjadi kesalahan inputan data!");
				$this->session->set_flashdata("pesan_tipe", "warning");
				echo "<script>self.history.back();</script>";
				exit;
			}
		} else {
			$id = $this->uri->segment(3);
			$key = "id_permohonan_aset";
			$value = $id;
			$select = "*";
			$where = $this->lib->sql_encrypt_url($key, $value);
			$result = $this->apps_m->get_data($tabel, $select, $where, '')->result_array();

			if (count($result) != 0) {
				$data = array(
					'aset' => $result,
					'last_id' => $last_id,
					'master_jenis' => $this->apps_m->get_data("master_jenis", "*", "", "id_jenis ASC")->result(),
					'master_kategori' => $this->apps_m->get_data("master_kategori", "*", "", "id_kategori ASC")->result(),
					'master_kondisi' => $this->apps_m->get_data("master_kondisi", "*", "", "id_kondisi ASC")->result(),
					'master_kantor' => $this->apps_m->get_data("master_kantor", "*", "", "id_kantor ASC")->result(),
				);
				$this->load->view('core-aset/tambah-aset-v', $data);
			} else {
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Data tidak ditemukan.");
				$this->session->set_flashdata("pesan_tipe", "warning");
				redirect('/core-aset/permohonan-aset-v');
			}
		}
	}

	//mutasi aset
	//nampilin data aset di form tambah mutasi
	public function mutasi()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();
		$join = array(
			'table' => array('master_kantor', 'master_lokasi'),
			'kondisi' => array('aset.kantor = master_kantor.kode_ktr', 'aset.lokasi = master_lokasi.id_lokasi'),
			'posisi' => array('left', 'left'),
		);
		$where = "aset.status_aset <> 99";
		$urut = "aset.kantor ASC";
		$data = array(
			'data_aset' => $this->apps_m->get_data("aset", "*", $where, $urut, $join)->result(),
			'master_kantor' => $this->apps_m->get_data("master_kantor", "*", "", "kode_ktr ASC")->result(),
		);
		$this->load->view('core-aset/mutasi-v', $data);
	}

	//tampilan awal menu mutasi aset
	function get_data_mutasi()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();
		$this->lib->check_method("POST");

		$tabel = "mutasi";
		$select = "*";
		$param = array(
			'column_order' => array(),
			'column_search' => array('nama_aset', 'lokasi_baru', 'kantor_baru', 'tgl_mutasi'),
			'order' => array('tgl_mutasi'),
			'order2' => array('DESC'),
		);
		$where = "";
		$join = array(
			'table' => array('aset', 'master_kantor', 'master_lokasi'),
			'kondisi' => array('mutasi.aset = aset.id_aset', 'mutasi.kantor_baru = master_kantor.kode_ktr', 'mutasi.lokasi_baru = master_lokasi.id_lokasi'),
			'posisi' => array('left', 'left', 'left'),
		);

		$list = $this->apps_m->get_datatables($tabel, $param, $select, $where, $join);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $field) {

			$data_kantor = $this->apps_m->get_data("master_kantor", "*", "kode_ktr = '" . $field->kantor_awal . "'")->result_array();
			if (count($data_kantor) == 0) {
				$nama_kantor = "";
			} else {
				$nama_kantor = $data_kantor[0]['nama_kantor'];
			}

			$data_lokasi = $this->apps_m->get_data("master_lokasi", "*", "id_lokasi = '" . $field->lokasi_awal . "'")->result_array();
			if (count($data_lokasi) == 0) {
				$nama_lokasi = "";
			} else {
				$nama_lokasi = $data_lokasi[0]['nama_lokasi'];
			}


			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $field->nama_aset;
			$row[] = $nama_lokasi;
			$row[] = $nama_kantor;
			$row[] = $field->nama_lokasi;
			$row[] = $field->nama_kantor;
			$row[] = $field->tgl_mutasi;

			$urlubah = "onclick='return ubah(\"" . $this->lib->encrypt_url($field->id_mutasi) . "\")'";
			$urlhapus = "onclick='return hapus(\"" . $this->lib->encrypt_url($field->id_mutasi) . "\")'";

			$temp = "";
			$temp .= "<a href=\"#\" " . $urlubah . " class=\"btn btn-icon btn-sm btn-warning\" data-toggle=\"tooltip\" data-original-title=\"Ubah\"><i class=\"fas fa-edit\"></i></a>";
			$temp .= " <a href=\"#\" " . $urlhapus . " class=\"btn btn-icon btn-sm btn-danger\" data-toggle=\"tooltip\" data-original-title=\"Hapus\"><i class=\"fas fa-trash\"></i></a>";
			$row[] = $temp;

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->apps_m->count_all($tabel, $select, $where, $join),
			"recordsFiltered" => $this->apps_m->count_filtered($tabel, $param, $select, $where, $join),
			"data" => $data,
		);
		//output dalam format JSON
		echo json_encode($output);
	}

	//data aset untuk dimunculkan di form saat penambahan data mutasi
	function get_data_aset()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();
		$this->lib->check_method("POST");

		$id_aset = $this->input->post('id_aset', TRUE);
		$aset = $this->apps_m->get_data("aset", "*", "id_aset = '" . $id_aset . "'")->result_array();
		$kantor_awal = $this->apps_m->get_data("master_kantor", "nama_kantor", "kode_ktr = '" . $aset[0]['kantor'] . "'")->result_array();
		$lokasi_awal = $this->apps_m->get_data("master_lokasi", "nama_lokasi", "id_lokasi = '" . $aset[0]['lokasi'] . "'")->result_array();
		$data = array(
			'kantor_awal' => $kantor_awal[0]['nama_kantor'],
			'lokasi_awal' => $lokasi_awal[0]['nama_lokasi']
		);
		echo json_encode($data);
	}

	//data lokasi untuk dimunculkan di form saat penambahan data mutasi
	function get_data_lokasi()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();
		$this->lib->check_method("POST");

		$kode_ktr = $this->input->post('kode_ktr', TRUE);
		$data = $this->apps_m->get_data("master_lokasi", "*", "kantor = '" . $kode_ktr . "'")->result_array();
		echo json_encode($data);
	}

	//respon dari form tambah mutasi
	public function add_mutasi()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();
		$this->lib->check_method("POST");

		$tabel = "mutasi";

		$aset = $this->input->post('aset');
		$dataaset = $this->apps_m->get_data("aset", "*", "id_aset = '" . $aset . "'")->result_array();
		$kode_aset = $dataaset[0]['kode_aset'];
		$lokasi_awal = $dataaset[0]['lokasi'];
		$kantor_awal = $dataaset[0]['kantor'];
		$lokasi_baru = $this->input->post('lokasi_baru');
		$kantor_baru = $this->input->post('kantor_baru');
		$tgl_mutasi = $this->input->post('tgl_mutasi');

		$data1 = array(
			'aset' => $aset,
			'lokasi_awal' => $lokasi_awal,
			'kantor_awal' => $kantor_awal,
			'lokasi_baru' => $lokasi_baru,
			'kantor_baru' => $kantor_baru,
			'tgl_mutasi' => $tgl_mutasi,
		);

		$kode_terbaru = str_replace("/", "-", $kode_aset);

		if ($_FILES['file_pdf']['name'] == "") {
			$data2 = array();
		} else {
			if ($_FILES['file_pdf']['type'] == "application/pdf") {
				$typefile = str_replace("application/", ".", $_FILES['file_pdf']['type']);
			}

			$tempfile = "mutasi-aset-" . $kode_terbaru . "-" . date('His-d-m-Y') . $typefile;
			$file = $this->lib->upload_pdf($tempfile);
			$this->load->library('upload', $file);

			if ($this->upload->do_upload('file_pdf')) {
				$file_info = $this->upload->data();
				$file_name = $file_info['file_name'];

				$data2 = array(
					'file_upload' => strtolower($file_name),
				);
			} else {
				// echo $this->upload->display_errors(); //untuk melihat penyebab eror
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Kesalahan Upload File.");
				$this->session->set_flashdata("pesan_tipe", "warning");
				echo "<script>self.history.back();</script>";
				exit;
			}
		}

		$data = array_merge($data1, $data2);
		$result = $this->apps_m->insert_data($tabel, $data);
		if ($result == TRUE) {
			// Update Data Aset
			$dataasetnew = array(
				"lokasi" => $lokasi_baru,
				"kantor" => $kantor_baru,
			);
			$whereaset = "id_aset = '" . $aset . "'";
			$this->apps_m->update_data("aset", $dataasetnew, $whereaset);
			// End Update Data Aset

			// Log
			$operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
			$pengguna = $operator;
			$aksi = "Mutasi Aset";
			$keterangan = "a/n <b>" . $kode_terbaru . "</b> dengan tanggal mutasi pada <b>" . $tgl_mutasi . "</b>";
			$this->lib->aksi_log($pengguna, $aksi, $keterangan);
			// End Log
			$respone = array("status" => TRUE, "pesan" => "Mutasi Aset berhasil ditambahkan.", "tipe_pesan" => "success");
			echo json_encode($respone);
		} else {
			$respone = array("status" => FALSE, "pesan" => "Terjadi kesalahan inputan data!", "tipe_pesan" => "warning");
			echo json_encode($respone);
		}
	}

	public function get_data_mutasi_aset_edit()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();
		$this->lib->check_method("GET");

		$id = $this->uri->segment(3);
		$tabel = "mutasi";

		$key = "id_mutasi";
		$value = $id;
		$where = $this->lib->sql_encrypt_url($key, $value);

		$select = "*, " . $this->lib->sql_select_encrypt($key, "idmutasi");
		$cek = $this->apps_m->get_data($tabel, $select, $where)->result_array();

		if (count($cek) != 0) {
			$result = $this->apps_m->get_data($tabel, $select, $where)->row();
			$lokasi_awal = $this->apps_m->get_data("master_lokasi", "*", "id_lokasi = '" . $cek[0]['lokasi_awal'] . "'")->result_array();
			$lokasi_awal = $lokasi_awal[0]['nama_lokasi'];
			$lokasi_baru = $this->apps_m->get_data("master_lokasi", "*", "id_lokasi = '" . $cek[0]['lokasi_baru'] . "'")->result_array();
			$lokasi_baru = $lokasi_baru[0]['id_lokasi'];

			$kantor_awal = $this->apps_m->get_data("master_kantor", "*", "kode_ktr = '" . $cek[0]['kantor_awal'] . "'")->result_array();
			$kantor_awal = $kantor_awal[0]['nama_kantor'];
			$kantor_baru = $this->apps_m->get_data("master_kantor", "*", "kode_ktr = '" . $cek[0]['kantor_baru'] . "'")->result_array();
			$kantor_baru = $kantor_baru[0]['kode_ktr'];

			$result2 = array(
				"lokasi_awal" => $lokasi_awal,
				"lokasi_baru" => $lokasi_baru,
				"kantor_awal" => $kantor_awal,
				"kantor_baru" => $kantor_baru,
			);
			$data = (object) array_merge((array) $result, (array) $result2);
			echo json_encode($data);
		} else {
			$respone = array("status" => FALSE, "pesan" => "Data tidak ditemukan.", "tipe_pesan" => "warning");
			echo json_encode($respone);
			echo "<script>self.history.back();</script>";
		}
	}

	function get_data_lokasi_edit()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();
		$this->lib->check_method("POST");
		$kantor = $this->input->post('id_kantor', TRUE);
		$data = $this->apps_m->get_data("master_lokasi", "*", "kantor = '" . $kantor . "'")->result();
		echo json_encode($data);
	}

	public function edit_mutasi()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();
		$this->lib->check_method("POST");

		$tabel = "mutasi";

		$id = $this->input->post('id_mutasi');
		$key = "id_mutasi";
		$value = $id;
		$wherecek = $this->lib->sql_encrypt_url($key, $value);
		$joincek = array(
			'table' => array('aset'),
			'kondisi' => array('mutasi.aset = aset.id_aset'),
			'posisi' => array('left'),
		);
		$select = "*";
		$cek = $this->apps_m->get_data($tabel, $select, $wherecek, "", $joincek)->result_array();

		if (count($cek) == 0) {
			$respone = array("status" => FALSE, "pesan" => "Data tidak ditemukan.", "tipe_pesan" => "warning");
			echo json_encode($respone);
			exit;
		}

		$id_mutasi = $cek[0]['id_mutasi'];
		$aset = $this->input->post('id_aset');
		$lokasi_awal = $this->input->post('lokasi_awal');
		$kantor_awal = $this->input->post('kantor_awal');
		$lokasi_baru = $this->input->post('lokasi_baru');
		$kantor_baru = $this->input->post('kantor_baru');
		$tgl_mutasi = $this->input->post('tgl_mutasi');
		$file_pdf = $this->input->post('file_pdf');

		$data_aset = $this->apps_m->get_data("aset", "*", "id_aset = '" . $aset . "'")->result_array();
		$data_kantor = $this->apps_m->get_data("master_kantor", "*", "kode_ktr = '" . $kantor_baru . "'")->result_array();

		if ($lokasi_baru == "") {
			$lokasi_baru = $data_aset[0]['lokasi'];
		} else {
			$lokasi_baru = $lokasi_baru;
		}

		$data1 = array(
			"lokasi_baru" => $lokasi_baru,
			'kantor_baru' => $data_kantor[0]['kode_ktr'],
			'tgl_mutasi' => $tgl_mutasi,
		);
		$kode_terbaru = str_replace("/", "-", $data_aset[0]['kode_aset']);

		if ($_FILES['file_pdf']['name'] == "") {
			$data2 = array();
		} else {
			if ($_FILES['file_pdf']['type'] == "application/pdf") {
				$typefile = str_replace("application/", ".", $_FILES['file_pdf']['type']);
			}

			$tempfile = "mutasi-aset-" . $kode_terbaru . "-" . date('His-d-m-Y') . $typefile;
			$file = $this->lib->upload_pdf($tempfile);
			$this->load->library('upload', $file);

			if ($this->upload->do_upload('file_pdf')) {
				$file_pdf_old = $cek[0]['file_upload'];
				@unlink("./assets/lampiran/upload/" . $file_pdf_old);

				$file_info = $this->upload->data();
				$file_name = $file_info['file_name'];

				$data2 = array(
					'file_upload' => strtolower($file_name),
				);
			} else {
				// echo $this->upload->display_errors(); //untuk melihat penyebab eror
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Kesalahan Upload File.");
				$this->session->set_flashdata("pesan_tipe", "warning");
				echo "<script>self.history.back();</script>";
				exit;
			}
		}

		$data = array_merge($data1, $data2);
		$where = "id_mutasi = '" . $id_mutasi . "'";
		$result = $this->apps_m->update_data($tabel, $data, $where);

		if ($result == TRUE) {

			// Update Data Aset
			$data_mutasi = $this->apps_m->get_data($tabel, "*", "aset = '" . $aset . "'", "tgl_mutasi DESC, id_mutasi DESC")->result_array();
			$id_mutasi_cek = $data_mutasi[0]['id_mutasi'];

			if ($id_mutasi_cek == $id_mutasi) {
				$dataaset = array(
					"lokasi" => $lokasi_baru,
					"kantor" => $kantor_baru,
				);
				$whereaset = "id_aset = '" . $aset . "'";
				$this->apps_m->update_data("aset", $dataaset, $whereaset);
			}
			// End Update Data Aset

			// Log
			$operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
			$pengguna = $operator;
			$aksi = "Ubah Mutasi Aset";
			$keterangan = "a/n <b>" . $data_aset[0]['kode_aset'] . "</b> dengan Tanggal Mutasi pada <b>" . $tgl_mutasi . "</b>";
			$this->lib->aksi_log($pengguna, $aksi, $keterangan);
			// End Log
			$respone = array("status" => TRUE, "pesan" => "Mutasi Aset berhasil diubah.", "tipe_pesan" => "success");
			echo json_encode($respone);
		} else {
			$respone = array("status" => FALSE, "pesan" => "Terjadi kesalahan inputan data!", "tipe_pesan" => "warning");
			echo json_encode($respone);
		}
	}

	public function delete_mutasi()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();
		$this->lib->check_method("POST");

		$tabel = "mutasi";

		$id = $this->input->post('id_mutasi');
		$key = "id_mutasi";
		$value = $id;
		$where = $this->lib->sql_encrypt_url($key, $value);
		$join = array(
			'table' => array('aset'),
			'kondisi' => array('mutasi.aset = aset.id_aset'),
			'posisi' => array('left'),
		);

		$select = "*";
		$cek = $this->apps_m->get_data($tabel, $select, $where, "", $join)->result_array();

		if (count($cek) != 0) {
			$id_mutasi = $cek[0]['id_mutasi'];
			$aset = $cek[0]['aset'];
			$lokasi_awal = $cek[0]['lokasi_awal'];
			$kantor_awal = $cek[0]['kantor_awal'];

			$data_lokasi = $this->apps_m->get_data("master_lokasi", "*", "id_lokasi = '" . $lokasi_awal . "'")->result_array();
			$data_kantor = $this->apps_m->get_data("master_kantor", "*", "kode_ktr = '" . $kantor_awal . "'")->result_array();

			$file_pdf = $cek[0]['file_upload'];
			@unlink("./assets/lampiran/upload/" . $file_pdf);

			$data_mutasi = $this->apps_m->get_data($tabel, "*", "aset = '" . $aset . "'", "tgl_mutasi DESC, id_mutasi DESC")->result_array();
			if (count($data_mutasi) == 0) {
				$id_mutasi_cek = 0;
			} else {
				$id_mutasi_cek = $data_mutasi[0]['id_mutasi'];
			}
			$result = $this->apps_m->delete_data($tabel, $where);
			if ($result == TRUE) {

				// Update Data Aset
				if ($id_mutasi_cek == $id_mutasi) {
					$dataaset = array(
						"lokasi" => $data_lokasi[0]['id_lokasi'],
						"kantor" => $data_kantor[0]['kode_ktr'],
					);
					$whereaset = "id_aset = '" . $aset . "'";
					$this->apps_m->update_data("aset", $dataaset, $whereaset);
				}
				// End Update Data Aset

				// Log
				$operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
				$pengguna = $operator;
				$aksi = "Hapus Mutasi Aset";
				$keterangan = "a/n <b>" . $cek[0]['kode_aset'] . "</b> pada tanggal <b>" . date('d-m-Y') . ' ' . date('His') . "</b>";
				$this->lib->aksi_log($pengguna, $aksi, $keterangan);
				// End Log
				$respone = array("status" => TRUE, "pesan" => "Data telah dihapus.", "tipe_pesan" => "success");
				echo json_encode($respone);
			} else {
				$respone = array("status" => FALSE, "pesan" => "Terjadi kesalahan!", "tipe_pesan" => "warning");
				echo json_encode($respone);
			}
		} else {
			$respone = array("status" => FALSE, "pesan" => "Data tidak ditemukan.", "tipe_pesan" => "warning");
			echo json_encode($respone);
		}
	}

	public function permohonan_perbaikan()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();

		$data = array();
		$this->load->view('core-aset/permohonan-perbaikan-v', $data);
	}

	function get_data_permohonan_perbaikan()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();
		$this->lib->check_method("POST");
		$kantor_pengguna = $this->session->userdata[$this->param['session']]['kantor'];
		$grup_pengguna = $this->session->userdata[$this->param['session']]['grup_pengguna'];


		$tabel = "permohonan_perbaikan";
		$select = "*";
		$param = array(
			'column_order' => array(),
			'column_search' => array('nama_aset', 'tgl_pengajuan'),
			'order' => array('tgl_pengajuan'),
			'order2' => array('ASC'),
		);
		if ($grup_pengguna == 99) {
			$where = "status_terima = 0";
		} else {
			$where = "status_terima = 0 AND permohonan_perbaikan.kantor = '" . $kantor_pengguna . "'";
		}

		$join = array(
			'table' => array('aset', 'master_kategori', 'master_kantor'),
			'kondisi' => array('permohonan_perbaikan.aset = aset.id_aset', 'aset.kategori_aset = master_kategori.id_kategori', 'permohonan_perbaikan.kantor = master_kantor.kode_ktr'),
			'posisi' => array('left', 'left', 'left'),
		);

		$list = $this->apps_m->get_datatables($tabel, $param, $select, $where, $join);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $field) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $field->nama_aset;
			$row[] = $field->nama_kategori;
			$row[] = $field->nama_kantor;
			$row[] = $field->tgl_pengajuan;
			$row[] = $field->estimasi_biaya;
			$row[] = $field->keterangan_perbaikan;

			$urlubah = base_url() . "core-aset/edit_permohonan_perbaikan/" . $this->lib->encrypt_url($field->id_permohonan_perbaikan);
			$urlhapus = "onclick='return hapus(\"" . $this->lib->encrypt_url($field->id_permohonan_perbaikan) . "\")'";
			$urlterima = base_url() . "core-aset/terima_permohonan_perbaikan/" . $this->lib->encrypt_url($field->id_permohonan_perbaikan);
			$urltolak = "onclick='return tolak(\"" . $this->lib->encrypt_url($field->id_permohonan_perbaikan) . "\")'";
			$urldetail = base_url() . "core-aset/detail_permohonan_perbaikan/" . $this->lib->encrypt_url($field->id_permohonan_perbaikan);

			$menu_kasie = array(41, 51, 99);
			$menu_pincab = array(42, 52, 99);
			$temp = "";
			if (in_array($grup_pengguna, $menu_pincab)) {
				$temp .= " <a href=\"" . $urlterima . "\" class=\"btn btn-icon btn-sm btn-info\" data-toggle=\"tooltip\" data-original-title=\"Terima\"><i class=\"fa fa-check\"></i></a>";
				$temp .= " <a href=\"#\" " . $urltolak . " class=\"btn btn-icon btn-sm btn-danger\" data-toggle=\"tooltip\" data-original-title=\"Tolak\"><i class=\"fas fa-times\"></i></a>";
				$temp .= " <a href=\"" . $urldetail . "\" class=\"btn btn-icon btn-sm btn-secondary\" data-toggle=\"tooltip\" data-original-title=\"Detail\"><i class=\"fa fa-info\"></i></a>";
			} elseif (in_array($grup_pengguna, $menu_kasie)) {
				$temp .= " <a href=\"" . $urlubah . "\" class=\"btn btn-icon btn-sm btn-warning\" data-toggle=\"tooltip\" data-original-title=\"Ubah\"><i class=\"fas fa-edit\"></i></a>";
				$temp .= " <a href=\"#\" " . $urlhapus . " class=\"btn btn-icon btn-sm btn-danger\" data-toggle=\"tooltip\" data-original-title=\"Hapus\"><i class=\"fas fa-trash\"></i></a>";
				$temp .= " <a href=\"" . $urldetail . "\" class=\"btn btn-icon btn-sm btn-secondary\" data-toggle=\"tooltip\" data-original-title=\"Detail\"><i class=\"fa fa-info\"></i></a>";
			} else {
				$temp .= " <a href=\"" . $urldetail . "\" class=\"btn btn-icon btn-sm btn-secondary\" data-toggle=\"tooltip\" data-original-title=\"Detail\"><i class=\"fa fa-info\"></i></a>";
			}

			$row[] = $temp;

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->apps_m->count_all($tabel, $select, $where, $join),
			"recordsFiltered" => $this->apps_m->count_filtered($tabel, $param, $select, $where, $join),
			"data" => $data,
		);
		//output dalam format JSON
		echo json_encode($output);
	}

	public function get_permohonan_perbaikan()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();

		$id = $this->uri->segment(3);
		$tabel = "permohonan_perbaikan";

		$key = "id_permohonan_perbaikan";
		$value = $id;
		$where = $this->lib->sql_encrypt_url($key, $value);

		$select = "*, " . $this->lib->sql_select_encrypt($key, "id_permohonan_perbaikan");
		$cek = $this->apps_m->get_data($tabel, $select, $where)->result_array();

		if (count($cek) != 0) {
			$result = $this->apps_m->get_data($tabel, $select, $where)->row();
			echo json_encode($result);
		} else {
			$respone = array("status" => FALSE, "pesan" => "Data tidak ditemukan.", "tipe_pesan" => "warning");
			echo json_encode($respone);
			echo "<script>self.history.back();</script>";
		}
	}

	public function add_permohonan_perbaikan()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();

		//0 = kasie
		//1 = pincab
		//2 = pusat
		$kantor_pengguna = $this->session->userdata[$this->param['session']]['kantor'];
		if (isset($_POST['btn-simpan'])) {

			$tabel = "permohonan_perbaikan";
			$nama_aset = $this->input->post('nama_aset');
			$kantor = $this->apps_m->get_data("aset", "*", "id_aset = '" . $nama_aset . "'")->result_array();
			$tgl_pengajuan = date("Y-m-d H:i:s");
			$keterangan_perbaikan = $this->input->post('keterangan_perbaikan');
			$estimasi_biaya = $this->input->post('estimasi_biaya');
			$status_lapor = "1";
			$status_terima = "0";
			$temp_pengguna = $this->session->userdata[$this->param['session']]['nama'];
			$grup_pengguna = $this->session->userdata[$this->param['session']]['grup_pengguna'];

			$data = array(
				'aset' => $nama_aset,
				'kantor' => $kantor[0]['kantor'],
				'tgl_pengajuan' => $tgl_pengajuan,
				'keterangan_perbaikan' => $keterangan_perbaikan,
				'estimasi_biaya' => $estimasi_biaya,
				'status_terima' => $status_terima,
				'pengguna' => $temp_pengguna
			);

			$komen = $this->input->post('komen');
			$waktu = date("Y-m-d H:i:s");
			$result = $this->apps_m->insert_data($tabel, $data);
			$temp_id_permohonan_perbaikan = $this->db->insert_id();
			if ($result == TRUE) {

				$this->apps_m->update_data("aset", array('status_lapor' => $status_lapor), "id_aset = '" . $nama_aset . "'");

				$this->apps_m->insert_data("komen", array('permohonan_perbaikan' => $temp_id_permohonan_perbaikan, 'isi_komen' => $komen, 'waktu' => $waktu, 'pengguna_komen' => $temp_pengguna, 'grup_jabatan' => $grup_pengguna));
				// Log
				$operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
				$pengguna = $operator;
				$aksi = "Tambah Permohonan Perbaikan";
				$keterangan = "nama aset : <b>" . $nama_aset . "</b> dengan estimasi biaya <b>" . $estimasi_biaya . "</b>";
				$this->lib->aksi_log($pengguna, $aksi, $keterangan);
				// End Log

				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Data berhasil ditambahkan.");
				$this->session->set_flashdata("pesan_tipe", "success");
				redirect('/core-aset/permohonan-perbaikan');
			} else {
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Terjadi kesalahan inputan data!");
				$this->session->set_flashdata("pesan_tipe", "warning");
				echo "<script>self.history.back();</script>";
			}
		} else {
			$grup_pengguna = $this->session->userdata[$this->param['session']]['grup_pengguna'];
			if ($grup_pengguna == 99) {
				//data select option untuk ditampilkan di form
				$data = array(
					'aset' => $this->apps_m->get_data("aset", "*", "status_lapor = 0")->result()
				);
			} else {
				$data = array(
					'aset' => $this->apps_m->get_data("aset", "*", "status_lapor = 0 AND status_aset <> 99 AND kantor = '" . $kantor_pengguna . "'")->result()
				);
			}
			$this->load->view('core-aset/add-permohonan-perbaikan-v', $data);
		}
	}

	public function edit_permohonan_perbaikan()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();

		$tabel = "permohonan_perbaikan";
		if (isset($_POST['btn-ubah'])) {

			$idpermohonan_perbaikan = $this->input->post('idpermohonan_perbaikan');

			$key = "id_permohonan_perbaikan";
			$value = $idpermohonan_perbaikan;
			$select = "*";
			$whereid = $this->lib->sql_encrypt_url($key, $value);
			$result = $this->apps_m->get_data($tabel, $select, $whereid)->result_array();

			if (count($result) == 0) {
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Data tidak ditemukan.");
				$this->session->set_flashdata("pesan_tipe", "warning");
				echo "<script>self.history.back();</script>";
				exit;
			}

			$id_permohonan_perbaikan = $result[0]['id_permohonan_perbaikan'];
			$nama_aset = $this->input->post('nama_aset');
			$tgl_perubahan = date("Y-m-d H:i:s");
			$keterangan_perbaikan = $this->input->post('keterangan_perbaikan');
			$estimasi_biaya = $this->input->post('estimasi_biaya');
			$temp_pengguna = $this->session->userdata[$this->param['session']]['id_pengguna'];
			$pengguna_pengubah = $this->apps_m->get_data("pengguna", "nama", "id_pengguna = $temp_pengguna")->result_array();
			$komen = $this->input->post('komen');

			$datakomen = $this->apps_m->get_data("komen", "*", "permohonan_perbaikan = '" . $id_permohonan_perbaikan . "' AND status_komen = '1'", "")->result_array();

			$data = array(
				'tgl_perubahan' => $tgl_perubahan,
				'keterangan_perbaikan' => $keterangan_perbaikan,
				'estimasi_biaya' => $estimasi_biaya,
				'pengubah' => $pengguna_pengubah[0]['nama']
			);

			$where = "id_permohonan_perbaikan = '" . $id_permohonan_perbaikan . "'";
			$result = $this->apps_m->update_data($tabel, $data, $where);
			if ($result == TRUE) {
				if ($komen != $datakomen[0]['isi_komen']) {
					$this->apps_m->update_data("komen", array('isi_komen' => $komen, 'waktu' => date("Y-m-d H:i:s")), "permohonan_perbaikan = '" . $id_permohonan_perbaikan . "' AND grup_jabatan IN ('99', '41','51')");
				}
				// Log
				$operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
				$pengguna = $operator;
				$aksi = "Ubah Data Permohonan Perbaikan";
				$keterangan = "id aset : <b>" . $nama_aset . "</b> dengan estimasi biaya : <b>" . $estimasi_biaya . "</b>";
				$this->lib->aksi_log($pengguna, $aksi, $keterangan);
				// End Log

				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Data berhasil diubah.");
				$this->session->set_flashdata("pesan_tipe", "success");
				redirect('/core-aset/permohonan-perbaikan');
			} else {
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Terjadi kesalahan inputan data!");
				$this->session->set_flashdata("pesan_tipe", "warning");
				echo "<script>self.history.back();</script>";
				exit;
			}
		} else {
			$kantor_pengguna = $this->session->userdata[$this->param['session']]['kantor'];
			$id = $this->uri->segment(3);
			$key = "id_permohonan_perbaikan";
			$value = $id;
			$select = "*";
			$where = $this->lib->sql_encrypt_url($key, $value);
			$join =  array(
				'table' => array('komen'),
				'kondisi' => array('permohonan_perbaikan.id_permohonan_perbaikan = komen.permohonan_perbaikan AND komen.status_komen=1'),
				'posisi' => array('left'),
			);
			$result = $this->apps_m->get_data($tabel, $select, $where, '', $join)->result_array();
			$id_permohonan_perbaikan = $result[0]['id_permohonan_perbaikan'];

			if (count($result) != 0) {
				$data = array(
					'permohonan_perbaikan' => $result,
					'aset' => $this->apps_m->get_data("aset", "*", "id_aset = '" . $result[0]['aset'] . "' AND status_lapor = 1 AND kantor = '" . $kantor_pengguna . "'", "")->result(),
				);
				$this->load->view('core-aset/edit-permohonan-perbaikan-v', $data);
			} else {
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Data tidak ditemukan.");
				$this->session->set_flashdata("pesan_tipe", "warning");
				redirect('/core-aset/permohonan-perbaikan');
			}
		}
	}

	public function terima_permohonan_perbaikan()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();

		$tabel = "permohonan_perbaikan";
		if (isset($_POST['btn-terima'])) {

			$idpermohonan_perbaikan = $this->input->post('idpermohonan_perbaikan');

			$key = "id_permohonan_perbaikan";
			$value = $idpermohonan_perbaikan;
			$select = "*";
			$whereid = $this->lib->sql_encrypt_url($key, $value);
			$result = $this->apps_m->get_data($tabel, $select, $whereid)->result_array();

			if (count($result) == 0) {
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Data tidak ditemukan.");
				$this->session->set_flashdata("pesan_tipe", "warning");
				echo "<script>self.history.back();</script>";
				exit;
			}

			$id_permohonan_perbaikan = $result[0]['id_permohonan_perbaikan'];
			$id_aset = $result[0]['aset'];

			$tgl_terima = date("Y-m-d H:i:s");
			$komen = $this->input->post('komen');
			$final_biaya_cabang = $this->input->post('final_biaya_cabang');
			//cek final biaya lbh dari 3 jt
			if ($final_biaya_cabang > $this->param['dana-limit']) {
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Melebihi Limit Cabang!");
				$this->session->set_flashdata("pesan_tipe", "warning");
				echo "<script>self.history.back();</script>";
				exit;
			}
			$waktu = date("Y-m-d H:i:s");
			$status_terima = "1";
			$kondisi_aset = "2";
			$temp_pengguna = $this->session->userdata[$this->param['session']]['id_pengguna'];
			$grup_pengguna = $this->session->userdata[$this->param['session']]['grup_pengguna'];
			$pengguna_penerima = $this->apps_m->get_data("pengguna", "nama", "id_pengguna = $temp_pengguna")->result_array();

			$data = array(
				'tgl_terima' => $tgl_terima,
				'final_biaya_cabang' => $final_biaya_cabang,
				'status_terima' => $status_terima,
				'penerima' => $pengguna_penerima[0]['nama'],
			);

			$where = "id_permohonan_perbaikan = '" . $id_permohonan_perbaikan . "'";
			$result = $this->apps_m->update_data($tabel, $data, $where);
			if ($result == TRUE) {
				$this->apps_m->update_data("aset", array('kondisi_aset' => $kondisi_aset), "id_aset = '" . $id_aset . "'");
				$this->apps_m->insert_data("komen", array('permohonan_perbaikan' => $id_permohonan_perbaikan, 'isi_komen' => $komen, 'waktu' => $waktu, 'pengguna_komen' => $pengguna_penerima[0]['nama'], 'grup_jabatan' => $grup_pengguna));
				// Log
				$operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
				$pengguna = $operator;
				$aksi = "Menerima Permohonan Perbaikan";
				$keterangan = "a/n : <b>" . $pengguna_penerima[0]['nama'] . "</b> dengan id permohonan : <b>" . $id_permohonan_perbaikan . "</b>";
				$this->lib->aksi_log($pengguna, $aksi, $keterangan);
				// End Log

				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Permohonan Telah Diterima.");
				$this->session->set_flashdata("pesan_tipe", "success");
				redirect('/core-aset/permohonan-perbaikan');
			} else {
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Terjadi kesalahan inputan data!");
				$this->session->set_flashdata("pesan_tipe", "warning");
				echo "<script>self.history.back();</script>";
				exit;
			}
		}
		if (isset($_POST['btn-ajukan'])) {
			//pengajuan ke pusat
			$idpermohonan_perbaikan = $this->input->post('idpermohonan_perbaikan');

			$key = "id_permohonan_perbaikan";
			$value = $idpermohonan_perbaikan;
			$select = "*";
			$whereid = $this->lib->sql_encrypt_url($key, $value);
			$result = $this->apps_m->get_data($tabel, $select, $whereid)->result_array();

			if (count($result) == 0) {
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Data tidak ditemukan.");
				$this->session->set_flashdata("pesan_tipe", "warning");
				echo "<script>self.history.back();</script>";
				exit;
			}

			$id_permohonan_perbaikan = $result[0]['id_permohonan_perbaikan'];
			$tgl_terima = date("Y-m-d H:i:s");
			$komen = $this->input->post('komen');
			$final_biaya_cabang = $this->input->post('final_biaya_cabang');
			//cek final biaya bila ajukan ke pusat namun kurang dari 3 jt
			if ($final_biaya_cabang < $this->param['dana-limit']) {
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Pengajuan ke Pusat harus melebihi atau sama dengan 3 Juta");
				$this->session->set_flashdata("pesan_tipe", "warning");
				echo "<script>self.history.back();</script>";
				exit;
			}
			$waktu = date("Y-m-d H:i:s");
			//ke pusat
			$status_terima = "3";

			$temp_pengguna = $this->session->userdata[$this->param['session']]['id_pengguna'];
			$grup_pengguna = $this->session->userdata[$this->param['session']]['grup_pengguna'];
			$pengguna_penerima = $this->apps_m->get_data("pengguna", "nama", "id_pengguna = $temp_pengguna")->result_array();

			$data = array(
				'tgl_terima' => $tgl_terima,
				'final_biaya_cabang' => $final_biaya_cabang,
				'status_terima' => $status_terima,
				'penerima' => $pengguna_penerima[0]['nama'],
			);

			$where = "id_permohonan_perbaikan = '" . $id_permohonan_perbaikan . "'";
			$result = $this->apps_m->update_data($tabel, $data, $where);
			if ($result == TRUE) {
				$this->apps_m->insert_data("komen", array('permohonan_perbaikan' => $id_permohonan_perbaikan, 'isi_komen' => $komen, 'waktu' => $waktu, 'pengguna_komen' => $pengguna_penerima[0]['nama'], 'grup_jabatan' => $grup_pengguna));
				// Log
				$operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
				$pengguna = $operator;
				$aksi = "Mengajukan Permohonan Perbaikan Ke Pusat";
				$keterangan = "a/n : <b>" . $pengguna_penerima[0]['nama'] . "</b> dengan id permohonan perbaikan : <b>" . $id_permohonan_perbaikan . "</b>";
				$this->lib->aksi_log($pengguna, $aksi, $keterangan);
				// End Log

				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Permohonan Telah Disetujui.");
				$this->session->set_flashdata("pesan_tipe", "success");
				redirect('/core-aset/permohonan-perbaikan');
			} else {
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Terjadi kesalahan inputan data!");
				$this->session->set_flashdata("pesan_tipe", "warning");
				echo "<script>self.history.back();</script>";
				exit;
			}
		} else {
			$kantor_pengguna = $this->session->userdata[$this->param['session']]['kantor'];
			$grup_pengguna = $this->session->userdata[$this->param['session']]['grup_pengguna'];

			$id = $this->uri->segment(3);
			$key = "id_permohonan_perbaikan";
			$value = $id;
			$select = "*";
			$where = $this->lib->sql_encrypt_url($key, $value);
			$result = $this->apps_m->get_data($tabel, $select, $where)->result_array();

			if (count($result) != 0) {
				if ($grup_pengguna == 99) {
					$data = array(
						'permohonan_perbaikan' => $result,
						'aset' => $this->apps_m->get_data("aset", "*", "id_aset = '" . $result[0]['aset'] . "' AND status_lapor = 1", "")->result(),
					);
					$this->load->view('core-aset/terima-permohonan-perbaikan-v', $data);
				} else {
					$data = array(
						'permohonan_perbaikan' => $result,
						'aset' => $this->apps_m->get_data("aset", "*", "id_aset = '" . $result[0]['aset'] . "' AND status_lapor = 1 AND kantor = '" . $kantor_pengguna . "'", "")->result(),
					);
				}
				$this->load->view('core-aset/terima-permohonan-perbaikan-v', $data);
			} else {
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Data tidak ditemukan.");
				$this->session->set_flashdata("pesan_tipe", "warning");
				redirect('/core-aset/permohonan-perbaikan');
			}
		}
	}

	//pincab
	public function tolak_permohonan_perbaikan()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();
		$this->lib->check_method("POST");

		$temp_pengguna = $this->session->userdata[$this->param['session']]['id_pengguna'];
		$pengguna_penginput = $this->apps_m->get_data("pengguna", "nama", "id_pengguna = $temp_pengguna")->result_array();
		$tabel = "permohonan_perbaikan";
		$id = $this->input->post('id_permohonan_perbaikan');
		$status_lapor = "0";
		$key = "id_permohonan_perbaikan";
		$value = $id;
		$where = $this->lib->sql_encrypt_url($key, $value);
		$select = "*";
		$cek = $this->apps_m->get_data($tabel, $select, $where)->result_array();

		$data = array(
			'permohonan_perbaikan' => $cek[0]['id_permohonan_perbaikan'],
			'aset' => $cek[0]['aset'],
			'kantor' => $cek[0]['kantor'],
			'estimasi_biaya' => $cek[0]['estimasi_biaya'],
			'final_biaya_cabang' => $cek[0]['final_biaya_cabang'],
			'final_biaya_pusat' => $cek[0]['final_biaya_pusat'],
			'keterangan_perbaikan' => $cek[0]['keterangan_perbaikan'],
			'tgl_pengajuan' => $cek[0]['tgl_pengajuan'],
			'penginput' => $cek[0]['pengguna'],
			'penolak' => $pengguna_penginput[0]['nama']
		);

		if (count($cek) != 0) {
			$this->apps_m->insert_data("permohonan_perbaikan_riwayat", $data);
			$result = $this->apps_m->delete_data($tabel, $where);
			if ($result == TRUE) {
				$this->apps_m->update_data("aset", array('status_lapor' => $status_lapor), "id_aset = '" . $cek[0]['aset'] . "'");
				$this->apps_m->update_data("komen", array('status_komen' => 2), "permohonan_perbaikan = '" . $cek[0]['id_permohonan_perbaikan'] . "'");
				// Log
				$operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
				$pengguna = $operator;
				$aksi = "Tolak Data Permohonan Perbaikan";
				$keterangan = "a/n <b>" . $pengguna . "</b> dengan id aset<b>" . $cek[0]['aset'] . "</b>";
				$this->lib->aksi_log($pengguna, $aksi, $keterangan);
				// End Log
				$respone = array("status" => TRUE, "pesan" => "Data Permohonan sudah di tolak!", "tipe_pesan" => "success");
				echo json_encode($respone);
			} else {
				$respone = array("status" => FALSE, "pesan" => "Terjadi kesalahan!", "tipe_pesan" => "warning");
				echo json_encode($respone);
			}
		} else {
			$respone = array("status" => FALSE, "pesan" => "Data tidak ditemukan.", "tipe_pesan" => "warning");
			echo json_encode($respone);
		}
	}

	//kasie
	public function delete_permohonan_perbaikan()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();
		$this->lib->check_method("POST");

		$tabel = "permohonan_perbaikan";
		$id = $this->input->post('id_permohonan_perbaikan');
		$status_lapor = "0";
		$status_komen = "99";
		$key = "id_permohonan_perbaikan";
		$value = $id;
		$where = $this->lib->sql_encrypt_url($key, $value);
		$select = "*";
		$cek = $this->apps_m->get_data($tabel, $select, $where)->result_array();


		$data = array(
			'permohonan_perbaikan' => $cek[0]['id_permohonan_perbaikan'],
			'aset' => $cek[0]['aset'],
			'kantor' => $cek[0]['kantor'],
			'estimasi_biaya' => $cek[0]['estimasi_biaya'],
			'final_biaya_cabang' => $cek[0]['final_biaya_cabang'],
			'final_biaya_pusat' => $cek[0]['final_biaya_pusat'],
			'keterangan_perbaikan' => $cek[0]['keterangan_perbaikan'],
			'tgl_pengajuan' => $cek[0]['tgl_pengajuan'],
			'penginput' => $cek[0]['pengguna'],
			'penolak' => ""
		);


		if (count($cek) != 0) {
			$this->apps_m->insert_data("permohonan_perbaikan_riwayat", $data);
			$result = $this->apps_m->delete_data($tabel, $where);
			if ($result == TRUE) {
				$this->apps_m->update_data("aset", array('status_lapor' => $status_lapor), "id_aset = '" . $cek[0]['aset'] . "'");
				$this->apps_m->update_data("komen", array('status_komen' => $status_komen), "permohonan_perbaikan = '" . $id . "'");
				// Log
				$operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
				$pengguna = $operator;
				$aksi = "Hapus Data Permohonan Perbaikan";
				$keterangan = "a/n <b>" . $pengguna . "</b> dengan id aset<b>" . $cek[0]['aset'] . "</b>";
				$this->lib->aksi_log($pengguna, $aksi, $keterangan);
				// End Log
				$respone = array("status" => TRUE, "pesan" => "Data Permohonan sudah di hapus!", "tipe_pesan" => "success");
				echo json_encode($respone);
			} else {
				$respone = array("status" => FALSE, "pesan" => "Terjadi kesalahan!", "tipe_pesan" => "warning");
				echo json_encode($respone);
			}
		} else {
			$respone = array("status" => FALSE, "pesan" => "Data tidak ditemukan.", "tipe_pesan" => "warning");
			echo json_encode($respone);
		}
	}

	public function detail_permohonan_perbaikan()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();

		$tabel = "permohonan_perbaikan";
		$id = $this->uri->segment(3);
		$key = "id_permohonan_perbaikan";
		$value = $id;
		$select = "*";
		$where = $this->lib->sql_encrypt_url($key, $value);
		$join = array(
			'table' => array('aset', 'master_kategori', 'master_jenis', 'master_kantor', 'master_lokasi'),
			'kondisi' => array('permohonan_perbaikan.aset = aset.id_aset', 'aset.kategori_aset = master_kategori.id_kategori', 'aset.jenis_aset = master_jenis.id_jenis', 'permohonan_perbaikan.kantor = master_kantor.kode_ktr', 'aset.lokasi = master_lokasi.id_lokasi'),
			'posisi' => array('left', 'left', 'left', 'left', 'left'),
		);
		$result = $this->apps_m->get_data($tabel, $select, $where, '', $join)->result_array();
		if (count($result) != 0) {
			$data = array(
				'permohonan_perbaikan' => $result,
				'komencabang' => $this->apps_m->get_data("komen", "*", "permohonan_perbaikan = '" . $result[0]['id_permohonan_perbaikan'] . "' AND status_komen = 1 AND grup_jabatan IN('99','41', '42')", "")->result_array(),
				'komenpusat' => $this->apps_m->get_data("komen", "*", "permohonan_perbaikan = '" . $result[0]['id_permohonan_perbaikan'] . "' AND status_komen = 1 AND grup_jabatan IN('99','51', '52', '53')", "")->result_array(),
				'komendireksi' => $this->apps_m->get_data("komen", "*", "permohonan_perbaikan = '" . $result[0]['id_permohonan_perbaikan'] . "' AND status_komen = 1 AND grup_jabatan IN('99','11')", "")->result_array()
			);
			$this->load->view('core-aset/detail-permohonan-perbaikan-v', $data);
		} else {
			$this->session->set_flashdata("pesan", "ada");
			$this->session->set_flashdata("pesan_isi", "Data tidak ditemukan.");
			$this->session->set_flashdata("pesan_tipe", "warning");
			redirect('/core-aset/permohonan-perbaikan');
		}
	}

	public function permohonan_perbaikan_pusat()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();

		$data = array();
		$this->load->view('core-aset/permohonan-perbaikan-pusat-v', $data);
	}

	function get_data_permohonan_perbaikan_pusat()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();
		$this->lib->check_method("POST");
		$kantor_pengguna = $this->session->userdata[$this->param['session']]['kantor'];
		$grup_pengguna = $this->session->userdata[$this->param['session']]['grup_pengguna'];

		$tabel = "permohonan_perbaikan";
		$select = "*";
		$param = array(
			'column_order' => array(),
			'column_search' => array('nama_aset', 'tgl_pengajuan'),
			'order' => array('tgl_pengajuan'),
			'order2' => array('ASC'),
		);

		if ($grup_pengguna == 99 || $grup_pengguna == 51 || $grup_pengguna == 52 || $grup_pengguna == 53) {
			$where = "status_terima = 3";
		} else {
			$where = "status_terima = 3 AND permohonan_perbaikan.kantor = '" . $kantor_pengguna . "'";
		}

		$join = array(
			'table' => array('aset', 'master_kategori', 'master_kantor'),
			'kondisi' => array('permohonan_perbaikan.aset = aset.id_aset', 'aset.kategori_aset = master_kategori.id_kategori', 'permohonan_perbaikan.kantor = master_kantor.kode_ktr'),
			'posisi' => array('left', 'left', 'left'),
		);

		$list = $this->apps_m->get_datatables($tabel, $param, $select, $where, $join);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $field) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $field->nama_aset;
			$row[] = $field->nama_kategori;
			$row[] = $field->nama_kantor;
			$row[] = $field->tgl_pengajuan;
			$row[] = $field->final_biaya_cabang;
			$row[] = $field->keterangan_perbaikan;

			$urlubah = base_url() . "core-aset/edit_permohonan_perbaikan/" . $this->lib->encrypt_url($field->id_permohonan_perbaikan);
			$urlhapus = "onclick='return hapus(\"" . $this->lib->encrypt_url($field->id_permohonan_perbaikan) . "\")'";
			$urlterima = base_url() . "core-aset/terima_permohonan_perbaikan_pusat/" . $this->lib->encrypt_url($field->id_permohonan_perbaikan);
			$urltolak = "onclick='return tolak(\"" . $this->lib->encrypt_url($field->id_permohonan_perbaikan) . "\")'";
			$urldetail = base_url() . "core-aset/detail_permohonan_perbaikan/" . $this->lib->encrypt_url($field->id_permohonan_perbaikan);

			$grup_pengguna = $this->session->userdata[$this->param['session']]['grup_pengguna'];
			$menu_kasie = array(51, 99); //staff umum
			$menu_pincab = array(52, 53, 99); //kasie umum dan kadiv
			$temp = "";
			if (in_array($grup_pengguna, $menu_pincab)) {
				$temp .= " <a href=\"" . $urlterima . "\" class=\"btn btn-icon btn-sm btn-info\" data-toggle=\"tooltip\" data-original-title=\"Terima\"><i class=\"fa fa-check\"></i></a>";
				$temp .= " <a href=\"#\" " . $urltolak . " class=\"btn btn-icon btn-sm btn-danger\" data-toggle=\"tooltip\" data-original-title=\"Tolak\"><i class=\"fas fa-times\"></i></a>";
				$temp .= " <a href=\"" . $urldetail . "\" class=\"btn btn-icon btn-sm btn-secondary\" data-toggle=\"tooltip\" data-original-title=\"Detail\"><i class=\"fa fa-info\"></i></a>";
			} elseif (in_array($grup_pengguna, $menu_kasie)) {
				$temp .= " <a href=\"" . $urlubah . "\" class=\"btn btn-icon btn-sm btn-warning\" data-toggle=\"tooltip\" data-original-title=\"Ubah\"><i class=\"fas fa-edit\"></i></a>";
				$temp .= " <a href=\"#\" " . $urlhapus . " class=\"btn btn-icon btn-sm btn-danger\" data-toggle=\"tooltip\" data-original-title=\"Hapus\"><i class=\"fas fa-trash\"></i></a>";
				$temp .= " <a href=\"" . $urldetail . "\" class=\"btn btn-icon btn-sm btn-secondary\" data-toggle=\"tooltip\" data-original-title=\"Detail\"><i class=\"fa fa-info\"></i></a>";
			} else {
				$temp .= " <a href=\"" . $urldetail . "\" class=\"btn btn-icon btn-sm btn-secondary\" data-toggle=\"tooltip\" data-original-title=\"Detail\"><i class=\"fa fa-info\"></i></a>";
			}

			$row[] = $temp;

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->apps_m->count_all($tabel, $select, $where, $join),
			"recordsFiltered" => $this->apps_m->count_filtered($tabel, $param, $select, $where, $join),
			"data" => $data,
		);
		//output dalam format JSON
		echo json_encode($output);
	}

	public function get_permohonan_perbaikan_pusat()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();

		$id = $this->uri->segment(3);
		$tabel = "permohonan_perbaikan";

		$key = "id_permohonan_perbaikan";
		$value = $id;
		$where = $this->lib->sql_encrypt_url($key, $value);

		$select = "*, " . $this->lib->sql_select_encrypt($key, "id_permohonan_perbaikan");
		$cek = $this->apps_m->get_data($tabel, $select, $where)->result_array();

		if (count($cek) != 0) {
			$result = $this->apps_m->get_data($tabel, $select, $where)->row();
			echo json_encode($result);
		} else {
			$respone = array("status" => FALSE, "pesan" => "Data tidak ditemukan.", "tipe_pesan" => "warning");
			echo json_encode($respone);
			echo "<script>self.history.back();</script>";
		}
	}

	public function terima_permohonan_perbaikan_pusat()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();

		$tabel = "permohonan_perbaikan";
		if (isset($_POST['btn-terima'])) {

			$idpermohonan_perbaikan = $this->input->post('idpermohonan_perbaikan');

			$key = "id_permohonan_perbaikan";
			$value = $idpermohonan_perbaikan;
			$select = "*";
			$whereid = $this->lib->sql_encrypt_url($key, $value);
			$result = $this->apps_m->get_data($tabel, $select, $whereid)->result_array();

			if (count($result) == 0) {
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Data tidak ditemukan.");
				$this->session->set_flashdata("pesan_tipe", "warning");
				echo "<script>self.history.back();</script>";
				exit;
			}

			$id_permohonan_perbaikan = $result[0]['id_permohonan_perbaikan'];
			$id_aset = $result[0]['aset'];

			$komen = $this->input->post('komen');
			$final_biaya_pusat = $this->input->post('final_biaya_pusat');
			//cek final biaya lbh dari 3 jt
			if ($final_biaya_pusat < $this->param['dana-limit']) {
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Biaya Final Perbaikan Pusat Harus Melebihi Limit Cabang !");
				$this->session->set_flashdata("pesan_tipe", "warning");
				echo "<script>self.history.back();</script>";
				exit;
			}
			$waktu = date("Y-m-d H:i:s");
			//status terima dari pusat = 4
			$status_terima = "4";
			$kondisi_aset = "2";
			$temp_pengguna = $this->session->userdata[$this->param['session']]['id_pengguna'];
			$grup_pengguna = $this->session->userdata[$this->param['session']]['grup_pengguna'];
			$pengguna_penerima = $this->apps_m->get_data("pengguna", "nama", "id_pengguna = $temp_pengguna")->result_array();

			$data = array(
				'final_biaya_pusat' => $final_biaya_pusat,
				'status_terima' => $status_terima,
			);

			$where = "id_permohonan_perbaikan = '" . $id_permohonan_perbaikan . "'";
			$result = $this->apps_m->update_data($tabel, $data, $where);
			if ($result == TRUE) {
				$this->apps_m->update_data("aset", array('kondisi_aset' => $kondisi_aset), "id_aset = '" . $id_aset . "'");
				$this->apps_m->insert_data("komen", array('permohonan_perbaikan' => $id_permohonan_perbaikan, 'isi_komen' => $komen, 'waktu' => $waktu, 'pengguna_komen' => $pengguna_penerima[0]['nama'], 'grup_jabatan' => $grup_pengguna));
				// Log
				$operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
				$pengguna = $operator;
				$aksi = "Menerima Permohonan Perbaikan dari Cabang Ke Pusat";
				$keterangan = "a/n : <b>" . $pengguna_penerima[0]['nama'] . "</b> dengan id permohonan perbaikan : <b>" . $id_permohonan_perbaikan . "</b>";
				$this->lib->aksi_log($pengguna, $aksi, $keterangan);
				// End Log

				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Permohonan Telah Diterima.");
				$this->session->set_flashdata("pesan_tipe", "success");
				redirect('/core-aset/permohonan-perbaikan-pusat');
			} else {
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Terjadi kesalahan inputan data!");
				$this->session->set_flashdata("pesan_tipe", "warning");
				echo "<script>self.history.back();</script>";
				exit;
			}
		} else {
			$kantor_pengguna = $this->session->userdata[$this->param['session']]['kantor'];
			$grup_pengguna = $this->session->userdata[$this->param['session']]['grup_pengguna'];
			$id = $this->uri->segment(3);
			$key = "id_permohonan_perbaikan";
			$value = $id;
			$select = "*";
			$where = $this->lib->sql_encrypt_url($key, $value);
			$result = $this->apps_m->get_data($tabel, $select, $where)->result_array();

			if (count($result) != 0) {
				if ($grup_pengguna == 99) {
					$data = array(
						'permohonan_perbaikan' => $result,
						'aset' => $this->apps_m->get_data("aset", "*", "id_aset = '" . $result[0]['aset'] . "' AND status_lapor = 1", "")->result(),
					);
				} else {
					$data = array(
						'permohonan_perbaikan' => $result,
						'aset' => $this->apps_m->get_data("aset", "*", "id_aset = '" . $result[0]['aset'] . "' AND status_lapor = 1 AND kantor = '" . $kantor_pengguna . "'", "")->result(),
					);
				}
				$this->load->view('core-aset/terima-permohonan-perbaikan-pusat-v', $data);
			} else {
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Data tidak ditemukan.");
				$this->session->set_flashdata("pesan_tipe", "warning");
				redirect('/core-aset/permohonan-perbaikan-pusat');
			}
		}
	}

	public function pemeliharaan()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();

		$data = array();
		$this->load->view('core-aset/pemeliharaan-v', $data);
	}

	function get_data_pemeliharaan()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();
		$this->lib->check_method("POST");

		$grup_pengguna = $this->session->userdata[$this->param['session']]['grup_pengguna'];
		$kantor_pengguna = $this->session->userdata[$this->param['session']]['kantor'];

		$tabel = "permohonan_perbaikan";
		$select = "*";
		$param = array(
			'column_order' => array(),
			'column_search' => array('nama_aset', 'tgl_pengajuan'),
			'order' => array('tgl_pengajuan'),
			'order2' => array('ASC'),
		);

		if ($grup_pengguna == 99 || $grup_pengguna == 51 || $grup_pengguna == 52 || $grup_pengguna == 53) {
			$where = "status_terima = 1 OR status_terima = 4";
		} else {
			$where = "status_terima = 1 OR status_terima = 4 AND permohonan_perbaikan.kantor = '" . $kantor_pengguna . "'";
		}


		$join = array(
			'table' => array('aset', 'master_kategori', 'master_kantor'),
			'kondisi' => array('permohonan_perbaikan.aset = aset.id_aset', 'aset.kategori_aset = master_kategori.id_kategori', 'permohonan_perbaikan.kantor = master_kantor.kode_ktr'),
			'posisi' => array('left', 'left', 'left'),
		);

		$list = $this->apps_m->get_datatables($tabel, $param, $select, $where, $join);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $field) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $field->nama_aset;
			$row[] = $field->nama_kategori;
			$row[] = $field->nama_kantor;
			$row[] = $field->tgl_pengajuan;
			$row[] = $field->keterangan_perbaikan;
			$row[] = $field->jumlah_perbaikan;

			$urlubah = base_url() . "core-aset/edit_permohonan_perbaikan/" . $this->lib->encrypt_url($field->id_permohonan_perbaikan);
			$urlhapus = "onclick='return hapus(\"" . $this->lib->encrypt_url($field->id_permohonan_perbaikan) . "\")'";
			$urlterima = base_url() . "core-aset/terima_permohonan_perbaikan/" . $this->lib->encrypt_url($field->id_permohonan_perbaikan);
			$urltolak = "onclick='return tolak(\"" . $this->lib->encrypt_url($field->id_permohonan_perbaikan) . "\")'";
			$urldetail = base_url() . "core-aset/detail_permohonan_perbaikan/" . $this->lib->encrypt_url($field->id_permohonan_perbaikan);

			$menu_kasie = array(41, 51, 99);
			$menu_pincab = array(42, 52, 99);
			$temp = "";
			if (in_array($grup_pengguna, $menu_pincab)) {
				$temp .= " <a href=\"" . $urlterima . "\" class=\"btn btn-icon btn-sm btn-info\" data-toggle=\"tooltip\" data-original-title=\"Terima\"><i class=\"fa fa-check\"></i></a>";
				$temp .= " <a href=\"#\" " . $urltolak . " class=\"btn btn-icon btn-sm btn-danger\" data-toggle=\"tooltip\" data-original-title=\"Tolak\"><i class=\"fas fa-times\"></i></a>";
				$temp .= " <a href=\"" . $urldetail . "\" class=\"btn btn-icon btn-sm btn-secondary\" data-toggle=\"tooltip\" data-original-title=\"Detail\"><i class=\"fa fa-info\"></i></a>";
			} elseif (in_array($grup_pengguna, $menu_kasie)) {
				$temp .= " <a href=\"" . $urlubah . "\" class=\"btn btn-icon btn-sm btn-warning\" data-toggle=\"tooltip\" data-original-title=\"Ubah\"><i class=\"fas fa-edit\"></i></a>";
				$temp .= " <a href=\"#\" " . $urlhapus . " class=\"btn btn-icon btn-sm btn-danger\" data-toggle=\"tooltip\" data-original-title=\"Hapus\"><i class=\"fas fa-trash\"></i></a>";
				$temp .= " <a href=\"" . $urldetail . "\" class=\"btn btn-icon btn-sm btn-secondary\" data-toggle=\"tooltip\" data-original-title=\"Detail\"><i class=\"fa fa-info\"></i></a>";
			} else {
				$temp .= " <a href=\"" . $urldetail . "\" class=\"btn btn-icon btn-sm btn-secondary\" data-toggle=\"tooltip\" data-original-title=\"Detail\"><i class=\"fa fa-info\"></i></a>";
			}

			$row[] = $temp;

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->apps_m->count_all($tabel, $select, $where, $join),
			"recordsFiltered" => $this->apps_m->count_filtered($tabel, $param, $select, $where, $join),
			"data" => $data,
		);
		//output dalam format JSON
		echo json_encode($output);
	}

	public function get_pemeliharaan()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();

		$id = $this->uri->segment(3);
		$tabel = "permohonan_perbaikan";

		$key = "id_permohonan_perbaikan";
		$value = $id;
		$where = $this->lib->sql_encrypt_url($key, $value);

		$select = "*, " . $this->lib->sql_select_encrypt($key, "id_permohonan_perbaikan");
		$cek = $this->apps_m->get_data($tabel, $select, $where)->result_array();

		if (count($cek) != 0) {
			$result = $this->apps_m->get_data($tabel, $select, $where)->row();
			echo json_encode($result);
		} else {
			$respone = array("status" => FALSE, "pesan" => "Data tidak ditemukan.", "tipe_pesan" => "warning");
			echo json_encode($respone);
			echo "<script>self.history.back();</script>";
		}
	}


	public function penghapusan()
	{
		$this->lib->check_logged_in();
	}

	public function pengajuan_pusat()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();

		$data = array();
		$this->load->view('core-aset/pengajuan-pusat-v', $data);
	}

	public function get_data_pengajuan_pusat()
	{
		$this->lib->check_logged_in();
		$this->lib->check_access();
		$this->lib->check_method("POST");
		$kantor_pengguna = $this->session->userdata[$this->param['session']]['kantor'];

		$tabel = "permohonan_aset";
		$select = "*";
		$param = array(
			'column_order' => array(),
			'column_search' => array('nama_permohonan_aset', 'tgl_pengajuan'),
			'order' => array('tgl_pengajuan'),
			'order2' => array('ASC'),
		);
		//terima 1, ketika 1 tidak akan ditampilkan lagi di menu permohonan aset karena statusnya sudah berubah
		$where = "status_terima = 3 AND kantor = '" . $kantor_pengguna . "'";

		// $join = array();
		$join = array(
			'table' => array('master_kategori', 'master_kantor', 'master_jenis'),
			'kondisi' => array('permohonan_aset.kategori_permohonan_aset = master_kategori.id_kategori', 'permohonan_aset.kantor = master_kantor.kode_ktr', 'permohonan_aset.jenis_permohonan_aset = master_jenis.id_jenis'),
			'posisi' => array('left', 'left', 'left'),
		);

		$list = $this->apps_m->get_datatables($tabel, $param, $select, $where, $join);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $field) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $field->nama_permohonan_aset;
			$row[] = $field->nama_jenis;
			$row[] = $field->nama_kategori;
			$row[] = $field->tgl_pengajuan;
			$row[] = $field->estimasi_biaya;
			$row[] = $field->keterangan_permohonan;
			if ($field->file_upload == "") {
				$row[] = "-";
			} else {
				$row[] = "<a href=\"" . base_url() . "/assets/lampiran/upload/" . $field->file_upload . "\" target=\"_blank\" class=\"btn btn-icon btn-sm btn-light\"><i class=\"fas fa-file\"></i></a>";
			}


			$urlterima = base_url() . "core-aset/terima_permohonan_aset/" . $this->lib->encrypt_url($field->id_permohonan_aset);
			$urltolak = "onclick='return tolak(\"" . $this->lib->encrypt_url($field->id_permohonan_aset) . "\")'";
			$urldetail = base_url() . "core-aset/detail_permohonan_aset/" . $this->lib->encrypt_url($field->id_permohonan_aset);

			$grup_pengguna = $this->session->userdata[$this->param['session']]['grup_pengguna'];
			$menu_kasie_umum = array(52, 99);
			$menu_kadiv = array(53, 99);
			$temp = "";
			$biaya_cabang = $field->final_biaya_cabang;

			if ($biaya_cabang < 30000000) {
				# code...
			}
			if (in_array($grup_pengguna, $menu_kadiv)) {
				$temp .= " <a href=\"" . $urlterima . "\" class=\"btn btn-icon btn-sm btn-info\" data-toggle=\"tooltip\" data-original-title=\"Terima\"><i class=\"fa fa-check\"></i></a>";
				$temp .= " <a href=\"#\" " . $urltolak . " class=\"btn btn-icon btn-sm btn-danger\" data-toggle=\"tooltip\" data-original-title=\"Tolak\"><i class=\"fas fa-times\"></i></a>";
				$temp .= " <a href=\"" . $urldetail . "\" class=\"btn btn-icon btn-sm btn-secondary\" data-toggle=\"tooltip\" data-original-title=\"Detail\"><i class=\"fa fa-info\"></i></a>";
			} elseif (in_array($grup_pengguna, $menu_kasie_umum)) {
				$temp .= " <a href=\"" . $urldetail . "\" class=\"btn btn-icon btn-sm btn-secondary\" data-toggle=\"tooltip\" data-original-title=\"Detail\"><i class=\"fa fa-info\"></i></a>";
			} else {
				$temp .= " <a href=\"" . $urldetail . "\" class=\"btn btn-icon btn-sm btn-secondary\" data-toggle=\"tooltip\" data-original-title=\"Detail\"><i class=\"fa fa-info\"></i></a>";
			}

			$row[] = $temp;

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->apps_m->count_all($tabel, $select, $where, $join),
			"recordsFiltered" => $this->apps_m->count_filtered($tabel, $param, $select, $where, $join),
			"data" => $data,
		);
		//output dalam format JSON
		echo json_encode($output);
	}
}
