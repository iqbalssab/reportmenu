<?php $ip = "http://".$_SERVER['SERVER_NAME']."/reportmenu/"; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $title ?></title>

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
        <?php if(!empty($checker)): ?>
            <div class="container-fluid mt-3">
                <div class="row mb-2">
                    <div class="col judul-data">
                        <h3 class="fw-bold">Monitoring Checker</h3>
                        <h5 class="fw-bold">Periode : <?= date('d-M-Y',strtotime($tanggal)); ?></h5>
                        <br><br>
                    </div>
                </div>
                <table class="table-bordered border-dark table-sm">
                    <thead>
                        <tr>
                            <th class="text-center">ID_KASIR</th>
                            <th class="text-center">KASSA</th>
                            <th class="text-center">NO_TRANSAKSI</th>
                            <th class="text-center">TANGGAL</th>
                            <th class="text-center">KD_MEMBER</th>
                            <th class="text-center">FLAG</th>
                            <th class="text-center">ID_CHECKER</th>
                            <th class="text-center">TGL_CHECKER</th>
                            <th class="text-center">PRIORITY</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($checker as $ck) : ?>
                        <tr>
                            <td class="text-center"><?=$ck['CASHIERID']; ?></td>
                            <td class="text-center"><?=$ck['CASHIERSTATION']; ?></td>
                            <td class="text-center"><?=$ck['TRANSACTIONNO']; ?></td>
                            <td class="text-center"><?=$ck['TRANSACTIONDATE']; ?></td>
                            <td class="text-center"><?=$ck['MEMBERCODE']; ?></td>
                            <td class="text-center"><?=$ck['FLAG']; ?></td>
                            <td class="text-center"><?=$ck['CHECKERID']; ?></td>
                            <td class="text-center"><?=$ck['CHECKERDATE']; ?></td>
                            <td class="text-center"><?=$ck['PRIORITY']; ?></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                <div class="">
                    <p style="font-size:small"><b><i>** Dicetak pada : <?php echo date('d M Y') ?> **</i></b></p>
                </div>
            </div>
        <?php endif; ?>
    </body>
</html>