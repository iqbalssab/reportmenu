<?php $this->extend('layout/template'); ?>
<?php $this->section('content'); ?>

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary">
                    <h6 class="text-light text-center fw-bolder">History Transaksi Klik</h6>
                </div>
                <div class="card-body py-2">
                    <form action="/store/transaksiklik" method="get">
                        <p class="mb-1 fw-bold">PERIODE</p>
                        <label for="tglawal">Tanggal Awal</label>
                        <input type="date" name="tglawal" id="tglawal" class="mb-2 w-100">
                        <label for="tglawal">Tanggal Akhir</label>
                        <input type="date" name="tglakhir" id="tglakhir" class="mb-2 w-100">

                        <button class="btn btn-primary w-100">Tampil</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-primary-subtle">
                    <h5>Transaksi Klik</h5>
                </div>
                <div class="card-body">
                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>