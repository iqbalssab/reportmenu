<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<?php $now = date('Y-m-d'); ?>
<div class="container-fluid mt-2">
    <div class="row">
        <div class="col-md-2">
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <h6 class="text-center fw-bold">Monitoring E-Faktur</h6>
                </div>
                <div class="card-body p-2">
                    <form action="efaktur" method="get">
                        <label for="tglawal" class="d-block fw-bold">Tanggal Awal</label>
                        <input type="date" name="tglawal" id="tglawal" class="w-100 d-block mb-2" value="<?= $now; ?>">
                        <label for="tglakhir" class="d-block fw-bold">Tanggal Akhir</label>
                        <input type="date" name="tglakhir" id="tglakhir" class="w-100 d-block mb-2" value="<?= $now; ?>">
                        <label for="kasir" class="d-block fw-bold">ID Kasir</label>
                        <input type="text" name="kasir" id="kasir" class="d-block w-25 mb-2">
                        <label for="kdmember" class="d-block fw-bold">Kode Member</label>
                        <input type="text" name="kdmember" id="kdmember" class="d-block w-50 mb-2">

                        <label for="status" class="fw-bold w-100">Status</label>
                        <select class="form-select" aria-label="Default select example" name="status">
                        <option value="bu" selected>Belum Upload</option>
                        <option value="suba">Sudah Upload, Belum Approve</option>
                        <option value="sa">Sudah Apporve</option>
                        </select>

                        <button type="submit" class="btn btn-primary w-100 fw-bold mt-3">Tampil</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-10">
            <?php if(!empty($monitor)): ?>
            <div class="card">
                <div class="card-header bg-success-subtle">
                    <h6 class="text-success fw-bold">Data E-Faktur Tanggal <?= $tglawal." - ".$tglakhir; ?> || Status : <?= $status; ?></h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-responsive table-hover">
                        <thead>
                            <tr>
                                <th rowspan="2" class="text-center">No.</th>
                                <th colspan="2" class="text-center">Faktur</th>
                                <th colspan="3" class="text-center">Kasir</th>
                                <th colspan="2" class="text-center">Member</th>
                                <th rowspan="2" class="text-center">Status</th>
                            </tr>
                            <tr>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Nomor Seri</th>

                                <th class="text-center">Stat</th>
                                <th class="text-center">ID</th>
                                <th class="text-center">Struk</th>

                                <th class="text-center">Kode</th>
                                <th class="text-center">Nama</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach($monitor as $m): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $m['FKT_TGLFAKTUR']; ?></td>
                                <td><?= $m['FKT_NOSERI']; ?></td>
                                <td><?= $m['FKT_STATION']; ?></td>
                                <td><?= $m['FKT_KASIR']; ?></td>
                                <td><?= $m['FKT_NOTRANSAKSI']; ?></td>
                                <td><?= $m['FKT_KODEMEMBER']; ?></td>
                                <td><?= $m['FKT_NAMAMEMBER']; ?></td>
                                <td><?= $m['FKT_STATUS']; ?></td>
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

<?= $this->endSection(); ?>