<?php $this->extend('layout/template'); ?>
<?php $this->section('content'); ?>

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary">
                    <h6 class="text-center text-light fw-bold">MONITORING MEMBER TIDUR</h6>
                </div>
                <div class="card-body">
                    <form action="membertidur" method="get">
                        <label for="jenismember">Jenis member</label>
                        <select class="form-select" name="jenismember" id="jenismember">
                            <option value="mm">MEMBER MERAH</option>
                            <option value="mb">MEMBER BIRU</option>
                            <option value="all">ALL MEMBER</option>
                        </select>
                        <button type="submit" name="btn" value="tampil" class="btn btn-primary w-100 mt-2">TAMPIL</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-success-subtle">
                    <h6 class="text-success fw-bold">Member Tidur <?= $jenis; ?></h6>
                </div>
                
                <div class="card-body">
                    <table class="table table-hover table-responsive">
                        <thead>
                            <tr>
                                <th class="bg-primary text-light">KODEMEMBER</th>
                                <th class="bg-primary text-light">NAMA MEMBER</th>
                                <th class="bg-primary text-light">ALAMAT</th>
                                <th class="bg-primary text-light">NO HP</th>
                                <th class="bg-primary text-light">NO. KTP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($tidur as $td): ?>
                                <tr>
                                    <td><?= $td['KODEMEMBER']; ?></td>
                                    <td><?= $td['NAMAMEMBER']; ?></td>
                                    <td><?= $td['ALAMAT1'].", ".$td['ALAMAT2'].$td['ALAMAT3'].", ".$td['ALAMAT4']; ?></td>
                                    <td><?= $td['HPMEMBER']; ?></td>
                                    <td><?= $td['KTP']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>