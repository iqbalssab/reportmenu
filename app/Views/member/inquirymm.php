<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="container-fluid mt-2 overflow-auto">
    <div class="row">
        <div class="col-md-2" style="width: 200px;">
            <div class="card w-100 mb-1">
                <div class="card-header text-light mb-2 text-center" style="background-color: #0040ff;">
                    <h5 class="fw-bold">Inquiry Member Merah</h5>
                </div>
                <div class="card-body">
                    <form class="form" method="post" role="form" action="inquirymm">
                        <?= csrf_field(); ?>
                        <input type="text" name="kodeMM" id="kodeMM" class="mb-3 text-center form-control input-sm" style="font-size: 14px;" value="<?= old('kodeMM'); ?>" required autofocus placeholder="Kode MM">
                        <button type="submit" name="tombol" value="btnbh" class="btn btn-sm w-100 mb-1 d-block text-light fw-bold" style="background-color: #33cc33;" style="font-size: 14px;">Tampil</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6" style="width: 750px;">
            <!-- Data inquiry MM -->
            <?php if(!empty($inquirymm)) {?>
                <div class="card w-100 mb-3">
                    <table class="table table-bordered table-responsive table-condensed" style="font-size: 14px;">
                        <thead class="table-group-divider table-info">
                            <tr>
                                <th colspan="4" class="fw-bold text-center">Inquiry Member Merah</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <?php foreach($inquirymm as $mm) : ?>
                                <tr>
                                    <td class="text-center">Kode MM</td>
                                    <td class="text-center"><?= $mm['NO_MM']; ?></td>
                                    <td class="text-center">Nama MM</td>
                                    <td class="text-start text-nowrap"><?= $mm['NAMA_MM']; ?></td>
                                </tr>
                                <tr>
                                    <td class="text-center text-nowrap" colspan="2">Alamat Usaha</td>
                                    <td class="text-center text-nowrap" colspan="2"><?= $mm['ALAMAT_USAHA']; ?></td>
                                </tr>
                                <tr>
                                    <td class="text-center text-nowrap" colspan="2">Kecamatan</td>
                                    <td class="text-start text-nowrap" colspan="2"><?= $mm['KECAMATAN']; ?></td>
                                </tr>
                                <tr>
                                    <td class="text-center text-nowrap" colspan="2">Kota</td>
                                    <td class="text-start text-nowrap" colspan="2"><?= $mm['KOTA_USAHA']; ?></td>
                                </tr>
                                <tr>
                                    <td class="text-center text-nowrap" colspan="2">No Telp</td>
                                    <td class="text-start text-nowrap" colspan="2"><?= $mm['NO_TLP']; ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                    <br>
                    <table class="table table-bordered table-responsive table-condensed" style="font-size: 14px;">
                        <tr>
                            <td class="text-center text-nowrap" colspan="2">Tgl Awal Kunjungan</td>
                            <td class="text-start text-nowrap" colspan="2"><?= $mm['AWAL']; ?></td>
                            <td class="text-center text-nowrap" colspan="2">Tgl Akhir Kunjungan</td>
                            <td class="text-start text-nowrap" colspan="2"><?= $mm['AKHIR']; ?></td>
                        </tr>
                    </table>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>