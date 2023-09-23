<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container mt-3">
    <div class="card">
        <form action="tampilmaslok" method="post">
        <div class="card-header bg-secondary-subtle">
            <h2 class="text-primary fw-bold"><i class="fa-solid fa-map me-2"></i>Master Lokasi</h2>
        </div>
        <div class="card-body d-flex justify-content-between">
            <div class="my-2 w-50">
                <label for="rak" class="fw-bold d-block">RAK</label>
                <select name="rak" id="rak" class="form-select w-50 border border-secondary-subtle">
                    <option value="all" selected>ALL RAK</option>
                    <option value="Toko">TOKO</option>
                    <option value="Gudang">GUDANG</option>
                </select>
            </div>
            <div class="card my-2 w-25">
                <div class="card-header bg-secondary-subtle">
                    <p class="fw-bold">Jenis Report</p>
                </div>
                <div class="card-body">
                    <select name="jenis" id="jenis" class="form-select">
                        <option value="perrak" selected>1. Lokasi Per Rak</option>
                    </select>
                </div>
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