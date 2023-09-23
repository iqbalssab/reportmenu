<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-2">
            <div class="card">
                <div class="card-header bg-primary">
                    <p class="text-light text-center fw-bold fs-5">Storage Null</p>
                </div>
                <div class="card-body">
                    <form action="storagenull" method="get">
                        <label for="storage" class="d-block fw-bold">Jenis Storage</label>
                        <select name="storage" id="storage" class="w-100 form-select mb-3">
                            <option value="sb">Storage Besar Null</option>
                            <option value="sk">Storage Kecil Null</option>
                            <option value="sc">Storage Campur Null</option>
                        </select>
                        <button type="submit" name="btn" value="tampil" class="btn btn-primary w-100 text-light"><i class="fa-solid fa-eye me-1"></i>Tampil</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary">
                    <p class="text-light fw-bold">Lokasi Storage : <?= $judulstorage; ?></p>
                </div>
            </div>
            <?php if(!empty($querystorage)): ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="bg-primary-subtle text-dark">RAK</th>
                            <th class="bg-primary-subtle text-dark">JENIS</th>
                            <th class="bg-primary-subtle text-dark">PLU</th>
                            <th class="bg-primary-subtle text-dark">DESKRIPSI</th>
                            <th class="bg-primary-subtle text-dark">FRAC</th>
                            <th class="bg-primary-subtle text-dark">QTY</th>
                            <th class="bg-primary-subtle text-dark">EXPDATE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($querystorage as $qs): ?>
                            <tr>
                                <td><?= $qs['RAK']; ?></td>
                                <td><?= $qs['LKS_JENISRAK']; ?></td>
                                <td><?= $qs['LKS_PRDCD']; ?></td>
                                <td><?= $qs['PRD_DESKRIPSIPANJANG']; ?></td>
                                <td><?= $qs['FRAC']; ?></td>
                                <td><?= $qs['LKS_QTY']; ?></td>
                                <td><?= $qs['LKS_EXPDATE']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>