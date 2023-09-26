<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid mt-4">
    <!-- Item NonAktif -->
    <div class="row mb-3">
        <div class="col-md-12">
            <h3 class="text-primary">Item Non-Aktif</h3>
            <table class="table table-bordered border border-dark">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>PLU</th>
                        <th>Nama_Barang</th>
                        <th>Unit</th>
                        <th>Frac</th>
                        <th>Tag</th>
                        <th>Flag_IDM</th>
                        <th>AVGCost</th>
                        <th>Saldo_AKhir</th>
                        <th>PO_Outstanding</th>
                        <th>No_PO</th>
                        <th>Harga_beli</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($itemnonaktif)): ?>
                        <?php $no=1; ?>
                        <?php foreach($itemnonaktif as $item): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Tabel Prodcrm Belum ada -->
    <div class="row mb-3">
        <div class="col-md-12">
            <h3 class="text-primary">Tabel Prodcrm Belum Ada</h3>
            <table class="table table-bordered border border-dark">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>PLU</th>
                        <th>Nama_Barang</th>
                        <th>Unit</th>
                        <th>Frac</th>
                        <th>Tag</th>
                        <th>Flag_IDM</th>
                        <th>AVGCost</th>
                        <th>Saldo_AKhir</th>
                        <th>PO_Outstanding</th>
                        <th>No_PO</th>
                        <th>Harga_beli</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($itemnonaktif)): ?>
                        <?php $no=1; ?>
                        <?php foreach($itemnonaktif as $item): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Harga Beli Nol -->
    <div class="row mb-3">
        <div class="col-md-12">
            <h3 class="text-primary">Harga Beli Nol</h3>
            <table class="table table-bordered border border-dark">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>PLU</th>
                        <th>Nama_Barang</th>
                        <th>Unit</th>
                        <th>Frac</th>
                        <th>Tag</th>
                        <th>Flag_IDM</th>
                        <th>AVGCost</th>
                        <th>Saldo_AKhir</th>
                        <th>PO_Outstanding</th>
                        <th>No_PO</th>
                        <th>Harga_beli</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($itemnonaktif)): ?>
                        <?php $no=1; ?>
                        <?php foreach($itemnonaktif as $item): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Stock Nol dan PO Outstanding Nol -->
    <div class="row mb-3">
        <div class="col-md-12">
            <h3 class="text-primary">Stock Nol dan PO Outstanding Nol</h3>
            <table class="table table-bordered border border-dark">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>PLU</th>
                        <th>Nama_Barang</th>
                        <th>Unit</th>
                        <th>Frac</th>
                        <th>Tag</th>
                        <th>Flag_IDM</th>
                        <th>AVGCost</th>
                        <th>Saldo_AKhir</th>
                        <th>PO_Outstanding</th>
                        <th>No_PO</th>
                        <th>Harga_beli</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($itemnonaktif)): ?>
                        <?php $no=1; ?>
                        <?php foreach($itemnonaktif as $item): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Stock Nol dan PO Outstanding Ada -->
    <div class="row mb-3">
        <div class="col-md-12">
            <h3 class="text-primary">Stock Nol dan PO Outstanding Ada</h3>
            <table class="table table-bordered border border-dark">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>PLU</th>
                        <th>Nama_Barang</th>
                        <th>Unit</th>
                        <th>Frac</th>
                        <th>Tag</th>
                        <th>Flag_IDM</th>
                        <th>AVGCost</th>
                        <th>Saldo_AKhir</th>
                        <th>PO_Outstanding</th>
                        <th>No_PO</th>
                        <th>Harga_beli</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($itemnonaktif)): ?>
                        <?php $no=1; ?>
                        <?php foreach($itemnonaktif as $item): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
