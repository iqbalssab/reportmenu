<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col">
            <h3 class="text-primary">Service Level IDM</h3>
            <table class="table table-bordered border border-dark">
                <thead>
                    <tr>
                        <th rowspan="2" class="bg-primary-subtle">No</th>
                        <th rowspan="2" class="bg-primary-subtle">Tanggal</th>
                        <th rowspan="2" class="bg-primary-subtle">Toko</th>

                        <th colspan="3" class="bg-primary-subtle">PB IDM</th>
                        <th colspan="3" class="bg-primary-subtle">TOLAKAN PB</th>

                        <th colspan="4" class="bg-primary-subtle">PB UPLOAD DPD</th>
                        <th colspan="4" class="bg-primary-subtle">DSPB</th>

                        <th colspan="3" class="bg-primary-subtle">PBIDM VS DSPB</th>
                        <th colspan="3" class="bg-primary-subtle">SL PICKING</th>

                        <th rowspan="2" class="bg-primary-subtle">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="bg-primary-subtle">Item</th>
                        <th class="bg-primary-subtle">Qty</th>
                        <th class="bg-primary-subtle">Rupiah</th>

                        <th class="bg-primary-subtle">Item</th>
                        <th class="bg-primary-subtle">Qty</th>
                        <th class="bg-primary-subtle">Rupiah</th>

                        <th class="bg-primary-subtle">Toko</th>
                        <th class="bg-primary-subtle">Item</th>
                        <th class="bg-primary-subtle">Qty</th>
                        <th class="bg-primary-subtle">Rupiah</th>

                        <th class="bg-primary-subtle">Item</th>
                        <th class="bg-primary-subtle">Qty</th>
                        <th class="bg-primary-subtle">Rupiah</th>

                        <th class="bg-primary-subtle">Item</th>
                        <th class="bg-primary-subtle">Qty</th>
                        <th class="bg-primary-subtle">Rupiah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($servicelevel)): ?>
                        
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>