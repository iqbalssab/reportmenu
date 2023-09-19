<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<?php $ip = "http://".$_SERVER['SERVER_NAME']."/reportmenu"; ?>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-sm-12">
            <div class="alert-warning alert fw-bolder fs-5 d-flex justify-content-between">
            <?php 
			if(!empty($_GET['lksasal'])) {
				$lksasal = $_GET['lksasal'];
				echo "SPB ". $filtertujuanspb ." BELUM DIREALISASI - LOKASI ASAL : $lksasal";
			}elseif(!empty($_GET['lkstujuan'])) {
				$lkstujuan = $_GET['lkstujuan'];
				echo "SPB ". $filtertujuanspb. " BELUM DIREALISASI - LOKASI TUJUAN : $lkstujuan";
			}elseif($tujuanspb != "ALL") {
				echo "SPB ". $filtertujuanspb ." BELUM DIREALISASI";
			}else{
				echo "SPB BELUM DIREALISASI";
			}
		?>
            <a class="btn btn-outline-warning" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
            Menu
            </a>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- sidebar kiri -->
        <div class="col-sm-2">
            <div class="card mb-2">
                <div class="card-header bg-primary">
                    <h6 class="text-light"><i class="fa-solid fa-boxes-stacked me-1"></i>SPB</h6>
                </div>
                <div class="card-body">
                <ul class="list-group">
                    <?php $totalspb = 0; 
                        foreach($tujuanspb as $ts):
                    ?>
                    <a href="spbo3?spb=<?= $ts['TUJUANSPB']; ?>" class="list-group-item" aria-current="true"><?= $ts['TUJUANSPB']; ?><span class="badge text-bg-secondary ms-2" style="font-size: small;"><?= $ts['JMLSPB']; ?></span></a>
                    <?php $totalspb += $ts['JMLSPB']; ?>
                    <?php endforeach; ?>
                    <a href="spbo3?spb=ALL" class="list-group-item">ALL<span class="badge text-bg-secondary ms-2" style="font-size: small;"><?= $totalspb; ?></span></a>
                </ul>
                </div>
            </div>
            <!-- Lokasi ASAL -->
            <div class="card mb-2">
                <div class="card-header bg-success">
                    <h6 class="text-light"><i class="fa-solid fa-right-from-bracket me-1 fw-bold"></i>LKS ASAL</h6>
                </div>
                <div class="card-body">
                    <?php if(!empty($lokasiasal)): ?>
                        <ul class="list-group">
                            <?php foreach($lokasiasal as $la): ?>
                                <a href="spbo?spb=<?= $filtertujuanspb; ?>&lksasal=<?= $la['LOKASIASAL']; ?>" class="list-group-item mb-1" aria-current="true">
                                    <?= $la['LOKASIASAL']; ?> <span class="badge text-bg-secondary ms-2"><?= $la['JMLSPB']; ?></span>
                                </a>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Lokasi TUJUAN -->
            <div class="card mb-2">
                <div class="card-header bg-danger">
                    <h6 class="text-light"><i class="fa-solid fa-right-to-bracket me-1 fw-bold"></i>LKS TUJUAN</h6>
                </div>
                <div class="card-body">
                    <?php if(!empty($lokasitujuan)): ?>
                <ul class="list-group">
                    <?php foreach($lokasitujuan as $lt): ?>
                    <a href="spbo?spb=<?= $filtertujuanspb; ?>&lkstujuan=<?= $lt['LOKASITUJUAN']; ?>" class="list-group-item"><?= $lt['LOKASITUJUAN']; ?>
                    <span class="badge text-bg-secondary ms-2"><?= $lt['JMLSPB']; ?></span>
                    </a>
                    <?php endforeach; ?>
                </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-sm-10">
            <div class="table-responsive">
                <table class="table table-responsive" style="font-size:18px;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Lks Asal</th>
                            <th>Lks Tujuan</th>
                            <th>Deskripsi</th>
                            <th>CTN</th>
                            <th>PCS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; 
                            foreach($spb as $s):
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td>
                                    <?= $s['SPB_LOKASIASAL']; ?>
                                    <br>
                                    <span class="badge text-bg-danger"><i class="fa-solid fa-arrows-down-to-line me-1"></i><?= $s['STATUS']; ?></span>
                                </td>
                                <td>
                                    <?= $s['SPB_LOKASITUJUAN']; ?>
                                    <br>
                                    <span class="badge text-bg-secondary"><i class="fa-regular fa-clock me-1"></i><?= $s['WAKTUSPB']; ?></span>
                                </td>
                                <td>
                                    <?= $s['SPB_DESKRIPSI']; ?>
                                    <br>
                                    <p class="text-secondary">PLU : <?= $s['SPB_PRDCD']." - ".$s['PRD_UNIT']."/".$s['PRD_FRAC']; ?></p>
                                </td>
                                <td><?= $s['MINUSCTN']; ?></td>
                                <td><?= $s['MINUSPCS']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title fw-bold" id="offcanvasExampleLabel">Plano Mobile IGR</h5>
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