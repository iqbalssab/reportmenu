<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary">
                    <h6 class="text-light fw-bold">Promo Per Rak</h6>
                </div>
                <div class="card-body">
                    <form action="promoperrak" method="get">
                        <label for="rak" class="d-block">RAK</label>
                        <select name="rak" id="rak" class="form-select mb-2">
                            <option value="all">ALL RAK</option>
                            <?php foreach($rak as $r): ?>
                            <option value="<?= $r['LKS_KODERAK']; ?>"><?= $r['LKS_KODERAK']; ?></option>
                            <?php endforeach; ?>
                        </select>
                            <label for="jenis" class="d-block">Jenis Laporan</label>
                            <select name="jenis" id="jenis" class="form-select mb-2">
                                <option value="cb">Promo Cashback</option>
                                <option value="gift">Promo Gift</option>
                            </select>
                        <button type="submit" name="btn" value="submit" class="btn btn-primary w-100">Tampil</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-primary-subtle">
                    <h6 class="fw-bold text-primary">Data Promo Per Rak : <?= $koderak; ?></h6>
                </div>
                <div class="card-body">
                    <?php if(!empty($cbrak)): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="bg-primary text-light">No</th>
                                <th class="bg-primary text-light">PLU</th>
                                <th class="bg-primary text-light">Deskripsi</th>
                                <th class="bg-primary text-light">Promosi</th>
                                <th class="bg-primary text-light">Tgl-Awal</th>
                                <th class="bg-primary text-light">Tgl-Akhir</th>
                                <th class="bg-primary text-light">Lokasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach($cbrak as $cr): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $cr['PLU']; ?></td>
                                    <td><?= $cr['DESK']; ?></td>
                                    <td><?= $cr['PROMO']; ?></td>
                                    <td><?= $cr['TGLAWAL']; ?></td>
                                    <td><?= $cr['TGLAKHIR']; ?></td>
                                    <td><?= $cr['LOK']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php elseif(!empty($giftrak)): ?>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>PLU</th>
                                <th>DESKRIPSI</th>
                                <th>PROMOSI</th>
                                <th>TGLAWAL</th>
                                <th>TGLAKHIR</th>
                                <th>LOKASI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach($giftrak as $gr): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $gr['PLU']; ?></td>
                                    <td><?= $gr['DESK']; ?></td>
                                    <td><?= $gr['PROMO']; ?></td>
                                    <td><?= $gr['TGLAWAL']; ?></td>
                                    <td><?= $gr['TGLAKHIR']; ?></td>
                                    <td><?= $gr['LOK']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>