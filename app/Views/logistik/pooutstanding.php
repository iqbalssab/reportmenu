<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="container-fluid mt-3 overflow-auto">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card w-60 mb-3 mx-auto" style="width: 480px;">
                <div class="card-header text-light" style="background-color: #0040ff;">
                    <h5 class="text-start fw-bold">Data PO Outstanding</h5>
                </div>
                <div class="card-body">
                    <form method="get" action="tampilpooutstanding" target="_blank" role="form" class="form-inline">
                        <?= csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-3" style="margin-left: 20px;">
                                <label class="fw-bold mb-3" style="font-size: 16px;">Divisi</label><br>
                                <label class="fw-bold mb-3" style="font-size: 16px;">Departemen</label><br>
                                <label class="fw-bold mb-3" style="font-size: 16px;">Kategori</label>
                                <label class="fw-bold mb-3" style="font-size: 16px;">Kode Sup</label><br>
                                <label class="fw-bold mb-2" style="font-size: 16px;">Nama Sup</label>
                            </div>
                            <div class="col-md-3" style="margin-left: 50px;">
                                <select class="form-select form-select-sm mb-2" name="divisi" id="divisi" style="font-size: 14px; width: 240px;">
                                    <option value="All">All Divisi</option>
                                    <?php foreach($divisi as $dv) : ?>
                                        <option value="<?= $dv['DIV_KODEDIVISI']; ?>"><?= $dv['DIV_KODEDIVISI']; ?> <?= $dv['DIV_NAMADIVISI'];?></option>
                                    <?php endforeach ?>
                                </select>
                                <select class="form-select form-select-sm mb-2" name="dep" id="dep" style="font-size: 14px; width: 240px;">
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
                                <select class="form-select form-select-sm mb-2" name="kat" id="kat" style="font-size: 14px; width: 240px;">
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
                                <input type="text" name="kdsup" id="kdsup" class="mb-2 form-control input-sm" value="<?= old('kdsup'); ?>" placeholder="Kode Supplier" style="font-size: 14px; width: 240px;">
                                <input type="text" name="nmsup" id="nmsup" class="mb-2 form-control input-sm" value="<?= old('nmsup'); ?>" placeholder="Nama Supplier" style="font-size: 14px; width: 240px;">
                            </div>
                        </div>
                        <hr>    
                        <div class="row justify-content-center mb-1 mx-auto">
                            <button type="submit" name="tombol" value="btnview" class="text-light btn d-block fw-bold" style=" background-color: #6528F7; width: 150px;">Tampil</button>
                            <button type="submit" name="tombol" value="btnxls" class="text-light btn ms-2 me-3 d-block fw-bold" style="background-color: #00b300; width: 150px;">Download</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>