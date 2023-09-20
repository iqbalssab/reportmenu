<?php $this->extend('layout/template'); ?>

<?php $this->section('content'); ?>

<div class="row justify-content-center mt-3 mb-3 overflow-x-visible">
    <div class="col-md-2">
        <h2 class="fw-bold">SO IC</h2>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h6 class="fw-bold">SO IC</h6>
            <table class="table mb-3 table-striped table-bordered table-responsive" style="width: 750px;" style="font-size: 15px;">
                <thead class="table-success table-group-divider">
                    <tr>
                        <th class="text center fw-bold">No</th>
                        <th class="text center fw-bold">Div</th>
                        <th class="text center fw-bold">Dept</th>
                        <th class="text center fw-bold">Katb</th>
                        <th class="text center fw-bold">PLU</th>
                        <th class="text center fw-bold">Deskripsi</th>
                        <th class="text center fw-bold">Frac</th>
                        <th class="text center fw-bold">Unit</th>
                        <th class="text center fw-bold">Tag</th>
                        <th class="text center fw-bold">Acost</th>
                        <th class="text center fw-bold text-nowrap">LPP Qty</th>
                        <th class="text center fw-bold text-nowrap">LPP Rph</th>
                        <th class="text center fw-bold text-nowrap">Plano Qty</th>
                        <th class="text center fw-bold text-nowrap">Plano Rph</th>
                        <th class="text center fw-bold text-nowrap">Selisih Qty</th>
                        <th class="text center fw-bold text-nowrap">Selisih Rph</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php $no = 1; ?>
                    <?php foreach($soic as $ic) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $ic['DIV']; ?></td>
                            <td class="text-center"><?= $ic['DEPT']; ?></td>
                            <td class="text-center"><?= $ic['KATB']; ?></td>
                            <td class="text-center"><?= $ic['PLU']; ?></td>
                            <td class="text-start text-nowrap"><?= $ic['DESKRIPSI']; ?></td>
                            <td class="text-center"><?= $ic['UNIT']; ?></td>
                            <td class="text-center"><?= $ic['FRAC']; ?></td>
                            <td class="text-center"><?= $ic['TAG']; ?></td>
                            <td class="text-end"><?= number_format($ic['ACOST'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($ic['LPP_QTY'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($ic['LPP_RPH'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($ic['PLANO_QTY'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($ic['PLANO_RPH'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($ic['SLSH_QTY'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($ic['SLSH_RPH'],'0',',','.'); ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>