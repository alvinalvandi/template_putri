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
            <h1>Data Mutasi Aset</h1>
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
                          <th>Nama Aset</th>
                          <th>Lokasi Awal</th>
                          <th>Kantor Awal</th>
                          <th>Lokasi Baru</th>
                          <th>Kantor Baru</th>
                          <th>Tanggal</th>
                          <th>Lampiran</th>
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

      <div class="modal fade" role="dialog" id="modal-aksi">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Mutasi</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form class="needs-validation" novalidate="" action="#" method="POST" id="form-aksi">
              <input type="hidden" name="id_mutasi" class="form-control">
              <input type="hidden" name="id_aset" class="form-control">
              <div class="modal-body">
                <div class="form-group mb-1">
                  <label>Aset</label>
                  <select name="aset" id="aset" class="form-control select2" style="width:100%" required="">
                    <option value=""> --- Pilih Aset ---</option>
                    <?php
                    foreach ($data_aset as $aset) {
                      echo "<option value=\"" . $aset->id_aset . "\">" . $aset->kode_aset . " - " . $aset->nama_kantor . "</option>";
                    }
                    ?>
                  </select>
                  <div class="invalid-feedback">
                    Aset harus dipilih
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 col-sm-12">
                    <div class="form-group mb-1">
                      <label>Kantor Awal</label>
                      <input type="text" name="kantor_awal" id="kantor_awal" class="form-control" required="" readonly="">
                      <div class="invalid-feedback">
                        Kantor Awal harus diisi
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 col-sm-12">
                    <div class="form-group mb-1">
                      <label>Lokasi Awal</label>
                      <input type="text" name="lokasi_awal" id="lokasi_awal" class="form-control" required="" readonly="">
                      <div class="invalid-feedback">
                        Lokasi Awal harus diisi
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 col-sm-12">
                    <div class="form-group mb-1">
                      <label>Kantor Baru</label>
                      <select name="kantor_baru" id="kantor_baru" class="form-control select2" style="width:100%" required="">
                        <option value=""> --- Pilih Kantor ---</option>
                        <?php
                        foreach ($master_kantor as $kantor) {
                          echo "<option value=\"" . $kantor->kode_ktr . "\">" . $kantor->kode_ktr . " - " . $kantor->nama_kantor . "</option>";
                        }
                        ?>
                      </select>
                      <div class="invalid-feedback">
                        Kantor Baru harus diisi
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 col-sm-12">
                    <div class="form-group mb-1">
                      <label>Lokasi Baru</label>
                      <select name="lokasi_baru" id="lokasi_baru" class="form-control select2" style="width:100%" required="">

                      </select>
                      <div class="invalid-feedback">
                        Lokasi Baru harus diisi
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                </div>
                <div class="row">
                  <div class="col-md-6 col-sm-12">
                    <div class="form-group mb-1">
                      <label>Tanggal Mutasi</label>
                      <input type="date" name="tgl_mutasi" min="2008-01-01" class="form-control" required="">
                      <div class="invalid-feedback">
                        Tanggal Mutasi harus diisi
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 col-sm-12">
                    <div div class="form-group mb-1">
                      <label>Lampiran <i style="color: red;" id="info_lampiran">(*Kosongkan jika tidak ingin ubah
                          lampiran)</i></label>
                      <input type="file" name="file_pdf" id="file_pdf" class="form-control" accept="application/pdf" required="">
                      <div class="invalid-feedback">
                        Lampiran harus diisi
                      </div>
                    </div>
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
    var action_method;

    table = $("#table-1").DataTable({
      "processing": true,
      "serverSide": true,
      "order": [],
      "ajax": {
        "url": "<?php echo base_url() ?>core-aset/get-data-mutasi",
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
          "targets": [-2],
          "className": 'text-center',
          "width": 150
        },
        {
          "targets": [-1],
          "className": 'text-center',
          "width": 100
        }
      ]
    });

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

    function get_data_lokasi_edit(kantor, lokasi) {
      $.ajax({
        "url": "<?php echo base_url() ?>core-aset/get-data-lokasi-edit",
        method: "POST",
        data: {
          id_kantor: kantor
        },
        async: true,
        dataType: 'json',
        success: function(data) {
          var html = '';
          var i;
          $('#lokasi_baru').empty();
          html += '<option value="">--- Pilih Lokasi Baru Penempatan Aset --- </option>';
          for (i = 0; i < data.length; i++) {
            if (data[i].id_lokasi == lokasi) {
              html += '<option selected value=' + data[i].id_lokasi + '>' + data[i].nama_lokasi +
                '</option>';
            } else {
              html += '<option value=' + data[i].id_lokasi + '>' + data[i].nama_lokasi + '</option>';
            }
          }
          $('#lokasi_baru').append(html);
        }
      });
    }

    //ketika pilih kantor baru maka get lokasi berdasarkan kantor tersebut
    $('#kantor_baru').change(function() {
      var ids = $(this).val();
      $('select[name="lokasi_baru"]').prop("disabled", false);

      $.ajax({
        "url": "<?php echo base_url() ?>core-aset/get-data-lokasi",
        method: "POST",
        data: {
          kode_ktr: ids
        },
        async: true,
        dataType: 'json',
        success: function(data) {
          var html = '';
          var i;
          if (data.length == 1) {
            $('#lokasi_baru').empty();
            html += '<option value=' + data[0].id_lokasi + '>' + data[0].nama_lokasi + '</option>';
          } else {
            html += '<option value="">--- Pilih Lokasi Baru Penempatan Aset --- </option>';
            $('#lokasi_baru').empty();
            for (i = 0; i < data.length; i++) {
              html += '<option value=' + data[i].id_lokasi + '>' + data[i].nama_lokasi + '</option>';
            }
          }
          $('#lokasi_baru').append(html);
        }
      });
      return false;
    });

    function add() {
      action_method = 'add';
      $('#form-aksi')[0].reset();
      $('.form-group').removeClass('has-error');
      $('#modal-aksi').modal('show');
      $('.modal-title').text('Mutasi Aset');
      $('#info_lampiran').hide();
      $('select[name="aset"]').prop("disabled", false)
      $('select[name="aset"]').val('').trigger('change');
      $('select[name="kantor_baru"]').val('').trigger('change');
      $('select[name="lokasi_baru"]').prop("disabled", true);
      $('input[name="file_pdf"]').prop("required", true);

      $('#aset').change(function() {
        var id = $(this).val();
        $.ajax({
          "url": "<?php echo base_url() ?>core-aset/get-data-aset",
          method: "POST",
          data: {
            id_aset: id
          },
          async: true,
          dataType: 'json',
          success: function(data) {
            $('input[name="kantor_awal"]').val(data.kantor_awal);
            $('input[name="lokasi_awal"]').val(data.lokasi_awal);
          }
        });
        return false;
      });
    }

    function ubah(id) {
      action_method = 'edit';
      $('#form-aksi')[0].reset();
      $('.form-group').removeClass('has-error');
      $('.help-block').empty();
      $('.modal-title').text('Ubah Mutasi Aset');
      $('#info_lampiran').show();
      $('input[name="file_pdf"]').prop("required", false);
      $('select[name="aset"]').prop("disabled", true);

      $.ajax({
        url: "<?= base_url() ?>core-aset/get-data-mutasi-aset-edit/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
          var html = '';
          $('input[name="id_mutasi"]').val(data.idmutasi);
          $('input[name="id_aset"]').val(data.aset);
          $('select[name="aset"]').val(data.aset);
          $('input[name="lokasi_awal"]').val(data.lokasi_awal);
          $('input[name="kantor_awal"]').val(data.kantor_awal);
          $('select[name="kantor_baru"]').val(data.kantor_baru);
          $('input[name="tgl_mutasi"]').val(data.tgl_mutasi);
          $('#modal-aksi').modal('show');

          get_data_lokasi_edit(data.kantor_baru, data.lokasi_baru);

        },
        error: function(jqXHR, textStatus, errorThrown) {
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
        $("#btn-simpan").attr("disabled", true);

        var form_data = new FormData(this);
        form_data.append('action_method', action_method);
        var url;
        if (action_method == "add") {
          url = "<?= base_url() ?>core-aset/add-mutasi";
        } else if (action_method == "edit") {
          url = "<?= base_url() ?>core-aset/edit-mutasi";
        }

        $.ajax({
          url: url,
          dataType: 'json',
          cache: false,
          contentType: false,
          processData: false,
          data: form_data,
          type: 'post',
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
            swal(jqXHR.responseText);
            console.error(jqXHR);
            console.error(textStatus);
            console.error(errorThrown);
            // swal('Terjadi kesalahan penyimpanan data!');
            $('#btn-simpan').text('Simpan');
            $('#btn-simpan').attr('disabled', false);
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
              url: "<?= base_url() ?>core-aset/delete-mutasi",
              type: "POST",
              data: {
                id_mutasi: id,
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
                // swal(jqXHR.responseText);
                // console.error(jqXHR);
                // console.error(textStatus);
                // console.error(errorThrown);
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