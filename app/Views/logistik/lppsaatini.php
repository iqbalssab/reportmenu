<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="container-fluid mt-3">
    <div class="row justify-content-center">
        <div class="col-ms-auto">
            <div class="card w-60 mb-3 mx-auto" style="width: 900px;">
                <div class="card-header text-light" style="background-color: #0040ff;">
                    <h5 class="text-start fw-bold">LPP Saat Ini</h5>
                </div>
                <div class="card-body">
                    <form method="get" action="/logistik/tampillpp" target="_blank" role="form" class="form-inline">
                        <div class="row">
                            <div class="col-sm-4">
                                <label class="fw-bold">Lokasi Stock</label>
                                <select class="form-select form-select-sm mb-3" name="stok" id="stok" style="font-size: 13px; width: 250px;">
                                    <option value="01">Barang Baik</option>
                                    <option value="02">Barang Retur</option>
                                    <option value="03">Barang Rusak</option>
                                </select>
                                <label class="fw-bold">Group Sales</label>
                                <select class="form-select form-select-sm mb-3" name="grup" id="grup" style="font-size: 13px; width: 250px;" disabled>
                                    <option value="All">IGR + IDM + OMI</option>
                                    <option value="IGR-OMI">IGR + OMI</option>
                                    <option value="IGR-ONLY">IGR Only</option>
                                    <option value="IDM-ONLY">IDM Only</option>
                                    <option value="OMI-ONLY">OMI Only</option>
                                </select>
                                <label class="fw-bold">Status Tag</label>
                                <select class="form-select form-select-sm" name="statustag" id="statustag" style="font-size: 13px; width: 250px;">
                                    <option value="All">All</option>
                                    <option value="Active">Tag Aktif</option>
                                    <option value="Discontinue">Tag Discontinue</option>
                                </select>
                                <p style="font-size: 12px; color: red;"><em>Discontinue: ARNHOTX</em></p>
                                <label class="fw-bold">Status Qty</label>
                                <select class="form-select form-select-sm mb-3" name="statusqty" id="statusqty" style="font-size: 13px; width: 250px;">
                                    <option value="All">All</option>
                                    <option value="1">Qty Minus</option>
                                    <option value="2">Qty Kosong</option>
                                    <option value="3">Qty Ada</option>
                                    <option value="4">Qty Dibawah DSI 3 Hari</option>
                                    <option value="5">Qty Dibawah PKM</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label class="fw-bold">Divisi</label>
                                <select class="form-select form-select-sm mb-3" name="divisi" id="divisi" style="font-size: 13px; width: 250px;">
                                    <option value="All">All Divisi</option>
                                    <?php foreach($divisi as $dv) : ?>
                                        <option value="<?= $dv['DIV_KODEDIVISI']; ?>"><?= $dv['DIV_KODEDIVISI']; ?> <?= $dv['DIV_NAMADIVISI'];?></option>
                                    <?php endforeach ?>
                                </select>
                                <label class="fw-bold">Departemen</label>
                                <select class="form-select form-select-sm mb-3" name="dep" id="dep" style="font-size: 13px; width: 250px;">
                                    <option value="All">All Departemen</option>
                                    <?php $namaDivisi = "0. Tidak diketahui"; ?>
                                    <?php foreach($departemen as $dp) : ?>
                                        <?php 
                                            if($namaDivisi <> $dp['DEP_KODEDIVISI']. ' '.$dp['DIV_NAMADIVISI']) {
                                                if($namaDivisi <> "0. Tidak diketahui")
                                                    {echo '</optgroup>' ;}
                                                    echo '<optgroup label="' . $dp['DEP_KODEDIVISI'] . ' ' . $dp['DIV_NAMADIVISI'] .'">';
                                                    $namaDivisi = $dp['DEP_KODEDIVISI'] . ' ' . $dp['DIV_NAMADIVISI'];
                                            }
                                            echo '<option value="' . $dp['DEP_KODEDEPARTEMENT'] . '">' . $dp['DEP_KODEDEPARTEMENT'] . ' ' . $dp['DEP_NAMADEPARTEMENT'] . '</option>';
                                        ?>
                                    <?php endforeach ?>
                                    </optgroup>
                                </select>
                                <label class="fw-bold">Kategori</label>
                                <select class="form-select form-select-sm mb-3" name="kat" id="kat" style="font-size: 13px; width: 250px;">
                                    <option value="All">All Kategori</option>
                                    <?php $namaDepartemen = "0. Tidak diketahui"; ?>
                                    <?php foreach($kategori as $kt) : ?>
                                        <?php 
                                            if($namaDepartemen <> $kt['KAT_KODEDEPARTEMENT']. ' '.$kt['KAT_NAMADEPARTEMENT']) {
                                                if($namaDepartemen <> "0. Tidak diketahui")
                                                    {echo '</optgroup>' ;}
                                                    echo '<optgroup label="' . $kt['KAT_KODEDEPARTEMENT'] . ' ' . $kt['KAT_NAMADEPARTEMENT'] .'">';
                                                    $namaDepartemen = $kt['KAT_KODEDEPARTEMENT'] . ' ' . $kt['KAT_NAMADEPARTEMENT'];
                                            }
                                            echo '<option value="' . $kt['KAT_KODEDEPARTEMENT'] . $kt['KAT_KODEKATEGORI'] .'">' . $kt['KAT_KODEDEPARTEMENT'] . ' - ' . $kt['KAT_KODEKATEGORI'] . ' ' . $kt['KAT_NAMAKATEGORI'] . '</option>';
                                        ?>
                                    <?php endforeach ?>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label class="fw-bold">Jenis Laporan</label>
                                <select class="form-select form-select-sm mb-3" name="lap" id="lap" style="font-size: 13px; width: 250px;">
                                    <option value="1">1. Laporan per Divisi</option>
                                    <option value="2">2. Laporan per Departemen</option>
                                    <option value="3">3. Laporan per Kategori</option>
                                    <option value="4">4. Laporan per Produk</option>
                                    <option value="4B">4B. Laporan Produk Diskon 2</option>
                                    <option value="5">5. Laporan per Supplier</option>
                                    <option value="6">6. Laporan per Kode Tag</option>
                                    <option value="7">7. Laporan per Group Sales</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="row justify-content-end mt-3">
                            <button type="submit" name="tombol" value="btnview" class="text-light btn d-block fw-bold" style=" background-color: #6528F7; width: 150px;">Tampil</button>
                            <button type="submit" name="tombol" value="btnxls" class="text-light btn ms-2 me-3 d-block fw-bold" style="background-color: #00b300; width: 150px;">Download</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>