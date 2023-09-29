<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header text-light" style="background-color: #0040ff;"">
            <h3 class="fw-bold my-auto"><i class="fa-solid fa-archive text-danger me-1"></i> Evaluasi Sales Promosi</h3>
        </div>
        <div class="card-body">
            <form action="tampilevaslspromo" method="get" target="_blank" role="form">
                <div class="row">
                    <div class="col-md-3">
                        <!-- Tanggal Promosi -->
                        <div class="card">
                            <div class="card-header bg-body-secondary">
                                <p class="fw-bold my-auto">Periode Promosi</p>
                            </div>
                            <div class="card-body">
                                <label for="" class="d-block mb-2 ms-2 fw-bold">Sebelum Promosi</label>
                                <input type="date" name="tglawalbefore" id="tglawalbefore" class="form-control ms-2" value="<?= old('tglawalbefore'); ?>" style="font-size: 14px; width: 210px;" required>
                                <input type="date" name="tglakhirbefore" id="tglakhirbefore" class="mb-3 form-control ms-2" value="<?= old('tglakhirbefore'); ?>" style="font-size: 14px; width: 210px;" required>
                                <label for="" class="d-block my-2 ms-2 fw-bold">Tanggal Promosi</label>
                                <input type="date" name="awal" id="awal" class="form-control ms-2" value="<?= old('awal'); ?>" style="font-size: 14px; width: 210px;" required>
                                <input type="date" name="akhir" id="akhir" class="mb-3 form-control ms-2" value="<?= old('akhir'); ?>" style="font-size: 14px; width: 210px;" required>
                                <label for="" class="d-block my-2 ms-2 fw-bold">Setelah Promosi</label>
                                <input type="date" name="tglawalafter" id="tglawalafter" class="form-control ms-2" value="<?= old('tglawalafter'); ?>" style="font-size: 14px; width: 210px;" required>
                                <input type="date" name="tglakhirafter" id="tglakhirafter" class="mb-3 form-control ms-2" value="<?= old('tglakhirafter'); ?>" style="font-size: 14px; width: 210px;" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <!-- Filter Member -->
                        <div class="card">
                            <div class="card-header bg-body-secondary">
                                <p class="fw-bold my-auto">Member</p>
                            </div>
                            <div class="card-body">
                                <input type="text" name="namamember" id="namamember" class="form-control w-100 mb-1" placeholder="Nama Member">
                                <input type="text" name="kodemember" id="kodemember" class="form-control w-100 mb-1" placeholder="Kode Member">
                                <select class="form-select mb-1" name="jenismember" id="jenismember">
                                    <option value ="All">All Member</option>
                                    <option value ="MERAH">Member Merah</option>
                                    <option value ="BIRU">Member Biru</option>
                                    <option value ="MERAHBIRU">Member Merah+Biru</option>
                                    <option value ="OMI">Omi</option>
                                    <option value ="IDM">Indomaret</option>
                                    <option value ="KLIK">All Klik</option>
                                    <option value ="KLIKMERAH">Klik Member Merah</option>
                                    <option value ="KLIKBIRU">Klik Member Biru</option>
                                    <option value ="OMIHJK">Diluar HJK+OMI+IDM+UMUM KLIK</option>
                                    <option value ="IDMOMIHJK">Diluar HJK+OMI+IDM</option>
                                </select>
                                <select class="form-select w-100 mb-1" name="outlet" id="outlet">
                                    <option value="ALL">All Outlet</option>
                                    <?php foreach($outlet as $out): ?>
                                    <option value="<?= $out['OUT_KODEOUTLET']; ?>"><?= $out['OUT_KODEOUTLET']." - ".$out['OUT_NAMAOUTLET']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select name="kodeSubOutlet" id="kodeSubOutlet" class="mb-1 form-select w-100">
                                    <option value="ALL">Jenis Member</option>
                                    <?php foreach($jenismember as $jm): ?>
                                    <option value="<?= $jm['JM_KODE']; ?>"><?= $jm['JM_KODE']." - ".$jm['JM_KETERANGAN']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Filter Produk -->
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-header bg-body-secondary">
                                <p class="fw-bold my-auto">Produk</p>
                            </div>
                            <div class="card-body">
                                <input type="text" name="namabarang" id="namabarang" class="form-control w-100 mb-1" placeholder="Nama Barang">
                                <input type="text" name="plu" id="plu" class="form-control w-100 mb-1" placeholder="PLU">
                                <input type="text" name="barcode" id="barcode" class="form-control w-100 mb-1" placeholder="Barcode">
                                <select name="divisi" id="divisi" class="form-select w-100 mb-1">
                                    <option value="ALL">All Divisi</option>
                                    <?php foreach($divisi as $div): ?>
                                    <option value="<?= $div['DIV_KODEDIVISI']; ?>"><?= $div['DIV_KODEDIVISI']." - ".$div['DIV_NAMADIVISI']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select name="departement" id="departement" class="form-select w-100 mb-1">
                                    <option value="ALL">All Departement</option>
                                    <?php $divisi = 1; ?>
                                    <?php foreach($departement as $dp): ?>
                                        <?php if($dp['DEP_KODEDIVISI']==$divisi): ?>
                                            <option value="all"></option>
                                            <optgroup label="<?= $dp['DEP_KODEDIVISI']." ".$dp['DEP_NAMADIVISI']; ?>"></optgroup>
                                            <?php $divisi++; ?>
                                        <?php endif; ?>
                                        <option value="<?= $dp['DEP_KODEDIVISI'].$dp['DEP_KODEDEPARTEMENT']; ?>"><?= $dp['DEP_KODEDIVISI']." - ".$dp['DEP_KODEDEPARTEMENT']." : ". $dp['DEP_NAMADEPARTEMENT']; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Filter Supplier -->
                    <div class="col-md-3">
                        <div class="card mb-3">
                            <div class="card-header bg-body-secondary">
                                <p class="fw-bold my-auto">Supplier</p>
                            </div>
                            <div class="card-body">
                                <input type="text" name="kodesupplier" id="kodesupplier" class="form-control w-100 mb-1" placeholder="Kode Supplier">
                                <input type="text" name="namasupplier" id="namasupplier" class="form-control w-100 mb-1" placeholder="Nama Supplier">
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header bg-body-secondary">
                                <p class="fw-bold my-auto">Jenis Laporan</p>
                            </div>
                            <div class="card-body">
                                <select name="jenislaporan" id="jenislaporan" class="w-100 form-select">
                                    <option value="1">1. Laporan per-Tipe Outlet</option>
                                    <option value="2">2. Laporan per-Member</option>
                                    <option value="3">3. Laporan per-Divisi</option>
                                    <option value="4">4. Laporan per-Departement</option>
                                    <option value="5">5. Laporan per-Produk</option>
                                    <option value="6">6. Laporan per-Supplier</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <div class="card-footer d-flex justify-content-end">
            <div class="row justify-content-end mb-1">
                    <button type="submit" name="tombol" value="btnview" class="text-light btn d-block fw-bold" style=" background-color: #6528F7; width: 200px;">Tampil</button>
                    <button type="submit" name="tombol" value="btnxls" class="text-light btn ms-2 me-3 d-block fw-bold" style="background-color: #00b300; width: 200px;">Download</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>