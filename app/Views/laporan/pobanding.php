<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container mt-3">
    <div class="card">
        <form action="tampilbanding" method="post">
        <div class="card-header bg-secondary-subtle">
            <h2 class="text-primary fw-bold"><i class="fa-solid fa-scale-balanced me-2"></i>PO Banding</h2>
        </div>
        <div class="card-body d-flex justify-content-between">
            <div class="my-2 w-50">
                <label for="nopo1" class="fw-bold d-block">NOMOR PO 1</label>
                <input type="text" name="nopo1" id="nopo1" class="w-50 mb-2" placeholder="Nomor PO 1">
                <label for="nopo2" class="fw-bold d-block">NOMOR PO 2</label>
                <input type="text" name="nopo2" id="nopo2" class="w-50 mb-2" placeholder="Nomor PO 2">
                
            </div>
            <div class="card my-2 w-25">
                <div class="card-header bg-secondary-subtle">
                    <p class="fw-bold">Jenis Report</p>
                </div>
                <div class="card-body">
                    <select name="jenis" id="jenis" class="form-select">
                        <option value="banding" selected>1. PO Banding</option>
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