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
            <h1>Ubah Pengguna</h1>
          </div>

          <div class="section-body">
            <div class="row">
              <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                  <form class="needs-validation" novalidate="" action="<?=base_url()?>pengguna/edit" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="idpengguna" value="<?=$this->lib->encrypt_url($pengguna[0]['id_pengguna']);?>">
                    <div class="card-body">
                      <div class="form-group mb-1">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" value="<?=$pengguna[0]['username'];?>" required="">
                        <div class="invalid-feedback">
                          Username harus diisi
                        </div>
                      </div>
                      <div class="form-group mb-1">
                        <label>Password Lama <i style="color: red;">(*Kosongkan jika tidak ingin ubah password)</i></label>
                        <input type="password" name="oldpassword" id="oldpassword" class="form-control">
                        <div class="invalid-feedback">
                          Password harus diisi
                        </div>
                      </div>
                      <div id="npassword">
                        <div class="form-group mb-1">
                          <label>Password Baru</label>
                          <input type="password" name="password" id="password" class="form-control" required="">
                          <div class="invalid-feedback">
                            Password harus diisi
                          </div>
                        </div>
                        <div class="form-group mb-1">
                          <label>Confirm Password</label>
                          <input type="password" name="cpassword" id="newpassword" class="form-control" required="">
                          <div class="invalid-feedback">
                            Confirm Password harus diisi
                          </div>
                        </div>
                      </div>
                      <div class="form-group mb-1">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" required="" value="<?=$pengguna[0]['nama'];?>">
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
                              $selected = "";
                              if($pengguna[0]['grup_pengguna'] == $grup->grup) {
                                $selected = "selected ";
                              }
                              echo "<option ".$selected."value=\"".$grup->grup."\">".$grup->nama_grup."</option>";
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
                          <option <?php if($pengguna[0]['status_pengguna'] == 1) echo "selected";?> value="1">Aktif</option>
                          <option <?php if($pengguna[0]['status_pengguna'] == 0) echo "selected";?> value="0">Tidak Aktif</option>
                        </select>
                        <div class="invalid-feedback">
                          Status pengguna harus dipilih
                        </div>
                      </div>
                    </div>
                    <div class="card-footer text-right">
                      <input type="submit" name="btn-ubah" class="btn btn-primary" value="Ubah" />
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
    $(document).ready(function() {
      $('#npassword').hide();
      if($('#oldpassword').val() != "") {
        $('#password').prop("required",true);
        $('#newpassword').prop("required",true);
        $('#npassword').show();
      } else {
        $('#password').prop("required",false);
        $('#newpassword').prop("required",false);
        $('#npassword').hide();
      }
    });

    $('#oldpassword').keyup(function() {
      if($(this).val() != "") {
        $('#password').prop("required",true);
        $('#newpassword').prop("required",true);
        $('#npassword').show();
      } else {
        $('#password').prop("required",false);
        $('#newpassword').prop("required",false);
        $('#npassword').hide();
      }
    });
    </script>

</body>
</html>