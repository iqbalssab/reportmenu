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
                <th class="fw-bold text-center bg-info text-nowrap">#</th>
                <th class="fw-bold text-center bg-info text-nowrap">Barcode</th>
                <th class="fw-bold text-center bg-info text-nowrap">PLU MCG</th>
                <th class="fw-bold text-center bg-info text-nowrap">PLU IGR</th>
                <th class="fw-bold text-center bg-info text-nowrap">Nama Barang</th>
                <th class="fw-bold text-center bg-info text-nowrap">Unit</th>
                <th class="fw-bold text-center bg-info text-nowrap">Kode Tag</th>
                <th class="fw-bold text-center bg-info text-nowrap">Frac</th>
                <th class="fw-bold text-center bg-info text-nowrap">Avg Cost</th>
                <th class="fw-bold text-center bg-info text-nowrap">Harga Jual</th>
                <th class="fw-bold text-center bg-info text-nowrap">Stock Qty</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php foreach($barcode as $bd) : ?>
                <tr>
                    <?php 
                        if ($barcode <> $bd['BRC_BARCODE']) {
							echo '<td class="text-end">'  . $no++ . '</td>';
							$barcode = $bd['BRC_BARCODE'];
						} else {
							echo '<td></td>';
						}
                    ?>                    
                    <td class="text-center"><?= $bd['BRC_BARCODE']; ?></td>
                    <td class="text-center"><?= $bd['BRC_PLUMCG']; ?></td>
                    <td class="text-center"><?= $bd['BRC_PRDCD']; ?></td>
                    <td class="text-start text-nowrap"><?= $bd['BRC_NAMA_BARANG']; ?></td>
                    <td class="text-center"><?= $bd['BRC_UNIT']; ?></td>
                    <td class="text-center"><?= $bd['BRC_FRAC']; ?></td>
                    <td class="text-center"><?= $bd['BRC_TAG']; ?></td>
                    <td class="text-center"><?= number_format($bd['BRC_AVG_COST'], 0, '.', ','); ?></td>
                    <td class="text-center"><?= number_format($bd['BRC_HARGA_JUAL'], 0, '.', ','); ?></td>
                    <td class="text-center"><?= number_format($bd['BRC_STOCK_QTY'], 0, '.', ','); ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<?= $this->endSection(); ?>