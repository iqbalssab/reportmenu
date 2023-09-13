<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="container-fluid mt-3 overflow-auto">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card w-60 mb-3 mx-auto">
                <div class="card-header text-light" style="background-color: #0040ff;">
                    <h6 class="text-start fw-bold">Form SO Harian</h6>
                </div>
                <div class="card-body">
                    <form method="get" action="/logistik/tampildatasoharian" target="_blank" role="form" class="form-inline">
                        <?= csrf_field(); ?>
                        <table style="width:100%;">
                            <tr>
                                <td colspan="4" align="center"><b>:: Masukkan PLU ::</b></td>
                            </tr>
                            <tr>
                                <td colspan="4" align="center"><textarea id="kodePLU" name='kodePLU' rows='3' cols='55' placeholder='850,60410,357330' style="width: 600px;"></textarea></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-center"><p style='font-size:small;padding:5px;margin:0 10px'>
                                Keterangan : <br/>untuk monitoring banyak PLU, gunakan tanda koma ( , ) untuk pemisah & tanpa spasi.
                                <br/><i>CONTOH : 0000850,0060410,357330,357320 dst. </i></p></td>
                            </tr>
                        </table><br>
                        <button type="submit" name="tombol" value="btnbh" class="btn w-100 mb-2 d-block text-light fw-bold" style="background-color: #6528F7;">Tampilkan Data</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>