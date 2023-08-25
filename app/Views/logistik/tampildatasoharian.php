<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

    <?php $totalqtyplano = 0; ?>
    <?php $totalqtylpp = 0; ?>
    <?php $acost = 0; ?>
    
    <?php if(!empty($soharian)): ?>
        <div class="container-fluid mt-3">
            <div class="row mb-2">
                <div class="col judul-data">
                    <h5 class="fw-bold">SO HARIAN >> <?=$plu0; ?> </h5>
                    <br>
                </div>
            </div>
            <table class="table table-hover table-bordered table-sm border-dark table-responsive">
                <thead class="table table-success border-dark">
                    <tr>
                        <th class="text-center">PLU</th>
                        <th class="text-center">DESKRIPSI</th>
                        <th class="text-center">FRAC</th>
                        <th class="text-center">TAG</th>
                        <th class="text-center">AREA</th>
                        <th class="text-center">LOKASI</th>
                        <th class="text-center">BY</th>
                        <th class="text-center">QTY PLANO</th>
                        <th class="text-center">QTY FISIK</th>
                        <th class="text-center">SELISIH</th>
                    </tr>
                </thead>
                <tbody class="table table-group-divider border-dark">
                    <?php foreach($soharian as $so) : ?>
                    <tr>
                        <td class="text-center"><?=$so['PLU']; ?></td>
                        <td class="text-start"><?=$so['DESKRIPSI']; ?></td>
                        <td class="text-center"><?=$so['FRAC']; ?></td>
                        <td class="text-center"><?=$so['TAG']; ?></td>
                        <td class="text-center"><?=$so['AREA']; ?></td>
                        <td class="text-start"><?=$so['LOKASI']; ?></td>
                        <td class="text-start"><?=$so['MODBY']; ?></td>
                        <td class="text-end"><?=number_format($so['STOK_PLANO'],'0',',','.'); ?></td>
                        <?php $totalqtyplano +=  $so['STOK_PLANO']; ?>
                        <?php $totalqtylpp =  $so['STOK_LPP']; ?>
                        <?php $acost =  $so['ACOST']; ?>
                        <td></td>
                        <td></td>
                    </tr>                    
                    <?php endforeach ?>
                    <tr>
                        <td class="fw-bold text-end">ACOST :</td>
                        <td class="fw-bold"><?= number_format($acost,'0',',','.'); ?></td>
                        <td class="fw-bold text-end" colspan="5">TOTAL QTY PLANO</td>
                        <td class="fw-bold text-end"><?=number_format($totalqtyplano,'0',',','.') ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-end">FLAG :</td>
                        <td class="fw-bold"><?= $so['FLAGJUAL']; ?></td>
                        <td class="fw-bold text-end" colspan="5">TOTAL QTY LPP</td>
                        <td class="fw-bold text-end"><?=number_format($totalqtylpp,'0',',','.') ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-end" colspan="7">SELISIH :</td>
                        <td class="fw-bold text-end"><?=number_format(($totalqtyplano-$totalqtylpp),'0',',','.') ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php if(!empty($cekslp)) {?>
                    <?php foreach($cekslp as $slp) : ?>
                        <tr>
                            <td colspan="7" class="fw-bold text-end">SLP Belum realisasi</td>
                            <td class="text-end text-danger fw-bold"><?=number_format($slp['SLP_QTYPCS'],'0',',','.'); ?></td>
                            <td colspan="2" class="fw-bold text-danger"><?=$slp['SLP_LOKASI']; ?></td>
                        </tr>
                    <?php endforeach ?>
                    <?php } ?>
                    <?php if(!empty($cekspb)) {?>
                    <?php foreach($cekspb as $spb) : ?>
                        <tr>
                            <td colspan="7" class="fw-bold text-end">SPB Belum realisasi</td>
                            <td class="text-end text-danger fw-bold"><?=number_format($spb['SPB_MINUS'],'0',',','.'); ?></td>
                            <td colspan="2" class="fw-bold text-danger"><?=$slp['SPB_LOKASITUJUAN']; ?></td>
                        </tr>
                    <?php endforeach ?>
                    <?php } ?>
                    <?php if(!empty($cekklik)) {?>
                    <?php foreach($cekklik as $klik) : ?>
                        <tr>
                            <td colspan="7" class="fw-bold text-end">Proses Klik</td>
                            <td class="text-end text-danger fw-bold"><?=number_format($klik['QTYREALISASI'],'0',',','.'); ?></td>
                            <td colspan="2" class="fw-bold text-danger"><?=$klik['ATRIBUT2']; ?> - <?=$klik['STATUS']; ?></td>
                        </tr>
                    <?php endforeach ?>
                    <?php } ?>
                    <?php if(!empty($cektmi)) {?>
                    <?php foreach($cektmi as $tmi) : ?>
                        <tr>
                            <td colspan="7" class="fw-bold text-end">Proses TMI</td>
                            <td class="text-end text-danger fw-bold"><?=number_format($tmi['QTYREALISASI'],'0',',','.'); ?></td>
                            <td colspan="2" class="fw-bold text-danger"><?=$tmi['ATRIBUT2']; ?> - <?=$tmi['STATUS']; ?></td>
                        </tr>
                    <?php endforeach ?>
                    <?php } ?>
                    <?php if(!empty($cekomi)) {?>
                    <?php foreach($cekomi as $omi) : ?>
                        <tr>
                            <td colspan="7" class="fw-bold text-end">Proses OMI</td>
                            <td class="text-end text-danger fw-bold"><?=number_format($omi['PBO_QTYREALISASI'],'0',',','.'); ?></td>
                            <td colspan="2" class="fw-bold text-danger"><?=$omi['PBO_KODEOMI']; ?> - <?=$omi['STATUS']; ?></td>
                        </tr>
                    <?php endforeach ?>
                    <?php } ?>
                </tbody>
            </table>
            <?php $totalqtyplano = 0; ?>
            <?php $totalqtylpp = 0; ?>
            <div class="">
                <p style="font-size:small"><b><i>** Dicetak pada : <?php echo date('d M Y') ?> **</i></b></p>
            </div>
        </div>
    <?php endif; ?>

<?= $this->endSection(); ?>