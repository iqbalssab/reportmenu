<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container mt-3">
    <div class="row">
        <div class="col">
            <h4 class="text-center fw-bold text-primary">CEK MD</h4>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="bg-primary text-light">No</th>
                        <th class="bg-primary text-light">PLU</th>
                        <th class="bg-primary text-light">DESKRIPSI</th>
                        <th class="bg-primary text-light">TGL AWAL</th>
                        <th class="bg-primary text-light">TGL AKHIR</th>
                        <th class="bg-primary text-light">HRG LAMA</th>
                        <th class="bg-primary text-light">HRG BARU</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php if(!empty($cekmd)): ?>
                        <?php foreach($cekmd as $md): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $md['PRMD_PRDCD']; ?></td>
                            <td><?= $md['PRD_DESKRIPSIPANJANG']; ?></td>
                            <td><?= $md['PRMD_TGLAWALBARU']; ?></td>
                            <td><?= $md['PRMD_TGLAKHIRBARU']; ?></td>
                            <td class="text-end"><?= number_format($md['HRG_LAMA']); ?></td>
                            <td class="text-end"><?= number_format($md['HRG_BARU']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-danger text-center">Data Tidak Ada!!</p>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>