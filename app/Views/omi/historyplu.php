<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<?php $now = date('Y-m-d'); ?>
<div class="container mt-3">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary">
                    <p class="text-light fw-bold">HISTORY PBOMI</p>
                </div>
                <div class="card-body p-2">
                    <form action="historyplu" method="get">
                        <label for="tglawal" class="d-block fw-bold">Dari Tanggal :</label>
                        <input type="date" name="tglawal" id="tglawal" class="w-100 mb-3" value="<?= old('tglawal') ? old('tglawal') : $now; ?>">
                        <label for="tglakhir" class="d-block fw-bold">Sampai Tanggal :</label>
                        <input type="date" name="tglakhir" id="tglakhir" class="w-100 mb-3" value="<?= old('tglakhir') ? old('tglakhir') : $now; ?>">
                        <label for="plu" class="d-block fw-bold">PLU :</label>
                        <input type="text" name="plu" id="plu" class="w-100 mb-3" value="<?= old('plu'); ?>">
                        <label for="kodeomi" class="d-block fw-bold">KODE OMI :</label>
                        <input type="text" name="kodeomi" id="kodeomi" class="w-100 mb-3" value="<?= old('kodeomi'); ?>">

                        <button type="submit" class="btn btn-success text-light w-100 mb-2"><i class="fa-solid fa-magnifying-glass-arrow-right me-1"></i>View</button>
                    </form>
                    <a href="cekprosespbomi" class="btn btn-dark text-light text-center"><i class="fa-solid fa-arrow-left"></i> Back</a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-primary-subtle">
                    <?php if($history): ?>
                    <p class="text-primary fw-bold">History per PLU - Tanggal <?= $tglawal." s/d ".$tglakhir; ?></p>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if(!empty($history)): ?>
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th class="bg-primary text-light text-center">No</th>
                                <th class="bg-primary text-light text-center">KodeOMI</th>
                                <th class="bg-primary text-light text-center">NamaOMI</th>
                                <th class="bg-primary text-light text-center">TglPB</th>
                                <th class="bg-primary text-light text-center">TglProses</th>
                                <th class="bg-primary text-light text-center">PLU</th>
                                <th class="bg-primary text-light text-center">Deskripsi</th>
                                <th class="bg-primary text-light text-center">QtyOrder</th>
                                <th class="bg-primary text-light text-center">QtyRealisasi</th>
                                <th class="bg-primary text-light text-center">QtyRefund</th>
                                <th class="bg-primary text-light text-center">QtySales</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach($history as $h): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $h['KODEOMI']; ?></td>
                                    <td><?= $h['NAMAOMI']; ?></td>
                                    <td><?= $h['TGLPB']; ?></td>
                                    <td><?= $h['TGLPROSES']; ?></td>
                                    <td><?= $h['PLUIGR']; ?></td>
                                    <td><?= $h['DESKRIPSI']; ?></td>
                                    <td><?= $h['QTYORDER']; ?></td>
                                    <td><?= $h['QTYREALISASI']; ?></td>
                                    <td><?= $h['QTYV']; ?></td>
                                    <td><?= $h['QTYSALES']; ?></td>
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