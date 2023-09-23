<?php $this->extend('layout/template'); ?>

<?php $this->section('content'); ?>

    <?php $totalqtyplano = 0; ?>
    <?php $totalqtylpp = 0; ?>
    <?php $acost = 0; ?>
    
    <style>
            .container {border:3px solid #666;padding:10px;margin:0 auto;width:500px}
            input {margin:5px;}
            table, th, td {
                border: 1px solid black;
                border-collapse: collapse;
                margin:0 0 10px;
                width:auto;
                font-size:12px;
            }
            th{
            background:#66CCFF;
            padding:5px;
            font-weight:400;
            }
            td{
            padding:2px 5px;
            }
    </style>
        <div class="container-fluid mt-3">
            <div class="row mb-2">
                <div class="col judul-data">
                    <h5 class="fw-bold">SO HARIAN </h5>
                    <!-- <br> -->
                </div>
            </div>
            <table class="table table-bordered border-dark table-responsive" style="width: 900px; font-size: 14px;">
                <thead class="table table-success border-dark">
                    <tr>
                        <th class="text-center">Div</th>
                        <th class="text-center">Dept</th>
                        <th class="text-center">Katb</th>
                        <th class="text-center">PLU</th>
                        <th class="text-center">Deskripsi</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Plano</th>
                        <th class="text-center">Selisih</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($selisih as $min) : ?>
                        <tr>
                            <td class="text-center"><?=$min['DIV']; ?></td>
                            <td class="text-center"><?=$min['DEPT']; ?></td>
                            <td class="text-center"><?=$min['KAT']; ?></td>
                            <td class="text-center"><?=$min['PLU']; ?></td>
                            <td class="text-start"><?=$min['DESKRIPSI']; ?></td>
                            <td class="text-end"><?=number_format($min['STOCK'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($min['PLANO'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($min['SELISIH'],'0',',','.'); ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <table class="table table-bordered border-dark table-responsive" style="width: 1100px; font-size: 14px;">
                <thead class="table table-success border-dark">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">PLU</th>
                        <th class="text-center">Area</th>
                        <th class="text-center">Lokasi</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Expired</th>
                        <th class="text-center">Selisih Qty</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach($soharian as $so) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?=$so['PLU']; ?></td>
                            <td class="text-center"><?=$so['AREA']; ?></td>
                            <td class="text-start"><?=$so['LOKASI']; ?></td>
                            <td class="text-end"><?=number_format($so['LKS_QTY'],'0',',','.'); ?></td>
                            <td class="text-center"><?=$so['LKS_EXPDATE']; ?></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            
            <?php $totalqtyplano = 0; ?>
            <?php $totalqtylpp = 0; ?>
            <div class="">
                <p style="font-size:small"><b><i>** Dicetak pada : <?php echo date('d M Y') ?> **</i></b></p>
            </div>
        </div>

<?= $this->endSection(); ?>