<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid mt-4">
    <!-- Status Tidak Jelas -->
    <div class="row mb-3">
        <div class="col-md-12">
            <h3 class="text-primary">Status Tidak Jelas</h3>
            <table class="table table-bordered border border-dark">
                <thead>
                    <tr>
                        <th class="bg-primary-subtle">No</th>
                        <th class="bg-primary-subtle">PLU</th>
                        <th class="bg-primary-subtle">Nama Barang</th>
                        <th class="bg-primary-subtle">Unit</th>
                        <th class="bg-primary-subtle">Frac</th>
                        <th class="bg-primary-subtle">Tag</th>
                        <th class="bg-primary-subtle">Flag IDM</th>
                        <th class="bg-primary-subtle">Avg Cost</th>
                        <th class="bg-primary-subtle">Saldo AKhir</th>
                        <th class="bg-primary-subtle">PO Outstanding</th>
                        <th class="bg-primary-subtle">No PO</th>
                        <th class="bg-primary-subtle">Harga Beli</th>
                        <th class="bg-primary-subtle">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($tidakjelas)): ?>

                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Status Item IDM Only -->
    <div class="row mb-3">
        <div class="col-md-12">
            <h3 class="text-primary">Item IDM Only</h3>
            <table class="table table-bordered border border-dark">
                <thead>
                    <tr>
                        <th class="bg-primary-subtle">No</th>
                        <th class="bg-primary-subtle">PLU</th>
                        <th class="bg-primary-subtle">Nama Barang</th>
                        <th class="bg-primary-subtle">Unit</th>
                        <th class="bg-primary-subtle">Frac</th>
                        <th class="bg-primary-subtle">Tag</th>
                        <th class="bg-primary-subtle">Flag IDM</th>
                        <th class="bg-primary-subtle">Avg Cost</th>
                        <th class="bg-primary-subtle">Saldo AKhir</th>
                        <th class="bg-primary-subtle">PO Outstanding</th>
                        <th class="bg-primary-subtle">No PO</th>
                        <th class="bg-primary-subtle">Harga Beli</th>
                        <th class="bg-primary-subtle">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($idmonly)): ?>

                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Status Item IGR-IDM -->
    <div class="row mb-3">
        <div class="col-md-12">
            <h3 class="text-primary">Item IGR-IDM</h3>
            <table class="table table-bordered border border-dark">
                <thead>
                    <tr>
                        <th class="bg-primary-subtle">No</th>
                        <th class="bg-primary-subtle">PLU</th>
                        <th class="bg-primary-subtle">Nama Barang</th>
                        <th class="bg-primary-subtle">Unit</th>
                        <th class="bg-primary-subtle">Frac</th>
                        <th class="bg-primary-subtle">Tag</th>
                        <th class="bg-primary-subtle">Flag IDM</th>
                        <th class="bg-primary-subtle">Avg Cost</th>
                        <th class="bg-primary-subtle">Saldo AKhir</th>
                        <th class="bg-primary-subtle">PO Outstanding</th>
                        <th class="bg-primary-subtle">No PO</th>
                        <th class="bg-primary-subtle">Harga Beli</th>
                        <th class="bg-primary-subtle">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($igridm)): ?>

                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>