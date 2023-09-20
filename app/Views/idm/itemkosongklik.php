<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="container-fluid mt-3 overflow-auto">
    <div class="row justify-content-center">
        <div class="col-ms-auto">
            <div class="card w-60 mb-3 mx-auto" style="width: 960px;">
                <div class="card-header text-light" style="background-color: #0040ff;">
                    <h4 class="text-start fw-bold fs-5">Item Kosong PB IDM</h4>
                </div>
                <div class="card-body">
                    <form method="get" action="tampilitemkosong" target="_blank" role="form" class="form-inline">
                        <fieldset>
                            <div class="row">
                                <!-- Kolom 1 -->
                                <div class="col-sm-3 ms-1">
                                    <div class="card mb-1 ms-1" style="width: 280px;">
                                        <div class="card-header fw-bold" style="font-size: 16px;">Tanggal PB</div>
                                        <div class="card-body">
                                            <input type="date" name="awal" id="awal" class="w-100 mb-3 form-control" value="<?= old('awal'); ?>" style="font-size: 14px;">
                                            <input type="date" name="akhir" id="akhir" class="w-100 mb-3 form-control" value="<?= old('akhir'); ?>" style="font-size: 14px;">
                                        </div>
                                    </div>
                                </div>
                                <!-- Kolom 2 -->
                                <div class="col-sm-3" style="margin-left: 80px;">
                                    <div class="card mb-1 ms-1" style="width: 280px;">
                                        <div class="card-header fw-bold" style="font-size: 16px;">Pilihan</div>
                                        <div class="card-body">
                                            <label class="fw-bold" style="font-size: 14px;">PLU</label>
                                            <input type="number" name="plu" id="plu" class="w-100 mb-2 form-control input-sm" value="<?= old('plu'); ?>" placeholder="PLU" style="font-size: 14px;">
                                            <label class="fw-bold" style="font-size: 14px;">No. Transaksi</label>
                                            <input type="number" name="notrans" id="notrans" class="w-100 mb-2 form-control input-sm" value="<?= old('notrans'); ?>" placeholder="No. Transaksi" style="font-size: 14px;">
                                        </div>
                                    </div>
                                </div>
                                <!-- Kolom 3 -->
                                <div class="col-sm-3" style="margin-left: 80px;">
                                    <div class="card mb-1 ms-1" style="width: 280px;">
                                        <div class="card-header fw-bold" style="font-size: 16px;">Jenis Laporan</div>
                                        <div class="card-body">
                                            <select class="form-select form-select-sm mb-3 mx-auto" name="jenisLaporan" id="jenisLaporan" style="font-size: 14px; width: 240px;">
                                                <option value="1">1. Laporan per PLU</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row justify-content-end mb-1">
                                <button type="submit" name="tombol" value="btnview" class="text-light btn d-block fw-bold me-4" style=" background-color: #6528F7; width: 200px;">Tampil</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>