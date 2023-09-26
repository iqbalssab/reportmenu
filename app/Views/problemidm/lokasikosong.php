<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid mt-4">
    <!-- Jenis Rak Kosong -->
    <div class="row">
        <div class="col-md-12">
            <h3 class="text-primary">Jenis Rak Kosong</h3>
            <table class="table table-bordered border border-dark">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Rak</th>
                        <th>Subrak</th>
                        <th>Shelving</th>
                        <th>No Urut</th>
                        <th>PLU</th>
                        <th>Nama Barang</th>
                        <th>Unit</th>
                        <th>Frac</th>
                        <th>Tag</th>
                        <th>NoID</th>
                        <th>Qty</th>
                        <th>Max Plano</th>
                        <th>Jenis Rak</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($jenisrak)): ?>

                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Max Plano Kosong -->
    <div class="row">
        <div class="col-md-12">
            <h3 class="text-primary">Max Plano Kosong</h3>
            <table class="table table-bordered border border-dark">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Rak</th>
                        <th>Subrak</th>
                        <th>Shelving</th>
                        <th>No Urut</th>
                        <th>PLU</th>
                        <th>Nama Barang</th>
                        <th>Unit</th>
                        <th>Frac</th>
                        <th>Tag</th>
                        <th>NoID</th>
                        <th>Qty</th>
                        <th>Max Plano</th>
                        <th>Jenis Rak</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($maxplano)): ?>

                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Qty DPD Kosong -->
    <div class="row">
        <div class="col-md-12">
            <h3 class="text-primary">Qty DPD Kosong</h3>
            <table class="table table-bordered border border-dark">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Rak</th>
                        <th>Subrak</th>
                        <th>Shelving</th>
                        <th>No Urut</th>
                        <th>PLU</th>
                        <th>Nama Barang</th>
                        <th>Unit</th>
                        <th>Frac</th>
                        <th>Tag</th>
                        <th>NoID</th>
                        <th>Qty</th>
                        <th>Max Plano</th>
                        <th>Jenis Rak</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($dpdkosong)): ?>

                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>