<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="main-sidebar sidebar-style-2">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="<?= base_url() ?>dashboard">
        <img src="<?= base_url() ?>assets/img/logo-text-black.png" alt="logo" width="200" class="rounded">
      </a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="<?= base_url() ?>"><?= $this->param['title-sm']; ?></a>
    </div>
    <ul class="sidebar-menu">
      <li class="menu-header">Menu</li>

      <?php
      $grup_pengguna = $this->session->userdata[$this->param['session']]['grup_pengguna'];
      ?>
      <li class="<?= $this->lib->active_menu_check("dashboard"); ?>"><a class="nav-link" href="<?= base_url() ?>dashboard"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>

      <?php
      $menu_aset = array(99, 1, 11, 41, 42, 51, 52, 53);
      if (in_array($grup_pengguna, $menu_aset)) {
      ?>
        <li class="dropdown <?= $this->lib->active_menu_check("aset"); ?>">
          <a href="#" class="nav-link has-dropdown"><i class="fas fa-users"></i><span>Data Aset</span></a>
          <ul class="dropdown-menu">
            <li class="<?= $this->lib->active_menu_check("aset", "aktif"); ?>"><a class="nav-link" href="<?= base_url() ?>aset/aktif">Aktif</a></li>
            <li class="<?= $this->lib->active_menu_check("aset", "tidak-aktif"); ?>"><a class="nav-link" href="<?= base_url() ?>aset/tidak-aktif">Tidak Aktif</a></li>
            <li class="<?= $this->lib->active_menu_check("aset", "cetak-label"); ?>"><a class="nav-link" href="<?= base_url() ?>aset/cetak-label">Cetak Label Aset</a></li>
          </ul>
        </li>
      <?php
      }
      ?>

      <?php
      $menu_master = array(51, 52, 53, 99);
      if (in_array($grup_pengguna, $menu_master)) {
      ?>
        <li class="dropdown <?= $this->lib->active_menu_check("master"); ?>">
          <a href="#" class="nav-link has-dropdown"><i class="fas fa-database"></i><span>Data Master</span></a>
          <ul class="dropdown-menu">
            <li class="<?= $this->lib->active_menu_check("master", "generate"); ?>"><a class="nav-link" href="<?= base_url() ?>master/generate">Generate QR Aset</a></li>
            <li class="<?= $this->lib->active_menu_check("master", "kategori"); ?>"><a class="nav-link" href="<?= base_url() ?>master/kategori">Kategori Aset</a></li>
            <li class="<?= $this->lib->active_menu_check("master", "jenis"); ?>"><a class="nav-link" href="<?= base_url() ?>master/jenis">Jenis Aset</a></li>
            <li class="<?= $this->lib->active_menu_check("master", "kondisi"); ?>"><a class="nav-link" href="<?= base_url() ?>master/kondisi">Kondisi Aset</a></li>
            <li class="<?= $this->lib->active_menu_check("master", "lokasi"); ?>"><a class="nav-link" href="<?= base_url() ?>master/lokasi">Lokasi Detail Aset</a></li>
          </ul>
        </li>
      <?php
      }
      ?>

      <?php
      $menu_laporan = array(51, 52, 53, 99);
      if (in_array($grup_pengguna, $menu_laporan)) {
      ?>
        <li class="dropdown <?= $this->lib->active_menu_check("laporan"); ?>">
          <a href="#" class="nav-link has-dropdown"><i class="fas fa-user-edit"></i><span>Laporan</span></a>
          <ul class="dropdown-menu">
            <li class="<?= $this->lib->active_menu_check("laporan", "aset"); ?>"><a class="nav-link" href="<?= base_url() ?>laporan/aset">Laporan Aset</a></li>
          </ul>
        </li>
      <?php
      }
      ?>


      <?php
      $menu_pengguna = array(99);
      if (in_array($grup_pengguna, $menu_pengguna)) {
      ?>
        <li class="<?= $this->lib->active_menu_check("pengguna"); ?>"><a class="nav-link" href="<?= base_url() ?>pengguna"><i class="fas fa-user"></i> <span>Pengguna</span></a></li>
      <?php
      }
      ?>

      <?php
      $menu_history = array(99);
      if (in_array($grup_pengguna, $menu_history)) {
      ?>
        <li class="dropdown <?= $this->lib->active_menu_check("riwayat"); ?>">
          <a href="#" class="nav-link has-dropdown"><i class="fas fa-history"></i><span>Riwayat</span></a>
          <ul class="dropdown-menu">
            <li class="<?= $this->lib->active_menu_check("riwayat", "pengguna"); ?>"><a class="nav-link" href="<?= base_url() ?>riwayat/pengguna">Pengguna</a></li>
          </ul>
        </li>
      <?php
      }
      ?>

    </ul>
    <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
      <a href="<?= base_url() ?>logout" class="btn btn-danger btn-block btn-icon-split">
        <i class="fas fa-sign-out-alt"></i> Logout
      </a>
    </div>
  </aside>
</div>