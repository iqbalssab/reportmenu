<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<?php $now = date('Y-m-d'); ?>
<div class="container">
    <div class="row mt-3 d-flex justify-content-center">
        <div class="col-md-6">
            <div class="card p-3 border-2 border-secondary">
                <form action="monitoringpbomi/tampilmonitoringomi" method="post" target="_blank">
                <?php csrf_field(); ?>
                <h5 class="fw-bold d-block">Monitoring PB OMI</h5>
                <label for="kodeomi" class="d-block">Kode OMI</label>
                <input type="text" name="kodeomi" id="kodeomi" class="w-25">
                <label for="tglawal" class="d-block">Tanggal Proses PB :</label>
                <input type="date" name="tglawal" id="tglawal" class="w-50 d-inline" value="<?= $now; ?>"> s/d <input type="date" name="tglakhir" id="tglakhir" class="w-50 d-inline" value="<?= $now; ?>">
                <label for="jenis" class="d-block">Jenis Laporan :</label>
                <select class="form-select w-50 mb-3" aria-label="Default select example" name="jenis">
                    <option value="rekap" selected>Rekap Order</option>
                    <option value="list">List Order (PB Upload)</option>
                    <option value="tolakan">Tolakan PB</option>
                    <option value="picking">Item Picking Non DPD</option>
                    <option value="masteritem">Master Item PB OMI (Data H-1)</option>
                    <option value="masterpb">Master PB OMI (Data H-1)</option>
                </select>
                <button type="submit" name="btn" value="tampil" class="btn btn-primary fw-bold w-25 mb-2 d-inline text-light">Tampil</button>
                <button type="submit" name="btn" value="xls" class="btn btn-success fw-bold w-25 mb-2 d-inline text-light">Export XLS</button>
                <button type="reset" name="btn" class="btn btn-danger fw-bold w-25 d-inline text-light">Clear</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>