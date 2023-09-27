<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<?php  
    $no = 0;
?>
<div class="container-fluid mt-3 overflow-auto">
    <div class="row">
        <div class="col-md-6">
            <h3 class="fw-bold">Tidak Ada No ID</h3>    
            <table class="table table-responsive table-striped table-hover table-bordered border-dark" style="font-size: 15px;">
                <thead class="table-success table-group-divider">
                    <tr>
                        <th class="fw-bold text-center text-nowrap">#</th>
                        <th class="fw-bold text-center text-nowrap">Div</th>
                        <th class="fw-bold text-center text-nowrap">Dep</th>
                        <th class="fw-bold text-center text-nowrap">Kat</th>
                        <th class="fw-bold text-center text-nowrap">PLU</th>
                        <th class="fw-bold text-center text-nowrap">Deskripsi</th>
                        <th class="fw-bold text-center text-nowrap">Tag</th>
                        <th class="fw-bold text-center text-nowrap">DPD</th>
                        <th class="fw-bold text-center text-nowrap">No ID</th>
                        <th class="fw-bold text-center text-nowrap">ProdCRM</th>
                        <th class="fw-bold text-center text-nowrap">Flag</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php  
                        $no++;
                        foreach($noid as $id) :
                    ?>
                        <tr>
                            <td class="text-end text-nowrap"><?= $no++; ?></td>
                            <td class="text-center text-nowrap"><?= $id['DIV']; ?></td>
                            <td class="text-center text-nowrap"><?= $id['DEP']; ?></td>
                            <td class="text-center text-nowrap"><?= $id['KAT']; ?></td>
                            <td class="text-start text-nowrap"><?= $id['PLUD']; ?></td>
                            <td class="text-start text-nowrap"><?= $id['DESK']; ?></td>
                            <td class="text-center text-nowrap"><?= $id['TAG']; ?></td>
                            <td class="text-center text-nowrap"><?= $id['LOKASID']; ?></td>
                            <td class="text-center text-nowrap"><?= $id['NOID']; ?></td>
                            <td class="text-center text-nowrap"><?= $id['PRODCRM']; ?></td>
                            <td class="text-start text-nowrap"><?= $id['FLAG']; ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <?php
            if($no == 0) {
                echo '<p class="text-danger fw-bold">Tidak Ada Data yang ditemukan</p>';
            }
        ?>
    </div>
</div>

<?= $this->endSection(); ?>