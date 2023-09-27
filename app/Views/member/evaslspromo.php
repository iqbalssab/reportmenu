<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3 class="fw-bold text-primary"><i class="fa-solid fa-archive text-danger me-1"></i> Evaluasi Sales Promosi</h3>
        </div>
        <div class="card-body">
            <form action="tampilevaslspromo" method="post" target="_blank">
                <div class="row">
                    <div class="col-md-3">
                        <!-- Tanggal Promosi -->
                        <div class="card">
                            <div class="card-header bg-success">
                                <p class="text-light">Periode Promosi</p>
                            </div>
                            <div class="card-body">
                                <label for="" class="d-block mb-2 fw-bold">Sebelum Promosi</label>
                                <label for="tglawal" class="d-block">Tanggal Awal</label>
                                <input type="date" name="tglawalbefore" id="tglawalbefore" class="w-100 form-control">
                                <label for="tglakhir" class="d-block">Tanggal Akhir</label>
                                <input type="date" name="tglakhirbefore" id="tglakhirbefore" class="w-100 form-control">
                                <br>
                                <label for="" class="d-block mt-2 mb-2 fw-bold">Tanggal Promosi</label>
                                <label for="tglawal" class="d-block">Tanggal Awal</label>
                                <input type="date" name="tglawal" id="tglawal" class="w-100 form-control">
                                <label for="tglakhir" class="d-block">Tanggal Akhir</label>
                                <input type="date" name="tglakhir" id="tglakhir" class="w-100 form-control">
                                <br>
                                <label for="" class="d-block mt-2 mb-2 fw-bold">Setelah Promosi</label>
                                <label for="tglawalafter" class="d-block">Tanggal Awal</label>
                                <input type="date" name="tglawalafter" id="tglawalafter" class="w-100 form-control">
                                <label for="tglakhirafter" class="d-block">Tanggal Akhir</label>
                                <input type="date" name="tglakhirafter" id="tglakhirafter" class="w-100 form-control">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <!-- Filter Member -->
                        <div class="card">
                            <div class="card-header bg-success">
                                <p class="text-light">Member</p>
                            </div>
                            <div class="card-body">
                                <input type="text" name="namamember" id="namamember" class="form-control w-100 mb-1" placeholder="Nama Member">
                                <input type="text" name="kodemember" id="kodemember" class="form-control w-100 mb-1" placeholder="Kode Member">
                                <input type="text" name="kodemonitoring" id="kodemonitoring" class="form-control w-100 mb-1" placeholder="Kode Monitoring Member">
                                <select class="form-select mb-1" name="jenismember" id="jenismember">
                                    <option value ="All">All Member</option>
                                    <option value ="MERAH">Member Merah</option>
                                    <option value ="BIRU">Member Biru</option>
                                    <option value ="MERAHBIRU">Member Merah+Biru</option>
                                    <option value ="OMI">Omi</option>
                                    <option value ="IDM">Indomaret</option>
                                    <option value ="KLIK">All Klik</option>
                                    <option value ="KLIKMERAH">Klik Merah</option>
                                    <option value ="KLIKBIRU">Klik Biru</option>
                                    <option value ="KLIKOTHER">Klik Other</option>
                                    <option value ="OMIHJK">Diluar HJK+OMI+IDM+UMUM KLIK</option>
                                    <option value ="IDMOMIHJK">Diluar HJK+OMI+IDM</option>
                                </select>
                                <select class="form-select w-100 mb-1" name="outlet" id="outlet">
                                    <option value="ALL">All Outlet</option>
                                    <?php foreach($outlet as $out): ?>
                                    <option value="<?= $out['OUT_KODEOUTLET']; ?>"><?= $out['OUT_KODEOUTLET']." - ".$out['OUT_NAMAOUTLET']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select name="subkategori" id="subkategori" class="form-select mb-1 w-100">
                                    <option value="ALL">All Subkategori(Horeka)</option>
                                    <?php foreach($subkategori as $sub): ?>
                                        <option value="<?= $sub['SUBKATEGORI']; ?>"><?= $sub['SUBKATEGORI']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="text" name="kodesub" id="kodesub" class="w-100 form-control mb-1" placeholder="Kode Sub Outlet">
                                <select name="jenismember" id="jenismember" class="mb-1 form-select w-100">
                                    <option value="">Jenis Member</option>
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
                            <div class="card-header bg-success">
                                <p class="text-light">Produk</p>
                            </div>
                            <div class="card-body">
                                <input type="text" name="namabarang" id="namabarang" class="form-control w-100 mb-1" placeholder="Nama Barang">
                                <input type="text" name="plu" id="plu" class="form-control w-100 mb-1" placeholder="PLU">
                                <input type="text" name="barcode" id="barcode" class="form-control w-100 mb-1" placeholder="BARCODE">
                                <input type="text" name="kodemonitoringplu" id="kodemonitoringplu" class="form-control w-100 mb-1" placeholder="Kode Monitoring PLU">
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
                                <select name="kodetag" id="kodetag" class="form-select w-100 mb-1">
                                    <option value="ALL">All Tag</option>
                                    <?php foreach($kodetag as $tag): ?>
                                        <option value="<?= $tag['TAG_KODE']; ?>"><?= $tag['TAG_KODE']." - ".$tag['TAG_KETERANGAN']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Filter Supplier -->
                    <div class="col-md-3">
                        <div class="card mb-3">
                            <div class="card-header bg-success">
                                <p class="text-light">Supplier</p>
                            </div>
                            <div class="card-body">
                                <input type="text" name="kodesupplier" id="kodesupplier" class="form-control w-100 mb-1" placeholder="Kode Supplier">
                                <input type="text" name="namasupplier" id="namasupplier" class="form-control w-100 mb-1" placeholder="Nama Supplier">
                                <input type="text" name="monitoringsupplier" id="monitoringsupplier" class="form-control w-100 mb-1" placeholder="Monitoring Supplier">
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header bg-primary">
                                <p class="text-light">Jenis Laporan</p>
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
            <button type="reset" class="btn btn-outline-secondary text-dark fw-bold border border-dark">Bersihkan</button>
            <button type="submit" class="btn btn-primary text-light fw-bold ms-2">Tampilkan Laporan</button>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>