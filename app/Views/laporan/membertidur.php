<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary">
                    <p class="fw-bold text-light">Cek Member Tidur</p>
                </div>
                <div class="card-body">
                    <form action="membertidur" method="get">
                        <label for="" class="d-block fw-bold"></label>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>