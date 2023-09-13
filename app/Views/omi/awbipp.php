<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<?php $now = date('Y-m-d'); ?>
<div class="container mt-3">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <p class="text-light fw-bold">AWB IPP</p>
                </div>
                <div class="card-body">
                    <form action="awbipp" method="get">
                    <label for="tgl" class="fw-bold d-block">TGL PROSES SOO :</label>
                    <input type="date" name="tgl" id="tgl" class="w-100 mb-3" value="<?= $now; ?>">

                    <button type="submit" name="view" value="view" class="w-100 btn btn-success text-light text-center mb-1"><i class="fa-solid fa-magnifying-glass"></i> View Data AWB</button>
                    </form>
                    <a href="cekprosespbomi" class="btn btn-dark text-light text-center"><i class="fa-solid fa-arrow-left"></i> Back</a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-primary-subtle">
                    <p class="text-primary fw-bold">Progress Realisasi PBOMI</p>
                </div>
                <div class="card-body">
                    <?php if(!empty($awb)): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="bg-primary text-light text-center">No</th>
                                <th class="bg-primary text-light text-center">KodeOMI</th>
                                <th class="bg-primary text-light text-center">NoPB</th>
                                <th class="bg-primary text-light text-center">TglPB</th>
                                <th class="bg-primary text-light text-center">NoDSPB</th>
                                <th class="bg-primary text-light text-center">Ongkir</th>
                                <th class="bg-primary text-light text-center">NoAWB</th>
                                <th class="bg-primary text-light text-center">Status</th>
                                <th class="bg-primary text-light text-center">Create_Dt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach($awb as $a): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $a['KODETOKO']; ?></td>
                                    <td><?= $a['NOPB']; ?></td>
                                    <td><?= $a['TGLPB']; ?></td>
                                    <td><?= $a['NODSPB']; ?></td>
                                    <td><?= $a['ONGKIR']; ?></td>
                                    <td><?= $a['NOAWB']; ?></td>
                                    <td><?= $a['STATUS']; ?></td>
                                    <td><?= $a['CREATE_DT']; ?></td>
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