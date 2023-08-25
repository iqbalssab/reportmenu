<?php $this->extend('layout/template'); ?>
<?php $this->section('content'); ?>

<?php $now = date('Y-m-d'); ?>
<div class="container mt-3">
    <div class="row d-flex justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <h6 class="text-center fw-bold">FORMULIR SO HARIAN</h6>
                </div>
                <div class="card-body p-4">
                    <form action="tampilsoharian" method="get" target="_blank">
                    <div class="wadah-plu ">
                        <p class="text-center fw-semibold">:: Masukkan PLU ::</p>
                        <textarea class="d-block w-100" name="plu" id="plu" cols="10" rows="5" placeholder="850,006410,dll"></textarea>
                        <small class="fw-lighter text-center">Untuk monitoring banyak PLU, gunakan tanda koma [ , ] untuk pemisah dan tanpa spasi.CONTOH : 0000850,0060410,357330,357320 dst.</small>
                    </div>
                    <div class="wadah-ket mt-2">
                        <p class="text-center fw-semibold">:: Keterangan ::</p>
                        <label for="tgl">Tanggal</label>
                        <input type="date" name="tgl" id="tgl" class="w-25 d-inline" value="<?= $now; ?>">
                        <input type="radio" name="divisi" id="divisi" value="food" checked>FOOD
                        <input type="radio" name="divisi" id="divisi" value="nonfood">NON-FOOD
                    </div>
                    <button type="submit" name="btn" value="submitplu" class="text-center btn btn-primary text-light mt-4 w-100">TAMPILKAN</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>