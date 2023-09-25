<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="container-fluid mt-2">
    <div class="row">
        <div class="col-md-3" style="width: 250px;">
            <div class="card w-100 mb-2">
                <div class="card-header text-light mb-1" style=" background-color: #0040ff;">
                    <h6 class="text-center fw-bold">History Approval Fingerprint</h6>
                </div>
                <div class="card-body ">
                    <form method="get" action="historyappfp">
                        <?= csrf_field(); ?>
                        <label class="mb-2 fw-bold" style="font-size: 16px;">Periode</label>
                        <input type="date" name="awal" id="awal" class="w-100 mb-2 form-control mx-auto" value="<?= old('awal'); ?>" style="font-size: 14px;">
                        <input type="date" name="akhir" id="akhir" class="w-100 mb-4 form-control mx-auto" value="<?= old('akhir'); ?>" style="font-size: 14px;">
                        <button type="submit" name="tombol" value="btnksr" class="btn w-100 mb-1 d-block text-light fw-bold btn-sm" style="background-color: #6C3428;font-size: 14px">POS KASIR</button>
                        <button type="submit" name="tombol" value="btnhh" class="btn w-100 mb-1 d-block text-light fw-bold btn-sm" style="background-color: #6528F7;font-size: 14px">HANDHELD</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8" style="width: 1080px;">
            <?php if($aksi == "btnksr") { ?>
                <?php 
                    if(isset($_GET['awal'])) {if ($_GET['awal'] !=""){$tanggalMulai = $_GET['awal']; }}
                    if(isset($_GET['akhir'])) {if ($_GET['akhir'] !=""){$tanggalSelesai = $_GET['akhir']; }}
                ?>
                <div class="card w-100 mb-3">
                    <div class="card-header bg-info text-dark">
                        <h6 class="fw-bold">History Approval Pos Kasir Periode : <?= $tanggalMulai; ?> s/d  <?= $tanggalSelesai; ?>**</h6>
                    </div>
                    <div class="card_body">
                        <table class="table mb-3" style="font-size: 14px;">
                            <thead>
                                <tr>
                                    <th class="fw-bold text-center">#</th>
                                    <th class="fw-bold text-center">Req Time</th>
                                    <th class="fw-bold text-center">User ID</th>
                                    <th class="fw-bold text-center">Kassa</th>
                                    <th class="fw-bold text-center">Keterangan</th>
                                    <th class="fw-bold text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                <?php $no=0 ; ?>
                                <?php 
                                    $no++;
                                    foreach($approval as $app) : ?>
                                    <tr>
                                        <td class="text-end"><?= $no++; ?></td>
                                        <td class="text-center"><?= $app['REQ_TIME']; ?></td>
                                        <td class="text-center"><?= $app['USERID']; ?></td>
                                        <td class="text-center"><?= $app['KASSA']; ?></td>
                                        <td class="text-start"><?= $app['KETERANGAN']; ?></td>
                                        <td class="text-center"><?= $app['APPROVAL']; ?></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } else if($aksi == "btnhh") { ?>
                <?php 
                    if(isset($_GET['awal'])) {if ($_GET['awal'] !=""){$tanggalMulai = $_GET['awal']; }}
                    if(isset($_GET['akhir'])) {if ($_GET['akhir'] !=""){$tanggalSelesai = $_GET['akhir']; }}
                ?>
                <div class="card w-100 mb-3">
                    <div class="card-header bg-info text-dark">
                        <h6 class="fw-bold">History Approval Handheld Periode : <?= $tanggalMulai; ?> s/d  <?= $tanggalSelesai; ?>**</h6>
                    </div>
                    <div class="card_body">
                        <table class="table mb-3" style="font-size: 14px;">
                            <thead>
                                <tr>
                                    <th class="fw-bold text-center">#</th>
                                    <th class="fw-bold text-center">Req Time</th>
                                    <th class="fw-bold text-center">User ID</th>
                                    <th class="fw-bold text-center">Kassa</th>
                                    <th class="fw-bold text-center">Keterangan</th>
                                    <th class="fw-bold text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                <?php $no=0 ; ?>
                                <?php 
                                    $no++;
                                    foreach($approval as $app) : ?>
                                    <tr>
                                        <td class="text-end"><?= $no++; ?></td>
                                        <td class="text-center"><?= $app['REQ_TIME']; ?></td>
                                        <td class="text-center"><?= $app['USERID']; ?></td>
                                        <td class="text-center"><?= $app['KASSA']; ?></td>
                                        <td class="text-start"><?= $app['KETERANGAN']; ?></td>
                                        <td class="text-center"><?= $app['APPROVAL']; ?></td>
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

<?= $this->endSection(); ?>