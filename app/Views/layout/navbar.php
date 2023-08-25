<nav class="navbar navbar-expand-lg" style="background-color: darkblue;">
  <div class="container-fluid">
  <?php $ip = "http://".$_SERVER['SERVER_NAME']."/reportmenu/"; ?>
    <a class="navbar-brand text-light" href="<?= $ip; ?>"><b>Report Menu IGR-PWT</b></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="<?= $ip; ?>">Dashboard</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle fw-bold text-light" href="<?= $ip; ?>ms" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            MS
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?= $ip; ?>ms/cekmember">Cek Data Member</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>ms/transaksimember">History Transaksi Member</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>ms/pengeluaranhadiah">Pengeluaran Hadiah</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>ms/monitoringchecker">Monitoring Checker</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>ms/cetaksso">Cetak SSO</a></li>
            
          </ul>
          <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="<?= $ip; ?>store" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Store
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?= $ip; ?>store/previewkasir">Preview Transaksi Kasir</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>store/cekpromo">Cek Promo</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>store/monitoringpromo">Monitoring Promo</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>store/diskonminus">Cek Diskon Minus</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>store/marginminus">Cek Margin Minus</a></li>
            <li><a class="dropdown-item">____________________</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>store/transaksiisaku">Data Transaksi i-Saku</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>store/transaksimypoint">Data Transaksi MyPoin</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>store/transaksimitra">Data Transaksi Mitra</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>store/transaksiklik">Data Transaksi Klik</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>store/transaksiproduk">History Transaksi Produk</a></li>
            <li><a class="dropdown-item">_____________________</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>store/salesmember">Laporan Sales Member</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>store/salesperdep">Monitoring Sales PerHari</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>store/kompetisikasir">Kompetisi Kasir</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle fw-bold text-light" href="/store" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Logistik
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?= $ip; ?>logistik/formsoharian">Form SO Harian</a></li>
            <hr>
            <li><a class="dropdown-item" href="<?= $ip; ?>logistik/lppvsplanodetail">LPP vs Plano Detail</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>logistik/lppvsplanorekap">LPP vs Plano Rekap</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle fw-bold text-light" href="/store" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            EDP
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?= $ip; ?>edp/barangtertinggal">Cek Barang Tertinggal</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle fw-bold text-light" href="/store" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Link External
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="http://192.168.240.179/mypoincs/public/">Verifikasi No HP Member</a></li>
            <li><a class="dropdown-item" href="http://172.31.2.118/apinewmember/public/membership-check">Aplikasi Cek Membership</a></li>
            <li><a class="dropdown-item" href="http://192.168.240.205:8080/reportmenu/cekmypoin/">Cek Saldo MyPoin</a></li>
            <li><a class="dropdown-item" href="https://mitraindogrosir.co.id/cms/login">CMS Mitra Indogrosir</a></li>
            <li><a class="dropdown-item" href="http://192.168.240.179/memberbina/public/auth/login">Web Member Binaan</a></li>
            <li><a class="dropdown-item" href="http://192.168.10.100/maps/public/auth/login">Web Pemetaan Member</a></li>
            <li><a class="dropdown-item" href="http://192.168.240.205:8080/reportmenu/aws_igr/">Upload Foto & Koordinat Member</a></li>
            <li><a class="dropdown-item" href="http://172.20.28.17/monitoring-inquiry/public/login">Inquiry Master Member</a></li>
            <li><a class="dropdown-item" href="http://172.31.27.68/login">CMS TMI</a></li>
            <li><a class="dropdown-item" href="http://172.20.28.17/WebLaporanKasir/public/login">Web Laporan Refund</a></li>
          </ul>
        </li>
    </div>
  </div>
</nav>