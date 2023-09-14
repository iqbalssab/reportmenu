<?php 
echo $this->extend('layout/template'); ?>

<?php
echo $this->section('content'); ?>

<div class="container-fluid mt-3">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card w-50 mb-3 mx-auto">
                <div class="card-header text-light" style="background-color: #0040ff;">
                    <h4 class="fs-5 text-start fw-bold">CETAK STRUK SSO</h4>
                </div>
                <div class="card-body">
                    <form method="get" action="tampildatasso" target="_blank">
                        <?= csrf_field(); ?>
                        <label class="mb-2 fw-bold text-start fs-6" for="nostr">NO STRUK :</label><br>
                        <input type="text" name="notrx" id="notrx" class="w-100 mb-2 form-control"  value="<?= old('notrx'); ?>">
                        <div class="fst-italic fw-bold text-danger mb-4 text-center" style="font-size: 14px;">** Harap menggunakan Huruf Besar ! **</div>
                        <button type="submit" name="tombol" value="btnsso" class="btn w-100 mb-2 d-block text-light fw-bold" style="background-color: #6528F7;">Cetak Struk</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>