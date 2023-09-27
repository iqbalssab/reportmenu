<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="container-fluid mt-3 overflow-auto">
    <div class="row">
        <div class="col-md-6">
            <h3 class="fw-bold">BPB GO <small>Detail per Dokumen</small></h3>    
            <table class="table table-responsive table-striped table-hover table-bordered border-dark" style="font-size: 15px;">
                <thead class="table-success table-group-divider">
                    <tr></tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>