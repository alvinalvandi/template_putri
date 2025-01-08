<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Riwayat extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array('apps_m'));
    $this->param = $this->lib->parameter();
  }

  public function riw_permohonan_aset()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();

    $data = array();
    $this->load->view('riwayat/riw-permohonan-aset-v', $data);
  }

  function get_data_riw_permohonan_aset()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();
    $this->lib->check_method("POST");
    $kantor_pengguna = $this->session->userdata[$this->param['session']]['kantor'];

    $tabel = "permohonan_aset";
    $select = "*";
    $param = array(
      'column_order' => array(),
      'column_search' => array('keterangan_permohonan', 'tgl_pengajuan', 'nama_permohonan_aset'),
      'order' => array('tgl_pengajuan'),
      'order2' => array('ASC'),
    );
    $where = "((status_terima = 1 AND status_ditambahkan = 1) OR status_terima = 2) AND kantor = '" . $kantor_pengguna . "'";
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

      switch ($field->status_terima) {
        case '1':
          $row[] = "Diterima";
          break;

        case '2':
          $row[] = "Ditolak";
          break;

        default:
          echo "-";
          break;
      }

      $row[] = $field->keterangan_permohonan;

      if ($field->file_upload == "") {
        $row[] = "-";
      } else {
        $row[] = "<a href=\"" . base_url() . "/assets/lampiran/upload/" . $field->file_upload . "\" target=\"_blank\" class=\"btn btn-icon btn-sm btn-light\"><i class=\"fas fa-file\"></i></a>";
      }

      $urldetail = base_url() . "core-aset/detail_permohonan_aset/" . $this->lib->encrypt_url($field->id_permohonan_aset);

      //rencana nya buat tombol untuk kembalikan permohonan aset yang ditolak
      $temp = " <a href=\"" . $urldetail . "\" class=\"btn btn-icon btn-sm btn-secondary\" data-toggle=\"tooltip\" data-original-title=\"Detail\"><i class=\"fa fa-info\"></i></a>";

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
  public function pengguna()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();

    $data = array();
    $this->load->view('riwayat/pengguna-v', $data);
  }

  function get_data_log_pengguna()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();
    $this->lib->check_method("POST");

    $tabel = "pengguna_log";
    $select = "*";
    $param = array(
      'column_order' => array(),
      'column_search' => array('nama', 'username', 'aksi', 'keterangan', 'tanggal'),
      'order' => array('tanggal'),
      'order2' => array('DESC'),
    );
    $where = "";
    $join = array(
      'table' => array('pengguna', 'master_grup'),
      'kondisi' => array('pengguna_log.pengguna = pengguna.id_pengguna', 'pengguna.grup_pengguna = master_grup.grup'),
      'posisi' => array('left', 'left'),
    );

    $list = $this->apps_m->get_datatables($tabel, $param, $select, $where, $join);
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $field) {

      $no++;
      $row = array();
      $row[] = $no;
      $row[] = $field->nama . " (" . $field->username . ")";
      $row[] = $field->aksi;
      $row[] = $field->keterangan;
      $row[] = $this->lib->tanggal_6t($field->tanggal);
      $row[] = $field->ip_address;

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
