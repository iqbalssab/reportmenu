<?php $this->extend('layout/template'); ?>
<?php $this->section('content'); ?>

<div class="container mt-3">
    <div class="row d-flex justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary">
                    <h6 class="text-center text-light fw-bold">STOK PER DEPARTEMENT</h6>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold d-block">Tampil Data Per-Departement</h6>
                    <form action="stokdep/tampilstokdep" method="post" class="mb-3" target="_blank">
                        <label for="tgl">Tanggal Proses Picking PB OMI</label>
                        <input type="date" name="tgl" id="tgl" class="form-control w-25 d-block border-1 border-black">
                        <label class="d-block" for="departement">Pilih Departement :</label>
                        <select name="departement" class="form-select border-1 border-black" aria-label="Default select example">
                            <?php $kodedivisi = 1; ?>
                            <?php foreach($dep as $d): ?>
                                <?php if($d['DEP_KODEDIVISI']==$kodedivisi): ?>
                                    <option value="all"></option>
                                    <option value="<?= $d['DEP_KODEDIVISI']; ?>"><?= $d['DIV_NAMADIVISI']; ?></option>
                                    <?php $kodedivisi++; ?>
                                <?php endif; ?>
                                    <option value="<?= $d['DEP_KODEDIVISI'].$d['DEP_KODEDEPARTEMENT']; ?>"><?= $d['DEP_KODEDIVISI']." - ".$d['DEP_KODEDEPARTEMENT']." : ".$d['DEP_NAMADEPARTEMENT']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label for="supplier">atau Input Kode Supplier :</label>
                        <input type="text" name="supplier" id="supplier" class="d-block w-25 m-1">
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary me-2" name="btn" value="tampil">Tampil</button>
                        <button type="submit" class="btn btn-success me-2" name="btn" value="xls">Export XLS</button>
                        <button type="reset" class="btn btn-danger" >Reset</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>