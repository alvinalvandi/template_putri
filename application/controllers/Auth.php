<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('apps_m'));
		$this->param = $this->lib->parameter();
	}

	public function index()
	{
		redirect('login');
	}

	public function login()
	{
		if (!isset($this->session->userdata[$this->param['session']])) {
			$data = array();
			$this->load->view('auth-login-v', $data);
		} else {
			$this->session->set_flashdata("logout", "ok");
			echo "<script>self.history.back();</script>";
		}
	}

	public function login_proses()
	{
		$tabel = "pengguna";

		$data = array(
			'username'	=> $this->input->post('username'),
			'password' => $this->lib->encrypt_password($this->input->post('password'))
		);
		$result = $this->apps_m->login($tabel, $data);
		// print_r($data);
		// exit;
		if (count($result) > 0) {

			$id_pengguna = $result[0]['id_pengguna'];
			$username = $result[0]['username'];
			$nik = $result[0]['nik'];
			$nama = $result[0]['nama'];
			$kantor = $result[0]['kantor'];
			$grup_pengguna = $result[0]['grup_pengguna'];
			$status_pengguna = $result[0]['status_pengguna'];
			$last_login = $result[0]['last_login'];

			if ($status_pengguna == 0) {
				$this->session->set_flashdata("pesan", "ada");
				$this->session->set_flashdata("pesan_isi", "Akun anda tidak aktif.");
				$this->session->set_flashdata("pesan_tipe", "warning");
				echo "<script>self.history.back();</script>";
				exit;
			}

			if ($status_pengguna == 1) {
				$datalogin = array(
					'last_login' => date("Y-m-d H:i:s")
				);

				$wherelogin = "id_pengguna = '" . $id_pengguna . "'";
				$this->apps_m->update_data($tabel, $datalogin, $wherelogin);

				$session_data = array(
					'id_pengguna' => $id_pengguna,
					'username' => $username,
					'nik' => $nik,
					'nama' => $nama,
					'kantor' => $kantor,
					'grup_pengguna' => $grup_pengguna,
					'status_pengguna' => $status_pengguna,
					'last_login' => date("Y-m-d H:i:s"),
				);
				$this->session->set_userdata($this->param['session'], $session_data);

				// Log
				$pengguna = $id_pengguna;
				$aksi = "Login";
				$keterangan = "";
				$this->lib->aksi_log($pengguna, $aksi, $keterangan);
				// End Log

				redirect('dashboard');
			}
		} else {
			$this->session->set_flashdata("pesan", "ada");
			$this->session->set_flashdata("pesan_isi", "User ID atau Password anda salah.");
			$this->session->set_flashdata("pesan_tipe", "warning");
			redirect('login');
		}
	}

	public function logout()
	{
		$id_pengguna = $this->session->userdata[$this->param['session']]['id_pengguna'];
		// Log
		$pengguna = $id_pengguna;
		$aksi = "Logout";
		$keterangan = "";
		$this->lib->aksi_log($pengguna, $aksi, $keterangan);
		// End Log

		$sess_array = array(
			'id_pengguna' => '',
			'username' => '',
			'nik' => '',
			'nama' => '',
			'kantor' => '',
			'grup_pengguna' => '',
			'status_pengguna' => '',
			'last_login' => '',
		);
		$this->session->unset_userdata($this->param['session'], $sess_array);
		redirect('login');
	}
}
