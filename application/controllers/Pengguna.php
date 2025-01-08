<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengguna extends CI_Controller
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
    $this->lib->check_access();

    $data = array();
    $this->load->view('pengguna/pengguna-v', $data);
  }

  function get_data()
  {
    $this->lib->check_logged_in();
    // $this->lib->check_access();
    // $this->lib->check_method("POST");

    $grup_pengguna = $this->session->userdata[$this->param['session']]['grup_pengguna'];

    $tabel = "pengguna";
    $select = "*";
    $param = array(
      'column_order' => array(),
      'column_search' => array('nama', 'username', 'nik'),
      'order' => array('status_pengguna', 'kantor', 'grup_pengguna'),
      'order2' => array('DESC', 'ASC', 'ASC'),
    );

    $menu_pengguna = array(99);
    if (in_array($grup_pengguna, $menu_pengguna)) {
      $where = "";
    } else {
      $where = "status_pengguna = '1'";
    }

    $join = array(
      'table' => array('master_kantor'),
      'kondisi' => array('pengguna.kantor=master_kantor.kode_ktr'),
      'posisi' => array('left'),
    );

    $list = $this->apps_m->get_datatables($tabel, $param, $select, $where, $join);
    $data = array();
    $no = $_POST['start'];

    foreach ($list as $field) {

      $no++;
      $row = array();
      $row[] = $no;
      $row[] = $field->nama;
      $row[] = $field->username;
      $row[] = $field->nik;
      $row[] = $field->nama_kantor;
      $row[] = $this->lib->tanggal_1t($field->last_login);

      if ($field->status_pengguna == 0) {
        $status_pengguna = "<a href=\"javascript:void(0)\" class=\"btn btn-icon btn-sm btn-danger\" data-toggle=\"tooltip\" data-original-title=\"Tidak Aktif\"><i class=\"fas fa-ban\"></i></a>";
        $row[] = $status_pengguna;
      } else {
        $status_pengguna = "<a href=\"javascript:void(0)\" class=\"btn btn-icon btn-sm btn-primary\" data-toggle=\"tooltip\" data-original-title=\"Aktif\"><i class=\"fas fa-check\"></i></a>";
        $row[] = $status_pengguna;
      }

      $urldetail = base_url() . "pengguna/detail/" . $this->lib->encrypt_url($field->id_pengguna);
      $urlubah = base_url() . "pengguna/edit/" . $this->lib->encrypt_url($field->id_pengguna);
      $urlnonaktif = "onclick='return nonaktif(\"" . $this->lib->encrypt_url($field->id_pengguna) . "\")'";

      $temp = "";
      $temp .= "<a href=\"" . $urldetail . "\" class=\"btn btn-icon btn-sm btn-primary\" data-toggle=\"tooltip\" data-original-title=\"Detail\"><i class=\"fas fa-info-circle\"></i></a>";
      $temp .= " <a href=\"" . $urlubah . "\" class=\"btn btn-icon btn-sm btn-warning\" data-toggle=\"tooltip\" data-original-title=\"Ubah\"><i class=\"fas fa-edit\"></i></a>";
      $temp .= " <a href=\"#\" " . $urlnonaktif . " class=\"btn btn-icon btn-sm btn-danger\" data-toggle=\"tooltip\" data-original-title=\"Nonaktif\"><i class=\"fas fa-ban\"></i></a>";
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

  public function add()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();

    if (isset($_POST['btn-simpan'])) {

      $tabel = "pengguna";

      $username = $this->input->post('username');
      $password = $this->lib->encrypt_password($this->input->post('password'));
      $cpassword = $this->lib->encrypt_password($this->input->post('cpassword'));
      $nama = $this->input->post('nama');
      $grup_pengguna = $this->input->post('grup_pengguna');
      $status_pengguna = $this->input->post('status_pengguna');
      $created_at = date("Y-m-d H:i:s");

      $whereusername = "username = '" . $username . "'";
      $cekusername = $this->apps_m->get_data($tabel, "*", $whereusername)->result_array();

      if (count($cekusername) != 0) {
        $this->session->set_flashdata("pesan", "ada");
        $this->session->set_flashdata("pesan_isi", "Username sudah pernah digunakan");
        $this->session->set_flashdata("pesan_tipe", "warning");
        echo "<script>self.history.back();</script>";
        exit;
      }

      if ($password != $cpassword) {
        $this->session->set_flashdata("pesan", "ada");
        $this->session->set_flashdata("pesan_isi", "Confirm password tidak sesuai");
        $this->session->set_flashdata("pesan_tipe", "warning");
        echo "<script>self.history.back();</script>";
        exit;
      }

      $data = array(
        'username' => $username,
        'nik' => $username,
        'password' => $password,
        'nama' => $nama,
        'grup_pengguna' => $grup_pengguna,
        'status_pengguna' => $status_pengguna,
        'created_at' => $created_at,
      );

      $result = $this->apps_m->insert_data($tabel, $data);
      if ($result == TRUE) {

        // Log
        $operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
        $pengguna = $operator;
        $aksi = "Tambah Pengguna";
        $keterangan = "a/n <b>" . $nama . "</b> dengan username <b>" . $username . "</b>";
        $this->lib->aksi_log($pengguna, $aksi, $keterangan);
        // End Log

        $this->session->set_flashdata("pesan", "ada");
        $this->session->set_flashdata("pesan_isi", "Data berhasil ditambahkan.");
        $this->session->set_flashdata("pesan_tipe", "success");
        redirect('/pengguna');
      } else {
        $this->session->set_flashdata("pesan", "ada");
        $this->session->set_flashdata("pesan_isi", "Terjadi kesalahan inputan data!");
        $this->session->set_flashdata("pesan_tipe", "warning");
        echo "<script>self.history.back();</script>";
      }
    } else {
      $data = array(
        'pengguna_grup' => $this->apps_m->get_data("master_grup", "*", "", "grup ASC")->result(),
      );
      $this->load->view('pengguna/pengguna-add-v', $data);
    }
  }

  public function edit()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();

    $tabel = "pengguna";
    if (isset($_POST['btn-ubah'])) {

      $idpengguna = $this->input->post('idpengguna');

      $key = "id_pengguna";
      $value = $idpengguna;
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

      $id_pengguna = $result[0]['id_pengguna'];
      $username = $this->input->post('username');
      $oldpassword = $this->input->post('oldpassword');
      $password = $this->input->post('password');
      $cpassword = $this->input->post('cpassword');
      $nama = $this->input->post('nama');
      $grup_pengguna = $this->input->post('grup_pengguna');
      $status_pengguna = $this->input->post('status_pengguna');
      $updated_at = date("Y-m-d H:i:s");

      $whereusername = "username = '" . $username . "' AND id_pengguna != '" . $id_pengguna . "'";
      $cekusername = $this->apps_m->get_data($tabel, "*", $whereusername)->result_array();

      if (count($cekusername) != 0) {
        $this->session->set_flashdata("pesan", "ada");
        $this->session->set_flashdata("pesan_isi", "Username sudah pernah digunakan");
        $this->session->set_flashdata("pesan_tipe", "warning");
        echo "<script>self.history.back();</script>";
        exit;
      }

      $data1 = array(
        'username' => $username,
        'nik' => $username,
        'nama' => $nama,
        'grup_pengguna' => $grup_pengguna,
        'status_pengguna' => $status_pengguna,
        'updated_at' => $updated_at,
      );

      if ($oldpassword != "") {
        if ($this->lib->encrypt_password($oldpassword) == $result[0]['password']) {
          if ($password != $cpassword) {
            $this->session->set_flashdata("pesan", "ada");
            $this->session->set_flashdata("pesan_isi", "Confirm password tidak sesuai");
            $this->session->set_flashdata("pesan_tipe", "warning");
            echo "<script>self.history.back();</script>";
            exit;
          } else {
            $data2 = array(
              'password' => $this->lib->encrypt_password($password),
            );
          }
        } else {
          $this->session->set_flashdata("pesan", "ada");
          $this->session->set_flashdata("pesan_isi", "Password lama tidak sesuai");
          $this->session->set_flashdata("pesan_tipe", "warning");
          echo "<script>self.history.back();</script>";
          exit;
        }
      } else {
        $data2 = array();
      }

      $data = array_merge($data1, $data2);
      $where = "id_pengguna = '" . $id_pengguna . "'";
      $result = $this->apps_m->update_data($tabel, $data, $where);
      if ($result == TRUE) {
        // Log
        $operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
        $pengguna = $operator;
        $aksi = "Ubah Pengguna";
        $keterangan = "a/n <b>" . $nama . "</b> dengan username <b>" . $username . "</b>";
        $this->lib->aksi_log($pengguna, $aksi, $keterangan);
        // End Log

        $this->session->set_flashdata("pesan", "ada");
        $this->session->set_flashdata("pesan_isi", "Data berhasil diubah.");
        $this->session->set_flashdata("pesan_tipe", "success");
        redirect('/pengguna');
      } else {
        $this->session->set_flashdata("pesan", "ada");
        $this->session->set_flashdata("pesan_isi", "Terjadi kesalahan inputan data!");
        $this->session->set_flashdata("pesan_tipe", "warning");
        echo "<script>self.history.back();</script>";
        exit;
      }
    } else {
      $id = $this->uri->segment(3);
      $key = "id_pengguna";
      $value = $id;
      $select = "*";
      $where = $this->lib->sql_encrypt_url($key, $value);
      $result = $this->apps_m->get_data($tabel, $select, $where)->result_array();

      if (count($result) != 0) {
        $data = array(
          'pengguna' => $result,
          'pengguna_grup' => $this->apps_m->get_data("master_grup", "*", "", "grup ASC")->result(),
        );
        $this->load->view('pengguna/pengguna-edit-v', $data);
      } else {
        $this->session->set_flashdata("pesan", "ada");
        $this->session->set_flashdata("pesan_isi", "Data tidak ditemukan.");
        $this->session->set_flashdata("pesan_tipe", "warning");
        redirect('/pengguna');
      }
    }
  }

  public function nonaktif()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();

    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
      $this->session->set_flashdata("pesan", "ada");
      $this->session->set_flashdata("pesan_isi", "Mohon maaf request anda tidak sesuai!");
      $this->session->set_flashdata("pesan_tipe", "warning");
      echo "<script>self.history.back();</script>";
      exit;
    }

    $tabel = "pengguna";
    $id = $this->input->post('pengguna_id');

    $key = "id_pengguna";
    $value = $id;
    $where = $this->lib->sql_encrypt_url($key, $value);
    $select = "*";
    $cek = $this->apps_m->get_data($tabel, $select, $where)->result_array();
    $data = array(
      'status_pengguna' => '0',
    );

    if (count($cek) != 0) {
      $result = $this->apps_m->update_data($tabel, $data, $where);
      if ($result == TRUE) {
        // Log
        $operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
        $pengguna = $operator;
        $aksi = "Nonaktifkan Pengguna";
        $keterangan = "a/n <b>" . $cek[0]['nama'] . "</b> dengan username <b>" . $cek[0]['username'] . "</b>";
        $this->lib->aksi_log($pengguna, $aksi, $keterangan);
        // End Log
        $respone = array("status" => TRUE, "pesan" => "Akun pengguna sudah dinonaktifkan.", "tipe_pesan" => "success");
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

  public function detail()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();

    $id = $this->uri->segment(3);
    $tabel = "pengguna";
    $key = "id_pengguna";
    $value = $id;
    $select = "*, " . $this->lib->sql_select_encrypt($key, "idpengguna");
    $where = $this->lib->sql_encrypt_url($key, $value);
    $join = array(
      'table' => array('master_grup'),
      'kondisi' => array('pengguna.grup_pengguna = master_grup.grup'),
      'posisi' => array('left'),
    );
    $result = $this->apps_m->get_data($tabel, $select, $where, "", $join)->result_array();

    if (count($result) != 0) {
      $data = array(
        'pengguna' => $result,
      );
      $this->load->view('pengguna/pengguna-detail-v', $data);
    } else {
      $this->session->set_flashdata("pesan", "ada");
      $this->session->set_flashdata("pesan_isi", "Data tidak ditemukan.");
      $this->session->set_flashdata("pesan_tipe", "warning");
      redirect('/pengguna');
    }
  }

  function get_data_log()
  {
    $this->lib->check_logged_in();
    $this->lib->check_access();

    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
      $this->session->set_flashdata("pesan", "ada");
      $this->session->set_flashdata("pesan_isi", "Mohon maaf request anda tidak sesuai!");
      $this->session->set_flashdata("pesan_tipe", "warning");
      echo "<script>self.history.back();</script>";
      exit;
    }

    $tabel = "pengguna_log";
    $select = "*";
    $param = array(
      'column_order' => array(),
      'column_search' => array('aksi', 'keterangan', 'tanggal'),
      'order' => array('tanggal'),
      'order2' => array('DESC'),
    );

    $id = $this->input->post('id_pengguna');
    $key = "pengguna";
    $value = $id;
    $where = $this->lib->sql_encrypt_url($key, $value);
    $join = "";

    $list = $this->apps_m->get_datatables($tabel, $param, $select, $where, $join);
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $field) {
      $no++;
      $row = array();
      $row[] = $no;
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

  public function profile()
  {
    $this->lib->check_logged_in();

    $id = $this->session->userdata[$this->param['session']]['id_pengguna'];
    $tabel = "pengguna";
    $key = "id_pengguna";
    $value = $this->lib->encrypt_url($id);
    $select = "*, " . $this->lib->sql_select_encrypt($key, "idpengguna");
    $where = $this->lib->sql_encrypt_url($key, $value);
    $join = array(
      'table' => array('master_grup'),
      'kondisi' => array('pengguna.grup_pengguna = master_grup.grup'),
      'posisi' => array('left'),
    );
    $result = $this->apps_m->get_data($tabel, $select, $where, "", $join)->result_array();

    if (count($result) != 0) {
      $data = array(
        'pengguna' => $result,
      );
      $this->load->view('pengguna/profile-v', $data);
    } else {
      $this->session->set_flashdata("pesan", "ada");
      $this->session->set_flashdata("pesan_isi", "Data tidak ditemukan.");
      $this->session->set_flashdata("pesan_tipe", "warning");
      redirect('/pengguna');
    }
  }

  function get_data_log_pengguna()
  {
    $this->lib->check_logged_in();

    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
      $this->session->set_flashdata("pesan", "ada");
      $this->session->set_flashdata("pesan_isi", "Mohon maaf request anda tidak sesuai!");
      $this->session->set_flashdata("pesan_tipe", "warning");
      echo "<script>self.history.back();</script>";
      exit;
    }

    $tabel = "pengguna_log";
    $select = "*";
    $param = array(
      'column_order' => array(),
      'column_search' => array('aksi', 'keterangan', 'tanggal'),
      'order' => array('tanggal'),
      'order2' => array('DESC'),
    );

    $id = $this->session->userdata[$this->param['session']]['id_pengguna'];
    $key = "pengguna";
    $value = $this->lib->encrypt_url($id);
    $where = $this->lib->sql_encrypt_url($key, $value);
    $join = "";

    $list = $this->apps_m->get_datatables($tabel, $param, $select, $where, $join);
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $field) {
      $no++;
      $row = array();
      $row[] = $no;
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

  public function change_password()
  {
    $this->lib->check_logged_in();

    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
      $this->session->set_flashdata("pesan", "ada");
      $this->session->set_flashdata("pesan_isi", "Mohon maaf request anda tidak sesuai!");
      $this->session->set_flashdata("pesan_tipe", "warning");
      echo "<script>self.history.back();</script>";
      exit;
    }

    $tabel = "pengguna";
    $operator = $this->session->userdata[$this->param['session']]['id_pengguna'];
    $id = $this->lib->encrypt_url($operator);

    $key = "id_pengguna";
    $value = $id;
    $where = $this->lib->sql_encrypt_url($key, $value);
    $select = "*";
    $cek = $this->apps_m->get_data($tabel, $select, $where)->result_array();

    $oldpassword = $this->input->post('oldpassword');
    $newpassword = $this->input->post('newpassword');
    $cpassword = $this->input->post('cpassword');
    $updated_at = date("Y-m-d H:i:s");

    if ($this->lib->encrypt_password($oldpassword) == $cek[0]['password']) {
      if ($newpassword != $cpassword) {
        $respone = array("status" => FALSE, "pesan" => "Confirm password tidak sesuai!", "tipe_pesan" => "warning");
        echo json_encode($respone);
        exit;
      } else {
        $data = array(
          'password' => $this->lib->encrypt_password($newpassword),
          'updated_at' => $updated_at
        );
      }
    } else {
      $respone = array("status" => FALSE, "pesan" => "Password lama tidak sesuai!", "tipe_pesan" => "warning");
      echo json_encode($respone);
      exit;
    }

    if (count($cek) != 0) {
      $result = $this->apps_m->update_data($tabel, $data, $where);
      if ($result == TRUE) {
        // Log
        $pengguna = $operator;
        $aksi = "Merubah Password";
        $keterangan = "";
        $this->lib->aksi_log($pengguna, $aksi, $keterangan);
        // End Log
        $respone = array("status" => TRUE, "pesan" => "Password Berhasil Diubah.", "tipe_pesan" => "success");
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
}
