<?php $this->extend('layout/template'); ?>
<?php $this->section('content'); ?>
<?php $now = date('Y-m-d'); ?>

<div class="container mt-3">
    <div class="row d-flex justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center text-light pt-2" style="background-color: #0040ff;">
                    <h6 class="fw-bold">MONITORING SALES PER DEPARTEMENT</h6>
                    <p class="text-warning"><i>** Menggunakan data H-1 **</i></p>
                </div>
                <div class="card-body p-2">
                    <form action="tampilsalesperdep" method="post" target="_blank">
                        <fieldset class="p-2 border border-dark-subtle">
                            <legend class="fw-bold fs-5">Tampil Data per-Departement</legend>
                            <label class="d-block" for="">Periode Sales :</label>
                            <input type="date" name="tglawal" id="tglawal" class="w-25" value="<?= $now; ?>"> s/d <input type="date" name="tglakhir" id="tglakhir" class="w-25" value="<?= $now; ?>">
                            <label class="mt-3 d-block" for="departement">Pilih Departement :</label>
                            
                            <select class="form-select w-50 form-select-sm border border-1 border-black" name="departement" id="departement" aria-label="Small select example">
                                <?php $divisi = 1; ?>
                                <?php foreach($departement as $dp): ?>
                                    <?php if($dp['DEP_KODEDIVISI']==$divisi): ?>
                                        <option value="all"></option>
                                        <option value="<?= $dp['DEP_KODEDIVISI']; ?>"><?= $dp['DIV_NAMADIVISI']; ?></option>
                                        <?php $divisi++; ?>
                                    <?php endif; ?>
                                    <option value="<?= $dp['DEP_KODEDEPARTEMENT']; ?>"><?= $dp['DEP_KODEDIVISI']." - ".$dp['DEP_KODEDEPARTEMENT']." : ". $dp['DEP_NAMADEPARTEMENT']; ?></option>
                                <?php endforeach; ?>
                            </select>

                            <label class="mt-2 d-block" for="jenislap">Pilih Jenis laporan</label>
                            <select class="form-select w-50 form-select-sm border border-1 border-black" name="jenislap" id="jenislap">
                            <option value='01'>01 - Sales Qty IGR + OMI</option>
                            <option value='02'>02 - Sales Qty IGR Only</option>
                            <option value='03'>03 - Sales Qty IGR to OMI</option>
                            <option value='04'>04 - Sales Nett IGR + OMI</option>
                            <option value='05'>05 - Sales Nett IGR Only</option>
                            <option value='06'>06 - Sales Nett IGR to OMI</option>
                            <option value='07'>07 - Margin IGR + OMI</option>
                            <option value='08'>08 - Margin IGR Only</option>
                            <option value='09'>09 - Margin IGR to OMI</option>
                            </select>

                            <button type="submit" class="btn text-light mt-3" name="btn" value="tampil" style=" background-color: #6528F7;">Tampil</button>
                            <button type="submit" class="btn btn-success text-light mt-3" name="btn" value="xls">Export XLS</button>
                            <button type="reset" class="btn btn-danger mt-3" value="reset">Reset</button>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row d-flex justify-content-center">
        <div class="col-md-6">
            <fieldset class="bg-danger-subtle border border-dark-subtle mt-2 text-center">
                <p class="fw-semibold text-danger">WARNING!</p>
                <p class="fw-lighter text-danger">Gunakan Sumber daya sebaik mungkin, waspadalah..</p>
            </fieldset>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>