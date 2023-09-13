<?php 
echo $this->extend('layout/template'); ?>

<?php
echo $this->section('content'); ?>

<div class="container-fluid mt-2">
    <div class="row">
        <div class="col-md-2">
            <div class="card w-100 mb-3">
                <div class="card-header text-light" style="background-color: #0040ff;">
                    <h6 class="text-center fw-bold">Pengeluaran Hadiah</h6>
                </div>
                <div class="card-body">
                    <form method="get" action="pengeluaranhadiah">
                        <?= csrf_field(); ?>
                        <label class="fw-bold mb-2" for="prd">PERIODE :</label><br>
                        <label class="mb-2" for="start">Tanggal Awal</label>
                        <input type="date" name="awal" id="awal" class="w-100 mb-3 form-control" value="<?= old('awal'); ?>">
                        <label class="mb-2" for="stop">Tanggal Akhir</label>
                        <input type="date" name="akhir" id="akhir" class="w-100 mb-3 form-control" value="<?= old('akhir'); ?>">
                        <button type="submit" name="tombol" value="btngift1" class="btn w-100 mb-2 d-block text-light fw-bold" style="background-color: #6528F7;">Detail</button>
                        <button type="submit" name="tombol" value="btngift2" class="btn w-100 mb-2 d-block text-light fw-bold" style="background-color: #6C3428;">Rekap</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-10">
            <?php if(!empty($detail)) {?>
                <div class="card w-100 mb-3">
                    <div class="card-header text-dark" style="background-color: #00E6E6;">
                        <h6 class="fw-bold">Periode Data pengeluaran Hadiah : <?= date('d M Y',strtotime($awal)); ?> s/d <?= date('d M Y',strtotime($akhir)); ?></h6>
                    </div>
                    <div class="card-body">
                        <table class="table mb-3" style="font-size: 14px;">
                            <thead>
                                <tr>
                                    <th class="text-center">NO</th>
                                    <th class="text-center">KD_PROMOSI</th>
                                    <th class="text-center">JENIS</th>
                                    <th class="text-center">KETERANGAN</th>
                                    <th class="text-center">KD_MEMBER</th>
                                    <th class="text-center">TGL_TRANSAKSI</th>
                                    <th class="text-center">NO_STRUK</th>
                                    <th class="text-end">JUMLAH</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                <?php $no = 1; ?>
                                <?php foreach($detail as $dt) : ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td class="text-center"><?=$dt['KODE_PROMO']; ?></td>
                                    <td class="text-center"><?=$dt['JENIS']; ?></td>
                                    <td class="text-start"><?=$dt['KETERANGAN']; ?></td>
                                    <td class="text-center"><?=$dt['KODE_MEMBER']; ?></td>
                                    <td class="text-center"><?=$dt['TANGGAL']; ?></td>
                                    <td class="text-center"><?=$dt['NOSTRUK']; ?></td>
                                    <td class="text-end"><?=number_format($dt['JUMLAH'],'0',',','.'); ?></td>
                                </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } else if(!empty($rekap)) {?>
                <div class="card w-100 mb-3">
                    <div class="card-header text-dark" style="background-color: #00E6E6;">
                        <h6 class="fw-bold">Periode Data pengeluaran Hadiah : <?= date('d-M-Y',strtotime($awal)); ?> s/d <?= date('d-M-Y',strtotime($akhir)); ?></h6>
                    </div>
                    <div class="card-body">
                        <table class="table mb-3" style="font-size: 14px;">
                            <thead>
                                <tr>
                                    <th class="text-center">NO</th>
                                    <th class="text-center">KD_PROMOSI</th>
                                    <th class="text-center">JENIS</th>
                                    <th class="text-center">KETERANGAN</th>
                                    <th class="text-end">JUMLAH</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                <?php $no = 1; ?>
                                <?php foreach($rekap as $rk) : ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td class="text-center"><?=$rk['KODE_PROMO']; ?></td>
                                    <td class="text-center"><?=$rk['JENIS']; ?></td>
                                    <td class="text-start"><?=$rk['KETERANGAN']; ?></td>
                                    <td class="text-end"><?=number_format($rk['JUMLAH'],'0',',','.'); ?></td>
                                </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>