<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<?php $now = date('Y-m-d'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="card mt-3">
                <div class="card-header bg-primary">
                    <h6 class="text-center fw-bold text-light">Transaksi MyPoint</h6>
                    <p class="fw-lighter text-center" style="color: yellow;">**Menggunakan Data H-1**</p>
                </div>
                <div class="card-body">
                    <form action="/store/transaksimypoint">
                    <?php csrf_field(); ?>
                    <label for="tglawal">TGL TRANSAKSI</label>
                    <input type="date" class="mb-4 w-100" name="tglawal" id="tglawal" value="<?= $now; ?>">

                    <button class="btn btn-success w-100 mb-2" type="submit" name="btn" value="perolehan">Perolehan MyPoin</button>
                    <button class="btn btn-danger w-100" type="submit" name="btn" value="penukaran">Penukaran MyPoin</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card mt-3">
                <div class="card-header bg-primary-subtle">
                    <h5 class="text-primary"><?= $judul; ?></h5>
                </div>
            <div class="card-body">
                <?php if(!empty($perolehan)): ?>
                <table class="table table-hover table-bordered table-responsive">
                    <thead>
                        <tr>
                            <th>TRXDATE</th>
                            <th>KODETRANSAKSI</th>
                            <th>KODE MEMBER</th>
                            <th>PEROLEHAN MYPOIN</th>
                            <th>DESKRIPSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $totalpoint = 0; ?>
                        <?php foreach($perolehan as $oleh): ?>
                        <tr>
                            <td><?= $oleh['TRXDATE']; ?></td>
                            <td><?= $oleh['POR_KODETRANSAKSI']; ?></td>
                            <td><?= $oleh['POR_KODEMEMBER']; ?></td>
                            <td><?= $oleh['POR_PEROLEHANPOINT']; ?></td>
                            <td><?= $oleh['POR_DESKRIPSI']; ?></td>
                        </tr>
                        <?php $totalpoint += $oleh['POR_PEROLEHANPOINT']; ?>
                        <?php endforeach; ?>
                        <tr>
                            <td><b>Total Nilai</b></td>
                            <td></td>
                            <td></td>
                            <td>Rp. <?= number_format($totalpoint); ?></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <?php elseif(!empty($penukaran)): ?>
                    <table class="table table-bordered table-hover table-responsive">
                        <thead>
                            <tr>
                                <th>TRXDATE</th>
                                <th>KODETRANSAKSI</th>
                                <th>KODEMEMBER</th>
                                <th>POIN PENUKARAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $totalpoint = 0; ?>
                            <?php foreach($penukaran as $tukar): ?>
                            <tr>
                                <td><?= $tukar['TRXDATE']; ?></td>
                                <td><?= $tukar['POT_KODETRANSAKSI']; ?></td>
                                <td><?= $tukar['POT_KODEMEMBER']; ?></td>
                                <td><?= number_format($tukar['POT_PENUKARANPOINT']); ?></td>
                            </tr>
                            <?php $totalpoint += $tukar['POT_PENUKARANPOINT']; ?>
                            <?php endforeach; ?>
                            <tr>
                                <td><b>Total Nilai</b></td>
                                <td></td>
                                <td></td>
                                <td>Rp. <?= number_format($totalpoint); ?></td>
                            </tr>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>