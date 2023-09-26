<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary">
                    <p class="fw-bold text-light">Cek Member Tidur</p>
                </div>
                <div class="card-body">
                    <form action="membertidur" method="get">
                        <label for="tglakhir" class="fw-bold d-block">Periode Belanja Terakhir</label>
                        <input type="date" name="tglakhir" id="tglakhir" class="w-100 input-group mb-2">
                        <label for="periode1" class="fw-bold d-block">Periode Sales 1</label>
                        <input type="month" name="periode1" id="periode1" class="w-100 input-group mb-2">
                        <label for="periode2" class="fw-bold d-block">Periode Sales 2</label>
                        <input type="month" name="periode2" id="periode2" class="w-100 input-group mb-2">
                        <label for="periode3" class="fw-bold d-block">Periode Sales 3</label>
                        <input type="month" name="periode3" id="periode3" class="w-100 input-group mb-2">
                        <br>
                        <button type="submit" name="btn" value="tampil" class="btn btn-primary w-100">Tampil</button>
                    </form>

                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <p class="fw-bold text-primary text-start">Cek Member Tidur(MM)</p>
                </div>
                <div class="card-body">
                    <table class="table table-bordered border border-dark">
                        <thead>
                            <tr>
                                <th class="bg-info text-dark">No</th>
                                <th class="bg-info text-dark">Kode_Member</th>
                                <th class="bg-info text-dark">Nama_Member</th>
                                <th class="bg-info text-dark">Tgl_Terakhir</th>
                                <th class="bg-info text-dark">AVG_KUNJ_3BLN</th>
                                <th class="bg-info text-dark">AVG_SALES_3BLN</th>
                                <th class="bg-info text-dark">AVG_MARGIN_3BLN</th>
                                <th class="bg-info text-dark">SISA SALDO</th>
                                <th class="bg-info text-dark">Alamat_Member</th>
                                <th class="bg-info text-dark">No_HP</th>
                                <th class="bg-info text-dark">No_KTP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($membertidur)): ?>
                                <?php $no = 1; ?>
                                <?php foreach($membertidur as $mt): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $mt['KODEMEMBER']; ?></td>
                                        <td><?= $mt['NAMAMEMBER']; ?></td>
                                        <td><?= $mt['TGL_AKHIR']; ?></td>
                                        <td><?= number_format($mt['AVG_KUNJ']); ?></td>
                                        <td><?= number_format($mt['AVG_SALES']); ?></td>
                                        <td><?= number_format($mt['AVG_MARGIN']); ?></td>
                                        <td>
                                            <?php if($mt['SISA_SALDO'] < 0): ?>
                                                0
                                            <?php else: ?>
                                                <?= $mt['SISA_SALDO']; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $mt['ALAMAT4']." - ".$mt['ALAMAT2']; ?></td>
                                        <td><?= $mt['HPMEMBER']; ?></td>
                                        <td><?= $mt['KTP']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>