<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
  <?php $this->load->view('layout/head'); ?>

  <link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/css/bootstrap.min.css">
  <link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/fonts/font-awesome/css/font-awesome.min.css">
  <link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/fonts/flaticon/font/flaticon.css">
  <link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/fonts/jost/jost.css">
  <link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/css/style-login.css">
</head>
<body>
  <div class="login" style="background: rgba(0, 0, 0, 0.04) url(assets/img/bg-login.jpg) top left repeat;">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="login-inner-form">
            <div class="details">
              <div class="logo-2">
                <img src="<?=base_url()?>assets/img/logo-text-black.png" alt="logo" width="80%">
              </div>
              
              <h3>Sistem Informasi Manajemen Inventaris Aset</h3>
              <form method="POST" action="<?=base_url()?>auth/login-proses">
                <div class="form-group form-box">
                  <input type="text" name="username" class="input-text" placeholder="User ID atau NIK" required autofocus>
                  <i class="flaticon-mail-2"></i>
                </div>
                <div class="form-group form-box">
                  <input type="password" name="password" class="input-text" placeholder="Password" required>
                  <i class="flaticon-password"></i>
                </div>
                <!-- <div class="checkbox clearfix">
                  <a href="#">Lupa Password</a>
                </div> -->
                <div class="form-group mb-10">
                  <button type="submit" class="btn-md btn-theme btn-block">Login</button>
                </div>
              </form>
              <p class="copyright" style="margin-top:30px;"><?=$this->param['footer-login'];?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="<?=base_url()?>assets/modules/jquery.min.js"></script>
  <script src="<?=base_url()?>assets/modules/bootstrap/js/bootstrap.min.js"></script>
  <script src="<?=base_url()?>assets/modules/sweetalert/sweetalert.min.js"></script>
  <script>
    $('input[name="emailusername"]').on('invalid', function(){
      return this.setCustomValidity('Username atau Email tidak boleh kosong!');
    }).on('input', function(){
      return this.setCustomValidity('');
    });

    $('input[name="password"]').on('invalid', function(){
      return this.setCustomValidity('Password tidak boleh kosong!');
    }).on('input', function(){
      return this.setCustomValidity('');
    });
  </script>
  <?php if ($this->session->flashdata('pesan') == "ada") { ?>
  <script type="text/javascript">
    swal({
      title: "Pesan!",
      text: "<?=$this->session->flashdata('pesan_isi'); ?>",
      timer: 3000,
      icon: "<?=$this->session->flashdata('pesan_tipe'); ?>",
      buttons: {
        // confirm : {text:'Ok',className:'btn btn-success'},
      },
    });
    $('input[name="emailusername"]').focus();
  </script>
  <?php } ?>
</body>
</html>