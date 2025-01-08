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
      if (count($permohonan_aset) != 0) {
        $id_permohonan_aset = $permohonan_aset[0]['id_permohonan_aset'];
        $nama_aset = $permohonan_aset[0]['nama_permohonan_aset'];
        $estimasi_biaya = $permohonan_aset[0]['estimasi_biaya'];
        $keterangan_permohonan = $permohonan_aset[0]['keterangan_permohonan'];
        $tgl_pengajuan = $permohonan_aset[0]['tgl_pengajuan'];
      }
      ?>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Persetujuan Permohonan Aset</h1>
          </div>

          <div class="section-body">
            <div class="row">
              <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                  <form class="needs-validation" novalidate="" action="<?= base_url() ?>core-aset/terima_permohonan_aset" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="idpermohonan_aset" value="<?= $this->lib->encrypt_url($id_permohonan_aset); ?>">
                    <div class="card-header">
                      <h4>Data Permohonan Aset</h4>
                    </div>

                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-bordered table-md">
                          <tbody>
                            <tr>
                              <th>Nama Aset</th>
                              <th>Tanggal Pengajuan</th>
                              <th>Estimasi Biaya</th>
                              <th>Keterangan Permohonan</th>
                              <th>Aksi</th>
                            </tr>
                            <?php

                            ?>
                            <tr>
                              <td><?= $nama_aset ?></td>
                              <td><?= $tgl_pengajuan ?></td>
                              <td><?= $estimasi_biaya ?></td>
                              <td><?= $keterangan_permohonan ?></td>
                              <td><a href="<?= base_url() . "core-aset/detail_permohonan_aset/" . $this->lib->encrypt_url($id_permohonan_aset); ?>" class="btn btn-secondary">Detail</a></td>
                            </tr>
                            <?php

                            ?>
                          </tbody>
                        </table>
                      </div>
                      <div class="form-group mb-1">
                        <label>Biaya Final Permohonan</label>
                        <input type="number" name="final_biaya_cabang" class="form-control" id="biaya" required="">
                        <div class="invalid-feedback">
                          Biaya Final Permohonan harus diisi
                        </div>
                      </div>
                      <div class="form-group mb-1">
                        <label>Komentar</label>
                        <textarea name="komen" class="form-control" required=""></textarea>
                        <div class="invalid-feedback">
                          Komentar Harus diisi
                        </div>
                      </div>
                      <div class="card-footer text-right">
                        <input type="submit" name="btn-terima" id="terima" class="btn btn-primary" value="Setujui" />
                        <input type="submit" name="btn-ajukan" id="ajukan" class="btn btn-info" value="Ajukan Ke Pusat" />
                      </div>
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
<script>
  $(document).ready(function() {
    $("#ajukan").hide();
    $('#biaya').keyup(function() {
      if ($(this).val() >= <?= $this->param['dana-limit']; ?>) {
        // alert("Biaya sama dengan atau diatas 3 Juta Harus diajukan ke Pusat");
        $('#ajukan').show();
        $('#terima').hide();
      } else if ($(this).val() < <?= $this->param['dana-limit']; ?>) {
        $('#ajukan').hide();
        $('#terima').show();
      }
    });
  });
</script>

</html>