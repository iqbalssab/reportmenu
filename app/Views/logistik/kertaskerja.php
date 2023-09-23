<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="container-fluid mt-3 overflow-auto">
    <div class="row justify-content-center">
        <div class="col-ms-auto">
            <div class="card w-60 mb-3 mx-auto" style="width: 960px;">
                <div class="card-header text-light" style="background-color: #0040ff;">
                    <h4 class="text-start fw-bold fs-5">Kertas Kerja Storage Kecil</h4>
                </div>
                <div class="card-body">
                    <form method="get" action="tampilkk" target="_blank" role="form" class="form-inline">
                        <fieldset>
                            <div class="row">
                                <!-- Kolom 1 -->
                                <div class="col-md-3 ms-1">
                                    <div class="card mb-1 ms-1" style="width: 280px;">
                                        <div class="card-header fw-bold" style="font-size: 16px;">Divisi & Status</div>
                                        <div class="card-body">
                                            <label class="fw-bold" style="font-size: 14px;">Divisi</label>
                                            <select class="form-select form-select-sm mb-3 mx-auto" name="divisi" id="divisi" style="font-size: 14px; width: 240px;">
                                                <option value="All">All Divisi</option>
                                                <?php foreach($divisi as $dv) : ?>
                                                    <option value="<?= $dv['DIV_KODEDIVISI']; ?>"><?= $dv['DIV_KODEDIVISI']; ?> <?= $dv['DIV_NAMADIVISI'];?></option>
                                                <?php endforeach ?>
                                            </select>
                                            <label class="fw-bold" style="font-size: 14px;">Departemen</label>
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
                                            <label class="fw-bold" style="font-size: 14px;">Kategori</label>
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
                                        <hr>
                                        <div class="form-group ms-3">
                                            <label class="fw-bold" style="font-size: 14px;">Status Tag</label>
                                            <select class="form-select form-select-sm mx-auto" name="statusTag" id="statusTag" style="font-size: 14px; width: 240px;">
                                                <option value="All">All</option>
                                                <option value="Active">Tag Aktif</option>
                                                <option value="Discontinue">Tag Discontinue</option>
                                            </select>
                                            <p style="font-size: 14px; color: red;"><em>Discontinue: ARNHOTX</em></p>
                                        </div>
                                        </div>
                                    </div>
                                    <!-- Kolom 2 -->
                                    <div class="col-md-3" style="margin-left: 80px;">
                                        <div class="card mb-1 ms-1" style="width: 280px;">
                                            <div class="card-header fw-bold" style="font-size: 16px;">Informasi Rak</div>
                                            <div class="card-body">
                                                <label class="fw-bold" style="font-size: 14px;">Lokasi</label>
                                                <select class="form-select form-select-sm mb-3 mx-auto" name="kodeRak" id="kodeRak" style="font-size: 14px; width: 240px;">
                                                    <?php foreach($kdrak as $rak) : ?>
                                                        <option value="<?= $rak['LKS_KODERAK']; ?>"><?=$rak['LKS_KODERAK']; ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                                <div class="form-group">
                                                    <label class="fw-bold" style="font-size: 14px;">Row Storage Besar</label>
                                                    <input type="number" class="form-control" name="rowStorageBesar" id="rowStorageBesar" value="132" >
                                                </div>
                                                <div class="form-group">
                                                    <label class="fw-bold" style="font-size: 14px;">Row Storage Kecil</label>
                                                    <input type="number" class="form-control" name="rowStorageKecil" id="rowStorageKecil" value="125" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Kolom 3 -->
                                    <div class="col-md-3" style="margin-left: 80px;">
                                        <div class="card mb-1 ms-1" style="width: 280px;">
                                            <div class="card-header fw-bold" style="font-size: 16px;">Evaluasi</div>
                                            <div class="card-body">
                                                <div class="checkbox">
                                                    <label><input type="checkbox" name="itemOmiDiSk"> Item OMI di Storage Kecil</label><br>
                                                    <label><input type="checkbox" name="pkmLebihKecilDariMaxPallet"> PKM lebih kecil dari Max Pallet</label><br>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mb-1 ms-1" style="width: 280px;">
                                            <div class="card-header fw-bold" style="font-size: 16px;">Jenis Laporan</div>
                                            <div class="card-body">
                                                <select class="form-select form-select-sm mb-3 mx-auto" name="jenisLaporan" id="jenisLaporan" style="font-size: 14px; width: 240px;">
                                                    <option value="1">Laporan per Produk</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>                                
                            </div>
                            <hr>
                            <div class="row justify-content-end mb-3">
                                <button type="submit" name="tombol" value="btnview" class="text-light btn d-block fw-bold" style=" background-color: #6528F7; width: 200px;">Tampil</button>
                                <button type="submit" name="tombol" value="btnxls" class="text-light btn ms-2 me-3 d-block fw-bold" style="background-color: #00b300; width: 200px;">Download</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>