<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
    <div class="container mt-3">
        <div class="card">
            <div class="card-header bg-primary text-light">
                <h5>Monitoring Promo IGR</h5>
            </div>
            <div class="card-body bg-secondary-subtle">
                <div class="row">
                    <div class="col-md-3 border-end border-3 border-danger">
                        <h6 class="d-block fw-bold mb-2">Pilihan Laporan</h6>
                        
                        <label for="jenisLaporan">Jenis Laporan</label>
                        <br>
                        <br>
                        <h6 class="mb-3">Pilihan Data Promo</h6>
                        <label class="mb-3" for="">a) Status Promo</label><br>
                        <label class="mb-3" for="">b) Tanggal Akhir Promo</label><br>
                        <label for="">c) Kode Promosi</label>

                    </div>
                    <div class="col-md-5 justify-content-md-start">
                    <form action="tampildatapromo" method="get" target="_blank">
                        <?= csrf_field(); ?>
                        <br>
                    <select class="form-select form-select-sm" name="jenisLaporan" aria-label="Small select example" required>
                    <option value="" selected>Jenis Laporan</option>
                    <option value="cb">Data Promo Cashback</option>
                    <option value="cbperplu">Data Promo Cashback per PLU</option>
                    <option value="perolehancb">Perolehan Promo Cashback</option>
                    <option value=""></option>
                    <option value="gift">Data Promo GIFT</option>
                    <option value="giftperplu">Data Promo GIFT per PLU</option>
                    <option value="perolehangift">Perolehan Promo GIFT</option>
                    <option value=""></option>
                    <option value="instore">Data Promo INSTORE</option>
                    <option value="instoreperplu">Data Promo INSTORE per PLU</option>
                    </select>
                        <br><br>

                    <select class="form-select form-select-sm mb-2" name="statusPromo" aria-label="Small select example" required>
                    <option value="" selected>Status Promo</option>
                    <option value="all">All</option>
                    <option value="aktif">Promo Aktif</option>
                    <option value="selesai">Sudah Selesai</option>
                    <option value="blmaktif">Belum Aktif</option>
                    </select>

                    <input class="mb-2" type="date" name="tglAkhir" id="tglAkhir" required><br>
                    <input class="mb-2" type="text" name="kdPromosi" id="kdPromosi">
                    <br>
                    <button type="submit" class="btn btn-primary" name="tombol" value="tampil">Tampil</button>
                    <button type="submit" class="btn btn-success" name="tombol" value="xls">Export xls</button>
                    <button type="reset" class="btn btn-danger" value="clear">Clear</button>
                    </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
<?= $this->endSection(); ?>