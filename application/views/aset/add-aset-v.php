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
            <h1>Tambah Data Aset</h1>
          </div>

          <div class="section-body">
            <div class="row">
              <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                  <form class="needs-validation" novalidate="" action="<?= base_url() ?>aset/add-aset" method="POST" enctype="multipart/form-data">
                    <div class="card-body">
                      <div class="form-group mb-1">
                        <label>Nama Aset</label>
                        <input type="text" name="nama_aset" class="form-control" required="">
                        <div class="invalid-feedback">
                          Nama harus diisi
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6 col-sm-12">
                          <div class="form-group mb-1">
                            <label>Jenis Aset</label>
                            <select name="jenis_aset" id="jenis_aset" class="form-control select2" required="">
                              <option value="">--- Pilih Jenis Aset ---</option>
                              <?php
                              foreach ($master_jenis as $jenis) {
                                echo "<option value=\"" . $jenis->id_jenis . "\">" . $jenis->nama_jenis . "</option>";
                              }
                              ?>
                            </select>
                            <div class="invalid-feedback">
                              Jenis Aset harus dipilih
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                          <div class="form-group mb-1">
                            <label>Kategori Aset</label>
                            <select name="kategori_aset" id="kategori_aset" class="form-control select2" required="">
                              <option value="">--- Pilih Kategori Aset ---</option>
                              <?php
                              foreach ($master_kategori as $kategori) {
                                echo "<option value=\"" . $kategori->id_kategori . "\">" . $kategori->nama_kategori . "</option>";
                                $namareal = $master_kategori->nama_kategori;
                              }
                              ?>
                            </select>
                            <div class="invalid-feedback">
                              Kategori Aset harus dipilih
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6 col-sm-12">
                          <div class="form-group mb-1">
                            <label>Lokasi Kantor Penempatan Aset</label>
                            <select name="kantor" id="kantor" class="form-control select2" required="">
                              <option value=""> --- Pilih Kantor ---</option>
                              <?php
                              foreach ($master_kantor as $kantor) {
                                echo "<option value=\"" . $kantor->kode_ktr . "\">" . $kantor->nama_kantor . "</option>";
                              }
                              ?>
                            </select>
                            <div class="invalid-feedback">
                              Kantor Penempatan Aset harus dipilih
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                          <div class="form-group mb-1">
                            <label>Tanggal Perolehan</label>
                            <input type="date" name="tgl_perolehan" id="tgl" class="form-control" required="">
                            <div class="invalid-feedback">
                              Tanggal Perolehan harus diisi
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group mb-0"><label for="">Kode Aset</label></div>
                      <div class="input-group form-group mb-1">
                        <input type="text" name="kode_aset" id="kode" class="form-control" required="" readonly="">
                        <div class=" invalid-feedback">
                          Kode harus diisi
                        </div>
                      </div>
                      <div class="form-group mb-1">
                        <label>Lokasi Detail Penempatan Aset</label>
                        <select name="lokasi" id="lokasi" class="form-control select2" required disabled>
                          <option value=""> --- Pilih Lokasi Detail Penempatan Aset ---</option>

                        </select>
                        <div class="invalid-feedback">
                          Lokasi Detail Penempatan Aset harus diisi
                        </div>
                      </div>
                      <div class="form-group mb-1">
                        <label>Harga Perolehan Aset</label>
                        <input type="number" name="harga_perolehan" class="form-control" required="">
                        <div class="invalid-feedback">
                          Harga Perolehan Aset harus diisi
                        </div>
                      </div>
                      <div class="form-group mb-1">
                        <label>Kondisi Aset</label>
                        <select name="kondisi_aset" id="kondisi_aset" class="form-control select2" required="">
                          <option value=""> --- Pilih Kondisi ---</option>
                          <?php
                          foreach ($master_kondisi as $kondisi) {
                            echo "<option value=\"" . $kondisi->id_kondisi . "\">" . $kondisi->nama_kondisi . "</option>";
                          }
                          ?>
                        </select>
                        <div class="invalid-feedback">
                          Kondisi Aset harus dipilih
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

  <?php
  $kode = "";
  if (strlen($last_id) == 1) {
    $kode = "000" . $last_id;
  } else if (strlen($last_id) == 2) {
    $kode = "00" . $last_id;
  } else if (strlen($last_id) == 3) {
    $kode = "0" . $last_id;
  } else {
    $kode = $last_id;
  }
  ?>

  <script type="text/javascript">
    // $(document).ready(function() {});

    var last_id = '<?= $kode; ?>';


    function generate_kode() {

      var option_kat = $("#kategori_aset :selected").text();
      var input1 = option_kat.substr(0, 3).toUpperCase();
      var input2 = $("#kantor").val();
      var input3 = $("#tgl").val().substr(0, 4);

      if (input1 == "---" || input2 == "" || input3 == "") {
        $("#kode").val("");
      } else {
        $('#kode').val(last_id + "/" + input1 + "/" + input2 + "/" + input3);
      }
    }


    $('#kategori_aset').change(function() {
      generate_kode();
    });
    $('#kantor').change(function() {
      generate_kode();
    });
    $('#tgl').change(function() {
      generate_kode();
    });



    // $('#generate-kode').click(function() {

    //   var option_kat = $("#kategori_aset :selected").text();
    //   var input1 = option_kat.substr(0, 3).toUpperCase();
    //   var input2 = $("#kantor").val();
    //   var input3 = $("#tgl").val().substr(0, 4);

    //   if (input1 == "---" || input2 == "" || input3 == "") {
    //     swal('Kategori Aset, Lokasi Kantor Atau Tanggal Perolehan Aset Belum dipilih!');
    //     $("#kode").val("");
    //   } else {
    //     $('#kode').val(last_id + "/" + input1 + "/" + input2 + "/" + input3);
    //   }
    // });

    $('#kantor').change(function() {
      $("#lokasi").prop("disabled", false);
      var kode_kantor = $(this).val();

      $.ajax({
        "url": "<?php echo base_url() ?>aset/get-data-detail-lokasi",
        method: "POST",
        data: {
          ajax: kode_kantor
        },
        async: true,
        dataType: 'json',
        success: function(data) {
          var html = '';
          var i;
          html += '<option value="">--- Pilih Lokasi Detail Penempatan Aset --- </option>';
          for (i = 0; i < data.length; i++) {
            html += '<option value=' + data[i].id_lokasi + '>' + data[i].nama_lokasi + '</option>';
          }
          $('#lokasi').html(html);
        }
      });
      return false;
    });
  </script>

</body>

</html>