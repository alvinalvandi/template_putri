<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  if (count($dataaset) != 0) {
    $nama_aset = $dataaset[0]['nama_aset'];
    $kode_aset = $dataaset[0]['kode_aset'];
    $link_qr = $dataaset[0]['content'];
  }
  ?>
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
    body {
      color: black;
    }

    .center {
      margin-right: 0px;
      margin-left: 0px;
      width: 520px;
    }

    td {
      border: 2px solid;
      text-align: center;
      font-size: 16px;
    }

    @page {
      size: 10cm 15cm;
      margin-top: 5mm;
      margin-right: 3mm;
      margin-bottom: 5mm;
      margin-left: 3mm;
    }
  </style>
</head>

<body>
  <div style="margin-left: 0px;">
    <table class="center">
      <tr>
        <td rowspan="3" width="180px">
          <img src="<?= base_url() . $link_qr ?>" style="width: 150px;" class="rounded img-fluid mx-auto d-block mb-0">
        </td>
        <td><strong><?= $nama_aset ?></strong></td>
      </tr>
      <tr>
        <td><strong><?= $kode_aset ?></strong></td>
      </tr>
      <tr>
        <td style="font-style: italic; font-size: 12px;"><strong>property of</strong> <img src="<?= base_url() . "/assets/img/logo-text-black.png" ?>" alt="aset" style="max-width: 170px;"></td>
      </tr>
      <tr>
        <td colspan="2" style="font-style: italic; font-weight: 700; font-size: 12px;">&copy; Sistem Informasi Manajemen Inventaris Aset created by Information Technology</td>
      </tr>
    </table>
  </div>

  <!-- General JS Scripts -->
  <script src=" <?= base_url() ?>assets/modules/jquery.min.js">
  </script>
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

</body>

</html>