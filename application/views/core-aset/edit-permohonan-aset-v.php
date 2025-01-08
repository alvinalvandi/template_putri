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
      $jenis_aset = "";
      $kategori_aset = "";
      $estimasi_biaya = "";
      $keterangan_permohonan = "";
      if (count($permohonan_aset) != 0) {
        $id_permohonan_aset = $permohonan_aset[0]['id_permohonan_aset'];
        $nama_aset = $permohonan_aset[0]['nama_permohonan_aset'];
        $jenis_aset = $permohonan_aset[0]['jenis_permohonan_aset'];
        $kategori_aset = $permohonan_aset[0]['kategori_permohonan_aset'];
        $estimasi_biaya = $permohonan_aset[0]['estimasi_biaya'];
        $keterangan_permohonan = $permohonan_aset[0]['keterangan_permohonan'];
        $isi_komen = $permohonan_aset[0]['isi_komen'];
      }
      ?>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Ubah Data Permohonan Aset</h1>
          </div>

          <div class="section-body">
            <div class="row">
              <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                  <form class="needs-validation" novalidate="" action="<?= base_url() ?>core-aset/edit_permohonan_aset" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="idpermohonan_aset" value="<?= $this->lib->encrypt_url($id_permohonan_aset); ?>">
                    <div class="card-body">
                      <div class="form-group mb-1">
                        <label>Nama Aset </label>
                        <input type="text" name="nama_aset" class="form-control" value="<?= $nama_aset; ?>" required="">
                        <div class="invalid-feedback">
                          Nama Aset harus diisi
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6 col-sm-12">
                          <div class="form-group mb-1">
                            <label>Jenis Aset</label>
                            <select name="jenis_permohonan_aset" id="jenis_permohonan_aset" class="form-control select2" required="">
                              <option value=""> --- Pilih Jenis ---</option>
                              <?php
                              foreach ($master_jenis as $data_jenis) {
                                $selected = "";
                                if ($jenis_aset == $data_jenis->id_jenis) {
                                  $selected = "selected ";
                                }
                                echo "<option " . $selected . "value=\"" . $data_jenis->id_jenis . "\">" . $data_jenis->nama_jenis . "</option>";
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
                            <select name="kategori_permohonan_aset" id="kategori_permohonan_aset" class="form-control select2" required="">
                              <option value=""> --- Pilih Kategori Aset ---</option>
                              <?php
                              foreach ($master_kategori as $data_kategori) {
                                $selected = "";
                                if ($kategori_aset == $data_kategori->id_kategori) {
                                  $selected = "selected ";
                                }
                                echo "<option " . $selected . "value=\"" . $data_kategori->id_kategori . "\">" . $data_kategori->nama_kategori . "</option>";
                              }
                              ?>
                            </select>
                            <div class="invalid-feedback">
                              Kategori Aset harus dipilih
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group mb-1">
                        <label>Estimasi Biaya Aset</label>
                        <input type="number" name="estimasi_biaya" class="form-control" value="<?= $estimasi_biaya; ?>" required="">
                        <div class="invalid-feedback">
                          Estimasi Biaya Aset harus diisi
                        </div>
                      </div>
                      <div class="form-group mb-1">
                        <label>Keterangan Permohonan</label>
                        <textarea name="keterangan_permohonan" class="form-control" required=""><?= $keterangan_permohonan; ?></textarea>
                        <div class="invalid-feedback">
                          Keterangan Permohonan harus diisi
                        </div>
                      </div>
                      <div class="form-group mb-1">
                        <label>Komen</label>
                        <textarea name="komen" class="form-control" required=""><?= $isi_komen ?></textarea>
                        <div class="invalid-feedback">
                          Komen harus diisi
                        </div>
                      </div>
                      <div div class="form-group mb-1">
                        <label>Lampiran <i style="color: red;" id="info_lampiran">(*Kosongkan jika tidak ingin ubah
                            lampiran)</i></label>
                        <input type="file" id="file_pdf" name="file_pdf" class="form-control" accept="application/pdf">
                        <div class="invalid-feedback">
                          Lampiran harus diisi
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
<script>
  $("#file_pdf").change(function() {
    var nama_ext = file_pdf.value.split('.').pop();

    if (nama_ext != "pdf") {
      swal("Maaf. File yang diupload harus dalam bentuk PDF !");
      file_pdf.value = "";

    }
    if (file_pdf.files[0].size > 10000000) { // ukuran file 10 mb, 1000000 untuk 1 MB.
      swal("Maaf. Ukuran file tidak boleh melebihi 10 MB !");
      file_pdf.value = "";
    };
  });
</script>

</html>