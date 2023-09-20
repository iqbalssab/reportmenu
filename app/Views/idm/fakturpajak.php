<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="row mt-3 mb-3">
    <h3 class="fw-bold ms-4">Monitoring Faktur Pajak IDM yang sudah menjadi Sales</h3>
<br><br> 
    <table class="table mb-3 table-striped table-bordered table-responsive" style="width: 750px;" style="font-size: 15px;">
        <thead class="table-group-divider">
            <tr>
                <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">#</th>
                <th colspan="4" class="fw-bold text-center bg-info text-nowrap">Struk</th>
                <th colspan="3" class="fw-bold text-center bg-info text-nowrap">Toko</th>
                <th colspan="2" class="fw-bold text-center bg-info text-nowrap">Faktur Pajak</th>
                <th colspan="6" class="fw-bold text-center bg-info text-nowrap">DSPB</th>
                <th colspan="3" class="fw-bold text-center bg-info text-nowrap">WT</th>
                <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">Keterangan</th>
            </tr>
            <tr>
                <th class="fw-bold text-center bg-info text-nowrap">Tanggal</th>
                <th class="fw-bold text-center bg-info text-nowrap">Stat</th>
                <th class="fw-bold text-center bg-info text-nowrap">Kasir</th>
                <th class="fw-bold text-center bg-info text-nowrap">DocNo</th>
                <th class="fw-bold text-center bg-info text-nowrap">Member</th>
                <th class="fw-bold text-center bg-info text-nowrap">Kode</th>
                <th class="fw-bold text-center bg-info text-nowrap">Nama</th>
                <th class="fw-bold text-center bg-info text-nowrap">Tanggal</th>
                <th class="fw-bold text-center bg-info text-nowrap">No. Seri</th>
                <th class="fw-bold text-center bg-info text-nowrap">Tanggal</th>
                <th class="fw-bold text-center bg-info text-nowrap">Nomor</th>
                <th class="fw-bold text-center bg-info text-nowrap">DPP BTKP</th>
                <th class="fw-bold text-center bg-info text-nowrap">DPP BKP</th>
                <th class="fw-bold text-center bg-info text-nowrap">PPN</th>
                <th class="fw-bold text-center bg-info text-nowrap">DPP + PPN</th>
                <th class="fw-bold text-center bg-info text-nowrap">Tanggal</th>
                <th class="fw-bold text-center bg-info text-nowrap">Nomor</th>
                <th class="fw-bold text-center bg-info text-nowrap">Jatuh Tempo</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php
                $no = 1; 
                foreach($pajak as $pj) : ?>
                <tr>
                    <td class="text-end"><?= $no++; ?></td>
                    <td class="text-center text-nowrap"><?= $pj['FKT_TGL']; ?></td>
                    <td class="text-center text-nowrap"><?= $pj['FKT_STATION']; ?></td>
                    <td class="text-start text-nowrap"><?= $pj['FKT_KASIR']; ?></td>
                    <td class="text-center text-nowrap"><?= $pj['FKT_NOTRANSAKSI']; ?></td>
                    <td class="text-center text-nowrap"><?= $pj['FKT_KODEMEMBER']; ?></td>
                    <td class="text-center text-nowrap"><?= $pj['TKO_KODEOMI']; ?></td>
                    <td class="text-start text-nowrap"><?= $pj['TKO_NAMAOMI']; ?></td>
                    <td class="text-center text-nowrap"><?= $pj['FKT_TGLFAKTUR']; ?></td>
                    <td class="text-center text-nowrap"><?= $pj['FKT_NOSERI']; ?></td>
                    <td class="text-center text-nowrap"><?= $pj['FKT_CREATE_DT']; ?></td>
                    <td class="text-center text-nowrap"><?= $pj['FKT_NODSPB']; ?></td>
                    <td class="text-end text-nowrap"><?= number_format($pj['RPB_TTLNILAI'] - $pj['RPB_TTLPPN'],'0',',','.'); ?></td>
                    <td class="text-end text-nowrap"><?= number_format($pj['RPB_TTLPPN'] * 10,'0',',','.'); ?></td>
                    <td class="text-end text-nowrap"><?= number_format($pj['RPB_TTLPPN'],'0',',','.'); ?></td>
                    <td class="text-end text-nowrap"><?= number_format($pj['RPB_TTLNILAI'] + $pj['RPB_TTLPPN'],'0',',','.'); ?></td>
                    <td class="text-center text-nowrap"><?= $pj['TRPT_SPH_TGL']; ?></td>
                    <td class="text-center text-nowrap"><?= $pj['TRPT_SPH_NO']; ?></td>
                    <td class="text-center text-nowrap"><?= $pj['TRPT_SALESDUEDATE']; ?></td>
                    <td class="text-center text-nowrap"></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<?= $this->endSection(); ?>