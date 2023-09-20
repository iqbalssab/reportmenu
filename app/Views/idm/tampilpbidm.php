<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Data Tolakan PB IDM</title>
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
            $tanggalMulai = $tanggalSelesai = date("Ymd");
            $orderToko = $orderItem = $orderQty = $orderRph = $realItem  = $realQty = $realRph = $rphTolakan = 0;
            $kodeTokoOMI = $kodeTolakan          = "All"; 
            $no = 1;
            if(isset($_GET['tokoOmi'])) {if ($_GET['tokoOmi'] !=""){$kodeTokoOMI = $_GET['tokoOmi']; }}
            if(isset($_GET['tolakan'])) {if ($_GET['tolakan'] !=""){$kodeTolakan = $_GET['tolakan']; }}
        ?>
        <?php if($jenisLaporan == '1') { ?>
            <h3 class="fw-bold">Laporan Per Produk</h3>
            <h4>Periode : <?= date('d M Y',strtotime($tanggalMulai)) ?> s/d <?= date('d M Y',strtotime($tanggalSelesai)) ?></h4>
            <h4>Kode Toko OMI : <?= $kodeTokoOMI; ?>, Jenis Tolakan : <?= $kodeTolakan; ?></h4>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark" style="width: 800px;">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">#</th>
                        <th colspan="4" class="fw-bold text-center bg-info text-nowrap">Produk</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">Keterangan</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">LPP</th>
                        <th colspan="5" class="fw-bold text-center bg-info text-nowrap">Summary</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center bg-info text-nowrap">PLU OMI</th>
                        <th class="fw-bold text-center bg-info text-nowrap">PLU IGR</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Deskripsi</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Tag</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Qty Order</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Rph Order</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Toko OMI</th>
                        <th class="fw-bold text-center bg-info text-nowrap">No PB</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Hari / Tanggal</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($tolakan as $tl) : ?>
                        <tr>
                            <td class="text-end text-nowrap"><?= $no++; ?></td>
                            <td class="text-center text-nowrap"><?= $tl['TLKO_PLUOMI']; ?></td>
                            <td class="text-center text-nowrap"><?= $tl['TLKO_PLUIGR']; ?></td>
                            <td class="text-start text-nowrap"><?= $tl['TLKO_DESC']; ?></td>
                            <td class="text-center text-nowrap"><?= $tl['TLKO_PTAG']; ?></td>
                            <td class="text-start text-nowrap"><?= $tl['TLKO_KETTOLAKAN']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tl['TLKO_LPP'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tl['TLKO_QTYORDER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tl['TLKO_NILAI'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tl['TLKO_KODE_OMI'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tl['TLKO_NOPB'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= $tl['TLKO_TANGGAL']; ?></td>
                            <td class="text-end text-nowrap"></td>
                            <?php
                                $rphTolakan += $tl['TLKO_NILAI'];
                            ?>
                        </tr>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="8" class="fw-bold text-center">Total</td>
                        <td class="fw-bold text-center"><?= number_format($rphTolakan, 0, '.', ',');?></td>
                        <td colspan="4"></td>
                    </tr>
                </tfoot>
            </table>
        <?php } ?>
    </body>
</html>