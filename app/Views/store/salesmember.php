<?php $this->extend('layout/template'); ?>
<?php $this->section('content'); ?>

<div class="container mt-3">
    <div class="card">
        <div class="card-header bg-primary">
            <h5 class="text-center text-light fw-bolder">EVALUASI SALES MEMBER</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2 justify-content-md-start">
                    <p class="fw-bold">Periode Tanggal</p>
                    <p class="fw-bold">Jenis Laporan</p>
                    <p class="fw-bold">Jenis Transaksi</p>
                    <p class="fw-bold">Pilihan Member</p>
                    <p class="fw-medium">- Kriteria Member</p>
                    <p class="fw-medium">- Member Tertentu*</p>
                    <p class="fw-bold">Pilihan Produk</p>
                    <p class="fw-medium">- Jenis Produk</p>
                    <p class="fw-medium">- Departement</p>
                    <p class="fw-medium">- Supplier</p>
                    <p class="fw-medium">- PLU tertentu *</p>
                </div>
                <div class="col-md-10 justify-content-md-start">
                <form action="/store/salesmember/tampildatasales" method="post">
                    <input type="date" name="tglawal" id="tglawal"> s/d <input type="date" name="tglakhir" id="tglakhir">
                    <select class="form-select form-select-sm mt-2 border border-1 border-black" name="jenislaporan" aria-label="Small select example">
                        <option value="tipeoutlet" selected>Sales Per Tipe Outlet</option>
                        <option value="member">Sales Per Member</option>
                        <option value="produk">Sales Per Produk</option>
                        <option value="bulan">Sales Per Bulan Member Tertentu</option>
                        <option value="struk">Sales Per Struk Member Tertentu</option>
                    </select>
                    <select class="form-select w-50 form-select-sm mt-2 border border-1 border-black" name="jenistransaksi" aria-label="Small select example">
                        <option value="">A</option>
                        <option value="">B</option>
                        <option value="">C</option>
                    </select>
                    <br><br>
                    <select class="form-select w-50 form-select-sm border border-1 border-black" name="jenismember" aria-label="Small select example">
                        <option value=""></option>
                        <option value="mm">Member Merah</option>
                        <option value="mb">Member Biru</option>
                    </select>
                    <input type="text" name="membertertentu" id="membertertentu" class="w-50 mt-2 border border-1 border-black rounded" placeholder="CONTOH = A4632,B3871,C91347">
                    <br><br>
                    <select class="form-select w-50 mt-4 form-select-sm border border-1 border-black" name="jenisproduk" aria-label="Small select example">
                        <option value="all">Semua Produk</option>
                        <option value="1">1</option>
                        <option value="2">2 Produk</option>
                    </select>
                    <select class="form-select w-50 mt-3 form-select-sm border border-1 border-black" name="departement" aria-label="Small select example">
                        <option value="all"></option>
                        <option value="food">FOOD</option>
                        <option value="nonfood">NON-FOOD</option>
                        <option value="gms">General Merchandising</option>
                    </select>
                    <input type="text" name="supplier" id="supplier" class="w-25 mt-2 border border-1 border-black rounded">
                    <br>
                    <input type="text" name="plu" id="plu" class="w-25 mt-3 border border-1 border-black rounded" placeholder="CONTOH = 0000850, 0000240, 0060140, DLL">
                    <br><br>
                    <button type="submit" name="btn" value="tampil" class="btn btn-primary">Tampil</button>
                    <button type="submit" name="btn" value="xls" class="btn btn-success">Export XLS</button>
                    <button type="reset" name="btn" value="clear" class="btn btn-danger">Clear</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>