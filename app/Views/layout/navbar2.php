<?php $ip = "http://".$_SERVER['SERVER_NAME']."/reportmenu/"; ?>
 

<nav class="main-header navbar-expand navbar-blue navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a data-widget="pushmenu" href="#" role="button" class="nav-link fw-bold">Reportmenu-IGR PWT</a>
      </li>
    </ul>
</nav>
<!-- Main Sidebar Container -->
 <aside class="main-sidebar sidebar-mini sidebar-dark-light elevation-4">
    <!-- Brand Logo -->
    <a href="<?= $ip; ?>" class="brand-link d-flex flex-column justify-content-center">
        <img src="<?= $ip; ?>public/img/igr2.png" alt="Logo" class="brand-image align-self-center elevation-3"
        style="height: fit-content; width:50%;">
      <span class="brand-text d-block text-center font-weight-light">INDOGROSIR PWT</span>
    </a>
    
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview">
            <a href="" class="nav-link">
              <i class="nav-icon fas fa-user-alt"></i>
              <p>
                MEMBER SERVICE
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?= $ip; ?>member/cekmember" class="nav-link">
                  <p>Cek Data Member</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>member/inquirymm" class="nav-link">
                  <p>Inquiry Member Merah</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>member/transaksimember" class="nav-link">
                  <p>History Transaksi Member</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>member/pengeluaranhadiah" class="nav-link">
                
                  <p>Pengeluaran Hadiah</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>member/serahterimahdh" class="nav-link">
                 
                  <p>Serah Terima Hadiah</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>member/salesmember" class="nav-link">
                  
                  <p>Laporan Sales Member</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>member/salesperdep" class="nav-link">
                 
                  <p>Monitoring Sales PerHari</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>member/evaluasiperish" class="nav-link">
                 
                  <p>Evaluasi Sales Perishable</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>member/evaslspromo" class="nav-link">
                  
                  <p>Evaluasi Sales Promo</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>member/efaktur" class="nav-link">
                 
                  <p>Monitoring Efaktur</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>member/salesperjam" class="nav-link">
             
                  <p>Sales Per Jam</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>member/kunjunganmember" class="nav-link">
                 
                  <p>Evaluasi Kunjungan Member</p>
                </a>
              </li>
            </ul>
          </li>
          
          <li class="nav-item has-treeview">
            <a href="<?= $ip; ?>store" class="nav-link">
              <i class="nav-icon fas fa-store"></i>
              <p>
                STORE
                <i class="fas fa-angle-left right"></i>
               
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?= $ip; ?>store/previewkasir" class="nav-link">
                  
                  <p>Preview Transaksi Kasir</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>store/kompetisikasir" class="nav-link">
              
                  <p>Kompetisi Kasir</p>
                </a>
              </li>
              <hr>
              <li class="nav-item">
                <a href="<?= $ip; ?>store/transaksiisaku" class="nav-link">
                
                  <p>Data Transaksi iSaku</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>store/transaksimypoint" class="nav-link">
                 
                  <p>Data Transaksi MyPoin</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>store/transaksimitra" class="nav-link">
                
                  <p>Data Transaksi Mitra</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="" class="nav-link">
                 
                  <p>Monitoring Transaksi Klik</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>store/slklik" class="nav-link">
                
                  <p>Service Level Klik</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>store/transaksiproduk" class="nav-link">
                
                  <p>History Transaksi Produk</p>
                </a>
              </li>
              <hr>
              <li class="nav-item">
                <a href="<?= $ip; ?>store/cekpromo" class="nav-link">
                 
                  <p>Cek Promo</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>store/monitoringpromo" class="nav-link">
                
                  <p>Monitoring Promo</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>store/diskonminus" class="nav-link">
              
                  <p>Cek Diskon Minus</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>store/marginminus" class="nav-link">
               
                  <p>Cek Margin Minus</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>store/promoperrak" class="nav-link">
                 
                  <p>Promo Per Rak</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                INVENTORY
                <i class="fas fa-angle-left right"></i>
                
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?= $ip; ?>logistik/informasiproduk" class="nav-link">
                 
                  <p>Informasi Produk</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>logistik/kkhpbm" class="nav-link">
                
                  <p>Monitoring KKHPBM</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>logistik/lppsaatini" class="nav-link">
                  
                  <p>LPP Saat Ini</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>logistik/tampillppblnlalu" class="nav-link">
                 
                  <p>LPP Bulan Sebelumnya</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>logistik/lppvsplanodetail" class="nav-link">
                 
                  <p>LPP vs Plano Detail</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>logistik/lppvsplanorekap" class="nav-link">
                 
                  <p>LPP vs Plano Rekap</p>
                </a>
              </li>
              <hr>
              <li class="nav-item">
                <a href="<?= $ip; ?>logistik/backoffice" class="nav-link">
               
                  <p>Lap BackOffice</p>
                </a>
              </li>
              <hr>
              <li class="nav-item">
                <a href="<?= $ip; ?>logistik/formsoharian" class="nav-link">
               
                  <p>SO Harian</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>logistik/soic" class="nav-link">
               
                  <p>SO IC</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>logistik/stockharian" class="nav-link">
               
                  <p>Stock Harian</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>logistik/servicelevel" class="nav-link">
               
                  <p>Service Level</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>logistik/servicelevelbo" class="nav-link">
               
                  <p>Service Level 3 Periode</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>logistik/kertaskerja" class="nav-link">
               
                  <p>Kertas Kerja Storage</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>logistik/livecks" class="nav-link">
               
                  <p>Live CKS</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>logistik/pooutstanding" class="nav-link">
               
                  <p>PO Outstanding</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>logistik/produkbaru" class="nav-link">
               
                  <p>Produk Baru</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>logistik/ubahstatus" class="nav-link">
               
                  <p>Perubahan Status</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>logistik/itemseasonal" class="nav-link">
               
                  <p>Item Seasonal</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>logistik/pertemanan" class="nav-link">
               
                  <p>Monitoring Pertemanan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>logistik/kesegaran" class="nav-link">
               
                  <p>Cek Kesegaran Produk</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>
                LAPORAN
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?= $ip; ?>laporan/nonhanox" class="nav-link">
                 
                  <p>NON HANOX</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>laporan/hanox" class="nav-link">
                  
                  <p>Tag HANOX</p>
                </a>
              </li>
              <hr>
              <li class="nav-item">
                <a href="<?= $ip; ?>laporan/masterlokasi" class="nav-link">
                  
                  <p>Master Lokasi</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>laporan/storagenull" class="nav-link">
                  
                  <p>Storage Toko Null</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>laporan/historyso" class="nav-link">
                  
                  <p>History SO</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>laporan/pobanding" class="nav-link">
                  
                  <p>PO Banding</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>laporan/cekmd" class="nav-link">
                  
                  <p>Cek Harga MD</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="<?= $ip; ?>" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                IDM/OMI
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?= $ip; ?>idm/idmcluster" class="nav-link">
                 
                  <p>Daftar Cluster IDM</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>idm/rakdpd" class="nav-link">
                  
                  <p>Rak DPD</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>idm/fakturpajak" class="nav-link">
                  
                  <p>Monitoring Faktur Pajak</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>idm/fakturpajakout" class="nav-link">
                  
                  <p>Monitoring Faktur Pajak Outstanding</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>idm/outstandingretur" class="nav-link">
                  
                  <p>Outstanding Retur</p>
                </a>
              </li>
              <hr>
              <li class="nav-item">
                <a href="<?= $ip; ?>omi/monitoringpbomi" class="nav-link">
                  
                  <p>Monitoring PB OMI</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>omi/cekprosespbomi" class="nav-link">
                  
                  <p>Cek Proses PB OMI</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>omi/cekprosessph" class="nav-link">
                  
                  <p>Cek Proses SPH</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>omi/historybkl" class="nav-link">
                  
                  <p>History BKL</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>omi/slomi" class="nav-link">
                  
                  <p>Service Level OMI</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>idm/itemkosongklik" class="nav-link">
                  
                  <p>Item Kosong PB IDM</p>
                </a>
              </li>
              <hr>
              <li class="nav-item">
                <a href="<?= $ip; ?>idm/idmbedatag" class="nav-link">
                  
                  <p>Item IDM Only Beda Tag dgn IGR</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>idm/tolakanpbidm" class="nav-link">
                  
                  <p>Tolakan PB IDM</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>omi/lokasidpd" class="nav-link">
                  
                  <p>Tidak Ada Lokasi DPD</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>omi/noid" class="nav-link">
                  
                  <p>Tidak Ada No ID</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-angry"></i>
              <p>
                PROBLEM
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?= $ip; ?>problem/barcodedouble" class="nav-link">
                 
                  <p>Barcode Double</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>logistik/tampilinfoproduk?satuanJual=0&statusTag=All&tanggalPromosi=All&divisi=All&dep=All&kat=All&lokasiTidakAda=on&jenisMarginNegatif=All&jenisLaporan=1A&tombol=btnview" class="nav-link">
                  
                  <p>Maslok Belum Ada</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>problem/lokasidouble" class="nav-link">
                  
                  <p>Maslok Double</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>logistik/planominus" class="nav-link">
                  
                  <p>Plano Minus</p>
                </a>
              </li>
              <hr>
              <li class="nav-item">
                <a href="<?= $ip; ?>logistik/tampilinfoproduk?satuanJual=All&statusTag=All&tanggalPromosi=All&divisi=All&dep=All&kat=All&hargaJualNol=on&jenisMarginNegatif=All&jenisLaporan=1A&tombol=btnview" class="nav-link">
                  
                  <p>Harga Jual Belum Ada</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>logistik/tampilinfoproduk?satuanJual=All&statusTag=All&tanggalPromosi=All&divisi=All&dep=All&kat=All&promoMahal=on&jenisMarginNegatif=All&jenisLaporan=1A&tombol=btnview" class="nav-link">
                  
                  <p>Harga Promo Lebih Mahal</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>logistik/tampilinfoproduk?satuanJual=All&statusTag=All&tanggalPromosi=All&divisi=All&dep=All&kat=All&marginNegatif=on&jenisMarginNegatif=1&jenisLaporan=1A&tombol=btnview" target="_b" class="nav-link">
                  
                  <p>Margin Negatif</p>
                </a>
              </li>
              <hr>
              <li class="nav-item">
                <a href="<?= $ip; ?>problem/membertidakbelanja" class="nav-link">
                  
                  <p>Member Tdk Berbelanja</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>problem/membertidur" class="nav-link">
                  
                  <p>Member Tidur</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>problem/belumaktivasi" class="nav-link">
                  
                  <p>Member Belum Aktivasi</p>
                </a>
              </li>
              <hr>
              <li class="nav-item">
                <a href="<?= $ip; ?>problem/itemtagnxmasih" class="nav-link">
                  
                  <p>Stock Item Tag N&X Masih Ada</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-anchor"></i>
              <p>
                PROBLEM IDM
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?= $ip; ?>problemidm/itemkosong" class="nav-link">
                 
                  <p>Item Kosong PB IDM</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>problemidm/dpddouble" class="nav-link">
                  
                  <p>DPD Double</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>problemidm/hppigridm" class="nav-link">
                  
                  <p>HPP IGR IDM</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>problemidm/itemidm" class="nav-link">
                  
                  <p>2846 Item IDM</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>problemidm/slidm" class="nav-link">
                  
                  <p>Service Level IDM</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>problemidm/lokasikosong" class="nav-link">
                  
                  <p>Qty,MaxPlano,Jenis Rak IDM</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-database"></i>
              <p>
                EDP
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?= $ip; ?>edp/barangtertinggal" class="nav-link">
                 
                  <p>Cek Barang Tertinggal</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>edp/monitoringchecker" class="nav-link">
                  
                  <p>Monitoring Checker</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>edp/cetaksso" class="nav-link">
                  
                  <p>Cetak SSO</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>edp/historyappfp" class="nav-link">
                  
                  <p>History Approval Fingerprint</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="http://172.20.28.17/Select-Oracle/public/select-oracle/login" class="nav-link">
                  
                  <p>Login Oracle Database</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="http://192.168.237.72/rekor/report-update-promosi-ho/index.php?linkCabang=&kdCabang=47" class="nav-link">
                  
                  <p>Promo Belum Download</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-warehouse"></i>
              <p>
                GO
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?= $ip; ?>logistik/tampildatabo?csrf_test_name=75f0af6adcd1fae36ead182d5c0dc48a&awal=2024-02-01&akhir=2024-05-31&jnstrx=B&divisi=All&kodePLU=&kodesup=&jenisLaporan=6&tombol=btnview" class="nav-link">
                 
                  <p>BPB Per Hari GO</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>go/bpbgo" class="nav-link">
                  
                  <p>BPB Detail Per PO GO</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>go/bpbperplugo" class="nav-link">
                  
                  <p>BPB Detail Item GO</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>go/pertemanan" class="nav-link">
                  
                  <p>Pertemanan GO</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= $ip; ?>logistik/tampilsl?awal=2024-02-01&akhir=2024-05-31&plu=&kdsup=&nmsup=&divisi=All&dep=All&kat=All&jenisLaporan=4B&tombol=btnview" class="nav-link">
                  
                  <p>Service Level GO</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="<?= $ip; ?>mplano/" class="nav-link">
              <i class="nav-icon fas fa-mobile"></i>
              <p>
                Plano Mobile IGR
                <i class="right fa fa"></i>
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  