<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="row mt-3 mb-3 ms-2">
    <h3 class="fw-bold ms-4">Item IDM Only beda Tag dengan IGR</h3>
<br><br> 
    <table class="table mb-3 table-striped table-bordered table-responsive" style="font-size: 15px;">
        <thead class="table-group-divider">
            <tr>
                <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">#</th>
                <th colspan="3" class="fw-bold text-center bg-info text-nowrap">Divisi</th>
                <th colspan="5" class="fw-bold text-center bg-info text-nowrap">Produk</th>
                <th colspan="2" class="fw-bold text-center bg-info text-nowrap">Tag</th>
                <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">Keterangan</th>
            </tr>
            <tr>
                <th class="fw-bold text-center bg-info text-nowrap">Div</th>
                <th class="fw-bold text-center bg-info text-nowrap">Dept</th>
                <th class="fw-bold text-center bg-info text-nowrap">Katb</th>
                <th class="fw-bold text-center bg-info text-nowrap">PLU IDM</th>
                <th class="fw-bold text-center bg-info text-nowrap">PLU IGR</th>
                <th class="fw-bold text-center bg-info text-nowrap">Deskripsi</th>
                <th class="fw-bold text-center bg-info text-nowrap">Unit</th>
                <th class="fw-bold text-center bg-info text-nowrap">Frac</th>
                <th class="fw-bold text-center bg-info text-nowrap">Tag IGR</th>
                <th class="fw-bold text-center bg-info text-nowrap">Tag IDM</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php 
                $no = 1;
                foreach($idmtag as $idm) : ?>
                <tr>
                    <td class="text-end"><?= $no++; ?></td>
                    <td class="text-end"><?= $idm['PRD_DIV']; ?></td>
                    <td class="text-end"><?= $idm['PRD_DEP']; ?></td>
                    <td class="text-end"><?= $idm['PRD_KAT']; ?></td>
                    <td class="text-end"><?= $idm['PRD_PLUIDM']; ?></td>
                    <td class="text-end"><?= $idm['PRD_PLUIGR']; ?></td>
                    <td class="text-end"><?= $idm['PRD_NAMA_BARANG']; ?></td>
                    <td class="text-end"><?= $idm['PRD_UNIT']; ?></td>
                    <td class="text-end"><?= $idm['PRD_FRAC']; ?></td>
                    <td class="text-end"><?= $idm['PRD_TAGIGR']; ?></td>
                    <td class="text-end"><?= $idm['PRD_TAGIDM']; ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<?= $this->endSection(); ?>