<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="row justify-content-center mt-3 mb-3">
    <div class="col-md-2">
        <h4 class="fw-bold">LPP vs PLANO</h4>
    </div>
</div>
<br><br>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-6">
            <h6 class="fw-bold">REKAP LPP vs PLANO</h6>    
            <table class="table mb-3 table-bordered table-responsive" style="width: 750px;" style="font-size: 15px;">
                <thead class="table-success">
                    <tr>
                        <th class="text-center">QTY LPP</th>
                        <th class="text-center">QTY PLANO</th>
                        <th class="text-center">RPH LPP</th>
                        <th class="text-center">RPH PLANO</th>
                        <th class="text-center">QTY SELISIH</th>
                        <th class="text-center">RPH SELISIH</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($rekap as $rk) : ?>
                        <tr>
                            <td class="text-end"><?=number_format($rk['LPP_QTY'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($rk['PLANO_QTY'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($rk['LPP_RPH'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($rk['PLANO_RPH'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($rk['SLSH_QTY'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($rk['SLSH_RPH'],'0',',','.'); ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <br>
            <h6 class="fw-bold">REKAP LPP vs PLANO Plus Minus</h6>    
            <table class="table mb-3 table-bordered table-responsive" style="width: 750px;" style="font-size: 15px;">
                <thead class="table-success">
                    <tr>
                        <th class="text-center">SELISIH PLUS</th>
                        <th class="text-center">SELISIH MINUS</th>
                        <th class="text-center">PLUS MINUS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($plusminus as $pl) : ?>
                        <tr>
                            <td class="text-end"><?=number_format($pl['SLS_PLUS'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($pl['SLS_MNUS'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($pl['SLS_PLUS'] + $pl['SLS_MNUS'],'0',',','.'); ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <br>
            <h6 class="fw-bold">REKAP LPP vs PLANO Plus Terbesar</h6>    
            <table class="table mb-3 table-bordered table-responsive table-hover" style="width: 1300px; font-size: 15px;">
                <thead class="table-success">
                    <tr>
                        <th class="text-center">PLU</th>
                        <th class="text-center">DESKRIPSI</th>
                        <th class="text-center">LPP</th>
                        <th class="text-center">QTY PLANO</th>
                        <th class="text-center">RPH LPP</th>
                        <th class="text-center">RPH PLANO</th>
                        <th class="text-center">QTY SELISIH</th>
                        <th class="text-center">RPH SELISIH</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($plus as $ps) : ?>
                        <tr>
                            <td class="text-center"><?=$ps['PLU']; ?></td>
                            <td class="text-start"><?=$ps['DESKRIPSI']; ?></td>
                            <td class="text-end"><?=number_format($ps['LPP_QTY'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($ps['PLANO_QTY'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($ps['LPP_RPH'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($ps['PLANO_RPH'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($ps['SLSH_QTY'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($ps['SLSH_RPH'],'0',',','.'); ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <br>
            <h6 class="fw-bold">REKAP LPP vs PLANO Minus Terbesar</h6>    
            <table class="table mb-3 table-bordered table-responsive table-hover" style="width: 1300px; font-size: 15px;">
                <thead class="table-success">
                    <tr>
                        <th class="text-center">PLU</th>
                        <th class="text-center">DESKRIPSI</th>
                        <th class="text-center">LPP</th>
                        <th class="text-center">QTY PLANO</th>
                        <th class="text-center">RPH LPP</th>
                        <th class="text-center">RPH PLANO</th>
                        <th class="text-center">QTY SELISIH</th>
                        <th class="text-center">RPH SELISIH</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($minus as $ms) : ?>
                        <tr>
                            <td class="text-center"><?=$ms['PLU']; ?></td>
                            <td class="text-start"><?=$ms['DESKRIPSI']; ?></td>
                            <td class="text-end"><?=number_format($ms['LPP_QTY'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($ms['PLANO_QTY'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($ms['LPP_RPH'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($ms['PLANO_RPH'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($ms['SLSH_QTY'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($ms['SLSH_RPH'],'0',',','.'); ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>