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
                                <td><?= $db['DIV']; ?></td>
                                <td><?= $db['DEP']; ?></td>
                                <td><?= $db['KAT']; ?></td>
                                <td><?= $db['PLU']; ?></td>
                                <td><?= $db['DESK']; ?></td>
                                <td><?= $db['FRAC']; ?></td>
                                <td class="bg-success text-light"><?= $db['TGLPO1']; ?></td>
                                <td class="bg-success text-light"><?= $db['QTYPO1']; ?></td>
                                <td class="bg-success text-light"><?= $db['QTYBPB1']; ?></td>
                                <td class="bg-success text-light"><?= $db['TGLBPB1']; ?></td>
                                <td class="bg-primary text-light"><?= $db['TGLPO2']; ?></td>
                                <td class="bg-primary text-light"><?= $db['QTYPO2']; ?></td>
                                <td class="bg-primary text-light"><?= $db['QTYBPB2']; ?></td>
                                <td class="bg-primary text-light"><?= $db['TGLBPB2']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>