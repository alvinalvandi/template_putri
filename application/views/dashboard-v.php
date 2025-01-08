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
            <h1>Dashboard</h1>
          </div>

          <div class="section-body">
            <div class="row">
              <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                  <div class="card-icon bg-info">
                    <i class="far fa-building"></i>
                  </div>
                  <div class="card-wrap">
                    <div class="card-header">
                      <h4>Total Aset</h4>
                    </div>
                    <div class="card-body"><?= $totalaset[0]['total']; ?> Aset</div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                  <div class="card-icon bg-primary">
                    <i class="fas fa-building"></i>
                  </div>
                  <div class="card-wrap">
                    <div class="card-header">
                      <h4>Total Aset Normal</h4>
                    </div>
                    <div class="card-body"><?= $totalasetnormal[0]['total']; ?> Aset</div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                  <div class="card-icon bg-warning">
                    <i class="fas fa-user"></i>
                  </div>
                  <div class="card-wrap">
                    <div class="card-header">
                      <h4>Total Aset Maintenance</h4>
                    </div>
                    <div class="card-body"><?= $totalasetmaintenance[0]['total']; ?> Aset</div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                  <div class="card-icon bg-danger">
                    <i class="fas fa-user"></i>
                  </div>
                  <div class="card-wrap">
                    <div class="card-header">
                      <h4>Total Aset Rusak</h4>
                    </div>
                    <div class="card-body"><?= $totalasetrusak[0]['total']; ?> Aset</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12 col-md-6 col-lg-6">
                <div class="card">
                  <div class="card-header">
                    <h4>Total Aset Setiap Cabang
                    </h4>
                  </div>
                  <div class="card-body">
                    <canvas id="chartAset"></canvas>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6 col-lg-6">
                <div class="card">
                  <div class="card-header">
                    <h4>Total Aset Berdasarkan Status</h4>
                  </div>
                  <div class="card-body">
                    <canvas id="chartStatus"></canvas>
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
  <script src="<?= base_url() ?>assets/modules/chart.min.js"></script>
  <!-- Page Specific JS File -->
  <!-- Template JS File -->
  <script src="<?= base_url() ?>assets/js/app.js"></script>
  <script src="<?= base_url() ?>assets/js/custom.js"></script>

  <?php $this->load->view('layout/alert_notification'); ?>
  <script>
    var ctx = document.getElementById("chartAset").getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'horizontalBar',
      data: {
        datasets: [{
          data: [
            <?php
            foreach ($totalaset2 as $jab) {
              echo $jab->total . ",";
            }

            ?>
          ],
          backgroundColor: [
            <?php
            for ($i = 0; $i < count($color); $i++) {
              echo "'" . $color[$i] . "',";
            }

            ?>
          ],
          label: 'Total Aset Setiap Cabang'
        }],
        labels: [
          <?php
          foreach ($totalaset2 as $jab) {
            echo "'" . $jab->nama_kantor . "',";
          }
          //         
          ?>
        ],
      },
      options: {
        responsive: true,
        legend: {
          position: 'bottom',
        },
      }
    });

    var ctx = document.getElementById("chartStatus").getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'pie',
      data: {
        datasets: [{
          data: [
            <?php
            foreach ($kondisi_aset as $unit) {
              echo $unit->total . ",";
            }

            ?>
          ],
          backgroundColor: [
            <?php
            for ($i = 0; $i < count($color); $i++) {
              echo "'" . $color[$i] . "',";
            }

            ?>
          ],
          label: 'Kondisi Berdasarkan Unit'
        }],
        labels: [
          <?php
          foreach ($kondisi_aset as $unit) {
            echo "'" . $unit->nama_kondisi . "',";
          }

          ?>
        ],
      },
      options: {
        responsive: true,
        legend: {
          position: 'bottom',
        },
      }
    });
  </script>

</body>

</html>