<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid overflow-auto mt-3">
    <div class="row mb-3">
        <div class="col-sm-12">
            <h4 class="text-primary text-center fw-bold">PRODMAST TAG HANOX</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="bg-primary-subtle text-dark">DIV</th>
                        <th class="bg-primary-subtle text-dark">DEP</th>
                        <th class="bg-primary-subtle text-dark">KAT</th>
                        <th class="bg-primary-subtle text-dark">PLU</th>
                        <th class="bg-primary-subtle text-dark">DESKRIPSI</th>
                        <th class="bg-primary-subtle text-dark">UNIT</th>
                        <th class="bg-primary-subtle text-dark">FRAC</th>
                        <th class="bg-primary-subtle text-dark">TAG</th>
                        <th class="bg-primary-subtle text-dark">STOK</th>
                        <th class="bg-primary-subtle text-dark">AVGSALES</th>
                        <th class="bg-primary-subtle text-dark">PKMT</th>
                        <th class="bg-primary-subtle text-dark">LCOST</th>
                        <th class="bg-primary-subtle text-dark">ACOST</th>
                        <th class="bg-primary-subtle text-dark">PO</th>
                        <th class="bg-primary-subtle text-dark">TGL PO</th>
                        <th class="bg-primary-subtle text-dark">KODE SUP</th>
                        <th class="bg-primary-subtle text-dark">NAMA SUP</th>
                        <th class="bg-primary-subtle text-dark">HRG CTN</th>
                        <th class="bg-primary-subtle text-dark">HRG PCS</th>
                        <th class="bg-primary-subtle text-dark">HRG MBT</th>
                        <th class="bg-primary-subtle text-dark">HRG BOX</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($hanox)): ?>
                    <?php foreach($hanox as $hn): ?>
                    <tr>
                        <td><?= $hn['DIV']; ?></td>
                        <td><?= $hn['DEP']; ?></td>
                        <td><?= $hn['KAT']; ?></td>
                        <td><?= $hn['PLU']; ?></td>
                        <td><?= $hn['DESKRIPSI']; ?></td>
                        <td><?= $hn['UNIT']; ?></td>
                        <td><?= $hn['FRAC']; ?></td>
                        <td><?= $hn['TAG']; ?></td>
                        <td><?= $hn['STOK']; ?></td>
                        <td><?= number_format($hn['AVGSALES']); ?></td>
                        <td><?= $hn['PKMT']; ?></td>
                        <td><?= number_format($hn['ACOST']); ?></td>
                        <td><?= number_format($hn['LASTCOST']); ?></td>
                        <td><?= $hn['PO']; ?></td>
                        <td><?= $hn['TGL_PO']; ?></td>
                        <td><?= $hn['KODESUP']; ?></td>
                        <td><?= $hn['NAMASUP']; ?></td>
                        <td><?= number_format($hn['HRG_CTN']); ?></td>
                        <td><?= number_format($hn['HRG_PCS']); ?></td>
                        <td><?= number_format($hn['HRG_MBT']); ?></td>
                        <td><?= number_format($hn['HRG_BOX']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>