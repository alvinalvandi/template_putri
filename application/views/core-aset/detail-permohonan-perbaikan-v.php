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

  <style>
    .table td,
    .table th {
      padding: 0.25rem;
      vertical-align: middle;
    }

    thead tr td {
      background-color: #00B050;
      color: #fff;
      font-weight: 700;
    }

    #table-1 .table tbody tr:nth-child(odd) {
      background-color: #eee;
    }

    #table-1 .table tbody tr:nth-child(even) {
      background-color: #fff;
    }

    #table-2 {
      width: 100%;
    }

    #table-3 {
      width: 100%;
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
            <h1>Detail Data Permohonan Perbaikan Aset</h1>
            <div class="section-header-breadcrumb">
              <a href="javascript:history.back()" class="btn btn-icon icon-left btn-info"><i class="fas fa-reply-all"></i> Kembali</a>
            </div>
          </div>

          <div class="section-body">
            <div class="row">
              <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Detail Informasi Aset</h4>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive" id="table-1">
                      <table class="table table-bordered table-md">
                        <tbody>
                          <?php
                          ?>
                          <tr>
                            <td>Nama Aset </td>
                            <td><?= $permohonan_perbaikan[0]['nama_aset'] ?></td>
                          </tr>
                          <tr>
                            <td>Jenis Aset </td>
                            <td><?= $permohonan_perbaikan[0]['nama_jenis'] ?></td>
                          </tr>
                          <tr>
                            <td>Kategori Aset </td>
                            <td><?= $permohonan_perbaikan[0]['nama_kategori'] ?></td>
                          </tr>
                          <tr>
                            <td>Harga Perolehan Aset </td>
                            <td><?= $permohonan_perbaikan[0]['harga_perolehan'] ?></td>
                          </tr>
                          <tr>
                            <td>Tanggal Perolehan Aset </td>
                            <td><?= $this->lib->tanggal_9($permohonan_perbaikan[0]['tgl_perolehan']) ?></td>
                          </tr>
                          <tr>
                            <td>Kantor Aset </td>
                            <td><?= $permohonan_perbaikan[0]['nama_kantor'] ?></td>
                          </tr>
                          <tr>
                            <td>Lokasi Detail Aset </td>
                            <td><?= $permohonan_perbaikan[0]['nama_lokasi'] ?></td>
                          </tr>

                          <tr>
                            <td>Keterangan Perbaikan </td>
                            <td><?= $permohonan_perbaikan[0]['keterangan_perbaikan'] ?></td>
                          </tr>
                          <tr>
                            <td>Estimasi Biaya </td>
                            <td><?= $permohonan_perbaikan[0]['estimasi_biaya'] ?></td>
                          </tr>
                          <?php
                          if ($permohonan_perbaikan[0]['final_biaya_cabang'] != 0) {
                          ?>
                            <tr>
                              <td>Final Biaya Cabang </td>
                              <td><?= $permohonan_perbaikan[0]['final_biaya_cabang'] ?></td>
                            </tr>
                          <?php
                          }
                          if ($permohonan_perbaikan[0]['final_biaya_pusat'] != 0) {
                          ?>
                            <tr>
                              <td>Final Biaya Pusat </td>
                              <td><?= $permohonan_perbaikan[0]['final_biaya_pusat'] ?></td>
                            </tr>
                          <?php
                          }
                          ?>
                          <tr>
                            <td>Tanggal Pengajuan Perbaikan Aset</td>
                            <td><?= $this->lib->tanggal_9($permohonan_perbaikan[0]['tgl_pengajuan']) ?></td>
                          </tr>
                          <tr>
                            <td>Jam</td>
                            <td><?= $this->lib->jam($permohonan_perbaikan[0]['tgl_pengajuan']) ?></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Komite</h4>
                  </div>
                  <div class="card-body">
                    <div id="accordion">
                      <div class="accordion">
                        <div class="accordion-header collapsed" id="cabang" role="button" data-toggle="collapse" data-target="#panel-body-1" aria-expanded="true">
                          <h4>Cabang</h4>
                        </div>
                        <div class="accordion-body collapse show" id="panel-body-1" data-parent="#accordion">
                          <div class="table table-sm" id="table-2">
                            <?php
                            if (!empty($komencabang)) {
                              foreach ($komencabang as $key => $value) {
                                $jabatan = $value['grup_jabatan'];
                                if ($jabatan != 0 && $jabatan == 41) {
                            ?>
                                  <hr>

                                  <table class="table table-bordered table-md">
                                    <p></p>
                                    <thead class="thead-dark">
                                      <tr>
                                        <th style="width:20%;" scope="col">Parameter</th>
                                        <th style="width:30%;" scope="col">Nilai</th>
                                        <th style="width:50%;" scope="col">Komentar</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td><b>Nama </b><br>Jabatan <br>Tanggal <br>Jam
                                        </td>
                                        <td><?= $value['pengguna_komen'] ?>
                                          <br>
                                          <?php
                                          switch ($jabatan) {
                                            case 1:
                                              echo "Karyawan";
                                              break;
                                            case 11:
                                              echo "Direksi";
                                              break;
                                            case 41:
                                              echo "Kasie";
                                              break;
                                            case 42:
                                              echo "Pincab";
                                              break;
                                            case 51:
                                              echo "Staff Umum";
                                              break;
                                            case 52:
                                              echo "Kasie Umum";
                                              break;
                                            case 53:
                                              echo "Kadiv";
                                              break;
                                            case 99:
                                              echo "Administrator";
                                              break;
                                            default:
                                              echo "-";
                                              break;
                                          }
                                          if (!is_null($value['waktu'])) {
                                            $tgl = explode(" ", $value['waktu']);
                                          ?>
                                            <br>
                                            <?= $tgl[0]; ?>
                                            <br>
                                            <?= $tgl[1]; ?>
                                            <br>
                                        </td>
                                        <td><?= $value['isi_komen'] ?></td>
                                      <?php
                                          }
                                      ?>
                                      </tr>
                                    </tbody>
                                  </table>

                                <?php

                                }

                                ?>
                          </div>
                          <div class="table table-sm" id="table-3">
                            <?php
                                if ($jabatan != 0) {
                                  if ($jabatan == 42) {

                            ?>
                                <hr>

                                <table class="table table-bordered table-md">
                                  <thead class="thead-dark">
                                    <tr>
                                      <th style="width:20%;" scope="col">Parameter</th>
                                      <th style="width:30%;" scope="col">Nilai</th>
                                      <th style="width:50%;" scope="col">Komentar</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td><b>Nama </b><br>Jabatan <br>Tanggal <br>Jam
                                      </td>
                                      <td><?= $value['pengguna_komen'] ?>
                                        <br>

                                        <?php

                                        switch ($jabatan) {
                                          case 1:
                                            echo "Karyawan";
                                            break;
                                          case 11:
                                            echo "Direksi";
                                            break;
                                          case 41:
                                            echo "Kasie";
                                            break;
                                          case 42:
                                            echo "Pincab";
                                            break;
                                          case 51:
                                            echo "Staff Umum";
                                            break;
                                          case 52:
                                            echo "Kasie Umum";
                                            break;
                                          case 53:
                                            echo "Kadiv";
                                            break;
                                          case 99:
                                            echo "Administrator";
                                            break;
                                          default:
                                            echo "-";
                                            break;
                                        }
                                        if (!is_null($value['waktu'])) {
                                          $tgl = explode(" ", $value['waktu']);
                                        ?>
                                          <br>
                                          <?= $tgl[0]; ?>
                                          <br>
                                          <?= $tgl[1]; ?>
                                          <br>
                                      </td>
                                      <td><?= $value['isi_komen'] ?></td>
                                    <?php
                                        }
                                    ?>
                                    </tr>
                                  </tbody>
                                </table>

                        <?php
                                  }
                                } else {
                                  echo '<p style="text-align: center"> No Data </p>';
                                }
                              }
                            } else {
                              echo '<p></p>
                              <p style="text-align: center"> No Data </p>';
                            }

                        ?>
                          </div>
                        </div>
                      </div>
                      <div class="accordion">
                        <div class="accordion-header collapsed" id="pusat" role="button" data-toggle="collapse" data-target="#panel-body-2" aria-expanded="false">
                          <h4>Pusat</h4>
                        </div>
                        <div class="accordion-body collapse" id="panel-body-2" data-parent="#accordion">
                          <div class="table table-sm" id="table-4">
                            <p></p>
                            <?php
                            if (!empty($komenpusat)) {
                              foreach ($komenpusat as $key => $value) {
                                $jabatan = $value['grup_jabatan'];

                                if ($jabatan != 0) {
                                  if ($jabatan == 51) {

                            ?>
                                    <hr>
                                    <table class="table table-bordered table-md">
                                      <thead class="thead-dark">
                                        <tr>
                                          <th style="width:20%;" scope="col">Parameter</th>
                                          <th style="width:30%;" scope="col">Nilai</th>
                                          <th style="width:50%;" scope="col">Komentar</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <tr>
                                          <td><b>Nama </b><br>Jabatan <br>Tanggal <br>Jam
                                          </td>
                                          <td><?= $value['pengguna_komen'] ?>
                                            <br>

                                            <?php

                                            switch ($jabatan) {
                                              case 1:
                                                echo "Karyawan";
                                                break;
                                              case 11:
                                                echo "Direksi";
                                                break;
                                              case 41:
                                                echo "Kasie";
                                                break;
                                              case 42:
                                                echo "Pincab";
                                                break;
                                              case 51:
                                                echo "Staff Umum";
                                                break;
                                              case 52:
                                                echo "Kasie Umum";
                                                break;
                                              case 53:
                                                echo "Kadiv";
                                                break;
                                              case 99:
                                                echo "Administrator";
                                                break;
                                              default:
                                                echo "-";
                                                break;
                                            }
                                            if (!is_null($value['waktu'])) {
                                              $tgl = explode(" ", $value['waktu']);
                                            ?>
                                              <br>
                                              <?= $tgl[0]; ?>
                                              <br>
                                              <?= $tgl[1]; ?>
                                              <br>
                                          </td>
                                          <td><?= $value['isi_komen'] ?></td>
                                        <?php
                                            }
                                        ?>
                                        </tr>
                                      </tbody>
                                    </table>

                                <?php
                                  }
                                } else {
                                  echo '<p style="text-align: center"> No Data </p>';
                                }

                                ?>
                          </div>
                          <div class="table table-sm" id="table-5">
                            <?php
                                if ($jabatan != 0) {
                                  if ($jabatan == 52) {
                            ?>
                                <hr>
                                <table class="table table-bordered table-md">
                                  <thead class="thead-dark">
                                    <tr>
                                      <th style="width:20%;" scope="col">Parameter</th>
                                      <th style="width:30%;" scope="col">Nilai</th>
                                      <th style="width:50%;" scope="col">Komentar</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td><b>Nama </b><br>Jabatan <br>Tanggal <br>Jam
                                      </td>
                                      <td><?= $value['pengguna_komen'] ?>
                                        <br>

                                        <?php

                                        switch ($jabatan) {
                                          case 1:
                                            echo "Karyawan";
                                            break;
                                          case 11:
                                            echo "Direksi";
                                            break;
                                          case 41:
                                            echo "Kasie";
                                            break;
                                          case 42:
                                            echo "Pincab";
                                            break;
                                          case 51:
                                            echo "Staff Umum";
                                            break;
                                          case 52:
                                            echo "Kasie Umum";
                                            break;
                                          case 53:
                                            echo "Kadiv";
                                            break;
                                          case 99:
                                            echo "Administrator";
                                            break;
                                          default:
                                            echo "-";
                                            break;
                                        }
                                        if (!is_null($value['waktu'])) {
                                          $tgl = explode(" ", $value['waktu']);
                                        ?>
                                          <br>
                                          <?= $tgl[0]; ?>
                                          <br>
                                          <?= $tgl[1]; ?>
                                          <br>
                                      </td>
                                      <td><?= $value['isi_komen'] ?></td>
                                    <?php
                                        }
                                    ?>
                                    </tr>
                                  </tbody>
                                </table>

                            <?php
                                  }
                                } else {
                                  echo '<p style="text-align: center"> No Data </p>';
                                }

                            ?>
                          </div>

                          <div class="table table-sm" id="table-6">
                            <?php
                                if ($jabatan != 0 && ($jabatan == 51 || $jabatan == 52 || $jabatan == 53)) {
                                  if ($jabatan == 53) {

                            ?>
                                <hr>
                                <table class="table table-bordered table-md">
                                  <thead class="thead-dark">
                                    <tr>
                                      <th style="width:20%;" scope="col">Parameter</th>
                                      <th style="width:30%;" scope="col">Nilai</th>
                                      <th style="width:50%;" scope="col">Komentar</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td><b>Nama </b><br>Jabatan <br>Tanggal <br>Jam
                                      </td>
                                      <td><?= $value['pengguna_komen'] ?>
                                        <br>

                                        <?php

                                        switch ($jabatan) {
                                          case 1:
                                            echo "Karyawan";
                                            break;
                                          case 11:
                                            echo "Direksi";
                                            break;
                                          case 41:
                                            echo "Kasie";
                                            break;
                                          case 42:
                                            echo "Pincab";
                                            break;
                                          case 51:
                                            echo "Staff Umum";
                                            break;
                                          case 52:
                                            echo "Kasie Umum";
                                            break;
                                          case 53:
                                            echo "Kadiv";
                                            break;
                                          case 99:
                                            echo "Administrator";
                                            break;
                                          default:
                                            echo "-";
                                            break;
                                        }
                                        if (!is_null($value['waktu'])) {
                                          $tgl = explode(" ", $value['waktu']);
                                        ?>
                                          <br>
                                          <?= $tgl[0]; ?>
                                          <br>
                                          <?= $tgl[1]; ?>
                                          <br>
                                      </td>
                                      <td><?= $value['isi_komen'] ?></td>
                                    <?php
                                        }
                                    ?>
                                    </tr>
                                  </tbody>
                                </table>

                        <?php
                                  }
                                } else {
                                  echo '<p style="text-align: center"> No Data </p>';
                                }
                              }
                            } else {
                              echo '<p style="text-align: center"> No Data </p>';
                            }
                        ?>
                          </div>
                        </div>
                      </div>
                      <div class="accordion">
                        <div class="accordion-header collapsed" role="button" data-toggle="collapse" data-target="#panel-body-3" aria-expanded="false">
                          <h4>Direksi</h4>
                        </div>
                        <p></p>
                        <div class="accordion-body collapse" id="panel-body-3" data-parent="#accordion">
                          <div class="table table-sm" id="table-7">
                            <?php
                            if (!empty($komendireksi)) {
                              foreach ($komendireksi as $key => $value) {
                                if ($jabatan != 0 && $jabatan == 11) {
                            ?>
                                  <hr>
                                  <table class="table table-bordered table-md">
                                    <thead class="thead-dark">
                                      <tr>
                                        <th style="width:20%;" scope="col">Parameter</th>
                                        <th style="width:30%;" scope="col">Nilai</th>
                                        <th style="width:50%;" scope="col">Komentar</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td><b>Nama </b><br>Jabatan <br>Tanggal <br>Jam
                                        </td>
                                        <td><?= $value['pengguna_komen'] ?>
                                          <br>

                                          <?php

                                          switch ($jabatan) {
                                            case 1:
                                              echo "Karyawan";
                                              break;
                                            case 11:
                                              echo "Direksi";
                                              break;
                                            case 41:
                                              echo "Kasie";
                                              break;
                                            case 42:
                                              echo "Pincab";
                                              break;
                                            case 51:
                                              echo "Staff Umum";
                                              break;
                                            case 52:
                                              echo "Kasie Umum";
                                              break;
                                            case 53:
                                              echo "Kadiv";
                                              break;
                                            case 99:
                                              echo "Administrator";
                                              break;
                                            default:
                                              echo "-";
                                              break;
                                          }
                                          if (!is_null($value['waktu'])) {
                                            $tgl = explode(" ", $value['waktu']);
                                          ?>
                                            <br>
                                            <?= $tgl[0]; ?>
                                            <br>
                                            <?= $tgl[1]; ?>
                                            <br>
                                        </td>
                                        <td><?= $value['isi_komen'] ?></td>
                                      <?php
                                          }
                                      ?>
                                      </tr>
                                    </tbody>
                                  </table>

                            <?php

                                } else {
                                  echo '<p style="text-align: center"> No Data </p>';
                                }
                              }
                            } else {
                              echo '<p style="text-align: center"> No Data </p>';
                            }
                            ?>
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
  <script src="<?= base_url() ?>assets/modules/select2/dist/js/select2.full.min.js"></script>
  <!-- Page Specific JS File -->
  <!-- Template JS File -->
  <script src="<?= base_url() ?>assets/js/app.js"></script>
  <script src="<?= base_url() ?>assets/js/custom.js"></script>

  <?php $this->load->view('layout/alert_notification'); ?>

</body>
<script>
  <?php
  if (!empty($komenpusat)) {
  ?>
    var komentar = <?= $komenpusat[0]['grup_jabatan'] ?>

    if (komentar !== "") {
      $('#pusat').attr("aria-expanded", "true");
      $('#cabang').attr("aria-expanded", "false");
      $('#panel-body-2').addClass("show");
      $('#panel-body-1').removeClass("show");
    }
  <?php
  }
  ?>
</script>

</html>