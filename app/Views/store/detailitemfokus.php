<?php $this->extend('layout/template'); ?>
<?php $this->section('content'); ?>

<div class="container mt-3">
    <h3 class="">DETAIL TRANSAKSI ITEM FOKUS KASIR : <?= $idkasir."/".$namakasir; ?></h3>
    <table class="table table-bordered table-responsive">
        <thead>
            <tr>
                <th>NO</th>
                <th>NO.STRUK</th>
                <th>TANGGAL</th>
                <th>TYPE</th>
                <th>PLU</th>
                <th>DESKRIPSI</th>
                <th>QTY</th>
                <th>RUPIAH</th>
                <th>KODE MEMBER</th>
                <th>Flag MM</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; ?>
            <?php foreach($jualdetail as $jd): ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= $jd['TRJD_CREATE_BY'].".".$jd['TRJD_CASHIERSTATION'].".".$jd['TRJD_TRANSACTIONNO']; ?></td>
                <td><?= $jd['TRJD_TRANSACTIONDATE']; ?></td>
                <td><?= $jd['TRJD_TRANSACTIONTYPE']; ?></td>
                <td><?= $jd['TRJD_PRDCD']; ?></td>
                <td><?= $jd['TRJD_PRD_DESKRIPSIPENDEK']; ?></td>
                <td><?= number_format($jd['TRJD_QUANTITY']); ?></td>
                <td><?= number_format($jd['TRJD_NOMINALAMT']); ?></td>
                <td><?= $jd['TRJD_CUS_KODEMEMBER']; ?></td>
                <td><?= $jd['CUS_FLAGMEMBERKHUSUS']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php $this->endSection(); ?>