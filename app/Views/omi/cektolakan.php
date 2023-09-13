<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<?php $now = date('Y-m-d'); ?>
<div class="container mt-3">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary">
                    <p class="text-light fw-bold">CEK TOLAKAN</p>
                </div>
                <div class="card-body">                
                    <form action="cektolakan" method="get">
                        <label for="tgl" class="d-block fw-bold">TGL PROSES :</label>
                        <input type="date" name="tgl" id="tgl" class="w-100 mb-3" value="<?= old('tgl') ? old('tgl') : $now; ?>">
                        <label for="plu" class="d-block fw-bold">PLU :</label>
                        <input type="text" name="plu" id="plu" class="w-100 mb-3" value="<?= old('plu'); ?>">
                        <button type="submit" class="btn btn-success text-light w-100 mb-2"><i class="fa-solid fa-magnifying-glass-arrow-right me-1"></i>Cari</button>
                    </form>
                    <a href="cekprosespbomi" class="btn btn-dark text-light text-center"><i class="fa-solid fa-arrow-left"></i> Back</a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-primary-subtle">
                    <p class="fw-bold text-primary">TOLAKAN PBOMI - Tanggal <?= $tgl; ?></p>
                </div>
                <div class="card-body">
                    <?php if(!empty($tolakan)): ?>
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th class="bg-success text-light text-center">No</th>
                                    <th class="bg-success text-light text-center">PLU</th>
                                    <th class="bg-success text-light text-center">Deskripsi</th>
                                    <th class="bg-success text-light text-center">KodeOMI</th>
                                    <th class="bg-success text-light text-center">NoPB</th>
                                    <th class="bg-success text-light text-center">QtyOrder</th>
                                    <th class="bg-success text-light text-center">QtyEkonomis</th>
                                    <th class="bg-success text-light text-center">QtyLPP</th>
                                    <th class="bg-success text-light text-center">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach($tolakan as $t): ?>
                                    <tr>
                                        <td class="text-justify"><?= $no++; ?></td>
                                        <td class="text-justify"><?= $t['TLKO_PLUIGR']; ?></td>
                                        <td class="text-justify"><?= $t['TLKO_DESC']; ?></td>
                                        <td class="text-justify"><?= $t['TLKO_KODEOMI']; ?></td>
                                        <td class="text-justify"><?= $t['TLKO_NOPB']; ?></td>
                                        <td class="text-justify"><?= $t['TLKO_QTYORDER']; ?></td>
                                        <td class="text-justify"><?= $t['TLKO_QTYEKONOMIS']; ?></td>
                                        <td class="text-justify"><?= $t['TLKO_LPP']; ?></td>
                                        <td class="text-justify"><?= $t['TLKO_KETTOLAKAN']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>