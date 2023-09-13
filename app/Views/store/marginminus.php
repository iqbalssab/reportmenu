<?= $this->extend('layout/template'); ?>
<?=$this->section('content'); ?>

<?php $tglnow = date('d-m-Y'); ?>

<div class="container">
    <div class="card mt-3">
        <div class="card-header bg-danger-subtle">
            <h5 class="text-danger text-center fw-bolder">Margin Minus tanggal <?= $tglnow; ?></h5>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Div</th>
                        <th>Dep</th>
                        <th>Kat</th>
                        <th>PLU</th>
                        <th>Deskripsi</th>
                        <th>Tag</th>
                        <th>Frac</th>
                        <th>Unit</th>
                        <th>Stok</th>
                        <th>Acost</th>
                        <th>BKP1</th>
                        <th>BKP2</th>
                        <th>HrgNormal</th>
                        <th>HrgPromo</th>
                        <th>Margin</th>
                        <th>Mrg%</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($marginminus as $marmin): ?>
                    <tr>
                        <td><?= $marmin['DIV']; ?></td>
                        <td><?= $marmin['DEP']; ?></td>
                        <td><?= $marmin['KAT']; ?></td>
                        <td><?= $marmin['PLU']; ?></td>
                        <td><?= $marmin['DESKRIPSI']; ?></td>
                        <td><?= $marmin['TAG']; ?></td>
                        <td><?= $marmin['FRAC']; ?></td>
                        <td><?= $marmin['UNIT']; ?></td>
                        <td><?= number_format($marmin['STOK']); ?></td>
                        <td><?= number_format($marmin['ACOST']); ?></td>
                        <td><?= $marmin['FLAGBKP1']; ?></td>
                        <td><?= $marmin['FLAGBKP2']; ?></td>
                        <td><?= number_format($marmin['HRG_NORMAL']); ?></td>
                        <td><?= number_format($marmin['HRG_PROMO']); ?></td>
                        <td><?= number_format($marmin['MARGIN']); ?></td>
                        <td><?= number_format($marmin['MRG']); ?>%</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>