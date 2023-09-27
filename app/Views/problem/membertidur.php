<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <p class="fw-bold text-danger text-start text-center fs-3">Cek Member Tidur (MM)</p>
                </div>
                <div class="card-body">
                    <table class="table table-bordered border border-dark">
                        <thead>
                            <tr>
                                <th class="bg-info text-dark">No</th>
                                <th class="bg-info text-dark">Kode_Member</th>
                                <th class="bg-info text-dark">Nama_Member</th>
                                <th class="bg-info text-dark">Belanja_Terakhir</th>
                                <th class="bg-info text-dark">SISA_SALDO</th>
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
                                        <td>
                                            <?php if($mt['SISA_SALDO'] < 0): ?>
                                                0
                                            <?php else: ?>
                                                <?= number_format($mt['SISA_SALDO']); ?>
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