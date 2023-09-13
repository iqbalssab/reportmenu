<?php $this->extend('layout/template'); ?>
<?php $this->section('content'); ?>

<?php $now = date('Y-m-d'); ?>
<?php if (session()->getFlashdata('Error')) : ?>
        <div class="alert alert-danger text-center fw-bold" role="alert">
            <?= session()->getFlashdata('Error'); ?>
        </div>
<?php endif; ?>
<div class="container mt-3">
    <div class="row d-flex justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary">
                    <h6 class="text-center text-light fw-semibold">HISTORY TRANSAKSI PRODUK</h6>
                </div>
                <div class="card-body px-4">
                    <form action="transaksiproduk/transaksi" method="get" target="_blank">
                        <?php csrf_field(); ?>
                        <label for="plu">PLU :</label>
                        <input type="text" name="plu" id="plu" class="w-100 mt-1">
                        <p class="fw-bold mt-2">Periode Transaksi :</p>
                        <label for="tglawal" class="">Tanggal Awal</label>
                        <input type="date" class="w-100" name="tglawal" id="tglawal" value="<?= old('tglawal') ? old('tglawal') : $now; ?>">
                        <label for="tglakhir" class="">Tanggal Akhir</label>
                        <input type="date" class="w-100" name="tglakhir" id="tglakhir" value="<?= old('tglakhir') ? old('tglakhir') : $now; ?>">

                        <p class="fw-bold mt-2">Filter Member :</p>
                        <input class="form-check-input border-1 border-black" type="radio" name="jenismember" value="all" id="jenismember" checked>
                        <label class="form-check-label" for="flexRadioDefault1">
                            ALL MEMBER
                        </label><br>
                        <input class="form-check-input border-1 border-black" type="radio" name="jenismember" value="mm" id="jenismember">
                        <label class="form-check-label" for="flexRadioDefault1">
                            MEMBER MERAH
                        </label><br>
                        <input class="form-check-input border-1 border-black" type="radio" name="jenismember" value="mb" id="jenismember">
                        <label class="form-check-label" for="flexRadioDefault1">
                            MEMBER BIRU
                        </label>
                        <label class="d-block" for="kodemember">Kode Member :</label>
                        <input type="text" name="kodemember" id="kodemember">
                        <button type="submit" class="btn btn-primary mt-3 w-100">Tampil</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php $this->endSection(); ?>