<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="container-fluid mt-3">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card w-50 mb-3 mx-auto">
                <div class="card-header text-light" style="background-color: #0040ff;">
                    <h6 class="text-center fw-bold">Cek Barang Tertinggal</h6>
                </div>
                <div class="card-body">
                    <form method="get" action="tampildatabrghlg" target="_blank">
                        <?= csrf_field(); ?>
                        <label class="fw-bold mb-2" for="ks">KASSA :</label>
                        <input type="text" name="kassa" id="kassa" class="w-100 mb-3 form-control" value="<?= old('kassa'); ?>">
                        <label class="fw-bold mb-2" for="tgl">TANGGAL :</label>
                        <input type="date" name="tgl" id="tgl" class="w-100 mb-3 form-control" value="<?= old('tgl'); ?>">
                        <label class="fw-bold mb-2" for="prd">PERKIRAAN JAM :</label><br>
                        <label class="mb-2" for="start">Jam Awal</label>
                        <input type="time" name="awal" id="awal" class="w-100 mb-3 form-control" value="<?= old('awal'); ?>">
                        <label class="mb-2" for="stop">Jam Akhir</label>
                        <input type="time" name="akhir" id="akhir" class="w-100 mb-3 form-control" value="<?= old('akhir'); ?>">
                        <button type="submit" name="tombol" value="btnbh" class="btn w-100 mb-2 d-block text-light fw-bold" style="background-color: #33cc33;">Tampilkan Data</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>