<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array('apps_m'));
    $this->param = $this->lib->parameter();
  }

  public function generate()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();

    $data = array();
    $this->load->view('master/generate-v', $data);
  }

  function get_data_generate()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();
    $this->lib->check_method("POST");

    $tabel = "master_generate";
    $select = "*";
    $param = array(
      'column_order' => array(),
      'column_search' => array('nama_aset', 'kode_aset'),
      'order' => array('aset.status_generate', 'aset.kode_aset', 'aset.tgl_perolehan'),
      'order2' => array('ASC', 'ASC', 'DESC'),
    );
    $where = "";
    // $join = array();
    $join = array(
      'table' => array('aset'),
      'kondisi' => array('master_generate.aset = aset.id_aset'),
      'posisi' => array('right'),
    );

    $list = $this->apps_m->get_datatables($tabel, $param, $select, $where, $join);
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $field) {

      $no++;
      $row = array();
      $row[] = $no;
      $row[] = $field->nama_aset;
      $row[] = $field->kode_aset;

      $urlgenerate = base_url() . "master/generate_aksi/" . $this->lib->encrypt_url($field->id_aset);

      $temp = "";
      if ($field->status_generate == 0) {
        $temp .= "<a href=\"" . $urlgenerate . "\"class=\"btn btn-icon icon-left btn-sm btn-info\" data-toggle=\"tooltip\" data-original-title=\"Generate\">Generate <i class=\"far fa-sun\"></i></a>";
      } else {
        $temp .= "<a href=\"" . $urlgenerate . "\"class=\"btn btn-icon icon-left btn-sm btn-warning\" data-toggle=\"tooltip\" data-original-title=\"Generate Ulang\">Regenerate <i class=\"far fa-sun\"></i></a>";
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

  public function generate_aksi()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();

    $id = $this->uri->segment(3);
    $tabel = "aset";
    $key = "id_aset";
    $value = $id;
    $where = $this->lib->sql_encrypt_url($key, $value);
    $join = array(
      'table' => array('master_generate'),
      'kondisi' => array('aset.id_aset = master_generate.aset'),
      'posisi' => array('left')
    );

    $select = "*, " . $this->lib->sql_select_encrypt($key, "idaset");
    $cek = $this->apps_m->get_data($tabel, $select, $where)->result_array();

    if (count($cek) != 0) {

      $result = $this->apps_m->get_data($tabel, $select, $where, "", $join)->result_array();
      // echo json_encode($result);
    } else {
      $respone = array("status" => FALSE, "pesan" => "Data tidak ditemukan.", "tipe_pesan" => "warning");
      echo json_encode($respone);
      echo "<script>self.history.back();</script>";
    }

    $aset = $result[0]['id_aset'];
    $status = $result[0]['status_generate'];
    $konten = $result[0]['content'];
    //link_qr tu link detail yang nanti merujuk ke data detail asetnya
    $link_qr = base_url() . "aset/detail_aset_qr/" . $this->lib->encrypt_url($aset);
    //content file tu letak file gambar qr di direktori
    $content = $this->lib->generate_qrcode($link_qr);

    //cek ketika data QR nya belum digenerate
    if ($status == 0) {
      $data = array('aset' => $aset, 'file_qr' => $link_qr, 'content' => $content['file']);
      $resultakhir = $this->apps_m->insert_data("master_generate", $data);
      $this->apps_m->update_data("aset", array('status_generate' => 1), $where);


      //cek ketika data QR nya sudah digenerate
    } else if ($status == 1) {

      $file_qr_old = explode("/", $konten);
      // @unlink("./assets/media/qrcode/" . $file_qr_old[3]);
      $where = "aset = '" . $aset . "'";
      $resultakhir = $this->apps_m->update_data("master_generate", array('file_qr' => $link_qr, 'content' => $content['file']), $where);
    }

    if ($resultakhir == TRUE) {
      // Log
      $operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
      $pengguna = $operator;
      $aksi = "Generate QR code";
      $keterangan = "Id aset : <b>" . $aset . "</b> dengan content : <b>" . $content['file'] . "</b>";
      $this->lib->aksi_log($pengguna, $aksi, $keterangan);
      // End Log

      $this->session->set_flashdata("pesan", "ada");
      $this->session->set_flashdata("pesan_isi", "Data berhasil digenerate.");
      $this->session->set_flashdata("pesan_tipe", "success");
      redirect('/master/generate');
    } else {
      $this->session->set_flashdata("pesan", "ada");
      $this->session->set_flashdata("pesan_isi", "Terjadi kesalahan inputan data!");
      $this->session->set_flashdata("pesan_tipe", "warning");
      echo "<script>self.history.back();</script>";
      exit;
    }
  }

  //untuk meregenaerate secara auto pakai function dibawah ini di url web browser
  public function generate_aksi_auto()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();
    // ini_set("max_execution_time", "0");

    $tabel = "aset";

    $join = array(
      'table' => array('master_generate'),
      'kondisi' => array('aset.id_aset = master_generate.aset'),
      'posisi' => array('left')
    );

    $select = "*";
    $result = $this->apps_m->get_data($tabel, $select, "status_generate = 0", "", $join, "", "")->result();

    foreach ($result as $data_baru) {
      $aset = $data_baru->id_aset;
      $status = $data_baru->status_generate;
      $konten = $data_baru->content;
      $link_qr = base_url() . "aset/detail_aset_qr/" . $this->lib->encrypt_url($aset);
      $content = $this->lib->generate_qrcode($link_qr);

      if ($status == 0) {
        $data = array('aset' => $aset, 'file_qr' => $link_qr, 'content' => $content['file']);
        $resultakhir = $this->apps_m->insert_data("master_generate", $data);
        $whereaset = "id_aset = '" . $aset . "'";
        $this->apps_m->update_data("aset", array('status_generate' => 1), $whereaset);

        //cek ketika data QR nya sudah digenerate
      } elseif ($status == 1) {
        $file_qr_old = explode("/", $konten);
        @unlink("./assets/media/qrcode/" . $file_qr_old[3]);
        $where = "aset = '" . $aset . "'";
        $resultakhir = $this->apps_m->update_data("master_generate", array('file_qr' => $link_qr, 'content' => $content['file']), $where);
      }

      if ($resultakhir == TRUE) {
        // Log
        $operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
        $pengguna = $operator;
        $aksi = "Generate QR code";
        $keterangan = "Id aset : <b>" . $aset . "</b> dengan content : <b>" . $content['file'] . "</b>";
        $this->lib->aksi_log($pengguna, $aksi, $keterangan);
      }
    }
  }

  public function kategori()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();

    $data = array();
    $this->load->view('master/kategori-v', $data);
  }

  function get_data_kategori()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();
    $this->lib->check_method("POST");

    $tabel = "master_kategori";
    $select = "*";
    $param = array(
      'column_order' => array(),
      'column_search' => array('nama_kategori'),
      'order' => array('id_kategori'),
      'order2' => array('ASC'),
    );
    $where = "";
    // $join = array();
    $join = "";

    $list = $this->apps_m->get_datatables($tabel, $param, $select, $where, $join);
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $field) {

      $no++;
      $row = array();
      $row[] = $no;
      $row[] = $field->nama_kategori;

      $urlubah = "onclick='return ubah(\"" . $this->lib->encrypt_url($field->id_kategori) . "\")'";
      $urlhapus = "onclick='return hapus(\"" . $this->lib->encrypt_url($field->id_kategori) . "\")'";

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

  public function get_kategori()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();

    $id = $this->uri->segment(3);
    $tabel = "master_kategori";

    $key = "id_kategori";
    $value = $id;
    $where = $this->lib->sql_encrypt_url($key, $value);

    $select = "*, " . $this->lib->sql_select_encrypt($key, "idkategori");
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

  public function kategori_aksi()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();
    $this->lib->check_method("POST");

    $tabel = "master_kategori";
    $action_method = $this->input->post('action_method');

    $id = $this->input->post('id_kategori');
    $nama_kategori = $this->input->post('nama_kategori');

    if ($action_method == "add") {

      $wherenama = "nama_kategori = '" . $nama_kategori . "'";
      $ceknama = $this->apps_m->get_data($tabel, "*", $wherenama)->result_array();

      if (count($ceknama) != 0) {
        $respone = array("status" => FALSE, "pesan" => "Nama kategori sudah pernah digunakan", "tipe_pesan" => "warning");
        echo json_encode($respone);
        exit;
      }

      $data = array(
        'nama_kategori' => $nama_kategori,
      );

      $result = $this->apps_m->insert_data($tabel, $data);
      if ($result == TRUE) {
        // Log
        $operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
        $pengguna = $operator;
        $aksi = "Tambah Data kategori";
        $keterangan = "dengan nama <b>" . $nama_kategori . "</b>";
        $this->lib->aksi_log($pengguna, $aksi, $keterangan);
        // End Log
        $respone = array("status" => TRUE, "pesan" => "Data berhasil ditambahkan.", "tipe_pesan" => "success");
        echo json_encode($respone);
      } else {
        $respone = array("status" => FALSE, "pesan" => "Terjadi kesalahan inputan data!", "tipe_pesan" => "warning");
        echo json_encode($respone);
      }
    } else if ($action_method == "edit") {

      $key = "id_kategori";
      $value = $id;
      $wherecek = $this->lib->sql_encrypt_url($key, $value);

      $select = "*";
      $cek = $this->apps_m->get_data($tabel, $select, $wherecek)->result_array();

      if (count($cek) != 0) {
        $id_kategori = $cek[0]['id_kategori'];

        $wherenama = "nama_kategori = '" . $nama_kategori . "' AND id_kategori != '" . $id_kategori . "'";
        $ceknama = $this->apps_m->get_data($tabel, $select, $wherenama)->result_array();

        if (count($ceknama) != 0) {
          $respone = array("status" => FALSE, "pesan" => "Nama Kategori sudah pernah digunakan", "tipe_pesan" => "warning");
          echo json_encode($respone);
          exit;
        }

        $data = array(
          'nama_kategori' => $nama_kategori,
        );

        $where = "id_kategori = '" . $id_kategori . "'";
        $result = $this->apps_m->update_data($tabel, $data, $where);
        if ($result == TRUE) {
          // Log
          $operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
          $pengguna = $operator;
          $aksi = "Ubah Data kategori";
          $keterangan = "dengan nama <b>" . $nama_kategori . "</b>";
          $this->lib->aksi_log($pengguna, $aksi, $keterangan);
          // End Log
          $respone = array("status" => TRUE, "pesan" => "Data berhasil diubah.", "tipe_pesan" => "success");
          echo json_encode($respone);
        } else {
          $respone = array("status" => FALSE, "pesan" => "Terjadi kesalahan inputan data!", "tipe_pesan" => "warning");
          echo json_encode($respone);
        }
      } else {
        $respone = array("status" => FALSE, "pesan" => "Data tidak ditemukan.", "tipe_pesan" => "warning");
        echo json_encode($respone);
      }
    } else if ($action_method == "hapus") {

      $key = "id_kategori";
      $value = $id;
      $where = $this->lib->sql_encrypt_url($key, $value);
      $select = "*";
      $cek = $this->apps_m->get_data($tabel, $select, $where)->result_array();

      if (count($cek) != 0) {
        $result = $this->apps_m->delete_data($tabel, $where);
        if ($result == TRUE) {
          // Log
          $operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
          $pengguna = $operator;
          $aksi = "Hapus Data kategori";
          $keterangan = "dengan nama <b>" . $cek[0]['nama_kategori'] . "</b>";
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
    } else {
      $respone = array("status" => FALSE, "pesan" => "Invalid Request", "tipe_pesan" => "warning");
      echo json_encode($respone);
    }
  }

  public function jenis()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();

    $data = array();
    $this->load->view('master/jenis-v', $data);
  }

  function get_data_jenis()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();
    $this->lib->check_method("POST");

    $tabel = "master_jenis";
    $select = "*";
    $param = array(
      'column_order' => array(),
      'column_search' => array('nama_jenis'),
      'order' => array('nama_jenis'),
      'order2' => array('ASC'),
    );
    $where = "";
    // $join = array();
    $join = "";

    $list = $this->apps_m->get_datatables($tabel, $param, $select, $where, $join);
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $field) {

      $no++;
      $row = array();
      $row[] = $no;
      $row[] = $field->nama_jenis;

      $urlubah = "onclick='return ubah(\"" . $this->lib->encrypt_url($field->id_jenis) . "\")'";
      $urlhapus = "onclick='return hapus(\"" . $this->lib->encrypt_url($field->id_jenis) . "\")'";

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

  public function get_jenis()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();

    $id = $this->uri->segment(3);
    $tabel = "master_jenis";

    $key = "id_jenis";
    $value = $id;
    $where = $this->lib->sql_encrypt_url($key, $value);

    $select = "*, " . $this->lib->sql_select_encrypt($key, "idjenis");
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

  public function jenis_aksi()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();
    $this->lib->check_method("POST");

    $tabel = "master_jenis";
    $action_method = $this->input->post('action_method');

    $id = $this->input->post('id_jenis');
    $nama_jenis = $this->input->post('nama_jenis');

    if ($action_method == "add") {

      $wherenama = "nama_jenis = '" . $nama_jenis . "'";
      $ceknama = $this->apps_m->get_data($tabel, "*", $wherenama)->result_array();

      if (count($ceknama) != 0) {
        $respone = array("status" => FALSE, "pesan" => "Nama Jenis sudah pernah digunakan", "tipe_pesan" => "warning");
        echo json_encode($respone);
        exit;
      }

      $data = array(
        'nama_jenis' => $nama_jenis,
      );

      $result = $this->apps_m->insert_data($tabel, $data);
      if ($result == TRUE) {
        // Log
        $operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
        $pengguna = $operator;
        $aksi = "Tambah Data jenis";
        $keterangan = "dengan nama <b>" . $nama_jenis . "</b>";
        $this->lib->aksi_log($pengguna, $aksi, $keterangan);
        // End Log
        $respone = array("status" => TRUE, "pesan" => "Data berhasil ditambahkan.", "tipe_pesan" => "success");
        echo json_encode($respone);
      } else {
        $respone = array("status" => FALSE, "pesan" => "Terjadi kesalahan inputan data!", "tipe_pesan" => "warning");
        echo json_encode($respone);
      }
    } else if ($action_method == "edit") {

      $key = "id_jenis";
      $value = $id;
      $wherecek = $this->lib->sql_encrypt_url($key, $value);

      $select = "*";
      $cek = $this->apps_m->get_data($tabel, $select, $wherecek)->result_array();

      if (count($cek) != 0) {
        $id_jenis = $cek[0]['id_jenis'];

        $wherenama = "nama_jenis = '" . $nama_jenis . "' AND id_jenis != '" . $id_jenis . "'";
        $ceknama = $this->apps_m->get_data($tabel, $select, $wherenama)->result_array();

        if (count($ceknama) != 0) {
          $respone = array("status" => FALSE, "pesan" => "Nama jenis sudah pernah digunakan", "tipe_pesan" => "warning");
          echo json_encode($respone);
          exit;
        }

        $data = array(
          'nama_jenis' => $nama_jenis,
        );

        $where = "id_jenis = '" . $id_jenis . "'";
        $result = $this->apps_m->update_data($tabel, $data, $where);
        if ($result == TRUE) {
          // Log
          $operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
          $pengguna = $operator;
          $aksi = "Ubah Data jenis";
          $keterangan = "dengan nama <b>" . $nama_jenis . "</b>";
          $this->lib->aksi_log($pengguna, $aksi, $keterangan);
          // End Log
          $respone = array("status" => TRUE, "pesan" => "Data berhasil diubah.", "tipe_pesan" => "success");
          echo json_encode($respone);
        } else {
          $respone = array("status" => FALSE, "pesan" => "Terjadi kesalahan inputan data!", "tipe_pesan" => "warning");
          echo json_encode($respone);
        }
      } else {
        $respone = array("status" => FALSE, "pesan" => "Data tidak ditemukan.", "tipe_pesan" => "warning");
        echo json_encode($respone);
      }
    } else if ($action_method == "hapus") {

      $key = "id_jenis";
      $value = $id;
      $where = $this->lib->sql_encrypt_url($key, $value);
      $select = "*";
      $cek = $this->apps_m->get_data($tabel, $select, $where)->result_array();

      if (count($cek) != 0) {
        $result = $this->apps_m->delete_data($tabel, $where);
        if ($result == TRUE) {
          // Log
          $operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
          $pengguna = $operator;
          $aksi = "Hapus Data jenis";
          $keterangan = "dengan nama <b>" . $cek[0]['nama_jenis'] . "</b>";
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
    } else {
      $respone = array("status" => FALSE, "pesan" => "Invalid Request", "tipe_pesan" => "warning");
      echo json_encode($respone);
    }
  }

  public function kondisi()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();

    $data = array();
    $this->load->view('master/kondisi-v', $data);
  }

  function get_data_kondisi()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();
    $this->lib->check_method("POST");

    $tabel = "master_kondisi";
    $select = "*";
    $param = array(
      'column_order' => array(),
      'column_search' => array('nama_kondisi'),
      'order' => array('id_kondisi'),
      'order2' => array('ASC'),
    );
    $where = "";
    // $join = array();
    $join = "";

    $list = $this->apps_m->get_datatables($tabel, $param, $select, $where, $join);
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $field) {

      $no++;
      $row = array();
      $row[] = $no;
      $row[] = $field->nama_kondisi;
      if ($field->status_kondisi == 1) {
        $row[] = "Aktif";
      } else {
        $row[] = "Tidak Aktif";
      }

      $urlubah = "onclick='return ubah(\"" . $this->lib->encrypt_url($field->id_kondisi) . "\")'";
      $urlhapus = "onclick='return hapus(\"" . $this->lib->encrypt_url($field->id_kondisi) . "\")'";

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

  public function get_kondisi()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();

    $id = $this->uri->segment(3);
    $tabel = "master_kondisi";

    $key = "id_kondisi";
    $value = $id;
    $where = $this->lib->sql_encrypt_url($key, $value);

    $select = "*, " . $this->lib->sql_select_encrypt($key, "idkondisi");
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

  public function kondisi_aksi()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();
    $this->lib->check_method("POST");

    $tabel = "master_kondisi";
    $action_method = $this->input->post('action_method');

    $id = $this->input->post('id_kondisi');
    $nama_kondisi = $this->input->post('nama_kondisi');
    $status_kondisi = $this->input->post('status_kondisi');

    if ($action_method == "add") {

      $wherenama = "nama_kondisi = '" . $nama_kondisi . "'";
      $ceknama = $this->apps_m->get_data($tabel, "*", $wherenama)->result_array();

      if (count($ceknama) != 0) {
        $respone = array("status" => FALSE, "pesan" => "Nama kondisi sudah pernah digunakan", "tipe_pesan" => "warning");
        echo json_encode($respone);
        exit;
      }

      $data = array(
        'nama_kondisi' => $nama_kondisi,
        'status_kondisi' => $status_kondisi,
      );

      $result = $this->apps_m->insert_data($tabel, $data);
      if ($result == TRUE) {
        // Log
        $operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
        $pengguna = $operator;
        $aksi = "Tambah Data kondisi";
        $keterangan = "dengan nama <b>" . $nama_kondisi . "</b>";
        $this->lib->aksi_log($pengguna, $aksi, $keterangan);
        // End Log
        $respone = array("status" => TRUE, "pesan" => "Data berhasil ditambahkan.", "tipe_pesan" => "success");
        echo json_encode($respone);
      } else {
        $respone = array("status" => FALSE, "pesan" => "Terjadi kesalahan inputan data!", "tipe_pesan" => "warning");
        echo json_encode($respone);
      }
    } else if ($action_method == "edit") {

      $key = "id_kondisi";
      $value = $id;
      $wherecek = $this->lib->sql_encrypt_url($key, $value);

      $select = "*";
      $cek = $this->apps_m->get_data($tabel, $select, $wherecek)->result_array();

      if (count($cek) != 0) {
        $id_kondisi = $cek[0]['id_kondisi'];

        $wherenama = "nama_kondisi = '" . $nama_kondisi . "' AND id_kondisi != '" . $id_kondisi . "'";
        $ceknama = $this->apps_m->get_data($tabel, $select, $wherenama)->result_array();

        if (count($ceknama) != 0) {
          $respone = array("status" => FALSE, "pesan" => "Nama kondisi sudah pernah digunakan", "tipe_pesan" => "warning");
          echo json_encode($respone);
          exit;
        }

        $data = array(
          'nama_kondisi' => $nama_kondisi,
          'status_kondisi' => $status_kondisi,
        );

        $where = "id_kondisi = '" . $id_kondisi . "'";
        $result = $this->apps_m->update_data($tabel, $data, $where);
        if ($result == TRUE) {
          // Log
          $operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
          $pengguna = $operator;
          $aksi = "Ubah Data kondisi";
          $keterangan = "dengan nama <b>" . $nama_kondisi . "</b>";
          $this->lib->aksi_log($pengguna, $aksi, $keterangan);
          // End Log
          $respone = array("status" => TRUE, "pesan" => "Data berhasil diubah.", "tipe_pesan" => "success");
          echo json_encode($respone);
        } else {
          $respone = array("status" => FALSE, "pesan" => "Terjadi kesalahan inputan data!", "tipe_pesan" => "warning");
          echo json_encode($respone);
        }
      } else {
        $respone = array("status" => FALSE, "pesan" => "Data tidak ditemukan.", "tipe_pesan" => "warning");
        echo json_encode($respone);
      }
    } else if ($action_method == "hapus") {

      $key = "id_kondisi";
      $value = $id;
      $where = $this->lib->sql_encrypt_url($key, $value);
      $select = "*";
      $cek = $this->apps_m->get_data($tabel, $select, $where)->result_array();

      if (count($cek) != 0) {
        $result = $this->apps_m->delete_data($tabel, $where);
        if ($result == TRUE) {
          // Log
          $operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
          $pengguna = $operator;
          $aksi = "Hapus Data kondisi";
          $keterangan = "dengan nama <b>" . $cek[0]['nama_kondisi'] . "</b>";
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
    } else {
      $respone = array("status" => FALSE, "pesan" => "Invalid Request", "tipe_pesan" => "warning");
      echo json_encode($respone);
    }
  }

  public function penyusutan()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();
    $join = array(
      'table' => array('aset', 'master_kategori'),
      'kondisi' => array('penyusutan.id_aset = aset.id_aset', 'aset.kategori_aset = master_kategori.id_kategori'),
      'posisi' => array('left', 'left'),
    );
    $where = "";
    $urut = "umur ASC";
    $data = array(
      'data_penyusutan' => $this->apps_m->get_data("penyusutan", "*", $where, $urut, $join)->result(),
    );
    $this->load->view('master/penyusutan-v', $data);
  }

  function get_data_penyusutan()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();
    $this->lib->check_method("POST");

    $tabel = "penyusutan";
    $select = "*";
    $param = array(
      'column_order' => array(),
      'column_search' => array('nama_aset'),
      'order' => array('id_penyusutan'),
      'order2' => array('ASC', 'ASC'),
    );
    $where = "";
    // $join = array();
    $join = array(
      'table' => array('aset'),
      'kondisi' => array('penyusutan.id_aset = aset.id_aset'),
      'posisi' => array('left'),
    );

    $list = $this->apps_m->get_datatables($tabel, $param, $select, $where, $join);
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $field) {

      $no++;
      $row = array();
      $row[] = $no;
      $row[] = $field->nama_aset;

      $urlubah = "onclick='return ubah(\"" . $this->lib->encrypt_url($field->id_penyusutan) . "\")'";
      $urlhapus = "onclick='return hapus(\"" . $this->lib->encrypt_url($field->id_penyusutan) . "\")'";

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

  //untuk select data di url
  public function get_penyusutan()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();

    $id = $this->uri->segment(3);
    $tabel = "penyusutan";

    $key = "id_penyusutan";
    $value = $id;
    $where = $this->lib->sql_encrypt_url($key, $value);

    $select = "*, " . $this->lib->sql_select_encrypt($key, "idpenyusutan");
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

  public function penyusutan_aksi()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();
    $this->lib->check_method("POST");

    $tabel = "penyusutan";
    $action_method = $this->input->post('action_method');

    $id = $this->uri->segment(3);
    $id_penyusutan = $this->input->post('id_penyusutan');
    $id_aset = $this->input->post('id_aset');
    $umur = $this->input->post('umur');
    $nilai_penyusutan = $this->input->post('nilai_penyusutan');

    if ($action_method == "add") {

      $where_aset = "id_aset = '" . $id_aset . "'";
      $cekid = $this->apps_m->get_data($tabel, "*", $where_aset)->result_array();

      if (count($cekid) != 0) {
        $respone = array("status" => FALSE, "pesan" => "Nilai penyusutan aset sudah ada", "tipe_pesan" => "warning");
        echo json_encode($respone);
        exit;
      }

      $data = array(
        'id_penyusutan' => $id_penyusutan,
      );

      $result = $this->apps_m->insert_data($tabel, $data);
      if ($result == TRUE) {
        // Log
        $operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
        $pengguna = $operator;
        $aksi = "Tambah Data penyusutan";
        $keterangan = "dengan id aset <b>" . $id_aset . "</b>";
        $this->lib->aksi_log($pengguna, $aksi, $keterangan);
        // End Log
        $respone = array("status" => TRUE, "pesan" => "Data berhasil ditambahkan.", "tipe_pesan" => "success");
        echo json_encode($respone);
      } else {
        $respone = array("status" => FALSE, "pesan" => "Terjadi kesalahan inputan data!", "tipe_pesan" => "warning");
        echo json_encode($respone);
      }
    } else if ($action_method == "edit") {

      $key = "id_penyusutan";
      $value = $id;
      $wherecek = $this->lib->sql_encrypt_url($key, $value);

      $select = "*";
      $cek = $this->apps_m->get_data($tabel, $select, $wherecek)->result_array();

      if (count($cek) != 0) {
        $id_penyusutan = $cek[0]['id_penyusutan'];

        $wherenama = "id_aset = '" . $id_aset . "' AND id_penyusutan != '" . $id_penyusutan . "'";
        $ceknama = $this->apps_m->get_data($tabel, $select, $wherenama)->result_array();

        if (count($ceknama) != 0) {
          $respone = array("status" => FALSE, "pesan" => "Nama penyusutan sudah pernah digunakan", "tipe_pesan" => "warning");
          echo json_encode($respone);
          exit;
        }

        $data = array(
          'id_penyusutan' => $id_penyusutan,
        );

        $where = "id_penyusutan = '" . $id_penyusutan . "'";
        $result = $this->apps_m->update_data($tabel, $data, $where);
        if ($result == TRUE) {
          // Log
          $operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
          $pengguna = $operator;
          $aksi = "Ubah Data penyusutan";
          $keterangan = "dengan id aset <b>" . $id_aset . "</b>";
          $this->lib->aksi_log($pengguna, $aksi, $keterangan);
          // End Log
          $respone = array("status" => TRUE, "pesan" => "Data berhasil diubah.", "tipe_pesan" => "success");
          echo json_encode($respone);
        } else {
          $respone = array("status" => FALSE, "pesan" => "Terjadi kesalahan inputan data!", "tipe_pesan" => "warning");
          echo json_encode($respone);
        }
      } else {
        $respone = array("status" => FALSE, "pesan" => "Data tidak ditemukan.", "tipe_pesan" => "warning");
        echo json_encode($respone);
      }
    } else if ($action_method == "hapus") {

      $key = "id_penyusutan";
      $value = $id;
      $where = $this->lib->sql_encrypt_url($key, $value);
      $select = "*";
      $cek = $this->apps_m->get_data($tabel, $select, $where)->result_array();

      if (count($cek) != 0) {
        $result = $this->apps_m->delete_data($tabel, $where);
        if ($result == TRUE) {
          // Log
          $operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
          $pengguna = $operator;
          $aksi = "Hapus Data penyusutan";
          $keterangan = "dengan id aset <b>" . $cek[0]['id_aset'] . "</b>";
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
    } else {
      $respone = array("status" => FALSE, "pesan" => "Invalid Request", "tipe_pesan" => "warning");
      echo json_encode($respone);
    }
  }
  public function lokasi()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();

    $data = array(
      'master_kantor' => $this->apps_m->get_data("master_kantor", "*", "")->result(),
    );
    $this->load->view('master/lokasi-v', $data);
  }

  function get_data_lokasi()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();
    $this->lib->check_method("POST");

    $tabel = "master_lokasi";
    $select = "*";
    $param = array(
      'column_order' => array(),
      'column_search' => array('nama_lokasi', 'nama_kantor'),
      'order' => array('kode_ktr', 'nama_lokasi'),
      'order2' => array('ASC', 'ASC'),
    );
    $where = "";

    $join = array(
      'table' => array('master_kantor'),
      'kondisi' => array('master_lokasi.kantor = master_kantor.kode_ktr'),
      'posisi' => array('left'),
    );

    $list = $this->apps_m->get_datatables($tabel, $param, $select, $where, $join);
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $field) {

      $no++;
      $row = array();
      $row[] = $no;
      $row[] = $field->nama_lokasi;
      $row[] = $field->nama_kantor;

      $urlubah = "onclick='return ubah(\"" . $this->lib->encrypt_url($field->id_lokasi) . "\")'";
      $urlhapus = "onclick='return hapus(\"" . $this->lib->encrypt_url($field->id_lokasi) . "\")'";

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

  public function get_lokasi()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();

    $id = $this->uri->segment(3);
    $tabel = "master_lokasi";

    $key = "id_lokasi";
    $value = $id;
    $where = $this->lib->sql_encrypt_url($key, $value);

    $select = "*, " . $this->lib->sql_select_encrypt($key, "idlokasi");
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

  public function lokasi_aksi()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();
    $this->lib->check_method("POST");

    $tabel = "master_lokasi";
    $action_method = $this->input->post('action_method');

    $id = $this->input->post('id_lokasi');
    $nama_lokasi = $this->input->post('nama_lokasi');
    $lokasi_kantor = $this->input->post('lokasi_kantor');

    if ($action_method == "add") {

      $wherenama = "nama_lokasi = '" . $nama_lokasi . "' AND kantor = '" . $lokasi_kantor . "'";
      $ceknama = $this->apps_m->get_data($tabel, "*", $wherenama)->result_array();

      if (count($ceknama) != 0) {
        $respone = array("status" => FALSE, "pesan" => "Nama lokasi di kantor bersangkutan sudah pernah digunakan", "tipe_pesan" => "warning");
        echo json_encode($respone);
        exit;
      }

      $data = array(
        'nama_lokasi' => $nama_lokasi,
        'kantor' => $lokasi_kantor,
      );

      $result = $this->apps_m->insert_data($tabel, $data);
      if ($result == TRUE) {
        // Log
        $operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
        $pengguna = $operator;
        $aksi = "Tambah Data lokasi";
        $keterangan = "dengan nama <b>" . $nama_lokasi . "</b>";
        $this->lib->aksi_log($pengguna, $aksi, $keterangan);
        // End Log
        $respone = array("status" => TRUE, "pesan" => "Data berhasil ditambahkan.", "tipe_pesan" => "success");
        echo json_encode($respone);
      } else {
        $respone = array("status" => FALSE, "pesan" => "Terjadi kesalahan inputan data!", "tipe_pesan" => "warning");
        echo json_encode($respone);
      }
    } else if ($action_method == "edit") {

      $key = "id_lokasi";
      $value = $id;
      $wherecek = $this->lib->sql_encrypt_url($key, $value);

      $select = "*";
      $cek = $this->apps_m->get_data($tabel, $select, $wherecek)->result_array();

      if (count($cek) != 0) {
        $id_lokasi = $cek[0]['id_lokasi'];

        $wherenama = "nama_lokasi = '" . $nama_lokasi . "' AND id_lokasi != '" . $id_lokasi . "' AND kantor != '" . $lokasi_kantor . "'";
        $ceknama = $this->apps_m->get_data($tabel, $select, $wherenama)->result_array();

        if (count($ceknama) != 0) {
          $respone = array("status" => FALSE, "pesan" => "Nama lokasi di kantor bersangkutan sudah pernah digunakan", "tipe_pesan" => "warning");
          echo json_encode($respone);
          exit;
        }

        $data = array(
          'nama_lokasi' => $nama_lokasi,
          'kantor' => $lokasi_kantor,
        );

        $where = "id_lokasi = '" . $id_lokasi . "'";
        $result = $this->apps_m->update_data($tabel, $data, $where);
        if ($result == TRUE) {
          // Log
          $operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
          $pengguna = $operator;
          $aksi = "Ubah Data lokasi";
          $keterangan = "dengan nama <b>" . $nama_lokasi . "</b>";
          $this->lib->aksi_log($pengguna, $aksi, $keterangan);
          // End Log
          $respone = array("status" => TRUE, "pesan" => "Data berhasil diubah.", "tipe_pesan" => "success");
          echo json_encode($respone);
        } else {
          $respone = array("status" => FALSE, "pesan" => "Terjadi kesalahan inputan data!", "tipe_pesan" => "warning");
          echo json_encode($respone);
        }
      } else {
        $respone = array("status" => FALSE, "pesan" => "Data tidak ditemukan.", "tipe_pesan" => "warning");
        echo json_encode($respone);
      }
    } else if ($action_method == "hapus") {

      $key = "id_lokasi";
      $value = $id;
      $where = $this->lib->sql_encrypt_url($key, $value);
      $select = "*";
      $cek = $this->apps_m->get_data($tabel, $select, $where)->result_array();

      if (count($cek) != 0) {
        $result = $this->apps_m->delete_data($tabel, $where);
        if ($result == TRUE) {
          // Log
          $operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
          $pengguna = $operator;
          $aksi = "Hapus Data lokasi";
          $keterangan = "dengan nama <b>" . $cek[0]['nama_lokasi'] . "</b>";
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
    } else {
      $respone = array("status" => FALSE, "pesan" => "Invalid Request", "tipe_pesan" => "warning");
      echo json_encode($respone);
    }
  }
}
