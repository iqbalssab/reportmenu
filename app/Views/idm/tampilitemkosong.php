<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Data Item Kosong PB IDM</title>
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
            $kodePLU = $kodeID = "All"; 
            $no = 1;

            if(isset($_GET['plu'])) {if ($_GET['plu'] !=""){$kodePLU = $_GET['plu']; }}
            if ($kodePLU != "All" AND $kodeID != "") {
                $kodePLU = substr('00000000' . $kodePLU, -7);   
            }
            if(isset($_GET['notrans'])) {if ($_GET['notrans'] !=""){$kodeID = $_GET['notrans']; }}
            if ($kodeID  != "All" AND $kodeID != "") {
                $kodeID = substr('000000' . $kodeID, -5);   
            }
        ?>
        <?php if($jenisLaporan == '1') { ?>
            <h4 class="fw-bold">Laporan Per PLU</h4>
            <h5>Periode : <?= date('d M Y',strtotime($tanggalMulai)) ?> s/d <?= date('d M Y',strtotime($tanggalSelesai)) ?></h5>
            <h6>PLU : <?= $kodePLU; ?>, No. Trans : <?= $kodeID; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark" style="width: 800px;">
                <thead class="table-group-divider">
                    <tr>
                        <th class="fw-bold text-center bg-info">#</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Tgl. Trans</th>
                        <th class="fw-bold text-center bg-info text-nowrap">No. Trans</th>
                        <th class="fw-bold text-center bg-info text-nowrap">PLU</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Deskripsi</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Qty Order</th>
                        <th class="fw-bold text-center bg-info text-nowrap">LPP</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($klik as $kl) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center text-nowrap"><?= $kl['TGLTRANS']; ?></td>
                            <td class="text-center text-nowrap"><?= $kl['NOTRANS']; ?></td>
                            <td class="text-center text-nowrap"><?= $kl['PLU']; ?></td>
                            <td class="text-start text-nowrap"><?= $kl['DESK']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($kl['QTYORDER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($kl['LPP'], 0, '.', ','); ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        <?php } ?>
    </body>
</html>