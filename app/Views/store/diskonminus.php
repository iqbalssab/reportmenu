<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<?php $tglnow = date('d-m-y'); ?>
<div class="container">
    <div class="row">
        <div class="col-md">
            <div class="card mt-4">
                <div class="card-header bg-danger-subtle">
                    <h5 class="text-danger text-center fw-bolder">DISKON MINUS TGL <?= $tglnow; ?></h5>
                </div>
                <div class="card-body mt-2">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>PLU</th>
                                <th>DESKRIPSI</th>
                                <th>TAG</th>
                                <th>FRAC</th>
                                <th>UNIT</th>
                                <th>STOK</th>
                                <th>HRG NORMAL</th>
                                <th>HRG PROMO</th>
                                <th>DISKON</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($diskonminus as $dm): ?>
                            <tr>
                                <td><?= $dm['PLU']; ?></td>
                                <td><?= $dm['DESKRIPSI']; ?></td>
                                <td><?= $dm['TAG']; ?></td>
                                <td><?= $dm['FRAC']; ?></td>
                                <td><?= $dm['UNIT']; ?></td>
                                <td><?= $dm['STOK']; ?></td>
                                <td><?= $dm['HRG_NORMAL']; ?></td>
                                <td><?= $dm['HRG_PROMO']; ?></td>
                                <td><?= $dm['DISKON']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>