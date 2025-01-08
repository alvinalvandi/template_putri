<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Aset extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array('apps_m'));
    $this->param = $this->lib->parameter();
  }
  public function aktif()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();

    $data = array(
      "page" => "aktif",
      'judul' => "Aktif",
    );
    $this->load->view('aset/aset-v', $data);
  }

  public function tidak_aktif()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();

    $data = array(
      "page" => "tidak-aktif",
      'judul' => "Tidak Aktif",
    );
    $this->load->view('aset/aset-v', $data);
  }

  public function hapus()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();

    $data = array(
      "page" => "hapus",
      'judul' => "Hapus",
    );
    $this->load->view('aset/aset-v', $data);
  }

  //tampilan tabel awal untuk nampilin data
  function get_data_aset()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();
    $this->lib->check_method("POST");

    $uri3 = $this->lib->slug($this->uri->segment(3));
    $grup_pengguna = $this->session->userdata[$this->param['session']]['grup_pengguna'];
    $kantor = $this->session->userdata[$this->param['session']]['kantor'];

    $tabel = "aset";
    $select = "*";
    $param = array(
      'column_order' => array(),
      'column_search' => array('nama_aset', 'nama_kategori', 'nama_kantor', 'kode_aset'),
      'order' => array('id_aset', 'kode_ktr'),
      'order2' => array('DESC', 'ASC'),
    );

    $where = "";
    if ($uri3 == "aktif") {
      $where .= "status_aset = 1";
    } else if ($uri3 == "tidak-aktif") {
      $where .= "status_aset = 0";
    } else if ($uri3 == "hapus") {
      $where .= "status_aset = 99";
    }

    if ($grup_pengguna == 99 || $grup_pengguna == 51 || $grup_pengguna == 52 || $grup_pengguna == 53) {
      $where = $where;
    } else {
      $where .= " AND aset.kantor = '" . $kantor . "'";
    }

    // $join = array();
    $join = array(
      'table' => array('master_jenis', 'master_kategori', 'master_kondisi', 'master_kantor', 'master_lokasi'),
      'kondisi' => array('aset.jenis_aset = master_jenis.id_jenis', 'aset.kategori_aset = master_kategori.id_kategori', 'aset.kondisi_aset = master_kondisi.id_kondisi', 'aset.kantor = master_kantor.kode_ktr', 'aset.lokasi = master_lokasi.id_lokasi'),
      'posisi' => array('left', 'left', 'left', 'left', 'left'),
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
      $row[] = $field->nama_jenis;
      $row[] = $field->nama_kategori;
      $row[] = $field->nama_kantor;
      $row[] = $field->nama_lokasi;
      $row[] = $field->tgl_perolehan;
      $row[] = $field->harga_perolehan;
      $row[] = $field->nama_kondisi;

      $urlubah = base_url() . "aset/edit_aset/" . $this->lib->encrypt_url($field->id_aset);
      $urlhapus = "onclick='return hapus(\"" . $this->lib->encrypt_url($field->id_aset) . "\")'";
      $urldetail = base_url() . "aset/detail_aset/" . $this->lib->encrypt_url($field->id_aset);

      $menu  = array(41, 42, 51, 52, 53, 99);

      $temp = "";
      if (in_array($grup_pengguna, $menu)) {
        $temp .= " <a href=\"" . $urldetail . "\" class=\"btn btn-icon btn-sm btn-info\" data-toggle=\"tooltip\" data-original-title=\"Detail\"><i class=\"fas fa-info-circle\"></i></a>";
        $temp .= " <a href=\"" . $urlubah . "\" class=\"btn btn-icon btn-sm btn-warning\" data-toggle=\"tooltip\" data-original-title=\"Ubah\"><i class=\"fas fa-edit\"></i></a>";
        $temp .= " <a href=\"#\" " . $urlhapus . " class=\"btn btn-icon btn-sm btn-danger\" data-toggle=\"tooltip\" data-original-title=\"Hapus\"><i class=\"fas fa-trash\"></i></a>";
      } else {
        $temp .= " <a href=\"" . $urldetail . "\" class=\"btn btn-icon btn-sm btn-info\" data-toggle=\"tooltip\" data-original-title=\"Detail\"><i class=\"fas fa-info-circle\"></i></a>";
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

  //data ajax
  public function get_aset()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();

    $id = $this->uri->segment(3);
    $tabel = "aset";

    $key = "id_aset";
    $value = $id;
    $where = $this->lib->sql_encrypt_url($key, $value);

    $select = "*, " . $this->lib->sql_select_encrypt($key, "id_aset");
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

  public function add_aset()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();

    //cek kantor pengguna
    $kantor = $this->session->userdata[$this->param['session']]['kantor'];

    if ($kantor == "000") {
      $data_kantor = $this->apps_m->get_data("master_kantor", "*", "jenis_kantor = 1", "id_kantor ASC")->result();
    } else {
      $data_kantor = $this->apps_m->get_data("master_kantor", "*", "kode_ktr = '" . $kantor . "'")->result();
    }

    $status_aset = "0";
    //generate kode qr 1 sudah 0 belum
    $status_generate = "1";
    $kode_awal_aset = $this->apps_m->get_data("aset", "kode_aset", "", "id_aset DESC")->result_array();
    $last_id2 = $kode_awal_aset[0]['kode_aset'];
    $last_id1 = explode("/", $last_id2);
    $last_id = intval($last_id1[0]) + 1;

    if (isset($_POST['btn-simpan'])) {

      $tabel = "aset";
      $nama_aset = $this->input->post('nama_aset');
      $kode_aset = $this->input->post('kode_aset');
      $jenis_aset = $this->input->post('jenis_aset');
      $kategori_aset = $this->input->post('kategori_aset');
      $kantor = $this->input->post('kantor');
      $lokasi = $this->input->post('lokasi');
      $kondisi_aset = $this->input->post('kondisi_aset');
      $tgl_perolehan = $this->input->post('tgl_perolehan');
      $harga_perolehan = $this->input->post('harga_perolehan');
      //mendapatkan data status berdasarkan post kondisi_aset yang berupa id
      $status_kondisi = $this->apps_m->get_data("master_kondisi", "status_kondisi", "id_kondisi = $kondisi_aset")->result_array();

      if ($status_kondisi[0]['status_kondisi'] != 0) {
        $status_aset = $status_kondisi[0]['status_kondisi'];
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

      $result = $this->apps_m->insert_data($tabel, $data);
      if ($result == TRUE) {
        $inserted_id = $this->db->insert_id();
        //link_qr tu link detail yang nanti merujuk ke data detail asetnya
        $link_qr = base_url() . "aset/detail_aset_qr/" . $this->lib->encrypt_url($inserted_id);
        //content file tu letak file gambar qr di direktori
        $content = $this->lib->generate_qrcode($link_qr);
        $this->apps_m->insert_data("master_generate", array('aset' => $inserted_id, 'file_qr' => $link_qr, 'content' => $content['file']));
        // Log
        $operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
        $pengguna = $operator;
        $aksi = "Tambah Aset";
        $keterangan = "nama aset : <b>" . $nama_aset . "</b> dengan harga perolehan <b>" . $harga_perolehan . "</b>";
        $this->lib->aksi_log($pengguna, $aksi, $keterangan);
        // End Log

        $this->session->set_flashdata("pesan", "ada");
        $this->session->set_flashdata("pesan_isi", "Data berhasil ditambahkan.");
        $this->session->set_flashdata("pesan_tipe", "success");
        redirect('/aset/aktif');
      } else {
        $this->session->set_flashdata("pesan", "ada");
        $this->session->set_flashdata("pesan_isi", "Terjadi kesalahan inputan data!");
        $this->session->set_flashdata("pesan_tipe", "warning");
        echo "<script>self.history.back();</script>";
      }
    } else {
      //data select option untuk ditampilkan di form
      $data = array(
        'last_id' => $last_id,
        'master_jenis' => $this->apps_m->get_data("master_jenis", "*", "", "id_jenis ASC")->result(),
        'master_kategori' => $this->apps_m->get_data("master_kategori", "*", "", "id_kategori ASC")->result(),
        'master_kondisi' => $this->apps_m->get_data("master_kondisi", "*", "", "id_kondisi ASC")->result(),
        'master_kantor' => $data_kantor,

      );
      $this->load->view('aset/add-aset-v', $data);
    }
  }

  public function edit_aset()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();

    $tabel = "aset";
    if (isset($_POST['btn-ubah'])) {

      $idaset = $this->input->post('idaset');

      $key = "id_aset";
      $value = $idaset;
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

      $id_aset = $result[0]['id_aset'];
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

      $wherekodeaset = "kode_aset = '" . $kode_aset . "' AND id_aset != '" . $id_aset . "'";
      $cekkodeaset = $this->apps_m->get_data($tabel, "*", $wherekodeaset)->result_array();
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
      );

      $where = "id_aset = '" . $id_aset . "'";
      $result = $this->apps_m->update_data($tabel, $data, $where);
      if ($result == TRUE) {
        // Log
        $operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
        $pengguna = $operator;
        $aksi = "Ubah Data Aset";
        $keterangan = "Nama aset : <b>" . $nama_aset . "</b> dengan harga perolehan : <b>" . $harga_perolehan . "</b>";
        $this->lib->aksi_log($pengguna, $aksi, $keterangan);
        // End Log

        $this->session->set_flashdata("pesan", "ada");
        $this->session->set_flashdata("pesan_isi", "Data berhasil diubah.");
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
      $key = "id_aset";
      $value = $id;
      $select = "*";
      $where = $this->lib->sql_encrypt_url($key, $value);
      $result = $this->apps_m->get_data($tabel, $select, $where)->result_array();

      if (count($result) != 0) {
        $data = array(
          'aset' => $result,
          'master_jenis' => $this->apps_m->get_data("master_jenis", "*", "", "id_jenis ASC")->result(),
          'master_kategori' => $this->apps_m->get_data("master_kategori", "*", "", "id_kategori ASC")->result(),
          'master_kondisi' => $this->apps_m->get_data("master_kondisi", "*", "", "id_kondisi ASC")->result(),
          'master_kantor' => $this->apps_m->get_data("master_kantor", "*", "", "id_kantor ASC")->result(),
        );
        $this->load->view('aset/edit-aset-v', $data);
      } else {
        $this->session->set_flashdata("pesan", "ada");
        $this->session->set_flashdata("pesan_isi", "Data tidak ditemukan.");
        $this->session->set_flashdata("pesan_tipe", "warning");
        redirect('/aset/aktif');
      }
    }
  }

  //menu detail aset
  public function detail_aset()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();

    $id = $this->uri->segment(3);
    $tabel = "aset";
    $key = "id_aset";
    $value = $id;
    $select = "*";
    $where = $this->lib->sql_encrypt_url($key, $value);
    $join = array(
      'table' => array('master_jenis', 'master_kategori', 'master_kondisi', 'master_kantor', 'master_lokasi', 'master_generate'),
      'kondisi' => array('aset.jenis_aset = master_jenis.id_jenis', 'aset.kategori_aset = master_kategori.id_kategori', 'aset.kondisi_aset = master_kondisi.id_kondisi', 'aset.kantor = master_kantor.kode_ktr', 'aset.lokasi = master_lokasi.id_lokasi', 'aset.id_aset = master_generate.aset'),
      'posisi' => array('left', 'left', 'left', 'left', 'left', 'left'),
    );
    $result = $this->apps_m->get_data($tabel, $select, $where, "", $join)->result_array();

    if (count($result) != 0) {
      $data = array(
        'aset' => $result,
      );
      $this->load->view('aset/aset-detail-v', $data);
    } else {
      $this->session->set_flashdata("pesan", "ada");
      $this->session->set_flashdata("pesan_isi", "Data tidak ditemukan.");
      $this->session->set_flashdata("pesan_tipe", "warning");
      redirect('/aset/aktif');
    }
  }

  //scan dari qr code
  public function detail_aset_qr()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();

    $id = $this->uri->segment(3);
    $tabel = "aset";
    $key = "id_aset";
    $value = $id;
    $select = "*";
    $where = $this->lib->sql_encrypt_url($key, $value);
    $join = array(
      'table' => array('master_jenis', 'master_kategori', 'master_kondisi', 'master_kantor', 'master_lokasi', 'master_generate'),
      'kondisi' => array('aset.jenis_aset = master_jenis.id_jenis', 'aset.kategori_aset = master_kategori.id_kategori', 'aset.kondisi_aset = master_kondisi.id_kondisi', 'aset.kantor = master_kantor.kode_ktr', 'aset.lokasi = master_lokasi.id_lokasi', 'aset.id_aset = master_generate.aset'),
      'posisi' => array('left', 'left', 'left', 'left', 'left', 'left'),
    );
    $result = $this->apps_m->get_data($tabel, $select, $where, "", $join)->result_array();

    if (count($result) != 0) {
      $data = array(
        'aset' => $result,
      );
      $this->load->view('aset/aset-detail-qr-v', $data);
    } else {
      $this->session->set_flashdata("pesan", "ada");
      $this->session->set_flashdata("pesan_isi", "Data tidak ditemukan.");
      $this->session->set_flashdata("pesan_tipe", "warning");
      redirect('/aset/aktif');
    }
  }

  function get_data_detail_lokasi()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();
    $this->lib->check_method("POST");
    $kode_ktr = $this->input->post('ajax', TRUE);
    $data = $this->apps_m->get_data("master_lokasi", "*", "kantor = '" . $kode_ktr . "'")->result();
    echo json_encode($data);
  }

  public function delete()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();
    $this->lib->check_method("POST");

    $tabel = "aset";
    $id = $this->input->post('id_aset');

    $key = "id_aset";
    $value = $id;
    $where = $this->lib->sql_encrypt_url($key, $value);
    $select = "*";
    $cek = $this->apps_m->get_data($tabel, $select, $where)->result_array();
    $data = array(
      'status_aset' => '99',
    );

    if (count($cek) != 0) {
      $result = $this->apps_m->update_data($tabel, $data, $where);
      if ($result == TRUE) {
        // Log
        $operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
        $pengguna = $operator;
        $aksi = "Hapus Data Aset";
        $keterangan = "ID aset <b>" . $cek[0]['id_aset'] . "</b> dengan nama <b>" . $cek[0]['nama_aset'] . "</b>";
        $this->lib->aksi_log($pengguna, $aksi, $keterangan);
        // End Log
        $respone = array("status" => TRUE, "pesan" => "Data Aset sudah di hapus!", "tipe_pesan" => "success");
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

  public function cetak_label()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();

    $data = array();
    $this->load->view('aset/cetak-label-v', $data);
  }

  function get_data_cetak_label()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();
    $this->lib->check_method("POST");

    $tabel = "aset";
    $select = "*";
    $param = array(
      'column_order' => array(),
      'column_search' => array('nama_aset', 'kode_aset', 'nama_kantor'),
      'order' => array('aset.kantor', 'aset.id_aset'),
      'order2' => array('ASC', 'DESC'),
    );
    $where = "aset.status_aset <> 99 AND aset.status_generate = 1";
    // $join = array();
    $join = array(
      'table' => array('master_kantor'),
      'kondisi' => array('aset.kantor = master_kantor.kode_ktr'),
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
      $row[] = $field->kode_aset;
      $row[] = $field->nama_kantor;

      $urlcetak = base_url() . "aset/cetak_label_aksi/" . $this->lib->encrypt_url($field->id_aset);

      $temp = "";
      $temp .= "<a href=\"$urlcetak\" class=\"btn btn-icon btn-sm icon-left btn-info\"><i class=\"fas fa-print\"></i> Cetak</a>";

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

  public function cetak_label_aksi()
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
      'posisi' => array('left'),
    );

    $select = "*, " . $this->lib->sql_select_encrypt($key, "idaset");
    $cek = $this->apps_m->get_data($tabel, $select, $where, "", $join)->result_array();

    if (count($cek) != 0) {
      $data = array(
        'dataaset' => $cek,
      );
    } else {
      $respone = array("status" => FALSE, "pesan" => "Data tidak ditemukan.", "tipe_pesan" => "warning");
      echo json_encode($respone);
      echo "<script>self.history.back();</script>";
    }
    $this->load->view('aset/cetak-label-aksi-v', $data);
  }
}
