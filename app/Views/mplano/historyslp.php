<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<?php $now = date('Y-m-d'); ?>
<?php $ip = "http://".$_SERVER['SERVER_NAME']."/reportmenu"; ?>
<style>
    th{
        font-size: 14px;
    }
    td{
        font: size 12px;
    }
</style>
<div class="container-fluid overflow-auto mt-3">
    <div class="row">
        <div class="col-sm-12">
        <div class="alert alert-primary fw-bold fs-4 d-flex justify-content-between">
            HISTORY SLP || periode  <?= $tglawal." s/d ".$tglakhir; ?>
            <a class="btn btn-outline-success" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
                Menu
        </a>
        </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-2">
            <div class="card">
                <div class="card-header bg-primary">
                    <p class="text-light fw-bold text-center fs-5">History SLP</p>
                </div>
                <div class="card-body">
                    <form action="historyslp" method="get">
                        <p class="fw-bold d-block">Periode</p>
                        <label for="tglawal" class="d-block">Tanggal Awal</label>
                        <input type="date" name="tglawal" id="tglawal" class="w-100 mb-3" value="<?= old('tglawal') ? old('tglawal') : $now; ?>" >
                        <label for="tglakhir" class="d-block">Tanggal Akhir</label>
                        <input type="date" name="tglakhir" id="tglakhir" class="w-100 mb-3" value="<?= old('tglakhir') ? old('tglakhir') : $now; ?>" >
                        <button type="submit" name="btn" value="tampil" class="btn btn-primary w-100"><i class="fa-solid fa-eye me-1"></i>Tampil</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-10">
            <?php if(!empty($history)): ?>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>SLPID</th>
                        <th>FLAG</th>
                        <th>KODERAK</th>
                        <th>SUB RAK</th>
                        <th>TIPE RAK</th>
                        <th>SHELVING</th>
                        <th>NO URUT</th>
                        <th>PLU</th>
                        <th>FRAC</th>
                        <th>DESKRIPSI</th>
                        <th>QTY CTN</th>
                        <th>QTY PCS</th>
                        <th>UNIT</th>
                        <th>EXPDATE</th>
                        <th>TGLBUAT</th>
                        <th>USER</th>
                        <th>USERUBAH</th>
                        <th>SLPTYPE</th>
                        <th>SLPJENIS</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach($history as $hs): ?>
                            <tr>
                                <td><?= $hs['SLP_ID']; ?></td>
                                <td><?= $hs['SLP_FLAG']; ?></td>
                                <td><?= $hs['SLP_KODERAK']; ?></td>
                                <td><?= $hs['SLP_KODESUBRAK']; ?></td>
                                <td><?= $hs['SLP_TIPERAK']; ?></td>
                                <td><?= $hs['SLP_SHELVINGRAK']; ?></td>
                                <td><?= $hs['SLP_NOURUT']; ?></td>
                                <td><?= $hs['SLP_PRDCD']; ?></td>
                                <td><?= $hs['SLP_FRAC']; ?></td>
                                <td><?= $hs['SLP_DESKRIPSI']; ?></td>
                                <td><?= $hs['SLP_QTYCRT']; ?></td>
                                <td><?= $hs['SLP_QTYPCS']; ?></td>
                                <td><?= $hs['SLP_UNIT']; ?></td>
                                <td><?= $hs['SLP_EXPDATE']; ?></td>
                                <td><?= $hs['SLP_CREATE_DT']; ?></td>
                                <td><?= $hs['SLP_CREATE_BY']; ?></td>
                                <td><?= $hs['SLP_MODIFY_BY']; ?></td>
                                <td><?= $hs['SLP_TIPE']; ?></td>
                                <td><?= $hs['SLP_JENIS']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Plano Mobile IGR</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="text-secondary">
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

<?= $this->endSection(); ?>