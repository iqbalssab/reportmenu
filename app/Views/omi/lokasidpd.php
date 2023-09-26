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
            <h3 class="fw-bold">Tidak Ada Lokasi DPD</h3>    
            <table class="table table-responsive table-striped table-hover table-bordered border-dark" style="font-size: 15px;">
                <thead class="table-success table-group-divider">
                    <tr>
                        <th class="fw-bold text-center text-nowrap">#</th>
                        <th class="fw-bold text-center text-nowrap">Div</th>
                        <th class="fw-bold text-center text-nowrap">Dep</th>
                        <th class="fw-bold text-center text-nowrap">Kat</th>
                        <th class="fw-bold text-center text-nowrap">PLU</th>
                        <th class="fw-bold text-center text-nowrap">Deskripsi</th>
                        <th class="fw-bold text-center text-nowrap">Tag IGR</th>
                        <th class="fw-bold text-center text-nowrap">Tag OMI</th>
                        <th class="fw-bold text-center text-nowrap">Toko</th>
                        <th class="fw-bold text-center text-nowrap">DPD</th>
                        <th class="fw-bold text-center text-nowrap">ProdCRM</th>
                        <th class="fw-bold text-center text-nowrap">Flag</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php  
                        $no++;
                        foreach($lokasidpd as $dpd) :
                    ?>
                        <tr>
                            <td class="text-end text-nowrap"><?= $no++; ?></td>
                            <td class="text-center text-nowrap"><?= $dpd['DIV']; ?></td>
                            <td class="text-center text-nowrap"><?= $dpd['DEP']; ?></td>
                            <td class="text-center text-nowrap"><?= $dpd['KAT']; ?></td>
                            <td class="text-start text-nowrap"><?= $dpd['PLUIGR']; ?></td>
                            <td class="text-start text-nowrap"><?= $dpd['DESK']; ?></td>
                            <td class="text-center text-nowrap"><?= $dpd['TAG']; ?></td>
                            <td class="text-center text-nowrap"><?= $dpd['TAG_OMI']; ?></td>
                            <td class="text-center text-nowrap"><?= $dpd['TOKO']; ?></td>
                            <td class="text-center text-nowrap"><?= $dpd['DPD']; ?></td>
                            <td class="text-center text-nowrap"><?= $dpd['PRODCRM']; ?></td>
                            <td class="text-start text-nowrap"><?= $dpd['FLAG']; ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>