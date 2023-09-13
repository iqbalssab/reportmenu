<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary">
                    <p class="text-light fw-bold">MONITORING PERTEMANAN</p>
                </div>
                <div class="card-body">
                    <form action="pertemanan" method="get">
                <label class="mt-1 d-block" for="departement">Pilih Departement :</label>
                            <select class="form-select w-100 form-select-sm border border-1 border-dark mb-3" name="departement" id="departement" aria-label="Small select example">
                                <?php $divisi = 1; ?>
                                    <option value="all" <?php old('departement')=="all" ? "selected" : ""; ?>>ALL</option>
                                <?php foreach($departement as $dp): ?>
                                    <?php if($dp['DEP_KODEDIVISI']==$divisi): ?>
                                        <option value="all" <?php old('departement')=="all" ? "selected" : ""; ?>></option>
                                        <option value="<?= $dp['DEP_KODEDIVISI']; ?>" <?php old('departement') ? "selected" : ""; ?>><?= $dp['DIV_NAMADIVISI']; ?></option>
                                        <?php $divisi++; ?>
                                    <?php endif; ?>
                                    <option value="<?= $dp['DEP_KODEDEPARTEMENT']; ?>"><?= $dp['DEP_KODEDIVISI']." - ".$dp['DEP_KODEDEPARTEMENT']." : ". $dp['DEP_NAMADEPARTEMENT']; ?></option>
                                <?php endforeach; ?>
                            </select>
                <label for="status" class="d-block">Status</label>
                <select class="form-select w-50 form-select-sm border border-1 border-dark mb-3" name="status" id="status" aria-label="Small select example">  
                    <option value="all">ALL</option>
                    <option value="igr">IGR-ONLY</option>
                    <option value="omi">OMI-ONLY</option>
                    <option value="igromi">IGR-OMI</option>
                </select>
                <label for="plu" class="d-block">PLU</label>    
                <input type="text" name="plu" id="plu" class="w-50"> 
                <button type="submit" name="btn" value="tampil" class="btn btn-primary w-100 mt-4"><i class="fa-solid fa-magnifying-glass me-1"></i> Tampil</button>
                <button type="submit" name="btn" value="reset" class="btn btn-danger w-100 mt-1"><i class="fa-solid fa-rotate-right me-1"></i> Reset</button>
                </form>                 
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-primary-subtle">
                    <p class="fw-bold text-primary">Daftar Pertemanan</p>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="bg-primary text-light text-center">DIV</th>
                                <th class="bg-primary text-light text-center">DEP</th>
                                <th class="bg-primary text-light text-center">KAT</th>
                                <th class="bg-primary text-light text-center">PLUIGR</th>
                                <th class="bg-primary text-light text-center">DESKRIPSI</th>
                                <th class="bg-primary text-light text-center">Pertemanan</th>
                                <th class="bg-primary text-light text-center">Palet</th>
                                <th class="bg-primary text-light text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($pertemanan)): ?>
                                <?php foreach($pertemanan as $pt): ?>
                                    <tr>
                                        <td><?= $pt['DIV']; ?></td>
                                        <td><?= $pt['DEP']; ?></td>
                                        <td><?= $pt['KAT']; ?></td>
                                        <td><?= $pt['PLU']; ?></td>
                                        <td><?= $pt['DESK']; ?></td>
                                        <td><?= $pt['PERTEMANAN']; ?></td>
                                        <td><?= $pt['PALET']; ?></td>
                                        <td><?= $pt['STATUS']; ?></td>
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