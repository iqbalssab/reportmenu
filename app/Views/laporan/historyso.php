<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container mt-3">
    <div class="card">
        <form action="tampilhistoryso" method="post">
        <div class="card-header bg-secondary-subtle">
            <h2 class="text-primary fw-bold"><i class="fa-solid fa-history me-2"></i>History SO</h2>
        </div>
        <div class="card-body d-flex justify-content-between">
            <div class="my-2 w-50">
                <label for="tglawal" class="fw-bold d-block">Tanggal SO</label>
                <label for="" class="d-block">Tgl awal</label>
                <input type="date" name="tglawal" id="tglawal" class="w-25 mb-3">
                <label for="" class="d-block">tgl akhir</label>
                <input type="date" name="tglakhir" id="tglakhir" class="w- mb-3">
                <label for="" class="d-block fw-bold">PLU</label>
                <input type="text" name="plu" id="plu" placeholder="kode PLU">
            </div> 
        </div>
        <div class="card-footer bg-secondary-subtle d-flex justify-content-end">
            <button type="submit" name="btn" value="clear" class="btn btn-light border border-secondary-subtle rounded-pill">Bersihkan</button>
            <button type="submit" name="btn" value="tampil" class="btn btn-primary rounded-pill ms-3">Tampil Laporan</button>
        </div>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>