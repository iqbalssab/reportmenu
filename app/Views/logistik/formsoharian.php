<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="container-fluid mt-3">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card w-60 mb-3 mx-auto">
                <div class="card-header text-light" style="background-color: #0040ff;">
                    <h6 class="text-center fw-bold">FORM SO HARIAN</h6>
                </div>
                <div class="card-body">
                    <form method="get" action="tampildatasoharian" target="_blank">
                        <?= csrf_field(); ?>
                        <label class="fw-bold mb-2" for="txtplu">Input PLU :</label>
                        <textarea class="form-control input-sm w-100 mb-2" name="plu" cols="55" rows="3" placeholder="850,60410,357330"></textarea>
                        <div class="text-center mb-3" style="font-size: 12px;">Untuk monitoring dengan banyak PLU, gunakan <b>tanda koma [ , ]</b> untuk pemisah antar PLU dan <b>tanpa spasi</b>.<br>
                        <i>Contoh : 0000850,0060410,357330, dst.</i></div>
                        <label class="fw-bold mb-2" for="txttgl">Tanggal :</label>
                        <input type="date" name="tgl" id="tgl" class="form-control w-100 mb-3" value="<?php echo strtoupper(date("Y-m-d")); ?>">
                        <!-- <label class="fw-bold mb-2" for="txtdiv">Divisi :</label><br>
                        <input type="radio" class="mb-2" name="divisi" value="food" checked>FOOD<br>
                        <input type="radio" class="mb-3" name="divisi" value="nonfood">NON-FOOD -->
                        <button type="submit" name="tombol" value="btnbh" class="btn w-100 mb-2 d-block text-light fw-bold" style="background-color: #33cc33;">Tampilkan Data</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>