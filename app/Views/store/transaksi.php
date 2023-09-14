<?php $this->extend('layout/template'); ?>
<?php $this->section('content'); ?>

<div class="container-fluid mt-3">
    <table class="table table-responsive table-hover table-bordered border-dark">
        <thead>
            <tr class="border border-dark">
                <th class="bg-success-subtle">Tanggal</th>
                <th class="bg-success-subtle">No Struk</th>
                <th class="bg-success-subtle">PLU</th>
                <th class="bg-success-subtle">Deskripsi</th>
                <th class="bg-success-subtle">Frac</th>
                <th class="bg-success-subtle">Qty</th>
                <th class="bg-success-subtle">UnitPrice</th>
                <th class="bg-success-subtle">NominalAmt</th>
                <th class="bg-success-subtle">BasePrice</th>
                <th class="bg-success-subtle">S/R</th>
                <th class="bg-success-subtle">KD_Member</th>
                <th class="bg-success-subtle">Nama Member</th>
                <th class="bg-success-subtle">MM</th>
                <th class="bg-success-subtle">Outlet</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($transaksi as $tr): ?>
            <tr>
                <td><?= $tr['TRJD_TRANSACTIONDATE']; ?></td>
                <td><?= $tr['TRJD_CREATE_BY']; ?>.<?= $tr['TRJD_CASHIERSTATION']; ?>.<?= $tr['TRJD_TRANSACTIONNO']; ?></td>
                <td><?= $tr['TRJD_PRDCD']; ?></td>
                <td><?= $tr['TRJD_PRD_DESKRIPSIPENDEK']; ?></td>
                <td><?= $tr['PRD_FRAC']; ?></td>
                <td><?= number_format($tr['TRJD_QUANTITY']); ?></td>
                <td><?= number_format($tr['TRJD_UNITPRICE']); ?></td>
                <td><?= number_format($tr['TRJD_NOMINALAMT']); ?></td>
                <td><?= number_format($tr['TRJD_BASEPRICE']); ?></td>
                <td><?= $tr['TRJD_TRANSACTIONTYPE']; ?></td>
                <td><?= $tr['TRJD_CUS_KODEMEMBER']; ?></td>
                <td><?= $tr['CUS_NAMAMEMBER']; ?></td>
                <td><?= $tr['CUS_FLAGMEMBERKHUSUS']; ?></td>
                <td><?= $tr['CUS_KODEOUTLET']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php $this->endSection(); ?>