<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid overflow-auto mt-3">
    <div class="row mb-3">
        <div class="col-sm-12">
            <h4 class="text-primary text-center fw-bold">PRODMAST NON HANOX</h4>
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
                    <?php if(!empty($nonhanox)): ?>
                    <?php foreach($nonhanox as $nh): ?>
                    <tr>
                        <td><?= $nh['DIV']; ?></td>
                        <td><?= $nh['DEP']; ?></td>
                        <td><?= $nh['KAT']; ?></td>
                        <td><?= $nh['PLU']; ?></td>
                        <td><?= $nh['DESKRIPSI']; ?></td>
                        <td><?= $nh['UNIT']; ?></td>
                        <td><?= $nh['FRAC']; ?></td>
                        <td><?= $nh['TAG']; ?></td>
                        <td><?= $nh['STOK']; ?></td>
                        <td><?= number_format($nh['AVGSALES']); ?></td>
                        <td><?= $nh['PKMT']; ?></td>
                        <td><?= number_format($nh['ACOST']); ?></td>
                        <td><?= number_format($nh['LASTCOST']); ?></td>
                        <td><?= $nh['PO']; ?></td>
                        <td><?= $nh['TGL_PO']; ?></td>
                        <td><?= $nh['KODESUP']; ?></td>
                        <td><?= $nh['NAMASUP']; ?></td>
                        <td><?= number_format($nh['HRG_CTN']); ?></td>
                        <td><?= number_format($nh['HRG_PCS']); ?></td>
                        <td><?= number_format($nh['HRG_MBT']); ?></td>
                        <td><?= number_format($nh['HRG_BOX']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>