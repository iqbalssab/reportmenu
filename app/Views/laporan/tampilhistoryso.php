<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid mt-4">
<div class="row">
    <div class="col-sm-12">
        <h3 class="fw-bold">History SO</h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <?php if(!empty($history)): ?>
            <table class="table table-bordered border-dark">
                <thead>
                    <tr>
                        <th class="bg-primary-subtle text-dark">LOKASI</th>
                        <th class="bg-primary-subtle text-dark">DIV</th>
                        <th class="bg-primary-subtle text-dark">DEP</th>
                        <th class="bg-primary-subtle text-dark">KAT</th>
                        <th class="bg-primary-subtle text-dark">PLU</th>
                        <th class="bg-primary-subtle text-dark">DESK</th>
                        <th class="bg-primary-subtle text-dark">QTY LAMA</th>
                        <th class="bg-primary-subtle text-dark">QTY BARU</th>
                        <th class="bg-primary-subtle text-dark">CREATEBY</th>
                        <th class="bg-primary-subtle text-dark">TANGGAL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($history as $hs): ?>
                        <tr>
                            <td><?= $hs['LOKASI']; ?></td>
                            <td><?= $hs['DIV']; ?></td>
                            <td><?= $hs['DEP']; ?></td>
                            <td><?= $hs['KAT']; ?></td>
                            <td><?= $hs['PLU']; ?></td>
                            <td><?= $hs['DESK']; ?></td>
                            <td><?= $hs['QTY_LAMA']; ?></td>
                            <td><?= $hs['QTY_BARU']; ?></td>
                            <td><?= $hs['CREATEBY']; ?></td>
                            <td><?= $hs['TANGGAL']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
</div>

<?= $this->endSection(); ?>