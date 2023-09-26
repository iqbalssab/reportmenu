<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<?php $now = date('Y-m-d'); ?>
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-2">
            <div class="card">
                <div class="card-header bg-primary">
                    <p class="text-light fw-bold fs-5">Item Kosong</p>
                </div>
                <div class="card-body">
                    <form action="itemkosong" method="post">
                        <p class="fw-bold text-dark">Periode Transaksi</p>
                        <label for="tglawal" class="d-block">Tanggal Awal</label>
                        <input type="date" name="tglawal" id="tglawal" class="w-100 mb-2" value="<?= old('tglawal')?old('tglawal') : $now; ?>">
                        <label for="tglakhir" class="d-block">Tanggal Akhir</label>
                        <input type="date" name="tglakhir" id="tglakhir" class="w-100 mb-3" value="<?= old('tglakhir')?old('tglakhir') : $now; ?>">
                        <label for="plu" class="d-block fw-bold">PLU</label>
                        <input type="text" name="plu" id="plu" class="w-100 mb-3">
                        <button type="submit" name="btn" value="tampil" class="btn btn-outline-primary w-100 fw-bold">Tampil</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-10">
        <div class="card">
                <div class="card-header bg-primary-subtle">
                    <p class="text-primary fw-bold">Item Kosong KLIKIGR</p>
                </div>
                <div class="card-body">
                    <?php if(!empty($itemkosong)): ?>
                        <table class="table table-bordered border border-dark">
                            <thead>
                                <tr>
                                    <th class="bg-primary-subtle">No</th>
                                    <th class="bg-primary-subtle">NoTrans</th>
                                    <th class="bg-primary-subtle">TglTrans</th>
                                    <th class="bg-primary-subtle">PLU</th>
                                    <th class="bg-primary-subtle">Deskripsi</th>
                                    <th class="bg-primary-subtle">Qty_Order</th>
                                    <th class="bg-primary-subtle">LPP</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                            <?php foreach($itemkosong as $is): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $is['NOTRANS']; ?></td>
                                    <td><?= $is['TGLTRANS']; ?></td>
                                    <td><?= $is['PLU']; ?></td>
                                    <td><?= $is['DESK']; ?></td>
                                    <td><?= $is['QTYORDER']; ?></td>
                                    <td><?= $is['LPP']; ?></td>
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