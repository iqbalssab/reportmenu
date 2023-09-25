<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

 <!-- Menu laporan ada disini -->
 <div class="container-fluid mt-4">
 <div class="card">
       <div class="card-header bg-primary">
        <h4 class="text-light fw-bold">
            BPB Per Hari GO
        </h4>
       </div>
       <div class="card-body">
        <div class="row">
            <form role="form" method="post" action="tampilperhari">
            <div class="col-md-6">
                <div id="kiri" class="">
                    <label for="tgl" class="d-block fw-bold w-100">Tanggal BPB</label>
                    <br>
                    <label for="jenis" class="d-block fw-bold w-100">Jenis Transaksi</label>
                    <br>
                    <label for="plu" class="d-block fw-bold w-100">PLU</label>
                    <br>
                    <label for="supplier" class="d-block fw-bold w-100">Kode Supplier</label>
                    <br>
                </div> 
            </div>
            <div class="col-md-6"></div>
        </form> 
        </div>
               
        </div>

      </div><!-- akhir dari menu laporan -->
 </div>

<?= $this->endSection(); ?>