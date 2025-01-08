<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array('apps_m'));
    $this->param = $this->lib->parameter();
  }

  public function index()
  {
    $this->lib->check_logged_in();

    $join = array(
      'table' => array('master_jenis', 'master_kategori', 'master_kondisi', 'master_kantor', 'master_lokasi'),
      'kondisi' => array('aset.jenis_aset = master_jenis.id_jenis', 'aset.kategori_aset = master_kategori.id_kategori', 'aset.kondisi_aset = master_kondisi.id_kondisi', 'aset.kantor = master_kantor.kode_ktr', 'aset.lokasi = master_lokasi.id_lokasi'),
      'posisi' => array('left', 'left', 'left', 'left', 'left'),
    );

    $color = array("#218838", "#ffc107", "#fc544b", "#6610f2", "#fd7e14", "#17a2b8", "#45FFCA", "#cac187", "#FFEEF4", "#9A3B3B", "#5d9c9d", "#040D12", "#191D88", "#EC53B0", "#EBE76C");
    $data = array(
      'page' => "Dashboard",
      'color' => $color,
      'totalaset' => $this->apps_m->get_data("aset", "nama_kantor, COUNT(id_aset) as total", "status_aset <> 99", "", $join)->result_array(),
      'totalaset2' => $this->apps_m->get_data("aset", "nama_kantor, COUNT(id_aset) as total", "status_aset <> 99", "aset.kantor ASC", $join, "nama_kantor")->result(),
      'totalasetnormal' => $this->apps_m->get_data("aset", "COUNT(id_aset) as total", "status_aset <> 99 AND aset.kondisi_aset = 1")->result_array(),
      'totalasetmaintenance' => $this->apps_m->get_data("aset", "COUNT(id_aset) as total", "status_aset <> 99 AND aset.kondisi_aset = 2")->result_array(),
      'totalasetrusak' => $this->apps_m->get_data("aset", "COUNT(id_aset) as total", "status_aset <> 99 AND aset.kondisi_aset = 3")->result_array(),
      'kondisi_aset' => $this->apps_m->get_data("aset", "nama_kondisi, COUNT(id_aset) as total", "status_aset <> 99", "id_kondisi ASC", $join, "nama_kondisi")->result(),

    );
    $this->load->view('dashboard-v', $data);
  }
}
