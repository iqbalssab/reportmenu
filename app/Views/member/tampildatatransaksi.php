<?php $this->extend('layout/template'); ?>

<?php $this->section('content'); ?>

<?php if(!empty($detail)): ?>
    <div class="container-fluid mt-3">
        <div class="row mb-2">
            <div class="col judul-detail">
                <?php foreach($member as $mbr): ?>
                <h5 class="fw-bold">DETAIL TRANSAKSI KASIR</h5><br>
                <h6>NAMA MEMBER = <b><?=$kd; ?> - [ <?=$mbr['NMMBR']; ?> ]</b> </h6>
                <h6>NOMOR TRANSAKI = <b><?=$nostr; ?></b> </h6>
                <h6>TANGGAL TRANSAKSI = <b><?= date('d M Y',strtotime($tgl)); ?> - <?=$jam; ?></b> </h6>
                <br>
                <?php endforeach ?>
            </div>
        </div>
        <table class="table table-bordered table-hover table-sm border-dark table-responsive" style="font-size: 15px; width: 1000px;">
            <thead class="table-success border-dark">
                <tr>
                    <th class="text-center">PLU</th>
                    <th class="text-center">DESKRIPSI</th>
                    <th class="text-center">QTY</th>
                    <th class="text-center">UNIT PRICE</th>
                    <th class="text-center">NOMINAL RPH</th>
                    <th class="text-center">TYPE</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                <?php foreach($detail as $dt) : ?>
                    <tr class="">
                        <td class="text-center"><?=$dt['PLU']; ?></td>
                        <td class="text-start"><?=$dt['DESKRIPSI']; ?></td>
                        <td class="text-end"><?=number_format($dt['QTY'],'0',',','.'); ?></td>
                        <td class="text-end"><?=number_format($dt['UNIT_PRICE'],'0',',','.'); ?></td>
                        <td class="text-end"><?=number_format($dt['NOMINAL_RPH'],'0',',','.'); ?></td>
                        <td class="text-center"><?=$dt['TIPE']; ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
            <tfoot class="table-group-divider">
                <?php foreach($ttl as $total) : ?>
                    <tr>
                        <td colspan="4" class="text-center fs-6"><b>TOTAL NILAI TRANSAKSI</b></td>
                        <td class="text-end fs-6 fw-bold">Rp. <?=number_format($total,0,',','.'); ?></td>
                        <td></td>
                    </tr>
                <?php endforeach ?>        
            </tfoot>
        </table>
    </div>
<?php endif; ?>
 
<?php $this->endSection(); ?>