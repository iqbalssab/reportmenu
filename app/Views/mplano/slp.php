<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<?php $ip = "http://".$_SERVER['SERVER_NAME']."/reportmenu"; ?>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-success fw-bold fs-4 d-flex justify-content-between">
            <?php 
			if(isset($_GET['lksasal'])) {
				$lksasal = $_GET['lksasal'];
				echo "SLP BELUM DIREALISASI - LOKASI ASAL : $lksasal";
			}elseif(isset($_GET['lkstujuan'])) {
				$lkstujuan = $_GET['lkstujuan'];
				echo "SLP BELUM DIREALISASI - LOKASI TUJUAN : $lkstujuan";
			}else{
				echo "SLP BELUM DIREALISASI";
			}
		    ?>
            <a class="btn btn-outline-success" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
                Menu
            </a>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- sidebar kiri -->
        <div class="col-sm-2">
            <div class="card">
                <div class="card-header bg-primary">
                <h6 class="text-light"><i class="fa-solid fa-boxes-stacked me-1"></i>LOKASI</h6>
                </div>
                <div class="card-body">
                <ul class="list-group">
                    <a href="slp" class="list-group-item">SEMUA LOKASI</a>
                    <?php if(!empty($lokasitujuan)): ?>
                        <?php foreach($lokasitujuan as $lt): ?>
                            <a href="slp?lkstujuan=<?= $lt['SLP_KODERAK']; ?>" class="list-group-item">
                                <?= $lt['SLP_KODERAK']; ?>
                                <span class="badge text-bg-secondary"><?= $lt['JMLSLP']; ?></span>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-10">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>LOKASI</th>
                            <th>PLU - DESKRIPSI</th>
                            <th>CTN</th>
                            <th>PCS</th>
                            <th>SLP_ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; 
                            foreach($slp as $s):
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td>
                                <button type="button" class="btn btn-light position-relative">
                                    <?= $s['LOKASI']; ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?= $s['TIPE']; ?></span>
                                </button>
                            </td>
                            <td>
                                <?= $s['DESKRIPSI']; ?>
                                <br>
                                <p class="text-secondary">PLU : <?= $s['PLU']." - ".$s['UNIT']."/".$s['FRAC']; ?></p>
                            </td>
                            <td><?= $s['QTYCRT']; ?></td>
                            <td><?= $s['QTYPCS']; ?></td>
                            <td>
                                <span class="badge text-bg-primary d-block w-100 mb-1"><?= $s['CREATEDT']; ?></span>
                                <span class="badge text-bg-success d-block w-100"><?= $s['SLP_ID']; ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
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
                <a href="<?= $ip; ?>/mplano/historyslp" class="list-group-item"><i class="fa-solid fa-history me-1"></i>History SLP</a>
            </ul>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>