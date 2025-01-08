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

    .judul {
      display: none;
    }

    .sub-judul {
      display: none;
    }

    .logo {
      display: none;
    }

    @media print {
      body * {
        visibility: hidden;
      }

      #print-area,
      #print-area * {
        visibility: visible;
        color: #000;
      }

      #print-area {
        position: absolute;
        left: -5px;
        top: -80px;
        right: -30px;
      }

      .judul {
        display: block;
        text-align: left;
        font-size: 16px;
      }

      .judul2 {
        display: none;
      }

      .logo {
        display: block;
        width: 80%;
        margin: auto;
      }

      .sub-judul {
        display: block;
        text-align: center;
        margin-left: -20px;
        margin-right: -25px;
        border: 1px solid black;
        border-radius: 2px;
        margin-bottom: 20px;
      }

      .table thead tr td {
        -webkit-print-color-adjust: exact !important;
        background: #00B050 !important;
        color: #fff !important;
        font-weight: 700 !important;
        text-align: center !important;
      }

      .table tbody tr:nth-child(odd) td {
        -webkit-print-color-adjust: exact !important;
        background: #fff !important;
        color: #000 !important;
      }

      .table tbody tr:nth-child(even) td {
        -webkit-print-color-adjust: exact !important;
        background: #d8f7be !important;
        color: #000 !important;
      }

      @page {
        size: A4 portrait;
        max-height: 100%;
        max-width: 100%
      }

      /* @page {size: A4 landscape;max-height:100%; max-width:100%} */
    }

    thead tr td {
      background-color: #00B050;
      color: #fff;
      font-weight: 700;
    }

    .table tbody tr:nth-child(odd) {
      background-color: #fff;
    }

    .table tbody tr:nth-child(even) {
      background-color: #d8f7be;
    }

    h6.judul2 {
      font-size: 14px;
    }
  </style>
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
            <h1>Laporan Aset</h1>
            <div class="section-header-breadcrumb">
              <button class="btn btn-icon icon-left" data-toggle="modal" data-target="#modalfilter"><i class="fas fa-edit"></i> Filter</button>&nbsp;&nbsp;
              <button href="javascript:void(0)" class="btn btn-icon icon-left btn-primary" onclick="window.print()"><i class="fas fa-print"></i> Cetak</button>
            </div>
          </div>

          <div class="section-body" id="print-area">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <img src="<?= base_url() ?>assets/img/kop-surat.png" class="logo">
                    <hr class="sub-judul">
                    <?php
                    if (count($datamutasi) > 0) {
                    ?>
                      <h3 style="margin-left: -20px;" class="judul">Aset &emsp;: <?= $datamutasi[0]['nama_aset']; ?></h3>
                      <h6 class="judul2" style="color:#000;">Aset &emsp;: <?= $datamutasi[0]['nama_aset']; ?></h6>
                    <?php
                    } else {
                    ?>
                      <h3 style="margin-left: -20px;" class="judul">Aset &nbsp;&nbsp;: No Data</h3>
                      <h6 class="judul2" style="color:#000;">Aset &nbsp;&nbsp;&nbsp;: No Data</h6>
                    <?php
                    }
                    ?>
                    <?php
                    if ($datatahun != "-") {
                    ?>
                      <h3 style="margin-left: -20px;" class="judul">Tahun &nbsp;: <?= $datatahun; ?></h3>
                      <h6 class="judul2" style="color:#000;">Tahun &nbsp;: <?= $datatahun; ?></h6>
                    <?php
                    } else {
                    ?>
                      <h3 style="margin-left: -20px;" class="judul">Tahun &nbsp;: No Data</h3>
                      <h6 class="judul2" style="color:#000;">Tahun : No Data</h6>
                    <?php
                    }
                    ?>
                    <?php
                    if ($databulan != "-") {
                    ?>
                      <h3 style="margin-left: -20px;" class="judul">Bulan : <?= $databulan; ?></h3>
                      <h6 class="judul2" style="color:#000;">Bulan &nbsp;: <?= $databulan; ?></h6>
                    <?php
                    } else {
                    ?>
                      <h3 style="margin-left: -20px;" class="judul">Bulan : No Data</h3>
                      <h6 class="judul2" style="color:#000;">Bulan &nbsp;: No Data</h6>
                    <?php
                    }
                    ?>
                  </div>

                  <table class="table table-striped table-bordered table-sm" id="table-1" style="width:100%;">
                    <thead>
                      <tr align="center" style="background: #218838; color: #fff; font-wight:700; font-size:13px;">
                        <td style="padding:0.25rem;">No</td>
                        <td style="white-space: nowrap;">Nama Aset</td>
                        <td style="white-space: nowrap;">Lokasi Awal</td>
                        <td style="white-space: nowrap;">Kantor Awal</td>
                        <td style="white-space: nowrap;">Lokasi Baru</td>
                        <td style="white-space: nowrap;">Kantor Baru</td>
                        <td style="white-space: nowrap;">Tanggal Mutasi</td>
                      </tr>
                    </thead>
                    <tbody>

                      <?php

                      if (count($datamutasi) == 0) {
                      ?>
                        <tr>
                          <td colspan="7" class="text-center">Data tidak ditemukan</td>
                        </tr>
                        <?php
                      } else {

                        $no = 1;
                        foreach ($datamutasi as $data) {

                          $lokasi_awal = $data['lokasi_awal'];
                          $data_lokasi_awal = $this->apps_m->get_data("master_lokasi", "*", "id_lokasi = '" . $lokasi_awal . "'")->result_array();
                          $nama_lokasi_awal = $data_lokasi_awal[0]['nama_lokasi'];

                          $lokasi_baru = $data['lokasi_baru'];
                          $data_lokasi_baru = $this->apps_m->get_data("master_lokasi", "*", "id_lokasi = '" . $lokasi_baru . "'")->result_array();
                          $nama_lokasi_baru = $data_lokasi_baru[0]['nama_lokasi'];

                          $kantor_awal = $data['kantor_awal'];
                          $data_kantor_awal = $this->apps_m->get_data("master_kantor", "*", "kode_ktr = '" . $kantor_awal . "'")->result_array();
                          $nama_kantor_awal = $data_kantor_awal[0]['nama_kantor'];

                          $kantor_baru = $data['kantor_baru'];
                          $data_kantor_baru = $this->apps_m->get_data("master_kantor", "*", "kode_ktr = '" . $kantor_baru . "'")->result_array();
                          $nama_kantor_baru = $data_kantor_baru[0]['nama_kantor'];

                        ?>
                          <tr>
                            <td style="text-align: center;"><?= $no++; ?></td>
                            <td><?= $data['nama_aset']; ?></td>
                            <td style="text-align: center;"><?= $nama_lokasi_awal; ?></td>
                            <td style="text-align: center;"><?= $nama_kantor_awal; ?></td>
                            <td style="text-align: center;"><?= $nama_lokasi_baru; ?></td>
                            <td style="text-align: center;"><?= $nama_kantor_baru; ?></td>
                            <td style="text-align: center;"><?= $this->lib->tanggal_9($data['tgl_mutasi']); ?></td>
                          </tr>
                      <?php
                        }
                      } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
      </div>
      </section>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalfilter" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Filter Data Mutasi Aset</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form class="needs-validation" novalidate="" action="<?= base_url() ?>laporan/mutasi" method="POST">
              <div class="form-group mb-2">
                <label>Daftar Aset</label>
                <select class="form-control select2" name="aset" style="width:100%" required="">
                  <option value="">Pilih Aset</option>
                  <?php
                  foreach ($dataaset as $aset) {
                  ?>
                    <option value="<?= $aset->id_aset; ?>"><?= $aset->kode_aset; ?> - <?= $aset->nama_aset; ?></option>
                  <?php
                  }
                  ?>
                </select>
                <div class="invalid-feedback">
                  Aset harus dipilih
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 col-sm-12">
                  <div class="form-group mb-2">
                    <label>Bulan</label>
                    <select class="form-control" name="bulan" id="bulan" required="">
                      <option value="">Pilih Bulan</option>
                      <option value="-">Semua Bulan</option>
                      <option value="01">Januari</option>
                      <option value="02">Februari</option>
                      <option value="03">Maret</option>
                      <option value="04">April</option>
                      <option value="05">Mei</option>
                      <option value="06">Juni</option>
                      <option value="07">Juli</option>
                      <option value="08">Agustus</option>
                      <option value="09">September</option>
                      <option value="10">Oktober</option>
                      <option value="11">November</option>
                      <option value="12">Desember</option>
                    </select>
                    <div class="invalid-feedback">
                      Bulan harus diisi
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-sm-12">
                  <div class="form-group mb-3">
                    <label>Tahun</label>
                    <select class="form-control" name="tahun" id="tahun" required="" disabled>
                      <option value=""> Pilih Tahun </option>
                    </select>
                    <div class="invalid-feedback">
                      Tahun harus diisi
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group mb-0 mt-3">
                <input type="submit" name="btn-filter" id="filter" class="btn btn-primary btn-md" value="Filter" />
              </div>
            </form>
          </div>

        </div>
      </div>
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
  <script src="<?= base_url() ?>assets/modules/datatables/datatables.min.js"></script>
  <script src="<?= base_url() ?>assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
  <script src="<?= base_url() ?>assets/modules/jquery-ui/jquery-ui.min.js"></script>
  <!-- Page Specific JS File -->
  <!-- Template JS File -->
  <script src="<?= base_url() ?>assets/js/app.js"></script>
  <script src="<?= base_url() ?>assets/js/custom.js"></script>

  <?php $this->load->view('layout/alert_notification'); ?>

  <script type="text/javascript">
    var table;
    var action_method;

    $('body').tooltip({
      selector: '[data-toggle="tooltip"]'
    });

    function reload_table() {
      table.ajax.reload(null, false);
    }

    $('#modal-aksi').on('hide.bs.modal', function(e) {
      $(this).find("input,textarea,select").val('').end()
        .find("input[type=checkbox], input[type=radio]").prop("checked", "").end();
    });

    $('#bulan').change(function() {
      $("#tahun").prop("disabled", false);
      $('#tahun').empty();
      var bulan = $(this).val();
      var a = '';
      var d = new Date().getFullYear();

      if (bulan != "-") {
        for (i = d; i >= 2008; i--) {
          a += '<option value="' + i + '">' + i + '</option>';
        }
        $('#tahun').append(a);
      } else {
        a += '<option value="-">Semua Tahun</option>';
        for (i = d; i >= 2008; i--) {
          a += '<option value="' + i + '">' + i + '</option>';
        }
        $('#tahun').append(a);
      }
      return false;
    });

    // menampilkan data pada tabel yang masih dalam bentuk angka
    $('#filter').click(function() {
      var lokasi_awal = $('#lokasi_awal').val();
      var kantor_awal = $('#kantor_awal').val();
      var lokasi_baru = $('#lokasi_baru').val();
      var kantor_baru = $('#kantor_baru').val();


    });
  </script>
</body>

</html>