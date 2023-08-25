<?php 
echo $this->extend('layout/template'); ?>

<?php
echo $this->section('content'); ?>

<div class="container-fluid mt-3">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card w-50 mb-3 mx-auto">
                <div class="card-header text-light" style="background-color: #0040ff;">
                    <h6 class="text-center fw-bold">CETAK STRUK SSO</h6>
                </div>
                <div class="card-body">
                    <form method="get" action="/ms/tampildatasso">
                        <?= csrf_field(); ?>
                        <label class="mb-2 fw-bold text-start" for="nostr">NO STRUK :</label><br>
                        <input type="text" name="notrx" id="notrx" style="text-transform: uppercase;" class="w-100 mb-2 form-control"  value="<?= old('notrx'); ?>">
                        <div class="fst-italic fw-bold text-danger mb-4 text-center" style="font-size: 12px;">** Harap menggunakan Huruf Besar ! **</div>
                        <button type="submit" name="tombol" value="btnsso" class="btn w-100 mb-2 d-block text-light fw-bold" style="background-color: #00b300;">Cetak Struk</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>