<?php $this->extend('layout/template'); ?>
<?php $this->section('content'); ?>
<div class="container mt-3">
<h3><?= $judul1." - ".$judul2; ?></h3>

<?php if(!empty($kompetisi)): ?>
    <p class="fw-bold">ITEM FOKUS:</p>
    <?php foreach($itemfokus as $if): ?>
        <p><?= $if['PRD_PRDCD']." : ". $if['PRD_DESKRIPSIPANJANG']; ?></p>
    <?php endforeach; ?>
    <table class="table table-responsive table-bordered">
        <thead>
            <tr>
                <th class="bg-primary text-light">ID KASIR</th>
                <th class="bg-primary text-light">NAMA KASIR</th>
                <th class="bg-primary text-light">STRUK ALL</th>
                <th class="bg-primary text-light">STRUK ITEM FOKUS</th>
                <th class="bg-primary text-light">JML MEMBER</th>
                <th class="bg-primary text-light">JML ITEM</th>
                <th class="bg-primary text-light">QTY SALES</th>
                <th class="bg-primary text-light">RPH SALES</th>
                <th class="bg-primary text-light">PILIHAN</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $totalqty = 0;
            $totalrph = 0;
             ?>
            <?php foreach($kompetisi as $kom): ?>
            <tr>
                <td><?= $kom['IDKASIR']; ?></td>
                <td><?= $kom['NAMAKASIR']; ?></td>
                <td><?= number_format($kom['STRUKALL']); ?></td>
                <td><?= number_format($kom['STRUKFOKUS']); ?></td>
                <td><?= number_format($kom['JMLMEMBER']); ?></td>
                <td><?= number_format($kom['JMLITEM']); ?></td>
                <td><?= number_format($kom['QTYSALES']); ?></td>
                <td><?= number_format($kom['RPHSALES']); ?></td>
                <td><a href="detailitemfokus?tglawal=<?= $tglawal; ?>&tglakhir=<?= $tglakhir; ?>&plugab=<?= $plugab; ?>&idkasir=<?= $kom['IDKASIR']; ?>&namakasir=<?= $kom['NAMAKASIR']; ?>">Detail</a></td>
            </tr>
            <?php $totalqty += $kom['QTYSALES']; ?>
            <?php $totalrph += $kom['RPHSALES']; ?>
            <?php endforeach; ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><?= number_format($totalqty); ?></td>
                <td><?= number_format($totalrph); ?></td>
                <td><a href="detailitemfokus?tglawal=<?= $tglawal; ?>&tglakhir=<?= $tglakhir; ?>&plugab=<?= $plugab; ?>&idkasir=all&namakasir=all">Detail</a></td>
            </tr>
        </tbody>
    </table>
<?php endif; ?>
<?php if(!empty($rekapkasir)): ?>
    <p class="fw-bold">ITEM FOKUS:</p>
    <?php foreach($itemfokus as $if): ?>
        <p><?= $if['PRD_PRDCD']." : ". $if['PRD_DESKRIPSIPANJANG']; ?></p>
    <?php endforeach; ?>
    <table class="table table-responsive table-bordered">
        <thead>
            <tr>
                <th class="bg-primary text-light">No.</th>
                <th class="bg-primary text-light">ID KASIR</th>
                <th class="bg-primary text-light">NAMA KASIR</th>
                <th class="bg-primary text-light">JML MEMBER</th>
                <th class="bg-primary text-light">QTY SALES</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1; 
            $totalsales=0; 
            $totalmember=0; 
            ?>
            <?php foreach($rekapkasir as $rk): ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= $rk['IDKASIR']; ?></td>
                <td><?= $rk['NAMAKASIR']; ?></td>
                <td><?= number_format($rk['JML_MEMBER']); ?></td>
                <td><?= number_format($rk['QTY_SALES']); ?></td>
            </tr>
            <?php $totalsales += $rk['QTY_SALES']; ?>
            <?php $totalmember += $rk['JML_MEMBER']; ?>
            <?php endforeach; ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td class="fw-bold"><?= number_format($totalmember) ; ?></td>
                <td class="fw-bold"><?= number_format($totalsales); ?></td>
            </tr>

        </tbody>
    </table>
<?php endif; ?>
<?php if(!empty($rinciankasir)): ?>
    <p class="fw-bold">ITEM FOKUS:</p>
    <?php foreach($itemfokus as $if): ?>
        <p><?= $if['PRD_PRDCD']." : ". $if['PRD_DESKRIPSIPANJANG']; ?></p>
    <?php endforeach; ?>
        <table class="table table-responsive table-bordered">
            <thead>
                <tr>
                    <th class="bg-primary text-light">No.</th>
                    <th class="bg-primary text-light">TGLTRANS</th>
                    <th class="bg-primary text-light">STRUK</th>
                    <th class="bg-primary text-light">IDKASIR</th>
                    <th class="bg-primary text-light">NAMA KASIR</th>
                    <th class="bg-primary text-light">KODE MEMBER</th>
                    <th class="bg-primary text-light">NAMA MEMBER</th>
                    <th class="bg-primary text-light">QTY SALES</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
            <?php foreach($rinciankasir as $rck): ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= $rck['TGLTRANS']; ?></td>
                    <td><?= $rck['STRUK']; ?></td>
                    <td><?= $rck['IDKASIR']; ?></td>
                    <td><?= $rck['NAMAKASIR']; ?></td>
                    <td><?= $rck['KDMEMBER']; ?></td>
                    <td><?= $rck['NAMAMEMBER']; ?></td>
                    <td><?= $rck['QTY_PCS']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
<?php endif; ?>
<?php if(!empty($memberbelanja)): ?>
    <p class="fw-bold">ITEM FOKUS:</p>
    <?php foreach($itemfokus as $if): ?>
        <p><?= $if['PRD_PRDCD']." : ". $if['PRD_DESKRIPSIPANJANG']; ?></p>
    <?php endforeach; ?>
    <table class="table table-responsive table-bordered">
        <thead>
            <tr>
                <th class="bg-primary text-light">No.</th>
                <th class="bg-primary text-light">KODE MEMBER</th>
                <th class="bg-primary text-light">NAMA MEMBER</th>
                <th class="bg-primary text-light">STRUK</th>
                <th class="bg-primary text-light">QTY SALES</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            <?php foreach($memberbelanja as $mbj): ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= $mbj['KDMEMBER']; ?></td>
                <td><?= $mbj['NAMAMEMBER']; ?></td>
                <td><?= $mbj['COUNT_STRUK']; ?></td>
                <td><?= number_format($mbj['SUM_QTY_PCS']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<?php if(!empty($rekapplu) && !empty($rekapplutotal)): ?>
    <p class="fw-bold mb-3">Periode Sales : <?= $tglawal." s/d ".$tglakhir; ?></p>

    <p class="fw-bold">TOTAL PER-ITEM</p>
    <table class="table table-responsive table-bordered mb-3">
        <thead>
            <tr>
                <th class="bg-primary text-light">PLU</th>
                <th class="bg-primary text-light">DESKRIPSI</th>
                <th class="bg-primary text-light">JML MEMBER</th>
                <th class="bg-primary text-light">QTY SALES</th>
                <th class="bg-primary text-light">SALES GROSS</th>
                <th class="bg-primary text-light">SALES NETT</th>
                <th class="bg-primary text-light">MARGIN</th>
                <th class="bg-primary text-light">HARI SALES</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rekapplu as $rpl): ?>
            <tr>
                <td><?= $rpl['PLU']; ?></td>
                <td><?= $rpl['DESKRIPSI']; ?></td>
                <td><?= $rpl['JML_MEMBER']; ?></td>
                <td><?= $rpl['QTY_SALES']; ?></td>
                <td><?= number_format($rpl['RPH_GROSS']); ?></td>
                <td><?= number_format($rpl['S_NETT']); ?></td>
                <td><?= number_format($rpl['MARGIN']); ?></td>
                <td><?= $rpl['HARISALES']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p class="mt-2 mb-2 fw-bold">TOTAL PENCAPAIAN :</p>
    <table class="table table-responsive table-bordered mb-3">
        <thead>
            <tr>
                <th class="bg-success text-light">KODE IGR</th>
                <th class="bg-success text-light">JML MEMBER</th>
                <th class="bg-success text-light">QTY SALES</th>
                <th class="bg-success text-light">SALES GROSS</th>
                <th class="bg-success text-light">SALES NETT</th>
                <th class="bg-success text-light">MARGIN</th>
                <th class="bg-success text-light">HARI SALES</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rekapplutotal as $rpt): ?>
            <tr>
                <td><?= $rpt['TRJD_KODEIGR']." - ".$rpt['CAB_NAMACABANG']; ?></td>
                <td><?= $rpt['JML_MEMBER']; ?></td>
                <td><?= number_format($rpt['QTY_SALES']); ?></td>
                <td><?= number_format($rpt['RPH_GROSS']); ?></td>
                <td><?= number_format($rpt['S_NETT']); ?></td>
                <td><?= number_format($rpt['MARGIN']); ?></td>
                <td><?= $rpt['HARISALES']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

</div>
<?php $this->endSection(); ?>