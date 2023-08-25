<?php $this->extend('layout/template'); ?>
<?php $this->section('content'); ?>
<?php $now = date('Y-m-d'); ?>

<div class="container mt-3">
    <div class="row d-flex justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <h6 class="fw-bold text-center">Monitoring Kompetisi Kasir</h6>
                </div>
                <div class="card-body p-3">
                    <form action="<?= base_url(); ?>store/kompetisikasir/tampilkompkasir" method="post" target="_blank">
                        <label for="plu">PLU Item Fokus :</label> <input class="w-50 mb-3" type="text" name="plu" id="plu" placeholder="CONTOH : 0000850,0010460,10024587"> 
                        <br>
                        <label class="mb-3 me-1" for="">Periode Sales :</label> <input type="date" name="tglawal" id="tglawal" class="w-25 ms-3" value="<?= $now; ?>"> s/d <input type="date" name="tglakhir" id="tglakhir" class="w-25" value="<?= $now; ?>">
                        <br>
                        <label for="jenismember">Jenis Member :</label> 
                        <select class="mb-3 w-50 ms-3 form form-select-sm" name="jenismember" id="jenismember">
                            <option value="all">ALL MEMBER</option>
                            <option value="mm">MEMBER MERAH</option>
                            <option value="mb">MEMBER BIRU</option>
                        </select>
                        <br>
                        <label for="minstruk">Min Struk(PCS)</label>
                        <input class="ms-2 mb-4" type="number" value="0" name="minstruk" id="minstruk">
                        <button type="submit" name="btn" value="viewrinci" class="btn btn-primary w-100 rounded mb-1 fw-semibold">Tampil Rincian Struk ALL vs Item Fokus</button>
                        <button type="submit" name="btn" value="viewrekapkasir" class="btn btn-danger w-100 rounded mb-1 fw-semibold">Tampil Rekap Per-Kasir</button>
                        <button type="submit" name="btn" value="viewrinciankasir" class="btn btn-warning w-100 rounded mb-1 text-light fw-semibold">Tampil Rincian Per-Kasir</button>
                        <button type="submit" name="btn" value="viewdatamember" class="btn btn-success w-100 rounded mb-1 fw-semibold">Tampil Member yang Belanja</button>
                        <button type="submit" name="btn" value="viewglobal" class="btn btn-info w-100 rounded mb-1 text-light fw-semibold">Tampil Rekap Per-PLU</button>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>

<?php $this->endSection(); ?>