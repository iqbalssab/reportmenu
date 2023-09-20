<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="container-fluid mt-3 overflow-auto">
    <div class="row">
        <h2 class="fw-bold">Master Lokasi Double <span class="fs-3"> - Rekap</span></h2>
        <table class="table table-responsive table-striped table-hover table-bordered border-dark" style="width: 850px;" style="font-size: 15px;">
            <thead class="table-group-divider">
                <tr>
                    <th class="fw-bold text-center bg-info">#</th>
                    <th class="fw-bold text-center bg-info">PLU</th>
                    <th class="fw-bold text-center bg-info">Deskripsi</th>
                    <th class="fw-bold text-center bg-info">Unit</th>
                    <th class="fw-bold text-center bg-info">Frac</th>
                    <th class="fw-bold text-center bg-info">Tag</th>
                    <th class="fw-bold text-center bg-info text-nowrap">Jumlah Lokasi</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                <?php
                    $no = 1;
                    $prdcd = "Tidak diketahui";
                ?>
                <?php foreach($rekap as $rk) : ?>
                    <tr>
                        <td class="text-end"><?= $no++; ?></td>
                        <td class="text-center"><?= $rk['LKS_PRDCD']; ?></td>
                        <td class="text-start text-nowrap"><?= $rk['LKS_NAMA_BARANG']; ?></td>
                        <td class="text-center"><?= $rk['LKS_UNIT']; ?></td>
                        <td class="text-center"><?= $rk['LKS_FRAC']; ?></td>
                        <td class="text-center"><?= $rk['LKS_TAG']; ?></td>
                        <td class="text-end"><?= $rk['LKS_JUMLAH_LOKASI']; ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <br><br>
        <h2 class="fw-bold">Master Lokasi Double <span class="fs-3"> - Detail</span></h2>
        <table class="table table-responsive table-striped table-hover table-bordered border-dark" style="width: 850px;" style="font-size: 15px;">
            <thead class="table-group-divider">
                <tr>
                    <th class="fw-bold text-center bg-info">#</th>
                    <th class="fw-bold text-center bg-info">PLU</th>
                    <th class="fw-bold text-center bg-info">Deskripsi</th>
                    <th class="fw-bold text-center bg-info">Unit</th>
                    <th class="fw-bold text-center bg-info">Frac</th>
                    <th class="fw-bold text-center bg-info">Tag</th>
                    <th class="fw-bold text-center bg-info">Rak</th>
                    <th class="fw-bold text-center bg-info text-nowrap">Sub Rak</th>
                    <th class="fw-bold text-center bg-info">Tipe</th>
                    <th class="fw-bold text-center bg-info">Shelving</th>
                    <th class="fw-bold text-center bg-info text-nowrap">No Urut</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                <?php
                    $no = 1;
                    $prdcd = "Tidak diketahui";
                ?>
                <?php foreach($detail as $dt) : ?>
                    <tr>
                        <?php
                            if ($prdcd <> $dt['LKS_PRDCD']) {
                                echo '<td class="text-end">'  . $no++ . '</td>';
    
                                echo '<td class="text-center">' . $dt['LKS_PRDCD'] . '</td>';
                                echo '<td class="text-start text-nowrap">' . $dt['LKS_NAMA_BARANG'] . '</td>';
    
                                echo '<td class="text-center">' . $dt['LKS_UNIT'] . '</td>';
                                echo '<td class="text-center">' . $dt['LKS_FRAC'] . '</td>';
                                echo '<td class="text-center">' . $dt['LKS_TAG'] . '</td>';
                            
                                $prdcd = $dt['LKS_PRDCD'];
                            } else {
                                echo '<td></td><td></td><td></td><td></td><td></td><td></td>';
                            }
                        ?>
                        <td class="text-start"><?= $dt['LKS_KODERAK']; ?></td>
                        <td class="text-center"><?= $dt['LKS_KODESUBRAK']; ?></td>
                        <td class="text-start"><?= $dt['LKS_TIPERAK']; ?></td>
                        <td class="text-center"><?= $dt['LKS_SHELVINGRAK']; ?></td>
                        <td class="text-center"><?= $dt['LKS_NOURUT']; ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection(); ?>