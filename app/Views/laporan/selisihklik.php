<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<?php $now = date('Y-m-d'); ?>
<div class="container-fluid overflow-auto mt-4">
    <div class="row">
        <div class="col-md-2">
            <div class="card">
                <div class="card-header bg-primary">
                    <p class="fw-bold text-light">Selisih KLIK</p>
                </div>
                <div class="card-body">
                <form action="selisihklik" method="get">
                    <label for="tglawal" class="fw-bold d-block">Tgl Awal</label>
                    <input type="date" name="tglawal" id="tglawal" class="w-100 mb-2" value="<?= old('tglawal')?old('tglawal'):$now; ?>">
                    <label for="tglakhir" class="fw-bold d-block">Tgl Akhir</label>
                    <input type="date" name="tglakhir" id="tglakhir" class="w-100 mb-3" value="<?= old('tglawal')?old('tglawal'):$now; ?>">
                    <label for="plu" class="fw-bold d-block">PLU</label>
                    <input type="text" name="plu" id="plu" class="w-100 mb-2 input-group" placeholder="850,0060410">
                    <label for="notrans" class="fw-bold d-block">No. Transaksi</label>
                    <input type="text" name="notrans" id="notrans" class="w-100 mb-3 input-group" placeholder="850,0060410">
                    <button type="submit" name="btn" value="tampil" class="btn btn-outline-primary w-100 fw-bold">Tampil</button>
                </form>
                </div>
            </div>
        </div>
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary-subtle">
                    <p class="fw-bold text-primary">Selisih Klik/OBI tgl <?= $tglawal." s/d ".$tglakhir; ?></p>
                    <?php if(!empty($notrans)): ?>
                        <p>No Transaksi : <span class="text-dark fw-bold"><?= $notrans; ?></span></p>
                    <?php else: ?>
                    
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if(!empty($selisih)): ?>
                        <table class="table table-bordered border border-dark">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>PLU</th>
                                    <th>Deskripsi</th>
                                    <th>NoTrans</th>
                                    <th>Qty_Order</th>
                                    <th>Qty_Real</th>
                                    <th>Selisih</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no =1; ?>
                                <?php foreach($selisih as $s): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $s['TGL']; ?></td>
                                        <td><?= $s['PLU']; ?></td>
                                        <td><?= $s['DESK']; ?></td>
                                        <td><?= $s['NOTRANS']; ?></td>
                                        <td><?= $s['QTYORDER']; ?></td>
                                        <td><?= $s['QTYREAL']; ?></td>
                                        <td><?= $s['SELISIH']; ?></td>
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