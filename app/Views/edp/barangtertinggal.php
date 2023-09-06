<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="container-fluid mt-3">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card w-50 mb-3 mx-auto">
                <div class="card-header text-light" style="background-color: #0040ff;">
                    <h6 class="text-center fw-bold" style="font-size: 16px;">Cek Barang Tertinggal</h6>
                </div>
                <div class="card-body">
                    <form method="get" action="/edp/tampildatabrghlg" target="_blank">
                        <?= csrf_field(); ?>
                        <label class="fw-bold mb-2" for="ks" style="font-size: 15px;">KASSA :</label>
                        <input type="text" name="kassa" id="kassa" class="w-100 mb-3 form-control" value="<?= old('kassa'); ?>" style="font-size: 15px;" required autofocus>
                        <label class="fw-bold mb-2" for="tgl" style="font-size: 15px;">TANGGAL :</label>
                        <input type="date" name="tgl" id="tgl" class="w-100 mb-3 form-control" value="<?= old('tgl'); ?>" style="font-size: 15px;">
                        <label class="fw-bold mb-2" for="prd" style="font-size: 15px;">PERKIRAAN JAM :</label><br>
                        <label class="mb-2" for="start" style="font-size: 15px;">Jam Awal</label>
                        <input type="time" name="awal" id="awal" class="w-100 mb-3 form-control" value="<?= old('awal'); ?>" style="font-size: 15px;">
                        <label class="mb-2" for="stop" style="font-size: 15px;">Jam Akhir</label>
                        <input type="time" name="akhir" id="akhir" class="w-100 mb-3 form-control" value="<?= old('akhir'); ?>" style="font-size: 15px;">
                        <button type="submit" name="tombol" value="btnbh" class="btn w-100 mb-2 d-block text-light fw-bold" style="background-color: #6528F7;">Tampilkan Data</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>