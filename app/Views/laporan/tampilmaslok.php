<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-12">
            <h3 class="fw-bold text-primary">Master Lokasi Rak : <?= $rak; ?></h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="bg-info text-dark">No</th>
                        <th class="bg-info text-dark">Rak</th>
                        <th class="bg-info text-dark">Subr</th>
                        <th class="bg-info text-dark">Tipe</th>
                        <th class="bg-info text-dark">Shelv</th>
                        <th class="bg-info text-dark">No</th>
                        <th class="bg-info text-dark">Jenis</th>
                        <th class="bg-info text-dark">PLU</th>
                        <th class="bg-info text-dark">Deskripsi</th>
                        <th class="bg-info text-dark">TAG</th>
                        <th class="bg-info text-dark">Frac</th>
                        <th class="bg-info text-dark">Qty</th>
                        <th class="bg-info text-dark">Maxpla</th>
                        <th class="bg-info text-dark">Maxdis</th>
                        <th class="bg-info text-dark">Expdate</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($perrak)): ?>
                        <?php $no = 1; ?>
                        <?php foreach($perrak as $pr): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $pr['LKS_KODERAK']; ?></td>
                                <td><?= $pr['LKS_KODESUBRAK']; ?></td>
                                <td><?= $pr['LKS_TIPERAK']; ?></td>
                                <td><?= $pr['LKS_SHELVINGRAK']; ?></td>
                                <td><?= $pr['LKS_NOURUT']; ?></td>
                                <td><?= $pr['LKS_JENISRAK']; ?></td>
                                <td><?= $pr['LKS_PRDCD']; ?></td>
                                <td><?= $pr['PRD_DESKRIPSIPANJANG']; ?></td>
                                <td><?= $pr['PRD_KODETAG']; ?></td>
                                <td><?= $pr['FRAC']; ?></td>
                                <td><?= number_format($pr['LKS_QTY']); ?></td>
                                <td><?= number_format($pr['LKS_MAXPLANO']); ?></td>
                                <td><?= number_format($pr['LKS_MAXDISPLAY']); ?></td>
                                <td><?= $pr['LKS_EXPDATE']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>