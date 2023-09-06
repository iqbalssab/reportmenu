<?php 
echo $this->extend('layout/template'); ?>

<?php
echo $this->section('content'); ?>

<div class="container-fluid mt-3">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card w-50 mb-3 mx-auto">
                <div class="card-header text-light" style="background-color: #0040ff;">
                    <h6 class="text-center fw-bold">Monitoring Checker :</h6>
                </div>
                <div class="card-body">
                    <form method="get" action="/edp/tampildatachecker">
                        <?= csrf_field(); ?>
                        <label class="mb-2 fw-bold text-start" for="prd">TANGGAL :</label><br>
                        <input type="date" name="tgl" id="tgl" class="w-100 mb-4 form-control"  value="<?= old('tgl'); ?>">
                        <button type="submit" name="tombol" value="btnmc" class="btn w-100 mb-2 d-block text-light fw-bold" style="background-color: #00b300;">Download Exel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>