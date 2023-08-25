<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $title ?></title>

        <link rel="stylesheet" href="<?= base_url('bootstrap/dist/css/bootstrap.min.css'); ?>">

        <!-- Style CSS -->
        <link rel="stylesheet" href="/css/style.css">
        <!-- Kalo gapake Laragon/XAMPP -->
        <link rel="stylesheet" href="/bootstrap/dist/css/bootstrap.min.css">
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
                <table class="table table-bordered border-dark table-sm">
                    <thead>
                        <tr>
                            <th>ID_KASIR</th>
                            <th>KASSA</th>
                            <th>NO_TRANSAKSI</th>
                            <th>TANGGAL</th>
                            <th>KD_MEMBER</th>
                            <th>FLAG</th>
                            <th>ID_CHECKER</th>
                            <th>TGL_CHECKER</th>
                            <th>PRIORITY</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($checker as $ck) : ?>
                        <tr>
                            <td><?=$ck['CASHIERID']; ?></td>
                            <td><?=$ck['CASHIERSTATION']; ?></td>
                            <td><?=$ck['TRANSACTIONNO']; ?></td>
                            <td><?=$ck['TRANSACTIONDATE']; ?></td>
                            <td><?=$ck['MEMBERCODE']; ?></td>
                            <td><?=$ck['FLAG']; ?></td>
                            <td><?=$ck['CHECKERID']; ?></td>
                            <td><?=$ck['CHECKERDATE']; ?></td>
                            <td><?=$ck['PRIORITY']; ?></td>
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