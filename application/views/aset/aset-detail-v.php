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
  <link rel="stylesheet" href="<?= base_url() ?>assets/modules/datatables/datatables.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
  <!-- Template CSS -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/components.css">
  <style>
    .table td,
    .table th {
      padding: 0.25rem;
      vertical-align: middle;
    }
  </style>
</head>

<?php
$grup_pengguna = $this->session->userdata[$this->param['session']]['grup_pengguna'];
?>

<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">

      <?php $this->load->view('layout/topbar'); ?>

      <?php $this->load->view('layout/sidebar'); ?>

      <?php

      if (count($aset) != 0) {
        $id_aset = $aset[0]['id_aset'];
        $nama_aset = $aset[0]['nama_aset'];
        $kode_aset = $aset[0]['kode_aset'];
        $jenis_aset = $aset[0]['nama_jenis'];
        $kategori_aset = $aset[0]['nama_kategori'];
        $kantor = $aset[0]['nama_kantor'];
        $lokasi = $aset[0]['nama_lokasi'];
        $kondisi_aset = $aset[0]['nama_kondisi'];
        $tgl_perolehan = $aset[0]['tgl_perolehan'];
        $harga_perolehan = $aset[0]['harga_perolehan'];
        $jumlah_perbaikan = $aset[0]['jumlah_perbaikan'];
        $status_aset = $aset[0]['status_aset'];
        $link_qr = $aset[0]['content'];

        if ($status_aset == 1) {
          $status_aset = "Aktif";
        } else if ($status_aset == 0) {
          $status_aset = "Tidak Aktif";
        } else {
          $status_aset = "Hapus";
        }
      }

      ?>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Data Detail Aset</h1>
          </div>
          <div class="section-body">
            <div class="row">
              <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                  <div class="card-body" style="color:#252525;">
                    <div class="row">
                      <div class="col-lg-2 col-md-12 order-sm-1 order-md-3 order-col-lg-3">

                        <?php
                        if ($link_qr != '') {
                        ?>
                          <img src="<?= base_url() . $link_qr ?>" style="border:2px #218838 solid; max-height:200px;" class="rounded img-fluid mx-auto d-block mb-2">
                        <?php
                        } else {
                        ?>
                          <p style="font-size: 30px; margin-top : 60px;">- No Data QR -</p>
                        <?php
                        }
                        ?>


                      </div>
                      <div class="col-lg-5 col-md-6 order-sm-2 order-md-1 order-col-lg-1">
                        <div class="row">
                          <div class="col-md-4 col-lg-4">
                            <label>Nama Aset</label>
                          </div>
                          <div class="col-md-8 col-lg-8">
                            <label><?= $nama_aset; ?></label>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4 col-lg-4">
                            <label>Kode Aset</label>
                          </div>
                          <div class="col-md-8 col-lg-8">
                            <label><?= $kode_aset; ?></label>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4 col-lg-4">
                            <label>Jenis Aset</label>
                          </div>
                          <div class="col-md-8 col-lg-8">
                            <label><?= $jenis_aset; ?></label>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4 col-lg-4">
                            <label>Kategori Aset</label>
                          </div>
                          <div class="col-md-8 col-lg-8">
                            <label><?= $kategori_aset; ?></label>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4 col-lg-4">
                            <label>Kantor</label>
                          </div>
                          <div class="col-md-8 col-lg-8">
                            <label><?= $kantor; ?></label>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4 col-lg-4">
                            <label>Lokasi</label>
                          </div>
                          <div class="col-md-8 col-lg-8">
                            <label><?= $lokasi; ?></label>
                          </div>
                        </div>

                      </div>
                      <div class="col-lg-5 col-md-6 order-sm-3 order-md-2 order-col-lg-2">
                        <div class="row">
                          <div class="col-md-4 col-lg-4">
                            <label>Tanggal Perolehan</label>
                          </div>
                          <div class="col-md-8 col-lg-8">
                            <label><?= $tgl_perolehan; ?></label>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4 col-lg-4">
                            <label>Harga Perolehan</label>
                          </div>
                          <div class="col-md-8 col-lg-8">
                            <label><?= $harga_perolehan; ?></label>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4 col-lg-4">
                            <label>Kondisi Aset</label>
                          </div>
                          <div class="col-md-8 col-lg-8">
                            <label><?= $kondisi_aset; ?></label>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4 col-lg-4">
                            <label>Jumlah Perbaikan</label>
                          </div>
                          <div class="col-md-8 col-lg-8">
                            <label><?= $jumlah_perbaikan; ?></label>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4 col-lg-4">
                            <label>Status Aset</label>
                          </div>
                          <div class="col-md-8 col-lg-8">
                            <label><?= $status_aset; ?></label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
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
  <script src="<?= base_url() ?>assets/modules/datatables/datatables.min.js"></script>
  <script src="<?= base_url() ?>assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
  <script src="<?= base_url() ?>assets/modules/jquery-ui/jquery-ui.min.js"></script>
  <!-- Page Specific JS File -->
  <!-- Template JS File -->
  <script src="<?= base_url() ?>assets/js/app.js"></script>
  <script src="<?= base_url() ?>assets/js/custom.js"></script>

  <?php $this->load->view('layout/alert_notification'); ?>

  <script type="text/javascript">
    $(document).ready(function() {

    });
  </script>

</body>

</html>