<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php $this->load->view('layout/head'); ?>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/modules/fontawesome/css/all.min.css">
  <!-- CSS Libraries -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/modules/select2/dist/css/select2.min.css">
  <!-- Template CSS -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/components.css">
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
            <h1>Tambah Permohonan Perbaikan Aset</h1>
          </div>

          <div class="section-body">
            <div class="row">
              <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                  <form class="needs-validation" novalidate="" action="<?= base_url() ?>core-aset/add-permohonan-perbaikan" method="POST" enctype="multipart/form-data">
                    <div class="card-body">
                      <div class="form-group mb-1">
                        <label>Nama Aset</label>
                        <select name="nama_aset" id="nama_aset" class="form-control select2" required="">
                          <option value=""> --- Pilih Aset ---</option>
                          <?php
                          foreach ($aset as $data_aset) {
                            echo "<option value=\"" . $data_aset->id_aset . "\">" . $data_aset->kode_aset . " - " . $data_aset->nama_aset . "</option>";
                          }
                          ?>
                        </select>
                      </div>
                      <div class="form-group mb-1">
                        <label>Estimasi Biaya Perbaikan</label>
                        <input type="number" name="estimasi_biaya" class="form-control" required="">
                        <div class="invalid-feedback">
                          Estimasi Biaya Perbaikan harus diisi
                        </div>
                      </div>
                      <div class="form-group mb-1">
                        <label>Keterangan Perbaikan</label>
                        <textarea name="keterangan_perbaikan" class="form-control" required=""></textarea>
                        <div class="invalid-feedback">
                          Keterangan Perbaikan harus diisi
                        </div>
                      </div>
                      <div class="form-group mb-1">
                        <label>Komen</label>
                        <textarea name="komen" class="form-control" required=""></textarea>
                        <div class="invalid-feedback">
                          Komen harus diisi
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
  <script src="<?= base_url() ?>assets/modules/jquery.min.js"></script>
  <script src="<?= base_url() ?>assets/modules/popper.js"></script>
  <script src="<?= base_url() ?>assets/modules/tooltip.js"></script>
  <script src="<?= base_url() ?>assets/modules/bootstrap/js/bootstrap.min.js"></script>
  <script src="<?= base_url() ?>assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
  <script src="<?= base_url() ?>assets/js/stisla.js"></script>
  <!-- JS Libraies -->
  <script src="<?= base_url() ?>assets/modules/sweetalert/sweetalert.min.js"></script>
  <script src="<?= base_url() ?>assets/modules/select2/dist/js/select2.full.min.js"></script>
  <!-- Page Specific JS File -->
  <!-- Template JS File -->
  <script src="<?= base_url() ?>assets/js/app.js"></script>
  <script src="<?= base_url() ?>assets/js/custom.js"></script>

  <?php $this->load->view('layout/alert_notification'); ?>

</body>

</html>