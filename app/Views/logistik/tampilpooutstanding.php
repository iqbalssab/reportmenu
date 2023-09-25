<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Data PO Outstanding</title>
        <?php $ip = "http://".$_SERVER['SERVER_NAME']."/reportmenu/"; ?>

        <link rel="stylesheet" href="<?= $ip; ?>public/bootstrap/dist/css/bootstrap.min.css">
        <style>
            .container {border:3px solid #666;padding:10px;margin:0 auto;width:500px}
            input {margin:5px;}
            table, th, td {
                border: 1px solid black;
                border-collapse: collapse;
                margin:0 0 10px;
                width:auto;
            }
            th{
                background:#66CCFF;
                padding:5px;
                font-weight:400;
                font-size:12px;
            }
            td{
                padding:2px 5px;
                font-size:12px;
                text-overflow: ellipsis;
            }
            h1, h2, h3, h4, h5, h6{
                font-weight: bold;
            }
            h3, h4 {
                font-size: 18px;
            }
            h5, h6 {
                font-size: 14px;
            }
        </style>
    </head>
    <body>
        <?php
            $kodeSupplier = $namaSupplier = $dvs = $dep = $katbr = $kdsup = $nmsup = "All";
            $no = 0;
            foreach($divisi as $dv) :
                if($kodeDivisi == $dv['DIV_KODEDIVISI']) {
                    $dvs = $dv['DIV_NAMADIVISI'];
                }
            endforeach;

            foreach($departemen as $dp) :
                if($kodeDepartemen == $dp['DEP_KODEDEPARTEMENT']) {
                    $dep = $dp['DEP_NAMADEPARTEMENT'];
                }
            endforeach;
    
            foreach($kategori as $kt) :
                if($kodeKategoriBarang == $kt['KAT_KODEDEPARTEMENT'].$kt['KAT_KODEKATEGORI'] ) {
                    $katbr = $kt['KAT_NAMAKATEGORI'];
                }
            endforeach;

            if(isset($_GET['kdsup'])) {if ($_GET['kdsup'] !=""){$kodeSupplier = $_GET['kdsup']; }}
            if ($kodeSupplier != "All" AND $kodeSupplier != "") {
                $kdsup = $kodeSupplier;
            }
            if(isset($_GET['nmsup'])) {if ($_GET['nmsup'] !=""){$namaSupplier = $_GET['nmsup']; }}
            if ($namaSupplier != "All" AND $namaSupplier != "") {
                $nmsup = $namaSupplier;
            }
        ?>
        <h4 class="fw-bold">PO Outstanding - Divisi : <?= $dvs; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr; ?></h4>
        <h5>Periode : <?= date('d M Y') ?></h5>
        <h6>Supplier : <?= $kdsup; ?>, Nama Supplier : <?= $nmsup; ?></h6>
        <br>
        <table class="table table-responsive table-striped table-hover table-bordered border-dark">
            <thead class="table-group-divider">
                <tr>
                    <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">#</th>
                    <th colspan="3" class="fw-bold text-center bg-info text-no-wrap">Divisi</th>
                    <th colspan="7" class="fw-bold text-center bg-info text-no-wrap">Produk</th>
                    <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Stock</th>
                    <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">PKMT</th>
                    <th colspan="2" class="fw-bold text-center bg-info text-no-wrap">PO</th>
                    <th colspan="2" class="fw-bold text-center bg-info text-no-wrap">PB</th>
                    <th colspan="2" class="fw-bold text-center bg-info text-no-wrap">PO+PB</th>
                    <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Supplier</th>
                    <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Keterangan</th>
                </tr>
                <tr>
                    <th class="fw-bold text-center bg-info text-nowrap">Div</th>
                    <th class="fw-bold text-center bg-info text-nowrap">Dept</th>
                    <th class="fw-bold text-center bg-info text-nowrap">Katb</th>
                    <th class="fw-bold text-center bg-info text-nowrap">PLU</th>
                    <th class="fw-bold text-center bg-info text-nowrap">Deskripsi</th>
                    <th class="fw-bold text-center bg-info text-nowrap">Tag</th>
                    <th class="fw-bold text-center bg-info text-nowrap">Unit</th>
                    <th class="fw-bold text-center bg-info text-nowrap">Frac</th>
                    <th class="fw-bold text-center bg-info text-nowrap">Acost</th>
                    <th class="fw-bold text-center bg-info text-nowrap">Lcost</th>
                    <th class="fw-bold text-center bg-info text-nowrap">Qty PO</th>
                    <th class="fw-bold text-center bg-info text-nowrap">Rph PO</th>
                    <th class="fw-bold text-center bg-info text-nowrap">Qty PB</th>
                    <th class="fw-bold text-center bg-info text-nowrap">Rph PB</th>
                    <th class="fw-bold text-center bg-info text-nowrap">Qty PO+PB</th>
                    <th class="fw-bold text-center bg-info text-nowrap">Rph PO+PB</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                <?php 
                    $no++;
                    foreach($outstanding as $po) :
                ?>
                    <tr>
                        <td class="text-end"><?= $no++; ?></td>
                        <td class="text-center"><?= $po['DIV']; ?></td>
                        <td class="text-center"><?= $po['DEP']; ?></td>
                        <td class="text-center"><?= $po['KAT']; ?></td>
                        <td class="text-center"><?= $po['PLU']; ?></td>
                        <td class="text-start text-nowrap"><?= $po['DESKRIPSI']; ?></td>
                        <td class="text-center"><?= $po['TAG']; ?></td>
                        <td class="text-center"><?= $po['UNIT']; ?></td>
                        <td class="text-center"><?= $po['FRAC']; ?></td>
                        <td class="text-end"><?= number_format($po['ACOST'], 0, '.', ','); ?></td>
                        <td class="text-end"><?= number_format($po['LCOST'], 0, '.', ','); ?></td>
                        <td class="text-end"><?= number_format($po['STOK'], 0, '.', ','); ?></td>
                        <td class="text-end"><?= number_format($po['PKMT'], 0, '.', ','); ?></td>
                        <td class="text-end"><?= number_format($po['QTY_PO'], 0, '.', ','); ?></td>
                        <td class="text-end"><?= number_format($po['RPH_PO'], 0, '.', ','); ?></td>
                        <td class="text-end"><?= number_format($po['QTY_PB'], 0, '.', ','); ?></td>
                        <td class="text-end"><?= number_format($po['RPH_PB'], 0, '.', ','); ?></td>
                        <td class="text-end"><?= number_format($po['QTY_PO_PB'], 0, '.', ','); ?></td>
                        <td class="text-end"><?= number_format($po['RPH_PO_PB'], 0, '.', ','); ?></td>
                        <td class="text-start text-nowrap"><?= $po['SUPPLIER']; ?></td>
                        <td></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </body>
</html>