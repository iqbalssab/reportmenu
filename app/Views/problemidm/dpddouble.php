<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h4 class="text-primary fw-bold">DPD Double Rekap</h4>
            <table class="table table-bordered border border-dark">
                <thead>
                    <tr>
                        <th class="bg-primary-subtle">No</th>
                        <th class="bg-primary-subtle">PLU</th>
                        <th class="bg-primary-subtle">Nama Barang</th>
                        <th class="bg-primary-subtle">Unit</th>
                        <th class="bg-primary-subtle">Frac</th>
                        <th class="bg-primary-subtle">Tag</th>
                        <th class="bg-primary-subtle">Jumlah_lokasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($doublerekap)): ?>
                        <?php $no = 1; ?>
                        <?php foreach($doublerekap as $dr): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $dr['LKS_PRDCD']; ?></td>
                                <td><?= $dr['LKS_NAMA_BARANG']; ?></td>
                                <td><?= $dr['LKS_UNIT']; ?></td>
                                <td><?= $dr['LKS_FRAC']; ?></td>
                                <td><?= $dr['LKS_TAG']; ?></td>
                                <td><?= $dr['LKS_JUMLAH_LOKASI']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-danger text-center fw-bold" role="alert">Data Kosong !</div>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h4 class="text-primary fw-bold mb-1">DPD Double Detail</h4>
            <table class="table table-bordered border border-dark">
                <thead>
                    <tr>
                        <th class="bg-success-subtle">No</th>
                        <th class="bg-success-subtle">PLU</th>
                        <th class="bg-success-subtle">Nama Barang</th>
                        <th class="bg-success-subtle">Unit</th>
                        <th class="bg-success-subtle">Frac</th>
                        <th class="bg-success-subtle">Tag</th>
                        <th class="bg-success-subtle">Rak</th>
                        <th class="bg-success-subtle">Subrak</th>
                        <th class="bg-success-subtle">Tipe</th>
                        <th class="bg-success-subtle">Shelving</th>
                        <th class="bg-success-subtle">No_Urut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($doubledetail)): ?>
                        <?php $no = 1; ?>
                        <?php foreach($doubledetail as $dd): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $dd['LKS_PRDCD']; ?></td>
                                <td><?= $dd['LKS_NAMA_BARANG']; ?></td>
                                <td><?= $dd['LKS_UNIT']; ?></td>
                                <td><?= $dd['LKS_FRAC']; ?></td>
                                <td><?= $dd['LKS_TAG']; ?></td>
                                <td><?= $dd['LKS_KODERAK']; ?></td>
                                <td><?= $dd['LKS_KODESUBRAK']; ?></td>
                                <td><?= $dd['LKS_TIPERAK']; ?></td>
                                <td><?= $dd['LKS_SHELVINGRAK']; ?></td>
                                <td><?= $dd['LKS_NOURUT']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-danger text-center fw-bold" role="alert">Data Kosong !</div>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?= $this->endSection(); ?>