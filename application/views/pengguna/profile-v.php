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
</head>

<?php
$grup_pengguna = $this->session->userdata[$this->param['session']]['grup_pengguna'];
?>

<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">

      <?php $this->load->view('layout/topbar'); ?>

      <?php $this->load->view('layout/sidebar'); ?>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Profile Pengguna</h1>
          </div>
          <div class="section-body">
            <div class="row">
              <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                  <div class="card-body" style="color:#252525;">
                    <div class="row">
                      <div class="col-lg-2 col-md-12">

                        <a href="javascript:void(0)" onclick="return ubah_password('<?= $pengguna[0]['idpengguna']; ?>')" class="btn btn-primary btn-block mb-3">Ubah Password</a>
                      </div>
                      <div class="col-lg-5 col-md-6">
                        <div class="row">
                          <div class="col-md-4 col-lg-4">
                            <label>Nama</label>
                          </div>
                          <div class="col-md-8 col-lg-8">
                            <label><?= $pengguna[0]['nama']; ?></label>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4 col-lg-4">
                            <label>Username</label>
                          </div>
                          <div class="col-md-8 col-lg-8">
                            <label><?= $pengguna[0]['username']; ?></label>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4 col-lg-4">
                            <label>NIK</label>
                          </div>
                          <div class="col-md-8 col-lg-8">
                            <label><?= $pengguna[0]['nik']; ?></label>
                          </div>
                        </div>
                        <?php
                        $grup_pengguna = $this->session->userdata[$this->param['session']]['grup_pengguna'];
                        $menu_pengguna = array(90, 99);
                        if (in_array($grup_pengguna, $menu_pengguna)) {
                        ?>
                          <div class="row">
                            <div class="col-md-4 col-lg-4">
                              <label>Grup Pengguna</label>
                            </div>
                            <div class="col-md-8 col-lg-8">
                              <label><?= $pengguna[0]['nama_grup']; ?></label>
                            </div>
                          </div>
                        <?php
                        }
                        ?>
                        <div class="row">
                          <div class="col-md-4 col-lg-4">
                            <label>Status Pengguna</label>
                          </div>
                          <div class="col-md-8 col-lg-8">
                            <label><?= $this->lib->status($pengguna[0]['status_pengguna']); ?></label>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4 col-lg-4">
                            <label>Last Login</label>
                          </div>
                          <div class="col-md-8 col-lg-8">
                            <label><?= $this->lib->tanggal_9t($pengguna[0]['last_login']); ?></label>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4 col-lg-4">
                            <label>Dibuat</label>
                          </div>
                          <div class="col-md-8 col-lg-8">
                            <label><?= $this->lib->tanggal_9t($pengguna[0]['created_at']); ?></label>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4 col-lg-4">
                            <label>Terakhir diubah</label>
                          </div>
                          <div class="col-md-8 col-lg-8">
                            <label><?= $this->lib->tanggal_9t($pengguna[0]['updated_at']); ?></label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row mt-2">
                      <div class="col-12">
                        <h6>Riwayat Aktivitas</h6>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12">
                        <table class="table table-striped" id="table-1">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Aktivitas</th>
                              <th>Keterangan</th>
                              <th>Tanggal</th>
                              <th>Ip Address</th>
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
            </div>
          </div>
        </section>
      </div>

      <div class="modal fade" tabindex="-1" role="dialog" id="modal-aksi">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Pengguna</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form class="needs-validation" novalidate="" action="#" method="POST" id="form-aksi">
              <div class="modal-body">
                <div class="form-group mb-1" id="oldpassword">
                  <label>Password Lama</label>
                  <input type="password" name="oldpassword" class="form-control">
                </div>
                <div class="form-group mb-1" id="newpassword">
                  <label>Password Baru</label>
                  <input type="password" name="newpassword" class="form-control" required="">
                  <div class="invalid-feedback">
                    Password harus diisi
                  </div>
                </div>
                <div class="form-group mb-1" id="cpassword">
                  <label>Confirm Password</label>
                  <input type="password" name="cpassword" class="form-control" required="">
                  <div class="invalid-feedback">
                    Confirm Password harus diisi
                  </div>
                </div>
              </div>
              <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" name="btn-simpan" id="btn-simpan" class="btn btn-primary">Simpan</button>
              </div>
            </form>
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

    table = $("#table-1").DataTable({
      "processing": true,
      "serverSide": true,
      "order": [],
      "ajax": {
        "url": "<?php echo base_url() ?>pengguna/get-data-log-pengguna",
        "type": "POST",
      },
      "ordering": false,
      "pagingType": "full_numbers",
      "stateSave": true,
      "lengthChange": false,
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
          "targets": [1],
          "className": 'text-center',
          "width": 190
        },
        {
          "targets": [-2],
          "className": 'text-center',
          "width": 140
        },
        {
          "targets": [-1],
          "className": 'text-center',
          "width": 110
        }
      ]
    });

    $('body').tooltip({
      selector: '[data-toggle="tooltip"]'
    });

    function reload_table() {
      table.ajax.reload(null, false);
    }

    function ubah_password(id) {
      $('#form-aksi')[0].reset();
      $('.form-group').removeClass('has-error');
      $('.help-block').empty();
      $('.modal-title').text('Ubah Password');
      $('#modal-aksi').modal('show');
    }

    $('#form-aksi').submit(function(event) {
      event.preventDefault();
      if ($('#form-aksi')[0].checkValidity() === false) {
        event.stopPropagation();
      } else {
        $("#btn-simpan").text("Proses Menyimpan");
        $("#btn-simpan").attr("disabled", true);

        $.ajax({
          url: "<?= base_url() ?>pengguna/change-password",
          type: "POST",
          data: {
            oldpassword: $('input[name="oldpassword"]').val(),
            newpassword: $('input[name="newpassword"]').val(),
            cpassword: $('input[name="cpassword"]').val(),
          },
          dataType: "JSON",
          success: function(data) {
            if (data.status) {
              $('#modal-aksi').modal('hide');
              infoalert(data.pesan, data.tipe_pesan);
              reload_table();
            } else {
              infoalert(data.pesan, data.tipe_pesan);
            }

            $('#btn-simpan').text('Simpan');
            $('#btn-simpan').attr('disabled', false);
          },
          error: function(jqXHR, textStatus, errorThrown) {
            swal('Terjadi kesalahan penyimpanan data!');
            $('#btn-simpan').text('Simpan');
            $('#btn-simpan').attr('disabled', false);
          }
        });
      }
      $('#form-aksi').addClass('was-validated');
    });
  </script>

</body>

</html>