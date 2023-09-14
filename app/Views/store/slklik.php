<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container mt-3">
    <div class="row d-flex justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary">
                    <h6 class="text-light text-center">SERVICE LEVEL KLIK</h6>
                </div>
                <div class="card-body">
                    <fieldset class="p-2">
                        <div class="row">
                            <div class="col-md-4">
                                <p class="fw-bold d-block">Periode Tanggal</p>
                                
                                <p class="fw-bold d-block">Jenis Laporan</p>
                                
                                <p class="fw-bold d-block">-Kode Member</p>
                            </div>
                            <div class="col">
                                <form action="slklik/tampilslklik" method="post" target="_blank">
                                <input type="date" name="tglawal" id="tglawal" style="width: 45%;">
                                s/d 
                                <input type="date" name="tglakhir" id="tglakhir" style="width: 45%;" class="mb-1">
                                <select class="form-select border border-secondary mb-1" name="jenis" aria-label="Default select example">
                                <option value="rekapnopb" selected>Monitoring PB KLIK</option>
                                <option value="detailplu">Monitoring Per Produk</option>
                                </select>
                                <input type="text" name="kdmember" id="kdmember" class="mb-3 d-block">
                                <button type="submit" name="btn" value="tampil" class="btn btn-primary me-1"><i class="fa-solid fa-file me-1"></i>Tampil</button>
                                <button type="submit" name="btn" value="xls" class="btn btn-success me-1"><i class="fa-solid fa-download me-1"></i>Export XLS</button>
                                </form>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="bg-warning-subtle border border-secondary mt-2">
                        <p class="text-center text-danger fw-bold">WARNING!!</p>
                        <p class="text-danger text-center">Gunakan sumber daya dengan bijak. Untuk meminimalisir kesalahan sistem hindari penarikan banyak data pada jam-jam sibuk!</p>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>