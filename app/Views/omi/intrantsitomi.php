<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="container-fluid mt-3 overflow-auto">
    <div class="row justify-content-center">
        <div class="col-ms-auto">
            <div class="card w-60 mb-3 mx-auto" style="width: 900px;">
                <div class="card-header text-light" style="background-color: #0040ff;">
                    <h4 class="text-start fw-bold fs-5">Intransit OMI</small></h4>
                </div>
                <div class="card-body">
                    <form method="get" action="tampilintrantsitomi" target="_blank" role="form" class="form-inline">
                        <div class="row">
                            <!-- Kolom 1 -->
                            <div class="col-md-3" style="margin-left: 0.5px;">
                                <div class="card mb-1 ms-1" style="width: 280px;">
                                    <div class="card-header fw-bold" style="font-size: 16px;">Pilih Toko</div>
                                    <div class="card-body">
                                        <label class="fw-bold" style="font-size: 14px;">Toko OMI</label>
                                        <select class="form-select form-select-sm mb-3 mx-auto" name="tokoOmi" id="tokoOmi" style="font-size: 14px; width: 240px;">
                                            <option value="All">All</option>
                                                <?php foreach($tokoOmi as $omi) : ?>
                                                    <option value="<?= $omi['TKO_KODEOMI']; ?>"><?= $omi['TKO_KODEOMI'].' '.$omi['TKO_NAMAOMI']; ?></option>
                                                <?php endforeach ?>
                                        </select>
                                        <label class="fw-bold" style="font-size: 14px;">Toko IDM</label>
                                        <select class="form-select form-select-sm mb-3 mx-auto" name="tokoIdm" id="tokoIdm" style="font-size: 14px; width: 240px;">
                                            <option value="All">All</option>
                                                <?php foreach($tokoIdm as $idm) : ?>
                                                    <option value="<?= $idm['TKO_KODEOMI']; ?>"><?= $idm['TKO_KODEOMI'].' '.$idm['TKO_NAMAOMI']; ?></option>
                                                <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- Kolom 2 -->
                            <div class="col-md-3" style="margin-left: 70px;">
                                <div class="card mb-1 ms-1" style="width: 280px;">
                                    <div class="card-header fw-bold" style="font-size: 16px;">Produk & Supplier</div>
                                    <div class="card-body">
                                        <input type="number" name="plu" id="plu" class="w-100 mb-1 form-control input-sm" value="<?= old('plu'); ?>" placeholder="PLU" style="font-size: 14px;">
                                        <input type="text" name="kdsup" id="kdsup" class="w-100 mb-1 form-control input-sm" value="<?= old('kdsup'); ?>" placeholder="Kode Supplier" style="font-size: 14px;">
                                        <input type="text" name="nmsup" id="nmsup" class="w-100 mb-1 form-control input-sm" value="<?= old('nmsup'); ?>" placeholder="Nama Supplier" style="font-size: 14px;">
                                        <select class="form-select form-select-sm mb-2 mx-auto" name="divisi" id="divisi" style="font-size: 14px; width: 240px;">
                                            <option value="All">All Divisi</option>
                                            <?php foreach($divisi as $dv) : ?>
                                                <option value="<?= $dv['DIV_KODEDIVISI']; ?>"><?= $dv['DIV_KODEDIVISI']; ?> <?= $dv['DIV_NAMADIVISI'];?></option>
                                            <?php endforeach ?>
                                        </select>
                                        <select class="form-select form-select-sm mb-1 mx-auto" name="dep" id="dep" style="font-size: 14px; width: 240px;">
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
                                        <select class="form-select form-select-sm mb-2 mx-auto" name="kat" id="kat" style="font-size: 14px; width: 240px;">
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
                            <div class="col-md-3" style="margin-left: 70px;">
                                <div class="card mb-1 ms-1" style="width: 280px;">
                                    <div class="card-header fw-bold" style="font-size: 16px;">Jenis Laporan</div>
                                    <div class="card-body">
                                    <select class="form-select form-select-sm mb-3 mx-auto" name="jenisLaporan" id="jenisLaporan" style="font-size: 14px; width: 240px;">
                                        <option value="1">1. per Toko OMI</option>
                                        <option value="2">2. per Tanggal</option>
                                        <option value="3">3. per Tanggal per OMI</option>
                                        <option value="4">4. per Produk</option>
                                        <option value="5">5. per Divisi</option>
                                        <option value="5B">5B. per Departemen</option>
                                        <option value="5C">5C. per Kategori</option>
                                        <option value="6">6. per Supplier</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row justify-content-end mb-1">
                            <button type="submit" name="tombol" value="btnview" class="text-light btn d-block fw-bold" style=" background-color: #6528F7; width: 200px;">Tampil</button>
                            <button type="submit" name="tombol" value="btnxls" class="text-light btn ms-2 me-3 d-block fw-bold" style="background-color: #00b300; width: 200px;">Download</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>