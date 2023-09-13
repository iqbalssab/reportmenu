<?php $this->extend('layout/template'); ?>
<?php $this->section('content'); ?>

<?php $now = date('Y-m-d'); ?>
<div class="container mt-3">
    <div class="card">
        <div class="card-header" style="background-color: #0040ff;">
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
                <form action="tampilsalesmember" method="post" target="_blank">
                    <input type="date" name="tglawal" id="tglawal" value="<?= old('tglawal')? old('tglawal') : $now ; ?>"> s/d <input type="date" name="tglakhir" id="tglakhir" value="<?= old('tglakhir')? old('tglakhir') : $now ; ?>">
                    <select class="form-select form-select-sm mt-2 border border-1 border-black w-50" name="jenislaporan" aria-label="Small select example">
                        <option value="tipeoutlet" selected>Sales Per Tipe Outlet</option>
                        <option value="member">Sales Per Member</option>
                        <option value="produk">Sales Per Produk</option>
                        <option value="bulan">Sales Per Bulan Member Tertentu</option>
                        <option value="struk">Sales Per Struk Member Tertentu</option>
                    </select>
                    <select class="form-select w-50 form-select-sm mt-2 border border-1 border-black" name="jenistransaksi" aria-label="Small select example">
                        <option value="all">Semua</option>
                        <option value="reguler">Reguler</option>
                        <option value="klik">Klik</option>
                    </select>
                    <br><br>
                    <select class="form-select w-25 form-select-sm border border-1 border-black d-inline" name="jenismember" aria-label="Small select example">
                        <option value="all">All Member</option>
                        <option value="nontmi">All Member Non TMI</option>
                        <option value="mm">Member Merah</option>
                        <option value="mmtmi">- Member Merah TMI</option>
                        <option value="mmnontmi">- Member Merah non TMI</option>
                        <option value="mmhoreka">- Member Merah Horeka</option>
                        <option value="mb">Member Biru</option>
                        <option value="mbend">- Member Biru End User</option>
                        <option value="mbomi">- Member OMI</option>
                    </select>

                    <select class="form-select form-select-sm border-1 border-black w-25 d-inline" name="outlet" id="outlet" aria-label="Small select example">
                        <?php $kodeoutlet = 0; ?>
                        <?php foreach($outlet as $o): ?>
                            <?php if($o['OUT_KODEOUTLET'] == $kodeoutlet): ?>
                                <option value="all"></option>
                                <option value="<?= $o['OUT_KODEOUTLET']; ?>"><?= $o['OUT_NAMAOUTLET']; ?></option>
                                <?php $kodeoutlet ++; ?>
                            <?php endif; ?>
                            <option value="<?= $o['OUT_KODEOUTLET'].$o['SUB_KODESUBOUTLET']; ?>"><?= $o['OUT_KODEOUTLET']. '-'. $o['SUB_KODESUBOUTLET'] . ' : '. $o['SUB_NAMASUBOUTLET']; ?></option>
                        <?php endforeach; ?>
                    </select>

                    <select class="form-select form-select-sm border-1 border-black w-25 d-inline" name="segmentasi" id="segmentasi" aria-label="Small select example">
                            <option value="0"></option>
                            <option value="1">1 Reguler</option>
                            <option value="2">2 Silver</option>
                            <option value="3">3 Gold 1</option>
                            <option value="4">4 Gold 2</option>
                            <option value="5">5 Gold 3</option>
                            <option value="6">6 Platinum</option>
                            <option value="7">7 Biru</option>
                            <option value="8">8 Biru Plus</option>
                    </select>

                    <input type="text" name="membertertentu" id="membertertentu" class="w-50 mt-3 border border-1 border-black rounded" placeholder="CONTOH = A4632,B3871,C91347">
                    <br><br>
                    <select class="form-select w-50 mt-4 form-select-sm border border-1 border-black" name="jenisproduk" aria-label="Small select example">
                        <option value="all">Semua Produk</option>
                        <option value="itempromo">Hanya Item Larangan</option>
                        <option value="itemnonpromo">Diluar Item Larangan</option>
                    </select>
                    <select class="form-select w-50 mt-3 form-select-sm border border-1 border-black" name="departement" id="departement" aria-label="Small select example">
                        <?php $divisi = 1; ?>
                        <?php foreach($departement as $dp): ?>
                            <?php if($dp['DEP_KODEDIVISI']==$divisi): ?>
                                <option value="all"></option>
                                <option value="<?= $dp['DEP_KODEDIVISI']; ?>"><?= $dp['DIV_NAMADIVISI']; ?></option>
                                <?php $divisi++; ?>
                            <?php endif; ?>
                            <option value="<?= $dp['DEP_KODEDIVISI'].$dp['DEP_KODEDEPARTEMENT']; ?>"><?= $dp['DEP_KODEDIVISI']." - ".$dp['DEP_KODEDEPARTEMENT']." : ". $dp['DEP_NAMADEPARTEMENT']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" name="supplier" id="supplier" class="w-25 mt-2 border border-1 border-black rounded">
                    <br>
                    <input type="text" name="plu" id="plu" class="w-25 mt-2 border border-1 border-black rounded" placeholder="CONTOH = 0000850, 0000240, 0060140, DLL">
                    <br><br>
                    <button type="submit" name="btn" value="tampil" class="btn text-light" style=" background-color: #6528F7;">Tampil</button>
                    <button type="submit" name="btn" value="xls" class="btn btn-success">Export XLS</button>
                    <button type="reset" name="btn" value="clear" class="btn btn-danger">Clear</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>