<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid mt-3 overflow-auto">
    <div class="row">
        <div class="col-md-2">
            <div class="card">
                <div class="card-header bg-primary">
                    <h4 class="fw-bold text-light text-start fs-5">Plano Minus</h4>
                </div>
                <div class="card-body">
                    <form action="planominus" method="get">
                        <label for="plano" class="d-block fw-bold">Plano</label>
                        <select name="plano" id="plano" class="form-select mb-3" aria-label="Default select example">
                            <option selected value="all">ALL PLANO</option>
                            <option value="toko">Toko</option>
                            <option value="gudang">Gudang</option>
                        </select>
                        <label for="jenis" class="fw-bold d-block">Jenis Report</label>
                        <select name="jenis" id="jenis" class="form-select mb-3" aria-label="Default select example">
                            <option selected value="1">1. Lokasi Plano</option>
                        </select>
                        <button type="submit" name="btn" value="tampil" class="btn btn-primary w-100 text-light"><i class="fa-solid fa-magnifying-glass"></i> Tampil</button>
                    </form>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card" style="width: 1050px;">
                <div class="card-header bg-primary-subtle" style=" width: 1040px;">
                    <p class="fw-bold fs-5">Plano Minus Berdasarkan Lokasi</p> 
                </div>
                <div class="card-body">
                    <?php if(!empty($planominus)): ?>
                        <table class="table table-bordered table-striped table-responsive mx-auto" style="font-size: 14px; width: 1010px;">
                            <thead>
                                <tr>
                                    <th class="fw-bold text-light bg-primary">No</th>
                                    <th class="fw-bold text-light bg-primary">RAK</th>
                                    <th class="fw-bold text-light bg-primary">SUB</th>
                                    <th class="fw-bold text-light bg-primary">TIPE</th>
                                    <th class="fw-bold text-light bg-primary">SHELV</th>
                                    <th class="fw-bold text-light bg-primary">PLU</th>
                                    <th class="fw-bold text-light bg-primary">DESKRIPSI</th>
                                    <th class="fw-bold text-light bg-primary">QTYPLANO</th>
                                    <th class="fw-bold text-light bg-primary">JENIS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach($planominus as $pm): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $pm['RAK']; ?></td>
                                        <td><?= $pm['SUB']; ?></td>
                                        <td><?= $pm['TIPE']; ?></td>
                                        <td><?= $pm['SHELV']; ?></td>
                                        <td><?= $pm['PLU']; ?></td>
                                        <td><?= $pm['DESK']; ?></td>
                                        <td><?= $pm['QTYPLANO']; ?></td>
                                        <td><?= $pm['JENIS']; ?></td>
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