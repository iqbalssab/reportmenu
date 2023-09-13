<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="container-fluid mt-3 overflow-auto">
    <div class="row">
        <div class="col-md-6">
            <h4 class="fw-bold">LPP BAIK</h4>    
            <table class="table mb-3 table-bordered table-striped table-responsive border-dark" style="font-size: 15px;">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center bg-info">#</th>
                        <th colspan="2" class="fw-bold text-center bg-info">Tanggal</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Koreksi</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">Saldo Awal</th>
                        <th colspan="2" class="fw-bold text-center bg-info">Pembelian</th>
                        <th colspan="5" class="fw-bold text-center bg-info">Penerimaan</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Penjualan</th>
                        <th colspan="4" class="fw-bold text-center bg-info">Pengeluaran</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Intransit</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Penyesuaian</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">Saldo Akhir</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center bg-info">Mulai</th>
                        <th class="fw-bold text-center bg-info">Selesai</th>
                        <th class="fw-bold text-center bg-info">Murni</th>
                        <th class="fw-bold text-center bg-info">Bonus</th>
                        <th class="fw-bold text-center bg-info">TAC In</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Retur Sales</th>
                        <th class="fw-bold text-center bg-info">Rafaksi</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Repack In</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Lain-lain In</th>
                        <th class="fw-bold text-center bg-info">TAC Out</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Repack Out</th>
                        <th class="fw-bold text-center bg-info">Hilang</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Lain-lain Out</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php $no = 1; ?>
                    <?php foreach($lppBaik as $lb) : ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td class="text-center text-nowrap"><?= $lb['LPP_TGL1']; ?></td>
                            <td class="text-center text-nowrap"><?= $lb['LPP_TGL2']; ?></td>
                            <td class="text-end"><?= number_format($lb['LPP_KOREKSI'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lb['LPP_RPHBEGBAL'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lb['LPP_RPHBELI'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lb['LPP_RPHBONUS'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lb['LPP_RPHTRMCB'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lb['LPP_RPHRETURSALES'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lb['LPP_RPHRAFAK'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lb['LPP_RPHREPACK'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lb['LPP_RPHLAININ'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lb['LPP_RPHSALES'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lb['LPP_RPHKIRIM'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lb['LPP_RPHPREPACKING'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lb['LPP_RPHHILANG'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lb['LPP_RPHLAINOUT'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lb['LPP_RPHINTRANSIT'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lb['LPP_RPHADJ'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lb['LPP_RPHAKHIR'],'0',',','.'); ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6">
        <h4 class="fw-bold">LPP RETUR</h4>    
            <table class="table mb-3 table-bordered table-striped table-responsive border-dark" style="font-size: 15px;">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center bg-info">#</th>
                        <th colspan="2" class="fw-bold text-center bg-info">Tanggal</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Koreksi</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">Saldo Awal</th>
                        <th colspan="2" class="fw-bold text-center bg-info">Penerimaan</th>
                        <th colspan="4" class="fw-bold text-center bg-info">Pengeluaran</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Penyesuaian</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">Saldo Akhir</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center bg-info">Mulai</th>
                        <th class="fw-bold text-center bg-info">Selesai</th>
                        <th class="fw-bold text-center bg-info">Baik</th>
                        <th class="fw-bold text-center bg-info">Rusak</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Retur Supplier</th>
                        <th class="fw-bold text-center bg-info">Hilang</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Lain-lain Baik</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Lain-lain Rusak</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php $no = 1; ?>
                    <?php foreach($lppRetur as $lr) : ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td class="text-center text-nowrap"><?= $lr['LRT_TGL1']; ?></td>
                            <td class="text-center text-nowrap"><?= $lr['LRT_TGL2']; ?></td>
                            <td class="text-end"><?= number_format($lr['LRT_KOREKSI'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lr['LRT_RPHBEGBAL'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lr['LRT_RPHBAIK'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lr['LRT_RPHRUSAK'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lr['LRT_RPHSUPPLIER'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lr['LRT_RPHHILANG'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lr['LRT_RPHLBAIK'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lr['LRT_RPHLRUSAK'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lr['LRT_RPHADJ'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lr['LRT_RPHAKHIR'],'0',',','.'); ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6">
        <h4 class="fw-bold">LPP RUSAK</h4>    
            <table class="table mb-3 table-bordered table-striped table-responsive border-dark" style="font-size: 15px;">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center bg-info">#</th>
                        <th colspan="2" class="fw-bold text-center bg-info">Tanggal</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Koreksi</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">Saldo Awal</th>
                        <th colspan="2" class="fw-bold text-center bg-info">Penerimaan</th>
                        <th colspan="4" class="fw-bold text-center bg-info">Pengeluaran</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Penyesuaian</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">Saldo Akhir</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center bg-info">Mulai</th>
                        <th class="fw-bold text-center bg-info">Selesai</th>
                        <th class="fw-bold text-center bg-info">Baik</th>
                        <th class="fw-bold text-center bg-info">Retur</th>
                        <th class="fw-bold text-center bg-info">Pemusnahan</th>
                        <th class="fw-bold text-center bg-info">Hilang</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Lain-lain Baik</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Lain-lain Rusak</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php $no = 1; ?>
                    <?php foreach($lppRusak as $lk) : ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td class="text-center text-nowrap"><?= $lk['LRS_TGL1']; ?></td>
                            <td class="text-center text-nowrap"><?= $lk['LRS_TGL2']; ?></td>
                            <td class="text-end"><?= number_format($lk['LRS_KOREKSI'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lk['LRS_RPHBEGBAL'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lk['LRS_RPHBAIK'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lk['LRS_RPHRETUR'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lk['LRS_RPHMUSNAH'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lk['LRS_RPHHILANG'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lk['LRS_RPHLBAIK'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lk['LRS_RPHLRETUR'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lk['LRS_RPHADJ'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lk['LRS_RPHAKHIR'],'0',',','.'); ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>