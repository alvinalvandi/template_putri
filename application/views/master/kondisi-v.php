<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php $this->load->view('layout/head'); ?>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="<?=base_url()?>assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?=base_url()?>assets/modules/fontawesome/css/all.min.css">
  <!-- CSS Libraries -->
  <link rel="stylesheet" href="<?=base_url()?>assets/modules/datatables/datatables.min.css">
  <link rel="stylesheet" href="<?=base_url()?>assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
  <!-- Template CSS -->
  <link rel="stylesheet" href="<?=base_url()?>assets/css/style.css">
  <link rel="stylesheet" href="<?=base_url()?>assets/css/components.css">
  <style>
    .table td, .table th {
      padding:0.25rem;
      vertical-align:middle;
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
            <h1>Data Kondisi Aset</h1>
            <div class="section-header-breadcrumb">
              <button class="btn btn-icon icon-left btn-primary" onclick="add()"><i class="fas fa-plus-circle"></i> Tambah</button>
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
                            <th>Nama Kondisi</th>
                            <th>Status</th>
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

      <div class="modal fade" tabindex="-1" role="dialog" id="modal-aksi">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Kondisi</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form class="needs-validation" novalidate="" action="#" method="POST" id="form-aksi">
              <input type="hidden" name="id_kondisi" class="form-control">
              <div class="modal-body">
                <div class="form-group mb-1">
                  <label>Nama Kondisi</label>
                  <input type="text" name="nama_kondisi" class="form-control" required="">
                  <div class="invalid-feedback">
                    Nama Kondisi harus diisi
                  </div>
                </div>
                <div class="form-group mb-1">
                <label>Status</label>
                  <select name="status_kondisi" class="form-control" required="">
                    <option value="">--- Pilih Status ---</option>
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                  </select>
                  <div class="invalid-feedback">
                    Nama Status harus diisi
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
  <script src="<?=base_url()?>assets/modules/jquery.min.js"></script>
  <script src="<?=base_url()?>assets/modules/popper.js"></script>
  <script src="<?=base_url()?>assets/modules/tooltip.js"></script>
  <script src="<?=base_url()?>assets/modules/bootstrap/js/bootstrap.min.js"></script>
  <script src="<?=base_url()?>assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
  <script src="<?=base_url()?>assets/js/stisla.js"></script>
  <!-- JS Libraies -->
  <script src="<?=base_url()?>assets/modules/sweetalert/sweetalert.min.js"></script>
  <script src="<?=base_url()?>assets/modules/datatables/datatables.min.js"></script>
  <script src="<?=base_url()?>assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
  <script src="<?=base_url()?>assets/modules/jquery-ui/jquery-ui.min.js"></script>
  <!-- Page Specific JS File -->
  <!-- Template JS File -->
  <script src="<?=base_url()?>assets/js/app.js"></script>
  <script src="<?=base_url()?>assets/js/custom.js"></script>

  <?php $this->load->view('layout/alert_notification'); ?>
  
  <script type="text/javascript">
    var table;
    var action_method;
    
    table = $("#table-1").DataTable({
      "processing": true, 
      "serverSide": true, 
      "order": [], 
      "ajax": {
        "url": "<?php echo base_url()?>master/get-data-kondisi",
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
            "first": "First", "previous": "Previous", "next": "Next", "last": "Last"
          }
        },
        "processing": "<i class='fa fa-refresh fa-spin'></i>"
      },
      "columnDefs": [
        {"targets": [0], "className": 'text-center', "width": 50},
        {"targets": [-1], "className": 'text-center', "width": 100}
      ]
    });

    $('body').tooltip({selector: '[data-toggle="tooltip"]'});

    function reload_table() {
      table.ajax.reload(null,false);
    }

    $('#modal-aksi').on('hide.bs.modal', function(e) {
      $(this).find("input,textarea,select").val('').end()
      .find("input[type=checkbox], input[type=radio]").prop("checked", "").end();
    });

    function add() {
      action_method = 'add';
      $('#form-aksi')[0].reset();
      $('.form-group').removeClass('has-error');
      $('#modal-aksi').modal('show');
      $('.modal-title').text('Tambah Kondisi');
    }

    function ubah(id) {
      action_method = 'edit';
      $('#form-aksi')[0].reset();
      $('.form-group').removeClass('has-error');
      $('.help-block').empty();
      $('.modal-title').text('Ubah Kondisi');

      $.ajax({
        url : "<?=base_url()?>master/get-kondisi/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
          $('input[name="id_kondisi"]').val(data.idkondisi);
          $('input[name="nama_kondisi"]').val(data.nama_kondisi);
          $('select[name="status_kondisi"]').val(data.status_kondisi);
          $('#modal-aksi').modal('show');
        },
        error: function (jqXHR, textStatus, errorThrown) {
          swal('Terjadi kesalahan!');
        }
      });
    }

    $('#form-aksi').submit(function(event) {
      event.preventDefault();
      if ($('#form-aksi')[0].checkValidity() === false) {
          event.stopPropagation();
      } else {
        $("#btn-simpan").text("Proses Menyimpan");
        $("#btn-simpan").attr("disabled",true);

        $.ajax({
          url : "<?=base_url()?>master/kondisi-aksi",
          type: "POST",
          data: {
            id_kondisi: $('input[name="id_kondisi"]').val(),
            nama_kondisi: $('input[name="nama_kondisi"]').val(),
            status_kondisi: $('select[name="status_kondisi"]').val(),
            action_method: action_method,
          },
          dataType: "JSON",
          success: function(data) {
            if(data.status) {
              $('#modal-aksi').modal('hide');
              infoalert(data.pesan, data.tipe_pesan);
              reload_table();
            } else {
              infoalert(data.pesan, data.tipe_pesan);
            }
            
            $('#btn-simpan').text('Simpan');
            $('#btn-simpan').attr('disabled',false);
          },
          error: function (jqXHR, textStatus, errorThrown) {
            swal('Terjadi kesalahan penyimpanan data!');
            $('#btn-simpan').text('Simpan');
            $('#btn-simpan').attr('disabled',false);
          }
        });
      }
      $('#form-aksi').addClass('was-validated');
    });
    
    function hapus(id) {
      swal({
        title: 'Anda yakin hapus data ini ?',
        icon: 'warning',
        buttons: {
          confirm : {text:'Hapus !',className:'btn btn-primary'},
          cancel : {text: 'Batalkan', className:'btn btn-default', visible: true}
        },
      })
      .then((willDeleted) => {
        if (willDeleted) {
          $.ajax({
            url : "<?=base_url()?>master/kondisi-aksi",
            type: "POST",
            data: {
              id_kondisi: id,
              action_method: "hapus",
            },
            dataType: "JSON",
            success: function(data) {
              if(data.status) {
                infoalert(data.pesan, data.tipe_pesan);
                reload_table();
              } else {
                infoalert(data.pesan, data.tipe_pesan);
              }
            },
            error: function (jqXHR, textStatus, errorThrown) {
              swal('Terjadi kesalahan!');
            }
          });
        } else {
          swal('Data tidak dihapus');
        }
      });
    }
  </script>
</body>
</html>