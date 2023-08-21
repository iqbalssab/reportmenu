<?php $this->extend('layout/template'); ?>
<?php $this->section('content'); ?>

<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary text-center">
                    <h6 class="fw-bold">MONITORING SALES PER DEPARTEMENT</h6>
                    <p><i>**Menggunakan data H-1**</i></p>
                </div>
                <div class="card-body p-2">
                    <form action="/store/salesperdep/tampilsalesperdep" method="post" target="_blank">
                        <fieldset>
                            <legend class="fw-bold">Tampil Data per-Departement</legend>
                            <label for="">Periode Sales :</label>
                            <input type="date" name="tglawal" id="tglawal" class="w-25"> s/d <input type="date" name="tglakhir" id="tglakhir" class="w-25">
                            <label class="mt-3" for="departement">Pilih Departement :</label>
                            
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>