<nav class="navbar navbar-expand-lg" style="background-color: blue;">
  <div class="container-fluid">
  <?php $ip = "http://".$_SERVER['SERVER_NAME']."/reportmenu"; ?>
    <a class="navbar-brand text-light" href="<?= $ip; ?>"><b>Report Menu IGR-PWT</b></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link text-light" aria-current="page" href="<?= $ip; ?>"><i class="fa-solid fa-house"></i></a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle fw-bold text-light" href="/store" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fa-solid fa-store me-1"></i> Store
          </a>
          <ul class="dropdown-menu">
            <li><a class="" style="font-size: 14px; color:gray">Kasir</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/store/previewkasir">Preview Transaksi Kasir</a></li>            
            <li><a class="dropdown-item" href="<?= $ip; ?>/store/kompetisikasir">Kompetisi Kasir</a></li>
            <hr>
            <li><a class="" style="font-size: 14px; color:gray">Transaksi</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/store/transaksiisaku">Data Transaksi i-Saku</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/store/transaksimypoint">Data Transaksi MyPoin</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/store/transaksimitra">Data Transaksi Mitra</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/store/transaksiklik">Data Transaksi Klik</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/store/monitoringklik">Monitoring Transaksi Klik</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/store/slklik">Service Level KLIK</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/store/transaksiproduk">History Transaksi Produk</a></li>
            <hr>
            <li><a class="" style="font-size: 14px; color:gray">Promo</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/store/cekpromo">Cek Promo</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/store/monitoringpromo">Monitoring Promo</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/store/diskonminus">Cek Diskon Minus</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/store/marginminus">Cek Margin Minus</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/store/promoperrak">Promo Per Rak</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle fw-bold text-light" href="/ms" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fa-solid fa-person me-1"></i> Member
          </a>
          <ul class="dropdown-menu">
            <li><a class="" style="font-size: 14px; color:gray">Data</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/member/cekmember">Cek Data Member</a></li>
            <hr>
            <li><a class="" style="font-size: 14px; color:gray">Transaksi</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/member/transaksimember">History Transaksi Member</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/member/pengeluaranhadiah">Pengeluaran Hadiah</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/member/serahterimahdh">Serah Terima Hadiah *</a></li>
            <hr>
            <li><a class="" style="font-size: 14px; color:gray">Sales</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/member/salesmember">Laporan Sales Member</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/member/salesperdep">Monitoring Sales PerHari</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/member/efaktur">Monitoring Efaktur</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle fw-bold text-light" href="/store" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fa-solid fa-warehouse me-1"></i> Inventory
          </a>
          <ul class="dropdown-menu">
            <li><a class="" style="font-size: 14px; color:gray">LPP</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/logistik/informasiproduk">Informasi Produk</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/logistik/kkhpbm">Monitoring KKHPBM</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/logistik/lppsaatini">LPP Saat Ini</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/logistik/tampillppblnlalu">LPP Bulan Sebelumnya</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/logistik/lppvsplanodetail">LPP vs Plano Detail</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/logistik/lppvsplanorekap">LPP vs Plano Rekap</a></li>
            <hr>
            <li><a class="" style="font-size: 14px; color:gray">Transaksi</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/logistik/backoffice">Laporan Back Office</a></li>
            <hr>
            <li><a class="" style="font-size: 14px; color:gray">Monitoring</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/logistik/formsoharian">SO Harian</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/logistik/soic">SO IC</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/logistik/stockharian">Stock Harian</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/logistik/kertaskerja">Kertas Kerja Storage</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/logistik/servicelevel">Service Level</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/logistik/servicelevelbo">Service Level 3 Periode</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/logistik/livecks">Live CKS</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/logistik/produkbaru">Produk Baru</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/logistik/ubahstatus">Perubahan Status</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/logistik/itemseasonal">Item Seasonal</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/logistik/pertemanan">Monitoring Pertemanan</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/logistik/kesegaran">Cek Kesegaran Produk</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle fw-bold text-light" href="<?= $ip; ?>/store" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fa-solid fa-computer me-1"></i>EDP
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?= $ip; ?>/edp/barangtertinggal">Cek Barang Tertinggal</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/edp/monitoringchecker">Monitoring Checker</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/edp/cetaksso">Cetak SSO</a></li>
            <hr>
            <li><a class="" style="font-size: 14px; color:gray">Link</a></li>
            <li><a class="dropdown-item" href="http://172.20.28.17/Select-Oracle/public/select-oracle/login">Login Oracle Database</a></li>            
            <li><a class="dropdown-item" href="http://192.168.237.72/rekor/report-update-promosi-ho/index.php?linkCabang=&kdCabang=47">Promo Belum Download</a></li>            
          </ul>
        </li>
        <!-- <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle fw-bold text-light" href="<?= $ip; ?>/store" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fa-solid fa-list me-1"></i> Link External
          </a>
          <ul class="dropdown-menu">
            <li><a class="" style="font-size: 14px; color:gray">MS</a></li>
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
            <hr>
            <li><a class="" style="font-size: 14px; color:gray">KLIK</a></li>
            <li><a class="dropdown-item" href="https://klikindogrosir.com/">Klik Indogrosir (User)</a></li>
            <li><a class="dropdown-item" href="https://b2b.klikindogrosir.com/admin">Klik Indogrosir (Admin)</a></li>
            <li><a class="dropdown-item" href="http://172.31.27.67:81/login">Klik Indogrosir (MS)</a></li>
            <li><a class="dropdown-item" href="http://172.20.28.17/serah-terima-ipp/public/">Serah Terima IPP</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle fw-bold text-light" href="<?= $ip; ?>/ms" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fa-solid fa-users-gear me-1"></i>Program HO
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="http://172.20.30.3/ess/homeportal">ESS 172.20.30.3</a></li>
            <li><a class="dropdown-item" href="http://172.20.30.4/ess/homeportal">ESS 172.20.30.4</a></li>
            <li><a class="dropdown-item" href="http://172.20.30.5/ess/homeportal">ESS 172.20.30.5</a></li>
            <li><a class="dropdown-item" href="http://172.20.30.3/tsm">TSM 172.20.30.3</a></li>
            <li><a class="dropdown-item" href="http://172.20.30.4/tsm">ESS 172.20.30.4</a></li>
            <li><a class="dropdown-item" href="http://172.20.30.5/tsm">ESS 172.20.30.5</a></li>
            <li><a class="dropdown-item" href="http://172.20.30.6/tsm">ESS 172.20.30.6</a></li>
            <li><a class="dropdown-item" href="http://172.31.16.71/Reports/Pages/Folder.aspx?ItemPath=%2fEISIGR&ViewMode=List">EIS Web Report (IE)</a></li>
            <li><a class="dropdown-item" href="http://172.20.28.33/qlikview/FormLogin.htm">Klik View</a></li>
            <li><a class="dropdown-item" href="http://192.168.240.179/soujipetik/public/auth/login">SO Uji Petik (Production)</a></li>
            <li><a class="dropdown-item" href="http://192.168.240.179/simsoujipetik/public/auth/login">SO Uji Petik (Simulasi)</a></li>
            <li><a class="dropdown-item" href="http://172.20.28.17/monitoring-plano/public/laporan">Monitoring Plano</a></li>
            <li><a class="dropdown-item" href="http://172.20.28.28/#/account/login">Tarra E-Faktur</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle fw-bold text-light" href="/store" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fa-solid fa-star me-1"></i> OMI
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?= $ip; ?>/omi/monitoringpbomi">Monitoring PB OMI</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/omi/cekprosespbomi">Cek Proses PB OMI</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/omi/cekprosessph">Cek Proses SPH</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/omi/historybkl">History BKL</a></li>
            <li><a class="dropdown-item" href="<?= $ip; ?>/omi/slomi">Service Level OMI</a></li>
          </ul>
        </li>  
        <li class="nav-item">
          <a class="nav-link text-light" aria-current="page" href="<?= $ip; ?>/mplano/"><i class="fa-solid fa-lightbulb me-1"></i>Plano Mobile IGR</a>
        </li>
      </ul>
    </div>


  </div>
</nav>