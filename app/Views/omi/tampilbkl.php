<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container mt-3">
    <h5>REKAP BKL OMI Tanggal : <?= $tglawal." s/d ".$tglakhir; ?> // OMI : <?= $omi ? $omi : "ALL"; ?></h5>
    <table class="table table-bordered table-hover text-sm">
        <thead>
            <tr>
                <th class="bg-primary text-light text-center">NO</th>
                <th class="bg-primary text-light text-center">OMI</th>
                <th class="bg-primary text-center text-light">IDFILE</th>
                <th class="bg-primary text-center text-light">NOBUKTI</th>
                <th class="bg-primary text-center text-light">TGLBUKTI</th>
                <th class="bg-primary text-center text-light">NODOC</th>
                <th class="bg-primary text-center text-light">TGLDOC</th>
                <th class="bg-primary text-center text-light">NOFAKTUR</th>
                <th class="bg-primary text-center text-light">TGLFAKTUR</th>
                <th class="bg-primary text-center text-light">NILAI_RPH</th>
                <th class="bg-primary text-center text-light">SUPPLIER</th>
                <th class="bg-primary text-center text-light">BY</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($rekapbkl)): ?>
                <?php $no = 1; ?>
                <?php foreach($rekapbkl as $rb): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $rb['BKL_KODEOMI']; ?></td>
                        <td><?= $rb['BKL_IDFILE']; ?></td>
                        <td><?= $rb['BKL_NOBUKTI']; ?></td>
                        <td><?= $rb['BKL_TGLBUKTI']; ?></td>
                        <td><?= $rb['MSTH_NODOC']; ?></td>
                        <td><?= $rb['MSTH_TGLDOC']; ?></td>
                        <td><?= $rb['MSTH_NOFAKTUR']; ?></td>
                        <td><?= $rb['MSTH_TGLFAKTUR']; ?></td>
                        <td><?= number_format($rb['NILAI']); ?></td>
                        <td><?= $rb['SUP_NAMASUPPLIER']; ?></td>
                        <td><?= $rb['BKL_CREATE_BY']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection(); ?>