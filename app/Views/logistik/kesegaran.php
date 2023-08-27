<?php $this->extend('layout/template'); ?>
<?php $this->section('content'); ?>

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-2">
            <div class="card mb-3">
                <div class="card-header bg-primary">
                    <h6 class="text-center text-light fw-bold">Cek Data Kesegaran Produk</h6>
                </div>
                <div class="card-body">
                    <form action="kesegaran" method="post">
                        <label for="plu" class="text-center fw-bold">Input PLU</label>
                        <input type="text" name="plu" id="plu" class="w-100 m-1 text-center">
                        <button type="submit" class="btn btn-primary w-100 m-1"><i class="fa-solid fa-magnifying-glass"></i>View</button>
                    </form>
                </div>
            </div>
            <div class="card mt-2">
                <div class="card-header bg-secondary-subtle">
                    <p class="fw-bold">Cari Produk :</p>
                </div>
                <div class="card-body">
                    <form action="" method="get">
                        <label for="desk1">Deskripsi1</label>
                        <input type="text" name="desk1" id="desk1" class="d-block w-100 m-1">
                        <label for="desk2">Deskripsi2</label>
                        <input type="text" name="desk2" id="desk2" class="d-block w-100 m-1">
                        <button type="submit" class="btn btn-outline-success w-100 m-1"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md 10">
            <?php if(!empty($kesegaran)): ?>
                <div class="card">
                    <div class="card-header bg-success-subtle">
                        <h6 class="text-success fw-bold">Informasi Data Kesegaran Produk</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive">
                            <?php foreach($kesegaran as $ks): ?>
                            <tr>
                                <td>PLU - DESKRIPSI</td>
                                <td><?= $ks['PRD_PRDCD']." - ".$ks['PRD_DESKRIPSIPANJANG']; ?></td>
                            </tr>
                            <tr>
                                <td>FLAG Kesegaran</td>
                                <td><?= $ks['BTR_FLAG_KESEGARAN']; ?></td>
                            </tr>
                            <tr>
                                <td>Umur Barang</td>
                                <td><?= $ks['BTR_UMUR_BRG']." ".$ks['BTR_SAT_UMUR_BRG']; ?></td>
                            </tr>
                            <tr>
                                <td>Umur Bisa Diterima</td>
                                <td><?= $ks['UBR_MAX_UMUR_BRG_DCI']." ".$ks['UBR_MAX_UMUR_BRG_DCI_S']; ?></td>
                            </tr>
                            <tr>
                                <td>SLP Terakhir</td>
                                <td><?= $ks['SLP_TERAKHIR']; ?></td>
                            </tr>
                            <tr>
                                <td>EXPDATE Terakhir</td>
                                <td><?= $ks['EXPDATE_TERAKHIR']; ?></td>
                            </tr>
                            <tr>
                                <td>EXPDATE Minimal Bisa Diterima</td>
                                <td><?= $ks['MINIMAL_DITERIMA']; ?></td>
                            </tr>
                            <tr>
                                <td>EXPDATE Maksimal Bisa Diterima</td>
                                <td><?= $ks['MAKSIMAL_DITERIMA']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
            <?php if(!empty($cariproduk) && (!empty($desk1) || !empty($desk2))): ?>
                <div class="card">
                    <div class="card-header bg-primary">
                        <h6 class="text-light fw-bold">Hasil Pencarian dengan Keyword : <?= $desk1." + ".$desk2; ?></h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>PLU</th>
                                    <th>DESKRIPSI</th>
                                    <th>UNIT</th>
                                    <th>TAG</th>
                                    <th>H.JUAL</th>
                                    <th>STOK</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                <?php foreach($cariproduk as $cp): ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $cp['PRD_PRDCD']; ?></td>
                                    <td><?= $cp['PRD_DESKRIPSIPANJANG']; ?></td>
                                    <td><?= $cp['PRD_UNIT']; ?></td>
                                    <td><?= $cp['PRD_KODETAG']; ?></td>
                                    <td><?= number_format($cp['PRD_HRGJUAL']); ?></td>
                                    <td><?= number_format($cp['ST_SALDOAKHIR']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php $this->endSection(); ?>