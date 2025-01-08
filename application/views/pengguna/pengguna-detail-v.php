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
            <h1>Data Pengguna</h1>
          </div>
          <div class="section-body">
            <div class="row">
              <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                  <div class="card-body" style="color:#252525;">
                    <div class="row">
                      <div class="col-lg-2 col-md-12">
                        <?php
                          $foto_pengguna = "";
                          $foto_pengguna = $this->lib->foto_pengguna($foto_pengguna);
                        ?>

                        <img src="<?=base_url()?>assets/img/photo/<?=$foto_pengguna;?>" style="border:2px #218838 solid; max-height:200px;" class="rounded img-fluid mx-auto d-block mb-2">
                        <?php
                          $menu_pengguna = array(90,99);
                          if(in_array($grup_pengguna,$menu_pengguna)) {
                        ?>
                        <a href="<?=base_url()?>pengguna/edit/<?=$this->lib->encrypt_url($pengguna[0]['id_pengguna'])?>" class="btn btn-primary btn-block mb-3">Ubah Data</a>
                        <?php
                          }
                        ?>
                        
                      </div>
                      <div class="col-lg-5 col-md-6">
                        <div class="row">
                          <div class="col-md-4 col-lg-4">
                            <label>Nama</label>
                          </div>
                          <div class="col-md-8 col-lg-8">
                            <label><?=$pengguna[0]['nama'];?></label>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4 col-lg-4">
                            <label>Username</label>
                          </div>
                          <div class="col-md-8 col-lg-8">
                            <label><?=$pengguna[0]['username'];?></label>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4 col-lg-4">
                            <label>NIK</label>
                          </div>
                          <div class="col-md-8 col-lg-8">
                            <label><?=$pengguna[0]['nik'];?></label>
                          </div>
                        </div>
                        <?php
                          $grup_pengguna = $this->session->userdata[$this->param['session']]['grup_pengguna'];
                          $menu_pengguna = array(90,99);
                          if(in_array($grup_pengguna,$menu_pengguna)) {
                        ?>
                        <div class="row">
                          <div class="col-md-4 col-lg-4">
                            <label>Grup Pengguna</label>
                          </div>
                          <div class="col-md-8 col-lg-8">
                            <label><?=$pengguna[0]['nama_grup'];?></label>
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
                            <label><?=$this->lib->status($pengguna[0]['status_pengguna']);?></label>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4 col-lg-4">
                            <label>Last Login</label>
                          </div>
                          <div class="col-md-8 col-lg-8">
                            <label><?=$this->lib->tanggal_9t($pengguna[0]['last_login']);?></label>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4 col-lg-4">
                            <label>Dibuat</label>
                          </div>
                          <div class="col-md-8 col-lg-8">
                            <label><?=$this->lib->tanggal_9t($pengguna[0]['created_at']);?></label>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4 col-lg-4">
                            <label>Terakhir diubah</label>
                          </div>
                          <div class="col-md-8 col-lg-8">
                            <label><?=$this->lib->tanggal_9t($pengguna[0]['updated_at']);?></label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php
                      $menu_pengguna = array(90,99);
                      if(in_array($grup_pengguna,$menu_pengguna)) {
                    ?>
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
                    <?php
                      }
                    ?>
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
  
  <?php
    $menu_pengguna = array(90,99);
    if(in_array($grup_pengguna,$menu_pengguna)) {
  ?>
  <script type="text/javascript">
    var table;
    
    table = $("#table-1").DataTable({
      "processing": true, 
      "serverSide": true, 
      "order": [], 
      "ajax": {
        "url": "<?php echo base_url()?>pengguna/get-data-log",
        "type": "POST",
        "data": function(data) {
          data.id_pengguna = '<?=$pengguna[0]['idpengguna'];?>';
        }
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
      "columnDefs": [
        {"targets": [0], "className": 'text-center', "width": 50},
        {"targets": [1], "className": 'text-center', "width": 190},
        {"targets": [-2], "className": 'text-center', "width": 140},
        {"targets": [-1], "className": 'text-center', "width": 110}
      ]
    });

    $('body').tooltip({selector: '[data-toggle="tooltip"]'});

    function reload_table() {
      table.ajax.reload(null,false);
    }
  </script>
  <?php
    }
  ?>

</body>
</html>