<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php $this->load->view('layout/head'); ?>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="<?=base_url()?>assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?=base_url()?>assets/modules/fontawesome/css/all.min.css">
  <!-- CSS Libraries -->
  <link rel="stylesheet" href="<?=base_url()?>assets/modules/select2/dist/css/select2.min.css">
  <!-- Template CSS -->
  <link rel="stylesheet" href="<?=base_url()?>assets/css/style.css">
  <link rel="stylesheet" href="<?=base_url()?>assets/css/components.css">
</head>

<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      
      <?php $this->load->view('layout/topbar'); ?>

      <?php $this->load->view('layout/sidebar'); ?>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Tambah Pengguna</h1>
          </div>

          <div class="section-body">
            <div class="row">
              <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                  <form class="needs-validation" novalidate="" action="<?=base_url()?>pengguna/add" method="POST" enctype="multipart/form-data">
                    <div class="card-body">
                      <div class="form-group mb-1">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required="">
                        <div class="invalid-feedback">
                          Username harus diisi
                        </div>
                      </div>
                      <div class="form-group mb-1">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required="">
                        <div class="invalid-feedback">
                          Password harus diisi
                        </div>
                      </div>
                      <div class="form-group mb-1">
                        <label>Confirm Password</label>
                        <input type="password" name="cpassword" class="form-control" required="">
                        <div class="invalid-feedback">
                          Confirm Password harus diisi
                        </div>
                      </div>
                      <div class="form-group mb-1">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" required="">
                        <div class="invalid-feedback">
                          Nama harus diisi
                        </div>
                      </div>
                      <div class="form-group mb-1">
                        <label>Grup Pengguna</label>
                        <select name="grup_pengguna" class="form-control select2" required="">
                          <option value=""> --- Pilih Grup Pengguna ---</option>
                          <?php
                            foreach($pengguna_grup as $grup) {
                              echo "<option value=\"".$grup->grup."\">".$grup->nama_grup."</option>";
                            }
                          ?>
                        </select>
                        <div class="invalid-feedback">
                          Grup Pengguna harus dipilih
                        </div>
                      </div>
                      <div class="form-group mb-0">
                        <label>Status Pengguna</label>
                        <select name="status_pengguna" class="form-control">
                          <option value="1">Aktif</option>
                          <option value="0">Tidak Aktif</option>
                        </select>
                        <div class="invalid-feedback">
                          Status pengguna harus dipilih
                        </div>
                      </div>
                    </div>
                    <div class="card-footer text-right">
                      <input type="submit" name="btn-simpan" class="btn btn-primary" value="Simpan" />
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>

      <?php $this->load->view('layout/footer'); ?> 
    </div>
  </div>

  <!-- General JS Scripts -->
  <script src="<?=base_url()?>assets/modules/jquery.min.js"></script>
  <script src="<?=base_url()?>assets/modules/popper.js"></script>
  <script src="<?=base_url()?>assets/modules/tooltip.js"></script>
  <script src="<?=base_url()?>assets/modules/bootstrap/js/bootstrap.min.js"></script>
  <script src="<?=base_url()?>assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
  <script src="<?=base_url()?>assets/js/stisla.js"></script>
  <!-- JS Libraies -->
  <script src="<?=base_url()?>assets/modules/sweetalert/sweetalert.min.js"></script>
  <script src="<?=base_url()?>assets/modules/select2/dist/js/select2.full.min.js"></script>
  <!-- Page Specific JS File -->
  <!-- Template JS File -->
  <script src="<?=base_url()?>assets/js/app.js"></script>
  <script src="<?=base_url()?>assets/js/custom.js"></script>

  <?php $this->load->view('layout/alert_notification'); ?>
  
  <script type="text/javascript">
  </script>
  
</body>
</html>