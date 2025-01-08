<?php if ($this->session->flashdata('pesan') == "ada") { ?>
  <script type="text/javascript">
    swal({
      title: "Pesan!",
      text: "<?=$this->session->flashdata('pesan_isi'); ?>",
      timer: 5000,
      icon: "<?=$this->session->flashdata('pesan_tipe'); ?>",
      buttons: {
        confirm : {text:'Ok',className:'btn btn-success'},
      },
    });
  </script>
  <?php } ?>
  <?php if ($this->session->flashdata('logout') == "ok") { ?>
  <script type="text/javascript">
    swal({
      title: 'Apakah anda ingin logout ?',
      icon: 'warning',
      buttons: {
        confirm : {text:'Logout !',className:'btn btn-success'},
        cancel : {text: 'Batalkan', className:'btn btn-default', visible: true}
      },
    }).then((logout) => {
      if (logout) {
        window.location.href = '<?=base_url()?>logout';
      }
    });
  </script>
  <?php } ?>
  <script type="text/javascript">
    function infoalert(pesan, tipe_pesan) {
      swal({
        title: "Pesan!",
        text: pesan,
        timer: 5000,
        icon: tipe_pesan,
        buttons: {
          confirm : {text:'Ok',className:'btn btn-primary'},
        },
      });
    }
  </script>