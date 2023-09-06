<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="container-fluid mt-3">
    <div class="row justify-content-center">
        <div class="col-md-6 mb-3">
            <div class="card w-40 mb-2 mx-auto">
                <div class="card-header text-center " style="background-color: #0040ff;">
                    <h6 class="text-light fw-bold">MONITORING ITEM SEASONAL</h6>
                </div>
                <div class="card-body text-center">
                    <form method="get" action="/logistik/tampilitemseasonal" target="_blank" role="form" class="form-inline">
                        <?= csrf_field(); ?>
                        <table class="mb-2">
                            <tr>
                                <td style="width: 200px;" class="mb-3">Periode Tanggal BPB :</td>
                                <td>
                                    <input type="date" name="awal" id="awal" class="form-control" value="<?= old('awal'); ?>" style="font-size: 15px; width: 200px;">
                                </td>
                                <td> s/d </td>
                                <td>
                                    <input type="date" name="akhir" id="akhir" class="form-control" value="<?= old('akhir'); ?>" style="font-size: 15px; width: 200px;">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <br/><button type="submit" name="tombol" value="btnssn" class="btn mx-auto d-block text-light fw-bold" style="background-color: #6528F7; width: 200px;">Tampil Data</button>
                                </td>
                                <td colspan="2">
                                    <br/><button type="submit" name="tombol" value="btnxls" class="btn mx-auto d-block text-light fw-bold" style="background-color: #00b300; width: 200px;">Download Data</button>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>