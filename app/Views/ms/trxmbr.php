<?php 
echo $this->extend('layout/template'); ?>

<?php
echo $this->section('content'); ?>

<!-- CSS Boostrap -->
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css"> -->
<!-- CSS Bootstrap Datepicker -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css"> -->

<div class="container-fluid mt-2">
    <div class="row">
        <div class="col-md-3">
            <div class="card w-100 mb-3">
                <div class="card-header text-light" style="background-color: #0040ff;">
                    <h6 class="text-center fw-bold">Transaksi Member</h6>
                    <h6 class="text-warning text-center">** Menggunakan Data H-1 **</h6>
                </div>
                <div class="card-body">
                    <form method="get" action="/ms/transaksimember">
                        <?= csrf_field(); ?>
                        <label class="fw-bold" for="kd">KODE MEMBER  : </label>
                        <input type="text" name="kode" id="kode" class="w-100 mb-3 form-control input-sm" value="<?= old('kode'); ?>" require autofocus>
                        <label class="fw-bold mb-1" for="prd">PERIODE TRANSAKSI  : </label><br>
                        <label class="" for="awal">Tanggal Awal</label>
                        <input type="date" name="awal" id="awal" class="w-100 mb-3 form-control" value="<?= old('awal'); ?>">
                        <label class="" for="akhir">Tanggal Akhir</label>
                        <input type="date" name="akhir" id="akhir" class="w-100 mb-3 form-control" value="<?= old('akhir'); ?>">
                        <button type="submit" name="tombol" value="btntrxmbr" class="text-light btn w-30 mb-2 d-block fw-bold" style=" background-color: #0040ff;">Tampil</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <?php if(!empty($trx)) {?>
                <div class="card w-100 mb-3">
                    <div class="card-header bg-info text-dark">
                        <h6>Hasil Pencarian Member : <?= $kode; ?>  |  Periode : <?= date('d M Y',strtotime($awal)); ?> s/d <?= date('d M Y',strtotime($akhir)); ?> </h6>
                    </div>
                    <form action="/ms/cekmember/tampildatatransaksi" method="get" target="_blank">
                        <div class="card-body">
                            <table class="table mb-3">
                                <thead>
                                    <tr>
                                        <th class="text-center">NO.</th>
                                        <th class="text-center">TANGGAL</th>
                                        <th class="text-center">TYPE</th>
                                        <th class="text-center">NO STRUK</th>
                                        <th class="text-end">NOMINAL</th>
                                        <th class="text-center">METODE BELANJA</th>
                                        <th class="text-center">VIEW</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    <!-- <?= csrf_field(); ?> -->
                                    <?php $no = 1; ?>
                                    <?php foreach ($trx as $tr) : ?>
                                        <tr>
                                            <td class="text-center"><?= $no++; ?></td>
                                            <td class="text-center"><?=$tr['TGL_TRANSAKSI']; ?></td>
                                            <td class="text-center"><?=$tr['TIPE']; ?></td>
                                            <td class="text-center"><?=$tr['NOSTRUK']; ?></td>
                                            <td class="text-end"><?= number_format($tr['NOMINAL'],'0',',','.'); ?></td>
                                            <td class="text-center"><?=$tr['METODE_BAYAR']; ?></td>
                                            <td class="text-center"><a href="cekmember/tampildatatransaksi.php?mbr=<?= $kode; ?>&tgltrx=<?=$tr['TGL_TRANSAKSI']; ?>&notrx=<?=$tr['NOSTRUK']; ?>&jamtrx=<?=$tr['JAM']; ?>" target="_blank" class="btn btn-success text-light">View</a></td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                    <div class="card-footer bg-warning text-dark">
                        <?php foreach ($ttl as $total) : ?>
                            <h6 class="text-center fs-5 fw-bold">TOTAL NILAI TRANSAKSI     : Rp. <?=number_format($total,0,',','.'); ?></h6>
                        <?php endforeach ?>
                    </div>
                </div>
            <?php }?>
        </div>
    </div>
</div>

<!-- //ambil jQuery -->
<!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script> -->
<!-- //ambil bootstrap-datepicker -->
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
<script
src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js">
</script>
<script type="text/javascript">
 $('.datepicker').datepicker({
      format: 'dd-mm-yyyy',
      autoclose: true,
      todayHighlight: true,
  });
</script> -->

<?php $this->endSection(); ?>