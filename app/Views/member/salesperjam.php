<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="container-fluid mt-3 overflow-auto">
    <div class="row justify-content-center">
        <div class="col-ms-auto">
            <div class="card w-60 mb-3 mx-auto" style="width: 300px;">
                <div class="card-header text-light" style="background-color: #0040ff;">
                    <h4 class="text-start fw-bold fs-5">Sales Per Jam</small></h4>
                </div>
                <div class="card-body">
                    <form method="get" action="tampilperjam" target="_blank" role="form" class="form-inline">
                        <div class="mb-1 ms-1" style="width: 250px;">
                            <label class="mb-2 fw-bold" style="font-size: 16px;">Tanggal</label>
                            <input type="date" name="akhir" id="akhir" class="w-100 mb-3 form-control mx-auto" value="<?= old('akhir'); ?>" style="font-size: 14px;">
                        </div>
                        <div class="mb-1" style="width: 250px;">
                            <label class="mb-2 fw-bold" style="font-size: 16px;">Jenis Member</label>
                            <select class="form-select form-select-sm mb-2 mx-auto w-100 mx-auto" name="mbr" id="mbr" style="font-size: 14px;">
                                <option value ="merah">Member Merah</option>
                                <option value ="biru">Member Biru</option>
                            </select>
                        </div>
                        <div class="mb-1" style="width: 250px;">
                            <label class="mb-2 fw-bold" style="font-size: 16px;">Jenis Laporan</label>
                            <select class="form-select form-select-sm mb-2 mx-auto w-100 mx-auto" name="jenisLaporan" id="jenisLaporan" style="font-size: 14px;">
                                <option value ="1">1. per Jam</option>
                                <option value ="2">2. per Jam per Hari</option>
                            </select>
                            <label style="font-size: 13px;"><strong class="text-danger">Perhatian!</strong> Laporan tidak menghitung transaksi di HJK, BKL, Klik, SOS, Omi dan IDM.</label>
                        </div>
                        <hr>
                            <button type="submit" name="tombol" value="btnview" class="text-light btn d-block fw-bold mx-auto mb-2" style=" background-color: #6528F7; width: 200px;">Tampil</button>
                            <button type="submit" name="tombol" value="btnxls" class="text-light btn d-block fw-bold mx-auto" style="background-color: #00b300; width: 200px;">Download</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>