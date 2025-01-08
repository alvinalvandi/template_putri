<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array('apps_m'));
    $this->param = $this->lib->parameter();
  }

  public function aset()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();

    $tabel = "aset";

    if (isset($_POST['btn-filter'])) {
      $kantor = $this->input->post('kantor');
      $jenis = $this->input->post('jenis');
      $kategori = $this->input->post('kategori');
      $minharga = $this->input->post('min');
      $maxharga = $this->input->post('max');
      $kondisi = $this->input->post('kondisi');
      $status = $this->input->post('status');
      $bulan = $this->input->post('bulan');
      $tahun = $this->input->post('tahun');


      $where = "";
      //deklarasi kondisi where dimana jika valuenya semua, maka value nya "-", jika bukan semua atau ada valuenya maka akan dideklarasikan di where
      if ($kantor == "-") {
        $wherekan = "";
      } else {
        $wherekan = " aset.kantor = '" . $kantor . "'";
      }

      if ($jenis == "-") {
        $wherejen = "";
      } else {
        $wherejen = " aset.jenis_aset = '" . $jenis . "'";
      }

      if ($kategori == "-") {
        $wherekat = "";
      } else {
        $wherekat = " aset.kategori_aset = '" . $kategori . "'";
      }

      if ($minharga == "" && $maxharga != "") {
        $whereharga = " aset.harga_perolehan <= '" . $maxharga . "'";
      } else if ($minharga != "" && $maxharga == "") {
        $whereharga = " aset.harga_perolehan >= '" . $minharga . "'";
      } else if ($minharga != "" && $maxharga != "") {
        $whereharga = " aset.harga_perolehan BETWEEN '" . $minharga . "' AND '" . $maxharga . "'";
      } else {
        $whereharga = "";
      }

      if ($kondisi == "-") {
        $wherekon = "";
      } else {
        $wherekon = " aset.kondisi_aset = '" . $kondisi . "'";
      }

      if ($status == "-") {
        $wherestat = "";
      } else {
        $wherestat = " aset.status_aset = '" . $status . "'";
      }

      if ($bulan == "-") {
        $wherebul = "";
      } else {
        $wherebul = " MONTH(aset.tgl_perolehan) = '" . (int)$bulan . "'";
      }

      if ($tahun == "-") {
        $wheretah = "";
      } else {
        $wheretah = " YEAR(aset.tgl_perolehan) = '" . (int)$tahun . "'";
      }

      if ($status == "-") {
        $wherestat = "";
      } else {
        $wherestat = " aset.status_aset = '" . $status . "'";
      }

      //logika kondisi where
      if ($wherekan != "" && ($wherejen != "" || $wherekat != "" || $whereharga != "" || $wherekon != "" || $wherestat != "" || $wherebul != "" || $wheretah != "")) {
        $wherekan .= " AND";
      }
      if ($wherejen != "" && ($wherekat != "" || $whereharga != "" || $wherekon != "" || $wherestat != "" || $wherebul != "" || $wheretah != "")) {
        $wherejen .= " AND";
      }
      if ($wherekat != "" && ($whereharga != "" || $wherekon != "" || $wherestat != "" || $wherebul != "" || $wheretah != "")) {
        $wherekat .= " AND";
      }
      if ($whereharga != "" && ($wherekon != "" || $wherestat != "" || $wherebul != "" || $wheretah != "")) {
        $whereharga .= " AND";
      }
      if ($wherekon != "" && ($wherestat != "" || $wherebul != "" || $wheretah != "")) {
        $wherekon .= " AND";
      }
      if ($wherebul != "" && ($wherestat != "" || $wheretah != "")) {
        $wherebul .= " AND";
      }
      if ($wheretah != "" && $wherestat != "") {
        $wheretah .= " AND";
      }
      if ($wherestat == "" && ($wherekan != "" || $wherejen != "" || $wherekat != "" || $whereharga != "" || $wherekon != "" || $wherebul != "" || $wheretah != "")) {
        $wherestat = " AND aset.status_aset <> 99";
      }
      if ($wherestat == "" && $wherekan == "" && $wherejen == "" && $wherekat == "" && $whereharga == "" && $wherekon == "" && $wherebul == "" && $wheretah == "") {
        $wherestat = "aset.status_aset <> 99";
      }

      $where = $wherekan . $wherejen . $wherekat . $whereharga . $wherekon . $wherebul . $wheretah . $wherestat;
      $select = "*";
      $join = array(
        'table' => array('master_kantor', 'master_lokasi', 'master_kategori', 'master_jenis', 'master_kondisi'),
        'kondisi' => array('aset.kantor = master_kantor.kode_ktr', 'aset.lokasi = master_lokasi.id_lokasi', 'aset.kategori_aset = master_kategori.id_kategori', 'aset.jenis_aset = master_jenis.id_jenis', 'aset.kondisi_aset = master_kondisi.id_kondisi'),
        'posisi' => array('left', 'left', 'left', 'left', 'left'),
      );
      $aset = $this->apps_m->get_data($tabel, $select, $where, "aset.kantor ASC, aset.tgl_perolehan DESC", $join)->result_array();

      $data = array(
        'page' => "Daftar Laporan Aset",
        'aset' => $aset,
        'kantor' => $kantor,
        'jenis' => $jenis,
        'kategori' => $kategori,
        'kondisi' => $kondisi,
        'status' => $status,
        'datakantor' => $this->apps_m->get_data("master_kantor", "*", "", "kode_ktr ASC")->result(),
        'datajenis' => $this->apps_m->get_data("master_jenis", "*", "")->result(),
        'datakategori' => $this->apps_m->get_data("master_kategori", "*", "")->result(),
        'datakondisi' => $this->apps_m->get_data("master_kondisi", "*", "", "")->result(),
        'filterkantor' => $this->apps_m->get_data("master_kantor", "*", "kode_ktr = '" . $kantor . "'", "kode_ktr ASC")->result_array(),
        'filterjenis' => $this->apps_m->get_data("master_jenis", "*", "id_jenis = '" . $jenis . "'", "id_jenis ASC")->result_array(),
        'filterkategori' => $this->apps_m->get_data("master_kategori", "*", "id_kategori = '" . $kategori . "'", "id_kategori ASC")->result_array(),
        'filterkondisi' => $this->apps_m->get_data("master_kondisi", "*", "id_kondisi = '" . $kondisi . "'", "id_kondisi ASC")->result_array()
      );
    } else {
      $kantor = "-";
      $jenis = "-";
      $kategori = "-";
      $kondisi = "-";
      $status = "-";
      $aset = "-";
      $where = "aset.status_aset <> 99";
      $select = "*";
      $join = array(
        'table' => array('master_kantor', 'master_lokasi', 'master_kategori', 'master_jenis', 'master_kondisi'),
        'kondisi' => array('aset.kantor = master_kantor.kode_ktr', 'aset.lokasi = master_lokasi.id_lokasi', 'aset.kategori_aset = master_kategori.id_kategori', 'aset.jenis_aset = master_jenis.id_jenis', 'aset.kondisi_aset = master_kondisi.id_kondisi'),
        'posisi' => array('left', 'left', 'left', 'left', 'left'),
      );

      $aset = $this->apps_m->get_data($tabel, $select, $where . " AND MONTH(aset.tgl_perolehan) = " . date('m') . " AND YEAR(aset.tgl_perolehan)  = " . date('Y') . "", "", $join)->result_array();

      $data = array(
        'page' => "Daftar Laporan Aset",
        'aset' => $aset,
        'kantor' => $kantor,
        'jenis' => $jenis,
        'kategori' => $kategori,
        'kondisi' => $kondisi,
        'status' => $status,
        'datakantor' => $this->apps_m->get_data("master_kantor", "*", "", "kode_ktr ASC")->result(),
        'datajenis' => $this->apps_m->get_data("master_jenis", "*", "")->result(),
        'datakategori' => $this->apps_m->get_data("master_kategori", "*", "")->result(),
        'datakondisi' => $this->apps_m->get_data("master_kondisi", "*", "", "")->result(),
        'filterkantor' => $this->apps_m->get_data("master_kantor", "*", "kode_ktr = '" . $kantor . "'", "kode_ktr ASC")->result_array(),
        'filterjenis' => $this->apps_m->get_data("master_jenis", "*", "id_jenis = '" . $jenis . "'", "id_jenis ASC")->result_array(),
        'filterkategori' => $this->apps_m->get_data("master_kategori", "*", "id_kategori = '" . $kategori . "'", "id_kategori ASC")->result_array(),
        'filterkondisi' => $this->apps_m->get_data("master_kondisi", "*", "id_kondisi = '" . $kondisi . "'", "id_kondisi ASC")->result_array()
      );
    }


    $this->load->view('laporan/aset-v', $data);
  }

  public function get_data_kondisi()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();
    $this->lib->check_method("POST");
    $kondisi = $this->input->post('ajax', TRUE);
    $data = $this->apps_m->get_data("master_kondisi", "*", "id_kondisi = '" . $kondisi . "'")->result_array();
    echo json_encode($data);
  }

  public function mutasi()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();

    $tabel = "mutasi";

    if (isset($_POST['btn-filter'])) {
      $aset = $this->input->post('aset');
      $bulan = $this->input->post('bulan');
      $tahun = $this->input->post('tahun');

      $where = "mutasi.aset = '" . $aset . "'";
      if ($bulan == "-") {
        $wherebul = "";
      } else {
        $wherebul = " MONTH(mutasi.tgl_mutasi) = '" . (int)$bulan . "'";
      }

      if ($tahun == "-") {
        $wheretah = "";
      } else {
        $wheretah = " YEAR(mutasi.tgl_mutasi) = '" . (int)$tahun . "'";
      }

      //-----------------------------------------------------------------------
      if ($wherebul != "" || $wheretah != "") {
        $where .= " AND";
      }

      if ($wheretah != "" && $wherebul != "") {
        $wherebul .= " AND";
      }

      $where .= $wherebul . $wheretah;
      $select = "*";
      $join = array(
        'table' => array('aset', 'master_kantor', 'master_lokasi'),
        'kondisi' => array('mutasi.aset = aset.id_aset', 'aset.kantor = master_kantor.kode_ktr', 'aset.lokasi = master_lokasi.id_lokasi'),
        'posisi' => array('left', 'left', 'left'),
      );
      if ($bulan == "-") {
        $bulan = "Semua Bulan";
      } else {
        $bulan = $bulan;
      }

      if ($tahun == "-") {
        $tahun = "Semua Tahun";
      } else {
        $tahun = $tahun;
      }
      $datamutasiaset = $this->apps_m->get_data($tabel, $select, $where, "mutasi.tgl_mutasi DESC", $join)->result_array();
      $urut = "mutasi.aset";
      $data = array(
        'dataaset' => $this->apps_m->get_data($tabel, "*", "", "mutasi.tgl_mutasi DESC", $join, $urut)->result(),
        'datamutasi' => $datamutasiaset,
        'datatahun' => $tahun,
        'databulan' => $bulan
      );
    } else {
      $where = "";
      $join = array(
        'table' => array('aset', 'master_kantor', 'master_lokasi'),
        'kondisi' => array('mutasi.aset = aset.id_aset', 'aset.kantor = master_kantor.kode_ktr', 'aset.lokasi = master_lokasi.id_lokasi'),
        'posisi' => array('left', 'left', 'left'),
      );
      $urut = "mutasi.aset";
      $data = array(
        'dataaset' => $this->apps_m->get_data($tabel, "*", $where, "mutasi.tgl_mutasi DESC", $join, $urut)->result(),
        'datamutasi' => [],
        'datatahun' => "-",
        'databulan' => "-"
      );
    }
    $this->load->view('laporan/mutasi-v', $data);
  }
}
