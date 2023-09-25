<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="container-fluid mt-3 overflow-auto">
    <div class="row">
        <div class="col-md-12">
            <h2 class="fw-bold">Stock Tag N</h2>    
            <table class="table mb-3 table-striped table-bordered table-responsive border-dark" style="font-size: 15px;">
                <thead class="table-success">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center text-nowrap bg-info">#</th>
                        <th colspan="3" class="fw-bold text-center text-nowrap bg-info">Divisi</th>
                        <th colspan="6" class="fw-bold text-center text-nowrap bg-info">Produk</th>
                        <th colspan="3" class="fw-bold text-center text-nowrap bg-info">Stock</th>
                        <th colspan="3" class="fw-bold text-center text-nowrap bg-info">Supplier</th>
                        <th rowspan="2" class="fw-bold text-center text-nowrap bg-info">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center text-nowrap bg-info">Div</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Dep</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Kat</th>
                        <th class="fw-bold text-center text-nowrap bg-info">PLU</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Deskripsi</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Unit</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Frac</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Tag</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Acost</th>
                        <th class="fw-bold text-center text-nowrap bg-info">CTN</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Pcs</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Rupiah</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Kode</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Nama</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Status</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php
                        $no = 0;
                        $no++;
                        foreach($tagn as $tag) :
                    ?>
                        <tr>
                            <td class="text-end text-nowrap"><?= $no++; ?></td>
                            <td class="text-center text-nowrap"><?= $tag['ST_DIV']; ?></td>
                            <td class="text-center text-nowrap"><?= $tag['ST_DEPT']; ?></td>
                            <td class="text-center text-nowrap"><?= $tag['ST_KATB']; ?></td>
                            <td class="text-center text-nowrap"><?= $tag['ST_PRDCD']; ?></td>
                            <td class="text-start text-nowrap"><?= $tag['ST_DESKRIPSI']; ?></td>
                            <td class="text-center text-nowrap"><?= $tag['ST_UNIT']; ?></td>
                            <td class="text-center text-nowrap"><?= $tag['ST_FRAC']; ?></td>
                            <td class="text-center text-nowrap"><?= $tag['ST_KODETAG']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tag['ST_AVGCOST'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tag['ST_SALDO_CTN'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tag['ST_SALDO_PCS'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tag['ST_SALDO_RPH'], 0, '.', ','); ?></td>
                            <td class="text-center text-nowrap"><?= $tag['ST_SUPP_KODE']; ?></td>
                            <td class="text-start text-nowrap"><?= $tag['ST_SUPP_NAMA']; ?></td>
                            <td class="text-center text-nowrap"><?= $tag['ST_PERLAKUAN_BARANG']; ?></td>
                            <td class="text-center text-nowrap"></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <?php 	if ($no == 0){ echo '<p>Data tidak ada!</p>'; } ?>
            <br>
            <h2 class="fw-bold">Stock Tag X</h2>    
            <table class="table mb-3 table-striped table-bordered table-responsive border-dark" style="font-size: 15px;">
                <thead class="table-success">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center text-nowrap bg-info">#</th>
                        <th colspan="3" class="fw-bold text-center text-nowrap bg-info">Divisi</th>
                        <th colspan="6" class="fw-bold text-center text-nowrap bg-info">Produk</th>
                        <th colspan="3" class="fw-bold text-center text-nowrap bg-info">Stock</th>
                        <th colspan="3" class="fw-bold text-center text-nowrap bg-info">Supplier</th>
                        <th rowspan="2" class="fw-bold text-center text-nowrap bg-info">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center text-nowrap bg-info">Div</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Dep</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Kat</th>
                        <th class="fw-bold text-center text-nowrap bg-info">PLU</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Deskripsi</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Unit</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Frac</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Tag</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Acost</th>
                        <th class="fw-bold text-center text-nowrap bg-info">CTN</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Pcs</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Rupiah</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Kode</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Nama</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Status</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php
                        $no = 0;
                        $no++;
                        foreach($tagx as $tag) :
                    ?>
                        <tr>
                            <td class="text-end text-nowrap"><?= $no++; ?></td>
                            <td class="text-center text-nowrap"><?= $tag['ST_DIV']; ?></td>
                            <td class="text-center text-nowrap"><?= $tag['ST_DEPT']; ?></td>
                            <td class="text-center text-nowrap"><?= $tag['ST_KATB']; ?></td>
                            <td class="text-center text-nowrap"><?= $tag['ST_PRDCD']; ?></td>
                            <td class="text-start text-nowrap"><?= $tag['ST_DESKRIPSI']; ?></td>
                            <td class="text-center text-nowrap"><?= $tag['ST_UNIT']; ?></td>
                            <td class="text-center text-nowrap"><?= $tag['ST_FRAC']; ?></td>
                            <td class="text-center text-nowrap"><?= $tag['ST_KODETAG']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tag['ST_AVGCOST'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tag['ST_SALDO_CTN'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tag['ST_SALDO_PCS'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tag['ST_SALDO_RPH'], 0, '.', ','); ?></td>
                            <td class="text-center text-nowrap"><?= $tag['ST_SUPP_KODE']; ?></td>
                            <td class="text-start text-nowrap"><?= $tag['ST_SUPP_NAMA']; ?></td>
                            <td class="text-center text-nowrap"><?= $tag['ST_PERLAKUAN_BARANG']; ?></td>
                            <td class="text-center text-nowrap"></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <?php 	if ($no == 0){ echo '<p>Data tidak ada!</p>'; } ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>