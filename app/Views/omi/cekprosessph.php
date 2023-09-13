<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<?php $now = date('Y-m-d'); ?>
<div class="container mt-3">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary">
                    <p class="text-light fw-bold">Detail SPH :</p>
                </div>
                <div class="card-body">
                    <form action="cekprosessph" method="get">
                        <label for="tgl" class="fw-bold d-block">TGL PROSES :</label>
                        <input type="date" name="tgl" id="tgl" class="w-100 mb-3" value="<?= old('tgl') ? old('tgl') : $now; ?>">

                        <button type="submit" name="btn" value="ceksph" class="btn mb-1 fw-bold text-dark btn-outline-warning w-100"><i class="fa-solid fa-check-circle me-1"></i>Cek Selisih SPH</button>
                        <button type="submit" name="btn" value="blmstruk" class="btn fw-bold text-light btn-success w-100"><i class="fa-solid fa-magnifying-glass me-1"></i>Cek DSP Belum DiStruk</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-primary-subtle">
                    <?php if($ceksph): ?>
                        <p class="text-primary fw-bold">Data Hasil Proses SPH</p>
                        <?php elseif($blmstruk): ?>
                        <p class="text-primary fw-bold">Data DSP Belum Distruk</p>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if(!empty($ceksph)): ?>
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th class="bg-success text-light text-center">No</th>
                                    <th class="bg-success text-light text-center">KdOMI</th>
                                    <th class="bg-success text-light text-center">KdMember</th>
                                    <th class="bg-success text-light text-center">NoPB</th>
                                    <th class="bg-success text-light text-center">NoSPH</th>
                                    <th class="bg-success text-light text-center">TOP</th>
                                    <th class="bg-success text-light text-center">TglSPH</th>
                                    <th class="bg-success text-light text-center">TglSales</th>
                                    <th class="bg-success text-light text-center">TglJT</th>
                                    <th class="bg-success text-light text-center">NilaiSales</th>
                                    <th class="bg-success text-light text-center">NilaiSPH</th>
                                    <th class="bg-success text-light text-center">Selisih</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach($ceksph as $cek): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $cek['KODEOMI']; ?></td>
                                        <td><?= $cek['KODEMEMBER']; ?></td>
                                        <td><?= $cek['NOMORPB']; ?></td>
                                        <td><?= $cek['NOSPH']; ?></td>
                                        <td><?= $cek['TOP']; ?></td>
                                        <td><?= $cek['TRPT_SPH_TGL']; ?></td>
                                        <td><?= $cek['TRPT_SALESINVOICEDATE']; ?></td>
                                        <td><?= $cek['TRPT_SALESDUEDATE']; ?></td>
                                        <td><?= $cek['NILAISALES']; ?></td>
                                        <td><?= $cek['NILAISPH']; ?></td>
                                        <td><?= $cek['SELISIH']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                    <?php if(!empty($blmstruk)): ?>
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th class="bg-primary text-light text-center">No</th>
                                    <th class="bg-primary text-light text-center">KodeOMI</th>
                                    <th class="bg-primary text-light text-center">NamaOMI</th>
                                    <th class="bg-primary text-light text-center">NoPB</th>
                                    <th class="bg-primary text-light text-center">Tgl DSP</th>
                                    <th class="bg-primary text-light text-center">Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach($blmstruk as $blm): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $blm['KODEOMI']; ?></td>
                                        <td><?= $blm['NAMAOMI']; ?></td>
                                        <td><?= $blm['NOPB']; ?></td>
                                        <td><?= $blm['TGL_DSP']; ?></td>
                                        <td>Rp. <?= number_format($blm['NILAI']); ?></td>
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