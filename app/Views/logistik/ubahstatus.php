<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="container-fluid mt-3">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card w-40 mb-3 mx-auto">
                <div class="card-header" style="background-color: #0040ff;">
                    <h4 class="text-light fw-bold fs-6 text-start">MONITORING DATA PERUBAHAN STATUS</h4>
                </div>
                <div class="card-body">
                    <form method="post" action="tampilubahstatus" target="_blank" role="form" class="form-inline">
                        <?= csrf_field(); ?>
                        <table>
                            <tr>
                                <td style="width: 200px;">Periode Tanggal Sortir :</td>
                                <td>
                                    <input type="date" name="awal" id="awal" class="form-control" value="<?= old('awal'); ?>" style="font-size: 15px; width: 150px;">
                                </td>
                                <td> s/d </td>
                                <td>
                                    <input type="date" name="akhir" id="akhir" class="form-control" value="<?= old('akhir'); ?>" style="font-size: 15px; width: 150px;">
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 200px;"><br>Perubahan Status :</td>
                                <td colspan="3">
                                    <br><select class="form-select form-select-sm" name="status" aria-label="Small select example" style="font-size: 15px; width: 250px;">
                                        <option value="0">Belum Ubah Status</option>
                                        <option value="1">Sudah Ubah Status</option>
                                        <option value="2">Semua</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="text-center">
                                <td colspan="4"><br>
                                    <button type="submit" name="tombol" value="btnsts" class="btn w-50 mb-2 mx-auto d-block text-light fw-bold" style="background-color: #00b300;">Download Data</button>
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