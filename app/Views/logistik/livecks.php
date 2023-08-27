<?php $this->extend('layout/template'); ?>
<?php $this->section('content'); ?>

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-2">
            <div class="card">
                <div class="card-header bg-primary">
                    <p class="text-center text-light fw-bold">INQUIRY PLANO</p>
                </div>
                <div class="card-body p-2">
                    <form action="livecks" method="post" class="mb-4">
                        <label class="block" for="plu">input PLU :</label>
                        <input type="text" name="plu" id="plu" class="w-100">
                        <button type="submit" name="btn" value="view" class="btn btn-primary w-100 m-1"><i class="fa-solid fa-magnifying-glass"></i>View</button>
                    </form>
                    <form action="livecks" method="post">
                        <label class="block" for="koderak">Kode Rak :</label>
                        <input type="text" name="koderak" id="koderak" class="w-100">
                        <label class="block" for="kodesubrak">Kode Sub Rak :</label>
                        <input type="text" name="kodesubrak" id="kodesubrak" class="w-100">

                        <button type="submit" class="btn btn-success w-100 d-block m-1" name="btn" value="all">ALL</button>
                        <button type="submit" class="btn btn-primary w-100 d-block m-1" name="btn" value="display">Display</button>
                        <button type="submit" class="btn btn-warning w-100 d-block m-1" name="btn" value="storage">Storage</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-10"></div>
    </div>
</div>

<?php $this->endSection(); ?>