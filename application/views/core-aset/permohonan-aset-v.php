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

<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">

      <?php $this->load->view('layout/topbar'); ?>

      <?php $this->load->view('layout/sidebar'); ?>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Data Permohonan Aset</h1>
            <div class="section-header-breadcrumb">
              <?php
              $grup_pengguna = $this->session->userdata[$this->param['session']]['grup_pengguna'];

              $menu_permohonan = array(99, 41, 51);
              if (in_array($grup_pengguna, $menu_permohonan)) {
              ?>
                <a href="<?= base_url() ?>core-aset/add-permohonan-aset" class="btn btn-icon icon-left btn-primary"><i class="fas fa-plus-circle"></i> Tambah</a>
              <?php
              }
              ?>

            </div>
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
                          <th>Jenis Aset</th>
                          <th>Kategori Aset</th>
                          <th>Tanggal Pengajuan</th>
                          <th>Estimasi Biaya</th>
                          <th>Keterangan Permohonan</th>
                          <th>Lampiran</th>
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
        "url": "<?php echo base_url() ?>core-aset/get-data-permohonan-aset",
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
          "targets": [0, 7],
          "className": 'text-center',
          "width": 50
        },
        {
          "targets": [6],
          "className": '',
          "width": 400
        },
        {
          "targets": [2, 3],
          "className": 'text-center',
          "width": 70
        },
        {
          "targets": [1],
          "width": 300
        },
        {
          "targets": [4, 5],
          "className": 'text-center',
          "width": 130
        },
        {
          "targets": [8],
          "className": 'text-center',
          "width": 120
        }
      ]
    });

    $('body').tooltip({
      selector: '[data-toggle="tooltip"]'
    });

    function reload_table() {
      table.ajax.reload(null, false);
    }

    function hapus(id) {
      swal({
          title: 'Anda yakin hapus permohonan ini ?',
          icon: 'warning',
          buttons: {
            confirm: {
              text: 'Hapus !',
              className: 'btn btn-primary'
            },
            cancel: {
              text: 'Batalkan',
              className: 'btn btn-default',
              visible: true
            }
          },
        })
        .then((willDeleted) => {
          if (willDeleted) {
            $.ajax({
              url: "<?= base_url() ?>core-aset/delete-permohonan-aset",
              type: "POST",
              data: {
                id_permohonan_aset: id,
                action_method: "hapus",
              },
              dataType: "JSON",
              success: function(data) {
                if (data.status) {
                  infoalert(data.pesan, data.tipe_pesan);
                  reload_table();
                } else {
                  infoalert(data.pesan, data.tipe_pesan);
                }
              },
              error: function(jqXHR, textStatus, errorThrown) {
                swal('Terjadi kesalahan!');
              }
            });
          } else {
            swal('Data tidak dihapus');
          }
        });
    }

    function tolak(id) {
      swal({
          title: 'Anda yakin tolak permohonan ini ?',
          icon: 'warning',
          buttons: {
            confirm: {
              text: 'Tolak !',
              className: 'btn btn-primary'
            },
            cancel: {
              text: 'Batalkan',
              className: 'btn btn-default',
              visible: true
            }
          },
        })
        .then((willDeleted) => {
          if (willDeleted) {
            $.ajax({
              url: "<?= base_url() ?>core-aset/tolak-permohonan-aset",
              type: "POST",
              data: {
                id_permohonan_aset: id,
                action_method: "tolak",
              },
              dataType: "JSON",
              success: function(data) {
                if (data.status) {
                  infoalert(data.pesan, data.tipe_pesan);
                  reload_table();
                } else {
                  infoalert(data.pesan, data.tipe_pesan);
                }
              },
              error: function(jqXHR, textStatus, errorThrown) {
                swal('Terjadi kesalahan!');
              }
            });
          } else {
            swal('Data tidak ditolak');
          }
        });
    }
  </script>
</body>

</html>