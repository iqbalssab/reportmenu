<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="container-fluid mt-3 overflow-auto">
    <div class="row justify-content-center">
        <div class="col-ms-auto">
            <div class="card w-60 mb-3 mx-auto" style="width: 1100px;">
                <div class="card-header text-light" style="background-color: #0040ff;">
                    <h4 class="text-start fw-bold fs-5">Member Tidak Berbelanja</small></h4>
                </div>
                <div class="card-body">
                    <form method="get" action="tampilmember" target="_blank" role="form" class="form-inline">
                        <div class="row">
                            <!-- Kolom 1 -->
                            <div class="col-md-2" style="margin-left: 0.5px;">
                                <div class="card mb-1 ms-1" style="width: 250px;">
                                    <div class="card-header fw-bold" style="font-size: 16px;">Tanggal Terakir Pembelian</div>
                                    <div class="card-body">
                                        <input type="date" name="akhir" id="akhir" class="w-100 mb-3 form-control" value="<?= old('akhir'); ?>" style="font-size: 14px;">
                                    </div>
                                </div>
                            </div>
                            <!-- Kolom 2 -->
                            <div class="col-md-2" style="margin-left: 90px;">
                                <div class="card mb-1 ms-1" style="width: 250px;">
                                    <div class="card-header fw-bold" style="font-size: 16px;">Jenis Member</div>
                                    <div class="card-body">
                                        <select class="form-select form-select-sm mb-2 mx-auto" name="jenisMember" id="jenisMember" style="font-size: 14px; width: 200px;">
                                            <option value ="Merah">Member Merah</option>
                                            <option value ="Biru">Member Biru</option>
                                            <option value ="All" disabled>All Member</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- Kolom 3 -->
                            <div class="col-md-2" style="margin-left: 90px;">
                                <div class="card mb-1 ms-1" style="width: 250px;">
                                    <div class="card-header fw-bold" style="font-size: 16px;">Status Aktivasi Kartu</div>
                                    <div class="card-body">
                                        <select class="form-select form-select-sm mb-2 mx-auto" name="aktivasiKartu" id="aktivasiKartu" style="font-size: 14px; width: 200px;">
                                            <option value ="All">All</option>
                                            <option value ="Sudah">Sudah Aktivasi</option>
                                            <option value ="Belum">Belum Aktivasi</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- Kolom 4 -->
                            <div class="col-md-2" style="margin-left: 90px;">
                                <div class="card mb-1 ms-1" style="width: 250px;">
                                    <div class="card-header fw-bold" style="font-size: 16px;">Jenis Laporan</div>
                                    <div class="card-body">
                                        <select class="form-select form-select-sm mb-2 mx-auto" name="jenisLaporan" id="jenisLaporan" style="font-size: 14px; width: 200px;">
                                            <option value ="1">1. per Member</option>
                                            <option value ="2">2. per Outlet</option>
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