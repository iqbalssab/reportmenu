<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="fw-bold text-primary">PO Banding</h3>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <?php if(!empty($banding)): ?>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                       <th rowspan="2">No</th> 
                       <th rowspan="6">Produk</th> 
                       <th rowspan="4" class="bg-success text-light text-center">NoPO 1 : <?= $nopo1; ?></th> 
                       <th rowspan="4" class="bg-primary text-light text-center">NoPO 2 : <?= $nopo2; ?></th> 
                    </tr>
                    <tr>
                        <th>DIV</th>
                        <th>DEP</th>
                        <th>KAT</th>
                        <th>PLU</th>
                        <th>DESKRIPSI</th>
                        <th>FRAC</th>
                        <th class="bg-success text-light">TGL PO</th>
                        <th class="bg-success text-light">QTY PO</th>
                        <th class="bg-success text-light">QTY BPB</th>
                        <th class="bg-success text-light">TGL BPB</th>
                        <th class="bg-primary text-light">TGL PO</th>
                        <th class="bg-primary text-light">QTY PO</th>
                        <th class="bg-primary text-light">QTY BPB</th>
                        <th class="bg-primary text-light">TGL BPB</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach($banding as $bd): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $bd['DIV']; ?></td>
                                <td><?= $bd['DEP']; ?></td>
                                <td><?= $bd['KAT']; ?></td>
                                <td><?= $bd['PLU']; ?></td>
                                <td><?= $bd['DESK']; ?></td>
                                <td><?= $bd['FRAC']; ?></td>
                                <td class="bg-success text-light"><?= $bd['TGLPO1']; ?></td>
                                <td class="bg-success text-light"><?= $bd['QTYPO1']; ?></td>
                                <td class="bg-success text-light"><?= $bd['QTYBPB1']; ?></td>
                                <td class="bg-success text-light"><?= $bd['TGLBPB1']; ?></td>
                                <td class="bg-primary text-light"><?= $bd['TGLPO2']; ?></td>
                                <td class="bg-primary text-light"><?= $bd['QTYPO2']; ?></td>
                                <td class="bg-primary text-light"><?= $bd['QTYBPB2']; ?></td>
                                <td class="bg-primary text-light"><?= $bd['TGLBPB2']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>