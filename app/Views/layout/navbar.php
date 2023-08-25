<nav class="navbar navbar-expand-lg bg-primary">
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
          <a class="nav-link dropdown-toggle" href="/store" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
          <a class="nav-link dropdown-toggle" href="logistik" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Logistik
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?= $ip; ?>logistik/soharianplu">Form SO Harian</a></li>
          </ul>
        </li>
    </div>
  </div>
</nav>