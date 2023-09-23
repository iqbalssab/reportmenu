<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="container-fluid mt-3 overflow-auto">
    <div class="row justify-content-center">
        <h2 class="fw-bold text-center">Daftar Cluster IDM</h2>
    </div>
    <br><br>
    <?php
        $no = 1;
        $totalIntransit = 0;
    ?>
    <table class="table table-responsive table-striped table-hover table-bordered border-dark">
        <thead class="table-group-divider">
            <tr>
                <th rowspan="2" class="fw-bold text-center bg-info">#</th>
                <th rowspan="2" class="fw-bold text-center bg-info">Cluster</th>
                <th colspan="2" class="fw-bold text-center bg-info text-nowrap">Toko OMI</th>
                <th rowspan="2" class="fw-bold text-center bg-info">Permintaan Barang</th>
                <th rowspan="2" class="fw-bold text-center bg-info">Intransit</th>
                <th colspan="7" class="fw-bold text-center bg-info text-nowrap">Group Cluster</th>
                <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">Kode Member</th>
                <th rowspan="2" class="fw-bold text-center bg-info">Alamat</th>
                <th rowspan="2" class="fw-bold text-center bg-info">Keterangan</th>
            </tr>
            <tr>
                <th class="fw-bold text-center bg-info">Kode</th>
                <th class="fw-bold text-center bg-info">Nama</th>
                <th class="fw-bold text-center bg-info">Group</th>
                <th class="fw-bold text-center bg-info">Group 1</th>
                <th class="fw-bold text-center bg-info">Group 2</th>
                <th class="fw-bold text-center bg-info">Group 3/th>
                <th class="fw-bold text-center bg-info">Jarak Kirim</th>
                <th class="fw-bold text-center bg-info">Jarak Asli</th>
                <th class="fw-bold text-center bg-info">Mobil</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php foreach($clusteridm as $idm) : ?>
                <tr>
                    $totalIntransit += $idm['TKO_INTRANSIT'];
                    <td class="text-end"><?= $no++; ?></td>
                    <td class="text-center"><?= $idm['TKO_CLS_KODE']; ?></td>
                    <td class="text-center"><?= $idm['TKO_KODE_OMI']; ?></td>
                    <td class="text-center"><?= $idm['TKO_NAMA_OMI']; ?></td>
                    <td class="text-center"><?= $idm['TKO_PB_TERAKHIR']; ?></td>
                    <td class="text-center"><?= number_format($idm['TKO_INTRANSIT'], 0, '.', ','); ?></td>
                    <td class="text-center"><?= $idm['TKO_CLS_GROUP']; ?></td>
                    <td class="text-center"><?= $idm['TKO_CLS_GR1']; ?></td>
                    <td class="text-center"><?= $idm['TKO_CLS_GR2']; ?></td>
                    <td class="text-center"><?= $idm['TKO_CLS_GR3']; ?></td>
                    <td class="text-center"><?= $idm['TKO_CLS_JARAKKIRIM']; ?></td>
                    <td class="text-center"><?= $idm['TKO_CLS_JARAKASLI']; ?></td>
                    <td class="text-center"><?= $idm['TKO_CLS_MOBIL']; ?></td>
                    <td class="text-center"><?= $idm['TKO_KODE_CUSTOMER']; ?></td>
                    <td class="text-center"><?= $idm['TKO_ALAMAT']; ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<?= $this->endSection(); ?>