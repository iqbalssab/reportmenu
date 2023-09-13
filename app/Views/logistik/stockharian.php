<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="container-fluid mt-3 overflow-auto">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card w-60 mb-3 mx-auto" style="width: 500px;">
                <div class="card-header text-light" style="background-color: #0040ff;">
                    <h5 class="text-start fw-bold">Data Stock All</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="/logistik/tampilstock" target="_blank" role="form" class="form-inline">
                        <?= csrf_field(); ?>
                        <table>
                            <tr>
                                <td class="fw-bold" style="width: 150px;">Divisi</td>
                                <td colspan="3">
                                    <select class="form-select form-select-sm" name="divisi" id="divisi" style="font-size: 14px; width: 300px;">
                                        <option value="All">All Divisi</option>
                                        <option value="1">1. Food</option>
                                        <option value="2">2. Non Food</option>
                                        <option value="3">3. GMS</option>
                                        <option value="4">4. Perishable</option>
                                        <option value="5">5. Counter & Promotion</option>
                                        <option value="6">6. Fast Food</option>
                                        <option value="7">7. I-Fashion</option>
                                        <option value="8">8. I-Tech</option>
                                        <option value="9">9. I-Tronik</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold" style="width: 150px;"><br>Departemen</td>
                                <td colspan="3">
                                    <br><select class="form-select" name="dep" id="dep" style="font-size: 14px; width: 300px;">
                                        <option value="All">All Departemen</option>
                                        <?php $divisi = 1; ?>
                                        <?php foreach($dep as $dp): ?>
                                            <?php if($dp['DEP_KODEDIVISI']==$divisi): ?>
                                                <option value="all"></option>
                                                <option class="fw-bold" style="font-size: 16px;" value="<?= $dp['DEP_KODEDIVISI']; ?>"><?= $dp['DEP_KODEDIVISI']." - ".$dp['DIV_NAMADIVISI']; ?></option>
                                                <?php $divisi++; ?>
                                            <?php endif; ?>
                                                <option value="<?= $dp['DEP_KODEDEPARTEMENT']; ?>"><?= $dp['DEP_KODEDEPARTEMENT']." : ".$dp['DEP_NAMADEPARTEMENT']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold" style="width: 150px;"><br>Tag</td>
                                <td colspan="3">
                                    <br><select class="form-select" name="tag" id="tag" style="font-size: 14px; width: 300px;">
                                        <option value="All">All Tag</option>
                                        <option value="1">Di Luar Tag [NHOAXT]</option>
                                        <option value="2">Hanya Tag [NHOAXT]</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold" style="width: 150px;"><br>Jenis Laporan</td>
                                <td colspan="3">
                                    <br><select class="form-select" name="lap" id="lap" style="font-size: 14px; width: 300px;">
                                        <option value="0">Laporan Per Produk Detail</option>
                                        <option value="1">Laporan Per Divisi</option>
                                        <option value="2">Laporan Per Departemen</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4"><hr></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <button type="submit" name="tombol" value="btnview" class="text-light btn mb-2 d-block fw-bold" style=" background-color: #6528F7; width: 150px;">Tampil</button>
                                </td>
                                <td>
                                    <button type="submit" name="tombol" value="btnxls" class="text-light btn mb-2 d-block fw-bold" style="background-color: #00b300; width: 150px;">Download</button>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>