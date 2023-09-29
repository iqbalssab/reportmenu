<?php $ip = "http://".$_SERVER['SERVER_NAME']."/reportmenu"; ?>

<style>
    /* membuat container atau wadah dari scrollbar dengan lebar 20px  */
    ::-webkit-scrollbar {
      width: 20px;
    }

    /* membuat background dari scrollbar */
    /* kasih warna transparan agar lebih estetik  */
    ::-webkit-scrollbar-track {
      background-color: transparent;
    }

  
    /* membuat styling pada batang atau bar scrollbar  */
    /* kita beri warna abu tua dengan lengkungan di sisi atas dan bawahnya  */
    ::-webkit-scrollbar-thumb {
      background-color: #6bb0ff;
      border-radius: 20px;
      border: 6px solid transparent;
      background-clip: content-box;
    }

    /* warna akan berubah menjadi abu mudah saat kursor diarahkan  */
    ::-webkit-scrollbar-thumb:hover {
      background-color: #2e4c6e;
    }
</style>

<nav class="bg-primary navbar">
    <!-- Left navbar links -->
    <a class="btn btn-primary pe-1 text-light" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
    <i class="fa-solid fa-bars me-1"></i>    Menu INDOGROSIR - PURWOKERTO
    </a>
</nav>

<div class="offcanvas offcanvas-start bg-dark text-light" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
  <div class="offcanvas-header">
    <a href="<?= $ip; ?>">
        <span class="d-flex flex-column justify-content-center">
            <img src="<?= $ip; ?>/public/img/igr2.png" alt="logo" class="d-block align-self-center mb-1" style="width: 30%;">
            <h4 class="offcanvas-title text-center text-light" id="offcanvasExampleLabel">INDOGROSIR PWT</h4>
        </span>
    </a>
    <button tton type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <div class="mb-3">
      <b class="text-warning">Warning !</b> Gunakan Reportmenu hanya sebagai acuan. Data asli tetap di IAS
    </div>
    <!-- Accordion Menus -->
    <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item">
                <a class="text-light" href="<?= $ip; ?>">
                <h2 class="accordion-header">
                    <button type="button" class="accordion-button collapsed bg-dark border border-secondary text-light" data-bs-toggle="collapse">
                        <i class="fa-solid fa-house me-2"></i> Home
                    </button>
                </h2>
                </a>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed bg-dark border border-secondary text-light" type="button" data-bs-toggle="collapse" 
                data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                <i class="fa-solid fa-user me-2"></i>
                Member
                </button>
              </h2>
            <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="list-group">
                    <a href="<?= $ip; ?>/member/cekmember" class="list-group-item list-group-item-action"></i>Cek Data Member</a>
                    <a href="<?= $ip; ?>/member/inquirymm" class="list-group-item list-group-item-action"></i>Inquiry Member Merah</a>
                    <a href="<?= $ip; ?>/member/transaksimember" class="list-group-item list-group-item-action"></i>History Transaksi Member</a>
                    <a href="<?= $ip; ?>/member/serahterimahdh" class="list-group-item list-group-item-action"></i>Serah Terima Hadiah</a>
                    <a href="<?= $ip; ?>/member/pengeluaranhadiah" class="list-group-item list-group-item-action"></i>Pengeluaran Hadiah</a>
                    <a href="<?= $ip; ?>/member/salesmember" class="list-group-item list-group-item-action"></i>Laporan Sales Member</a>
                    <a href="<?= $ip; ?>/member/salesperdep" class="list-group-item list-group-item-action"></i>Monitoring Sales PerHari</a>
                    <a href="<?= $ip; ?>/member/evaluasiperish" class="list-group-item list-group-item-action"></i>Evaluasi Sales Perishable</a>
                    <a href="<?= $ip; ?>/member/evaslspromo" class="list-group-item list-group-item-action"></i>Evaluasi Sales Promo</a>
                    <a href="<?= $ip; ?>/member/efaktur" class="list-group-item list-group-item-action"></i>Monitoring Efaktur</a>
                    <a href="<?= $ip; ?>/member/salesperjam" class="list-group-item list-group-item-action"></i>Monitoring Sales Per Jam</a>
                    <a href="<?= $ip; ?>/member/kunjunganmember" class="list-group-item list-group-item-action"></i>Monitoring Kunjungan Member</a>
                </div>
            </div>
            </div>
            <!-- Store -->
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed bg-dark border border-secondary text-light" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                    <i class="fa-solid fa-store me-2"></i>Store
                </button>
              </h2>
            <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="list-group">
                    <a href="<?= $ip; ?>/store/previewkasir" class="list-group-item list-group-item-action"></i>Preview Transaksi Kasir</a>
                    <a href="<?= $ip; ?>/store/kompetisikasir" class="list-group-item list-group-item-action"></i>Kompetisi Kasir</a>
                    <a href="<?= $ip; ?>/store/transaksiisaku" class="list-group-item list-group-item-action"></i>Data Transaksi iSaku</a>
                    <a href="<?= $ip; ?>/store/transaksimypoint" class="list-group-item list-group-item-action"></i>Data Transaksi MyPoin</a>
                    <a href="<?= $ip; ?>/store/transaksimitra" class="list-group-item list-group-item-action"></i>Data Transaksi Mitra</a>
                    <a href="<?= $ip; ?>/store/transaksiklik" class="list-group-item list-group-item-action"></i>Data Transaksi Klik</a>
                    <a href="<?= $ip; ?>/store/monitoringklik" class="list-group-item list-group-item-action"></i>Monitoring Transaksi Klik</a>
                    <a href="<?= $ip; ?>/store/slklik" class="list-group-item list-group-item-action"></i>Service Level Klik</a>
                    <a href="<?= $ip; ?>/store/transaksiproduk" class="list-group-item list-group-item-action"></i>History Transaksi Produk</a>
                    <a href="<?= $ip; ?>/storecekpromo" class="list-group-item list-group-item-action"></i>Cek Promo</a>
                    <a href="<?= $ip; ?>/store/monitoringpromo" class="list-group-item list-group-item-action"></i>Monitoring Promo</a>  
                    <a href="<?= $ip; ?>/store/diskonminus" class="list-group-item list-group-item-action"></i>Cek Diskon Minus</a>  
                    <a href="<?= $ip; ?>/store/marginminus" class="list-group-item list-group-item-action"></i>Cek Margin Minus</a>  
                    <a href="<?= $ip; ?>/store/promoperrak" class="list-group-item list-group-item-action"></i>Promo Per-Rak</a>  
                       
                </div>
            </div>
            </div>
            <!-- Inventory -->
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed bg-dark border border-secondary text-light" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                    <i class="fa-solid fa-warehouse me-2"></i>Inventory
                </button>
              </h2>
              <div id="flush-collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="list-group">
                    <a href="<?= $ip; ?>/logistik/informasiproduk" class="list-group-item list-group-item-action"></i>Informasi Produk</a>
                    <a href="<?= $ip; ?>/logistik/kkhpbm" class="list-group-item list-group-item-action"></i>Monitoring KKHPBM</a>
                    <a href="<?= $ip; ?>/logistik/lppsaatini" class="list-group-item list-group-item-action"></i>LPP Saat Ini</a>
                    <a href="<?= $ip; ?>/logistik/tampillppblnlalu" class="list-group-item list-group-item-action"></i>LPP Bulan Sebelumnya</a>
                    <a href="<?= $ip; ?>/logistik/lppvsplanodetail" class="list-group-item list-group-item-action"></i>LPP vs Plano Detail</a>
                    <a href="<?= $ip; ?>/logistik/lppvsplanorekap" class="list-group-item list-group-item-action"></i>LPP vs Plano Rekap</a>
                    <hr>
                    <a href="<?= $ip; ?>/logistik/backoffice" class="list-group-item list-group-item-action"></i>Laporan Back Office</a>
                    <hr>
                    <a href="<?= $ip; ?>/logistik/formsoharian" class="list-group-item list-group-item-action"></i>SO Harian</a>
                    <a href="<?= $ip; ?>/logistik/soic" class="list-group-item list-group-item-action"></i>SO IC</a>
                    <a href="<?= $ip; ?>/logistik/stockharian" class="list-group-item list-group-item-action"></i>Stock Harian</a>
                    <a href="<?= $ip; ?>/logistik/servicelevel" class="list-group-item list-group-item-action"></i>Service Level</a>
                    <a href="<?= $ip; ?>/logistik/servicelevelbo" class="list-group-item list-group-item-action"></i>Service Level 3 Periode</a>
                    <a href="<?= $ip; ?>/logistik/kertaskerja" class="list-group-item list-group-item-action"></i>Kertas Kerja Storage</a>
                    <a href="<?= $ip; ?>/logistik/livecks" class="list-group-item list-group-item-action"></i>Live CKS</a>
                    <a href="<?= $ip; ?>/logistik/pooutstanding" class="list-group-item list-group-item-action"></i>PO Oustanding</a>
                    <a href="<?= $ip; ?>/logistik/produkbaru" class="list-group-item list-group-item-action"></i>Produk Baru</a>
                    <a href="<?= $ip; ?>/logistik/ubahstatus" class="list-group-item list-group-item-action"></i>Perubahan Status</a>
                    <a href="<?= $ip; ?>/logistik/itemseasonal" class="list-group-item list-group-item-action"></i>Item Seasonal</a>
                    <a href="<?= $ip; ?>/logistik/pertemanan" class="list-group-item list-group-item-action"></i>Monitoring Pertemanan</a>
                    <a href="<?= $ip; ?>/logistik/kesegaran" class="list-group-item list-group-item-action"></i>Cek Kesegaran Produk</a>
                    
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed bg-dark border border-secondary text-light" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                    <i class="fa-solid fa-boxes-stacked me-2"></i>Laporan
                </button>
              </h2>
              <div id="flush-collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="list-group">
                    <a href="<?= $ip; ?>/laporan/nonhanox" class="list-group-item list-group-item-action"></i>NON HANOX</a>
                    <a href="<?= $ip; ?>/laporan/hanox" class="list-group-item list-group-item-action"></i>Tag HANOX</a>
                    <hr>
                    <a href="<?= $ip; ?>/laporan/masterlokasi" class="list-group-item list-group-item-action"></i>Master Lokasi</a>
                    <a href="<?= $ip; ?>/laporan/masterlokasi" class="list-group-item list-group-item-action"></i>Master Lokasi</a>
                    <a href="<?= $ip; ?>/laporan/storagenull" class="list-group-item list-group-item-action"></i>Storage Toko Null</a>
                    <hr>
                    <a href="<?= $ip; ?>/laporan/historyso" class="list-group-item list-group-item-action"></i>History SO</a>
                    <a href="<?= $ip; ?>/laporan/pobanding" class="list-group-item list-group-item-action"></i>PO Banding</a>
                    <a href="<?= $ip; ?>/laporan/cekmd" class="list-group-item list-group-item-action"></i>Cek Harga MD</a>

                </div>
              </div>
            </div>
            <!-- IDM.OMI -->
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed bg-dark border border-secondary text-light" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSix" aria-expanded="false" aria-controls="flush-collapseSix">
                  <i class="fa-solid fa-cart-shopping me-2"></i>OMI/IDM
                </button>
              </h2>
              <div id="flush-collapseSix" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="list-group">
                    <a href="<?= $ip; ?>idm/idmcluster" class="list-group-item list-group-item-action"></i>Daftar Cluster IDM</a>
                    <a href="<?= $ip; ?>/idm/rakdpd" class="list-group-item list-group-item-action"></i>Rak DPD</a>
                    <a href="<?= $ip; ?>/idm/fakturpajak" class="list-group-item list-group-item-action"></i>Monitoring Faktur Pajak</a>
                    <a href="<?= $ip; ?>/idm/fakturpajakout" class="list-group-item list-group-item-action"></i>Monitoring Faktur Pajak Outstanding</a>
                    <a href="<?= $ip; ?>/idm/outstandingretur" class="list-group-item list-group-item-action"></i>Outstanding Retur</a>
                    <hr>
                    <a href="<?= $ip; ?>/omi/monitoringpbomi" class="list-group-item list-group-item-action"></i>Monitoring PB OMI</a>
                    <a href="<?= $ip; ?>/omi/cekprosespbomi" class="list-group-item list-group-item-action"></i>Cek Proses PB OMI</a>
                    <a href="<?= $ip; ?>/omi/cekprosessph" class="list-group-item list-group-item-action"></i>Cek Proses SPH</a>
                    <a href="<?= $ip; ?>/omi/historybkl" class="list-group-item list-group-item-action"></i>Histroy BKL</a>
                    <a href="<?= $ip; ?>/omi/slomi" class="list-group-item list-group-item-action"></i>Service Level OMI</a>
                    <hr>
                    <a href="<?= $ip; ?>/idm/itemkosongklik" class="list-group-item list-group-item-action"></i>Item Kosong PB IDM</a>

                </div>
              </div>
            </div>
            <!-- EDP -->
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed bg-dark border border-secondary text-light" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive">
                    <i class="fa-solid fa-server me-2"></i>EDP
                </button>
              </h2>
              <div id="flush-collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="list-group">
                    <a href="<?= $ip; ?>" class="list-group-item list-group-item-action"></i>Cetak SSO</a>
                    <a href="<?= $ip; ?>" class="list-group-item list-group-item-action"></i>Item Fokus</a>
                    <a href="<?= $ip; ?>" class="list-group-item list-group-item-action"></i>Serah Terima Hadiah</a>
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed bg-dark border border-secondary text-light" type="button">
                    <a class="text-light" href="<?= $ip; ?>/mplano">
                        <i class="fa-solid fa-mobile me-2"></i>Plano Mobile IGR
                    </a>
                </button>
              </h2>
            </div>
        </div>
    
  </div>
</div>
