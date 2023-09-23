<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="container-fluid mt-3 overflow-auto">
    <div class="row justify-content-center">
        <h3 class="fw-bold text-center">Barcode Double</h3>
    </div>
    <br><br>
    <?php
        $no = 1;
    ?>
    <table class="table table-responsive table-striped table-hover table-bordered border-dark">
        <thead class="table-group-divider">
            <tr>
                <th rowspan="2" class="fw-bold text-center bg-info">#</th>
                <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">Jenis Rak</th>
                <th colspan="5" class="fw-bold text-center bg-info text-nowrap">Lokasi</th>
                <th colspan="5" class="fw-bold text-center bg-info text-nowrap">Produk</th>
                <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">Qty Plano</th>
                <th rowspan="2" class="fw-bold text-center bg-info">Keterangan</th>
             </tr>
             <tr>
                <th class="fw-bold text-center bg-info">Rak</th>
                <th class="fw-bold text-center bg-info">Sub</th>
                <th class="fw-bold text-center bg-info">Tipe</th>
                <th class="fw-bold text-center bg-info">Shelving</th>
                <th class="fw-bold text-center bg-info text-nowrap">No Urut</th>
                <th class="fw-bold text-center bg-info">PLU</th>
                <th class="fw-bold text-center bg-info text-nowrap">Deskripsi</th>
                <th class="fw-bold text-center bg-info">Unit</th>
                <th class="fw-bold text-center bg-info">Frac</th>
                <th class="fw-bold text-center bg-info">Tag</th>
             </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php
                $no =1;
                foreach($qtyminus as $qty) : ?>
                <tr>
                    <td class="text-end"><?= $no++; ?></td>
                    <td class="text-center"><?= $qty['LKS_JENISRAK']; ?></td>
                    <td class="text-start"><?= $qty['LKS_KODERAK']; ?></td>
                    <td class="text-center"><?= $qty['LKS_KODESUBRAK']; ?></td>
                    <td class="text-start"><?= $qty['LKS_TIPERAK']; ?></td>
                    <td class="text-center"><?= $qty['LKS_SHELVINGRAK']; ?></td>
                    <td class="text-center"><?= $qty['LKS_NOURUT']; ?></td>
                    <td class="text-center"><?= $qty['LKS_PRDCD']; ?></td>
                    <td class="text-start text-nowrap"><?= $qty['LKS_NAMA_BARANG']; ?></td>
                    <td class="text-center"><?= $qty['LKS_UNIT']; ?></td>
                    <td class="text-center"><?= $qty['LKS_FRAC']; ?></td>
                    <td class="text-center"><?= $qty['PRD_KODE_TAG']; ?></td>
                    <td class="text-end"><?= number_format($qty['LKS_QTY'], 0, '.', ','); ?></td>
                    <td></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<?= $this->endSection(); ?>