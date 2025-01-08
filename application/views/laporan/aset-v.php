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
                    if ($kantor != "-") {
                    ?>
                      <h3 style="margin-left: -20px;" class="judul">Kantor : <?= $filterkantor[0]['nama_kantor']; ?></h3>
                      <h6 class="judul2" style="color:#000;">Kantor : <?= $filterkantor[0]['nama_kantor']; ?></h6>
                    <?php
                    } else {
                    ?>
                      <h3 style="margin-left: -20px;" class="judul">Seluruh Kantor</h3>
                      <h6 class="judul2" style="color:#000;">Seluruh Kantor</h6>
                    <?php
                    }
                    ?>
                    <?php
                    if ($jenis != "-") {
                    ?>
                      <h3 style="margin-left: -20px;" class="judul">Jenis Aset : <?= $filterjenis[0]['nama_jenis']; ?></h3>
                      <h6 class="judul2" style="color:#000;">Jenis Aset : <?= $filterjenis[0]['nama_jenis']; ?></h6>
                    <?php
                    } else {
                    ?>
                      <h3 style="margin-left: -20px;" class="judul">Seluruh Jenis Aset</h3>
                      <h6 class="judul2" style="color:#000;">Seluruh Jenis Aset</h6>
                    <?php
                    }
                    ?>
                    <?php
                    if ($kategori != "-") {
                    ?>
                      <h3 style="margin-left: -20px;" class="judul">Kategori Aset : <?= $filterkategori[0]['nama_kategori']; ?></h3>
                      <h6 class="judul2" style="color:#000;">Kategori Aset : <?= $filterkategori[0]['nama_kategori']; ?></h6>
                    <?php
                    } else {
                    ?>
                      <h3 style="margin-left: -20px;" class="judul">Seluruh Kategori Aset</h3>
                      <h6 class="judul2" style="color:#000;">Seluruh Kategori Aset</h6>
                    <?php
                    }
                    ?>
                    <?php
                    if ($kondisi != "-") {
                    ?>
                      <h3 style="margin-left: -20px;" class="judul">Kondisi Aset : <?= $filterkondisi[0]['nama_kondisi']; ?></h3>
                      <h6 class="judul2" style="color:#000;">Kondisi Aset : <?= $filterkondisi[0]['nama_kondisi']; ?></h6>
                    <?php
                    } else {
                    ?>
                      <h3 style="margin-left: -20px;" class="judul">Seluruh Kondisi Aset</h3>
                      <h6 class="judul2" style="color:#000;">Seluruh Kondisi Aset</h6>
                    <?php
                    }
                    ?>
                    <?php
                    if ($status != "-") {
                      if ($status == 1) {
                        $status = "Aktif";
                      } else {
                        $status = "Tidak Aktif";
                      }
                    ?>
                      <h3 style="margin-left: -20px;" class="judul">Status Aset : <?= $status; ?></h3>
                      <h6 class="judul2" style="color:#000;">Status Aset : <?= $status; ?></h6>
                    <?php
                    } else {
                    ?>
                      <h3 style="margin-left: -20px;" class="judul">Seluruh Status Aset</h3>
                      <h6 class="judul2" style="color:#000;">Seluruh Status Aset</h6>
                    <?php
                    }
                    ?>
                  </div>

                  <table class="table table-striped table-bordered table-sm" id="table-1" style="width:100%;">
                    <thead>
                      <tr align="center" style="background: #218838; color: #fff; font-wight:700; font-size:13px;">
                        <td style="padding:0.25rem;">No</td>
                        <td style="white-space: nowrap;">Nama Aset</td>
                        <td style="white-space: nowrap;">Kode Aset</td>
                        <td style="white-space: nowrap;">Kantor</td>
                        <td style="white-space: nowrap;">Harga Perolehan</td>
                        <td style="white-space: nowrap;">Tanggal Perolehan</td>
                        <td style="white-space: nowrap;">Status Aset</td>
                        <td style="white-space: nowrap;">Kondisi Aset</td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (count($aset) == 0) {
                      ?>
                        <tr>
                          <td colspan="7" class="text-center">Data tidak ditemukan</td>
                        </tr>
                        <?php
                      } else {

                        $no = 1;
                        foreach ($aset as $data) {
                          if ($data['status_aset'] == 1) {
                            $data['status_aset'] = "Aktif";
                          } else {
                            $data['status_aset'] = "Tidak Aktif";
                          }
                        ?>
                          <tr>
                            <td style="text-align: center;"><?= $no++; ?></td>
                            <td><?= $data['nama_aset']; ?></td>
                            <td style="text-align: center;"><?= $data['kode_aset']; ?></td>
                            <td style="text-align: center;"><?= $data['nama_kantor']; ?></td>
                            <td style="text-align: center;"><?= $data['harga_perolehan']; ?></td>
                            <td style="text-align: center;"><?= $data['tgl_perolehan']; ?></td>
                            <td style="text-align: center;"><?= $data['status_aset']; ?></td>
                            <td style="text-align: center;"><?= $data['nama_kondisi']; ?></td>
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
            <h5 class="modal-title" id="exampleModalLabel">Filter Data Aset</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form class="needs-validation" novalidate="" action="<?= base_url() ?>laporan/aset" method="POST">
              <div class="form-group mb-2">
                <label>Kantor Aset</label>
                <select class="form-control select2" name="kantor" style="width:100%" required="">
                  <option value="">Pilih Kantor Aset</option>
                  <option value="-">Semua Kantor</option>
                  <?php
                  foreach ($datakantor as $kantor) {
                  ?>
                    <option value="<?= $kantor->kode_ktr; ?>"><?= $kantor->kode_ktr; ?> - <?= $kantor->nama_kantor; ?></option>
                  <?php
                  }
                  ?>
                </select>
                <div class="invalid-feedback">
                  Kantor harus diisi
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 col-sm-12">
                  <div class="form-group mb-2">
                    <label>Jenis Aset</label>
                    <select class="form-control select2" name="jenis" style="width:100%" required="">
                      <option value="">Pilih Jenis Aset</option>
                      <option value="-">Semua Jenis Aset</option>
                      <?php
                      foreach ($datajenis as $jenis) {
                      ?>
                        <option value="<?= $jenis->id_jenis; ?>"><?= $jenis->nama_jenis; ?></option>
                      <?php
                      }
                      ?>
                    </select>
                    <div class="invalid-feedback">
                      Jenis Aset harus diisi
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-sm-12">
                  <div class="form-group mb-2">
                    <label>Kategori Aset</label>
                    <select class="form-control select2" name="kategori" style="width:100%" required="">
                      <option value="">Pilih Kategori Aset</option>
                      <option value="-">Semua Kategori Aset</option>
                      <?php
                      foreach ($datakategori as $kategori) {
                      ?>
                        <option value="<?= $kategori->id_kategori; ?>"><?= $kategori->nama_kategori; ?></option>
                      <?php
                      }
                      ?>
                    </select>
                    <div class="invalid-feedback">
                      Kategori Aset harus diisi
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 col-sm-12">
                  <div class="form-group mb-2">
                    <label>Harga Minimal Aset</label>
                    <input type="number" name="min" id="min" class="form-control" placeholder="-">
                  </div>
                </div>
                <div class="col-md-6 col-sm-12">
                  <div class="form-group mb-2">
                    <label>Harga Maximal Aset</label>
                    <input type="number" name="max" id="max" class="form-control" placeholder="-">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 col-sm-12">
                  <div class="form-group mb-2">
                    <label>Kondisi Aset</label>
                    <select class="form-control select2" name="kondisi" id="kondisi" style="width:100%" required="">
                      <option value="">Pilih Kondisi Aset</option>
                      <option value="-">Semua Kondisi Aset</option>
                      <?php
                      foreach ($datakondisi as $kondisi) {
                      ?>
                        <option value="<?= $kondisi->id_kondisi; ?>"><?= $kondisi->nama_kondisi; ?></option>
                      <?php
                      }
                      ?>
                    </select>
                    <div class="invalid-feedback">
                      Kondisi Aset harus diisi
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-sm-12">
                  <div class="form-group mb-2">
                    <label>Status Aset</label>
                    <select class="form-control select2" name="status" id="status" style="width:100%" required="" disabled>
                    </select>
                    <div class="invalid-feedback">
                      Status Aset harus diisi
                    </div>
                  </div>
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
                <input type="submit" name="btn-filter" onclick="" id="filter" class="btn btn-primary btn-md" value="Filter" />
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
        for (i = d; i >= 1985; i--) {
          a += '<option value="' + i + '">' + i + '</option>';
        }
        $('#tahun').append(a);
      } else {
        a += '<option value="-">Semua Tahun</option>';
        for (i = d; i >= 1985; i--) {
          a += '<option value="' + i + '">' + i + '</option>';
        }
        $('#tahun').append(a);
      }
      return false;
    });

    function cek_harga() {
      var min = $('#min').val();
      var max = $('#max').val();
      if (max != "") {
        if (min > max) {
          swal('Maaf', 'Harga minimal harus lebih besar dari harga maksimal', 'warning');
          $('#max').val("");
          $('#min').val("");
        }
      }
    }

    $('#filter').click(function() {
      cek_harga();
    });

    $('#kondisi').change(function() {
      $("#status").prop("disabled", false);
      $('#status').empty();

      var kondisi = $(this).val();
      if (kondisi == "-") {
        html = '';
        html += '<option value="">--- Pilih Status Aset --- </option>' +
          '<option value="-"> Semua Status Aset</option>' +
          '<option value=' + 1 + '>' + 'Aktif' + '</option>' +
          '<option value=' + 0 + '>' + 'Tidak Aktif' + '</option>';
        $('#status').append(html);
      } else {
        $('#status').empty();
        $.ajax({
          "url": "<?php echo base_url() ?>laporan/get-data-kondisi",
          method: "POST",
          data: {
            ajax: kondisi
          },
          async: true,
          dataType: 'json',
          success: function(data) {
            var html = '';
            var i;
            html += '<option value="">--- Pilih Status Aset --- </option>';
            for (i = 0; i < data.length; i++) {
              if (data[i].status_kondisi == 1) {
                html += '<option value=' + 1 + '>' + 'Aktif' + '</option>';
              } else if (data[i].status_kondisi == 0) {
                html += '<option value=' + 0 + '>' + 'Tidak Aktif' + '</option>';
              }
            }
            $('#status').html(html);
          }
        });
      }
      return false;
    });
  </script>
</body>

</html>