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

      @page {
        size: 10cm 5cm portrait;
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
      background-color: #F2F2F2;
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
            <h1>Data Aset</h1>
          </div>
          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <table class="table table-striped" id="table-1">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Nama Aset</th>
                          <th>Kode Aset</th>
                          <th>Kantor</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
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
    var table;
    var action_method;

    table = $("#table-1").DataTable({
      "processing": true,
      "serverSide": true,
      "order": [],
      "ajax": {
        "url": "<?php echo base_url() ?>aset/get-data-cetak-label",
        "type": "POST"
      },
      "ordering": false,
      "pagingType": "full_numbers",
      "stateSave": true,
      "pageLength": 25,
      "language": {
        "decimal": ",",
        "thousands": ".",
        "lengthMenu": "Tampilkan _MENU_",
        "zeroRecords": "Data tidak tersedia.",
        "info": "Tampilkan _START_ sampai _END_ dari _TOTAL_ data",
        "infoEmpty": "Data tidak tersedia",
        "infoFiltered": "(disaring dari _MAX_ data)",
        "search": "Pencarian :",
        "searchPlaceholder": '',
        "paginate": {
          "first": "<i class='fa fa-step-backward'></i>",
          "previous": "<i class='fa fa-chevron-left'></i>",
          "next": "<i class='fa fa-chevron-right'></i>",
          "last": "<i class='fa fa-step-forward'></i>",
        },
        "aria": {
          "paginate": {
            "first": "First",
            "previous": "Previous",
            "next": "Next",
            "last": "Last"
          }
        },
        "processing": "<i class='fa fa-refresh fa-spin'></i>"
      },
      "columnDefs": [{
          "targets": [0],
          "className": 'text-center',
          "width": 50
        },
        {
          "targets": [-1],
          "className": 'text-center',
        },
      ]
    });

    $('body').tooltip({
      selector: '[data-toggle="tooltip"]'
    });

    function reload_table() {
      table.ajax.reload(null, false);
    }
  </script>
</body>

</html>