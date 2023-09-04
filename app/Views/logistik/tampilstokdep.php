<?php $this->extend('layout/template'); ?>
<?php $this->section('content'); ?>

<div class="container-fluid mt-3">
    <h3 class="fw-bold"><?= $judul; ?></h3>
    <?php if($stokdep): ?>
        <table class="table table-bordered table-responsive table-hover">
            <thead>
                <tr>
                    <th class="fw-bold bg-primary text-light">SUPPLIER</th>
                    <th class="fw-bold bg-primary text-light">DIV</th>
                    <th class="fw-bold bg-primary text-light">DEP</th>
                    <th class="fw-bold bg-primary text-light">KAT</th>
                    <th class="fw-bold bg-primary text-light">PLU</th>
                    <th class="fw-bold bg-primary text-light">DESKRIPSI</th>
                    <th class="fw-bold bg-primary text-light">FRAC</th>
                    <th class="fw-bold bg-primary text-light">TAG</th>
                    <th class="fw-bold bg-primary text-light">BKP</th>
                    <th class="fw-bold bg-primary text-light">STOK AWAL</th>
                    <th class="fw-bold bg-primary text-light">TRFIN</th>
                    <th class="fw-bold bg-primary text-light">TRFOUT</th>
                    <th class="fw-bold bg-primary text-light">SALES</th>
                    <th class="fw-bold bg-primary text-light">RETUR</th>
                    <th class="fw-bold bg-primary text-light">ADJ</th>
                    <th class="fw-bold bg-primary text-light">INTRANSIT</th>
                    <th class="fw-bold bg-primary text-light">STOK AKHIR <?= $tglskg; ?></th>
                    <th class="fw-bold bg-primary text-light">PICKING OMI <?= $tgl; ?></th>
                    <th class="fw-bold bg-primary text-light">AVGSALES</th>
                    <th class="fw-bold bg-primary text-light">DSI</th>
                    <th class="fw-bold bg-primary text-light">ACOST</th>
                    <th class="fw-bold bg-primary text-light">HRG NORMAL</th>
                    <th class="fw-bold bg-primary text-light">% MRG</th>
                    <th class="fw-bold bg-primary text-light">HRG PROMO</th>
                    <th class="fw-bold bg-primary text-light">% MRG</th>
                    <th class="fw-bold bg-primary text-light">DISPLAY</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($stokdep as $sd): ?>
                <tr>
                    <td><?= $sd['KDSUPPLIER']." - ".$sd['NAMASUPPLIER']; ?></td>
                    <td><?= $sd['DIV']; ?></td>
                    <td><?= $sd['DEPT']; ?></td>
                    <td><?= $sd['KATB']; ?></td>
                    <td><?= $sd['PLU']; ?></td>
                    <td><?= $sd['DESKRIPSI']; ?></td>
                    <td><?= $sd['FRAC']; ?></td>
                    <td><?= $sd['TAG']; ?></td>
                    <td><?= $sd['BKP']; ?></td>
                    <td><?= $sd['STOCKAWAL']; ?></td>
                    <td><?= $sd['TRFIN']; ?></td>
                    <td><?= $sd['TRFOUT']; ?></td>
                    <td><?= $sd['SALES']; ?></td>
                    <td><?= $sd['RETUR']; ?></td>
                    <td><?= $sd['ADJ']; ?></td>
                    <td><?= $sd['INTRANSIT']; ?></td>
                    <td><?= $sd['STOCKAKHIR']; ?></td>
                    <td><?= $sd['PICKING_OMI']; ?></td>
                    <td><?= $sd['AVGSALES']; ?></td>
                        <?php if($sd['SALES']==0){
                            $dsi = 0;
                        }else{
                            $dsi = ($sd['STOCKAWAL']+$sd['STOCKAKHIR']/2)/$sd['SALES']*$jmlhari;
                        }
                        ?>
                    <td><?= $dsi; ?></td>
                    <td><?= $sd['ACOST']; ?></td>
                    <td><?= $sd['HRGNORMAL']; ?></td>
                    <td><?= $sd['MRG1']; ?></td>
                    <td><?= $sd['HRGPROMO']; ?></td>
                    <td><?= $sd['MRG2']; ?></td>
                    <td><?= $sd['LKS_DISPLAY']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>


<?php $this->endSection(); ?>