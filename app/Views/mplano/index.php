<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<?php $ip = "http://".$_SERVER['SERVER_NAME']."/reportmenu"; ?>
<?php $now = date('d-M-Y h:i'); ?>
<div class="container-fluid mt-3">
    <div id="dashboard" class="row">
        <div class="col-sm-12">
            <h2 class="text-center fw-bold">Plano Mobile IGR Purwokerto</h2>
            <p class="text-center">Tanggal : <?= $now; ?></p>
        </div>
    </div>
    <div id="konten" class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header bg-primary">
                    <h4 class="text-light text-center fw-bold">SPB Belum Diturunkan</h4>
                </div>
                <div class="card-body">
                    <?php foreach($blmturun as $bt): ?>
                        <a href="spbo?spb=TOKO" class="btn btn-light btn-lg text-center w-100">SPB TOKO<span class="badge text-bg-secondary ms-2 rounded-pill" style="font-size: small;"><?= $bt['TOKOBLMTURUN']; ?></span>
                        </a>
                        <a href="spbo?spb=GUDANG" class="btn btn-light btn-lg text-center w-100">SPB GUDANG<span class="badge text-bg-secondary ms-2 rounded-pill" style="font-size: small;"><?= $bt['GUDANGBLMTURUN']; ?></span>
                        </a>
                        <?php endforeach; ?>
                        <?php foreach($spbmanual as $sm): ?>
                            <a href="spbm" class="btn btn-light btn-lg text-center w-100">SPB GUDANG<span class="badge text-bg-secondary ms-2 rounded-pill" style="font-size: small;"><?= $sm['MANUALBLMTURUN']; ?></span>
                            </a>
                        <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
        <div class="card">
                <div class="card-header bg-primary">
                    <h4 class="text-light text-center fw-bold">SPB Belum Diturunkan</h4>
                </div>
                <div class="card-body">
                    <?php if(!empty($blmreal)): ?>
                        <?php foreach($blmreal as $br): ?>
                        <a href="spbo3?spb=TOKO" class="btn btn-light btn-lg text-center w-100">SPB TOKO<span class="badge text-bg-secondary ms-2 rounded-pill" style="font-size: small;"><?= $br['TOKOBLMREAL']; ?></span>
                        </a>
                        <a href="spbo3?spb=GUDANG" class="btn btn-light btn-lg text-center w-100">SPB GUDANG<span class="badge text-bg-secondary ms-2 rounded-pill" style="font-size: small;"><?= $br['GUDANGBLMREAL']; ?></span>
                        </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <a href="spbo3?spb=TOKO" class="btn btn-light btn-lg text-center w-100">SPB TOKO<span class="badge text-bg-secondary ms-2 rounded-pill" style="font-size: small;">0</span>
                        </a>
                        <a href="spbo3?spb=GUDANG" class="btn btn-light btn-lg text-center w-100">SPB GUDANG<span class="badge text-bg-secondary ms-2 rounded-pill" style="font-size: small;">0</span>
                        </a>
                    <?php endif; ?>
                        <?php foreach($spbmanual as $sm): ?>
                            <a href="spbm" class="btn btn-light btn-lg text-center w-100">SPB GUDANG<span class="badge text-bg-secondary ms-2 rounded-pill" style="font-size: small;"><?= $sm['MANUALBLMREAL']; ?></span>
                            </a>
                        <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-sm-12 d-flex justify-content-center">
        <a class="btn btn-success" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
            Menu Monitoring Plano
        </a>
        </div>
    </div>
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Plano Mobile IGR</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div>
            Create by MIQ-IGRPWT 2023
            © to MHT-IGRBGR
            </div>
            <ul class="list-group mt-2">
                <a href="<?= $ip; ?>/mplano" class="list-group-item active"><i class="fa-solid fa-house me-1"></i>Home Plano IGR</a>
                <a href="<?= $ip; ?>/mplano/spbo?spb=ALL" class="list-group-item"><i class="fa-solid fa-arrows-down-to-line me-1"></i>SPB Blm Diturunkan</a>
                <a href="<?= $ip; ?>/mplano/spbo3?spb=ALL" class="list-group-item"><i class="fa-solid fa-arrows-up-to-line me-1"></i>SPB Blm Direalisasi</a>
                <a href="<?= $ip; ?>/mplano/spbm" class="list-group-item"><i class="fa-solid fa-mobile-retro me-1"></i>SPB Manual</a>
                <a href="<?= $ip; ?>/mplano/slp" class="list-group-item"><i class="fa-solid fa-barcode me-1"></i>SLP</a>
            </ul>
        </div>
        </div>
</div>

<?= $this->endSection(); ?>