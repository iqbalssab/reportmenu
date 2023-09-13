<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="container-fluid mt-3 overflow-auto">
    <div class="row justify-content-center">
        <div class="col-ms-auto">
            <div class="card w-60 mb-3 mx-auto" style="width: 1250px;">
                <div class="card-header text-light" style="background-color: #0040ff;">
                    <h5 class="text-start fw-bold">Informasi Produk</h5>
                </div>
                <div class="card-body">
                    <form method="get" action="/logistik/tampilinfoproduk" target="_blank" role="form" class="form-inline">
                        <fieldset>
                            <div class="row">
                                <!-- Kolom 1 -->
                                <div class="col-sm-3 mx-auto">
                                    <div class="card mb-1 ms-1" style="width: 280px;">
                                        <div class="card-header fw-bold">Status</div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label class="fw-bold">Satuan Jual</label>
                                                <select class="form-select form-select-sm mb-3 mx-auto" name="satuanJual" id="satuanJual" style="font-size: 14px; width: 240px;">
                                                    <option value="All">All</option>
                                                    <option value="0">Satuan Jual 0</option>
                                                </select>
                                                <label class="fw-bold">Status Tag</label>
                                                <select class="form-select form-select-sm mx-auto" name="statusTag" id="statusTag" style="font-size: 14px; width: 240px;">
                                                    <option value="All">All</option>
                                                    <option value="Active">Tag Aktif</option>
                                                    <option value="Discontinue">Tag Discontinue</option>
                                                </select>
                                                <p style="font-size: 12px; color: red;"><em>Discontinue: ARNHOTX</em></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-1 ms-1" style="width: 280px;">
                                        <div class="card-header fw-bold">Pilihan</div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <label><input type="checkbox" name="itemOMI"> Item OMI saja</label><br>
                                                    <label><input type="checkbox" name="discount2"> Ada Discount 2</label><br>
                                                    <label><input type="checkbox" name="poOutstanding"> Ada PO Outstansding</label><br>
                                                </div>
                                                <label><input type="checkbox" name="promoMD"> Ada Promo MD :</label><br>
                                                <select class="form-select form-select-sm mx-auto" name="tanggalPromosi" id="tanggalPromosi" style="font-size: 14px; width: 240px;">
                                                    <option value="All">All</option>
                                                        <?php foreach($promoMD as $md) : ?>
                                                            <option value="<?= $md['PRMD_TANGGAL']; ?>"><?= $md['PRMD_TGLAWAL']; ?> s/d <?= $md['PRMD_TGLAKHIR']; ?>
                                                    </option>
                                                        <?php endforeach ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Kolom 2 -->
                                <div class="col-sm-3 mx-auto">
                                    <div class="card mb-3 ms-1" style="width: 280px;">
                                        <div class="card-header fw-bold">Divisi</div>
                                        <div class="card-body">
                                            <label class="fw-bold">Divisi</label>
                                            <select class="form-select form-select-sm mb-3 mx-auto" name="divisi" id="divisi" style="font-size: 14px; width: 240px;">
                                                <option value="All">All Divisi</option>
                                                <?php foreach($divisi as $dv) : ?>
                                                    <option value="<?= $dv['DIV_KODEDIVISI']; ?>"><?= $dv['DIV_KODEDIVISI']; ?> <?= $dv['DIV_NAMADIVISI'];?></option>
                                                <?php endforeach ?>
                                            </select>
                                            <label class="fw-bold">Departemen</label>
                                            <select class="form-select form-select-sm mb-3 mx-auto" name="dep" id="dep" style="font-size: 14px; width: 240px;">
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
                                            <select class="form-select form-select-sm mb-3 mx-auto" name="kat" id="kat" style="font-size: 14px; width: 240px;">
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
                                    </div>
                                </div>
                                <!-- Kolom 3 -->
                                <div class="col-sm-3 mx-auto">
                                    <div class="card mb-3 ms-1" style="width: 280px;">
                                        <div class="card-header fw-bold">Masalah</div>
                                        <div class="card-body">
                                            <div class="checkbox">
                                                <label><input type="checkbox" name="hargaJualNol"> Harga Jual Belum Ada </label><br>
                                                <label><input type="checkbox" name="promoMahal"> Harga Promo Lebih Mahal</label><br>
                                                <label><input type="checkbox" name="stockKosong"> Stok Minus</label><br>
                                                <label><input type="checkbox" name="lokasiTidakAda"> Master Lokasi Belum Ada</label><br>
                                            </div>
                                            <label><input type="checkbox" name="marginNegatif"> Margin Negatif :</label>
                                            <select class="form-select form-select-sm mb-3 mx-auto" name="jenisMarginNegatif" id="jenisMarginNegatif" style="font-size: 14px; width: 240px;">
                                                <option value="All">All Kategori</option>
                                                <option value="1">by Average Cost</option>
                                                <option value="2">by Last Cost</option>
                                                <option value="3">by Harga Beli</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- Kolom 3 -->
                                <div class="col-sm-3 mx-auto">
                                    <div class="card mb-3 ms-1" style="width: 280px;">
                                        <div class="card-header fw-bold">Jenis Laporan</div>
                                        <div class="card-body">
                                            <select class="form-select form-select-sm mb-3 mx-auto" name="jenisLaporan" id="jenisLaporan" style="font-size: 14px; width: 240px;">
                                                <option value="1A">Laporan per Produk</option>
                                                <option value="1B">Laporan per Produk IC</option>
                                                <option value="1C">Laporan Barkos</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row justify-content-end mt-3">
                                <button type="submit" name="tombol" value="btnview" class="text-light btn d-block fw-bold" style=" background-color: #6528F7; width: 150px;">Tampil</button>
                                <button type="submit" name="tombol" value="btnxls" class="text-light btn ms-2 me-3 d-block fw-bold" style="background-color: #00b300; width: 150px;">Download</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>