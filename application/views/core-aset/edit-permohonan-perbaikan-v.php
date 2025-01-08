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

      <?php
      $nama_aset = "";
      $estimasi_biaya = "";
      $keterangan_perbaikan = "";
      if (count($permohonan_perbaikan) != 0) {
        $id_permohonan_perbaikan = $permohonan_perbaikan[0]['id_permohonan_perbaikan'];
        $nama_aset = $permohonan_perbaikan[0]['aset'];
        $estimasi_biaya = $permohonan_perbaikan[0]['estimasi_biaya'];
        $keterangan_perbaikan = $permohonan_perbaikan[0]['keterangan_perbaikan'];
        $isi_komen = $permohonan_perbaikan[0]['isi_komen'];
      }
      ?>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Ubah Data Permohonan Perbaikan</h1>
          </div>

          <div class="section-body">
            <div class="row">
              <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                  <form class="needs-validation" novalidate="" action="<?= base_url() ?>core-aset/edit_permohonan_perbaikan" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="idpermohonan_perbaikan" value="<?= $this->lib->encrypt_url($id_permohonan_perbaikan); ?>">
                    <div class="card-body">
                      <div class="form-group mb-1">
                        <select name="nama_aset" id="nama_aset" class="form-control select2" required="">
                          <option value=""> --- Pilih Aset ---</option>
                          <?php
                          foreach ($aset as $data_aset) {
                            $selected = "";
                            if ($nama_aset == $data_aset->id_aset) {
                              $selected = "selected ";
                            }
                            echo "<option " . $selected . "value=\"" . $data_aset->id_aset . "\">" . $data_aset->nama_aset . "</option>";
                          }
                          ?>
                        </select>
                        <div class="invalid-feedback">
                          Aset harus dipilih
                        </div>
                      </div>
                      <div class="form-group mb-1">
                        <label>Estimasi Biaya Perbaikan</label>
                        <input type="number" name="estimasi_biaya" class="form-control" value="<?= $estimasi_biaya; ?>" required="">
                        <div class="invalid-feedback">
                          Estimasi Biaya Perbaikan harus diisi
                        </div>
                      </div>
                      <div class="form-group mb-1">
                        <label>Keterangan Perbaikan</label>
                        <textarea name="keterangan_perbaikan" class="form-control" required=""><?= $keterangan_perbaikan; ?></textarea>
                        <div class="invalid-feedback">
                          Keterangan Perbaikan harus diisi
                        </div>
                      </div>
                      <div class="form-group mb-1">
                        <label>Komen</label>
                        <textarea name="komen" class="form-control" required=""><?= $isi_komen; ?></textarea>
                        <div class="invalid-feedback">
                          Komen harus diisi
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