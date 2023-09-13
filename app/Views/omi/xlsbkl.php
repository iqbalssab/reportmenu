<!DOCTYPE html>
<html lang="en">

<?php $ip = "http://".$_SERVER['SERVER_NAME']."/reportmenu/"; ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="<?= $ip; ?>public/fontawesome/css/fontawesome.css">
    <link rel="stylesheet" href="<?= $ip; ?>public/fontawesome/css/brands.css">
    <link rel="stylesheet" href="<?= $ip; ?>public/fontawesome/css/solid.css">
    <link rel="stylesheet" href="<?= $ip; ?>public/fontawesome/css/regular.css">
    <!-- Laragon -->
    <link rel="stylesheet" href="<?= $ip; ?>public/bootstrap/dist/css/bootstrap.min.css">

    <!-- Style CSS -->
    <link rel="stylesheet" href="<?= $ip; ?>public/css/style.css">
    <!-- Kalo gapake Laragon/XAMPP -->
    <!-- <link rel="stylesheet" href="/bootstrap/dist/css/bootstrap.min.css"> -->

        <!-- Favicons -->
    <link href="<?= $ip; ?>public/assets/img/igr2.png" rel="icon">

</head>

<body>


<div class="container mt-3">
    <h5>REKAP BKL OMI Tanggal : <?= $tglawal." s/d ".$tglakhir; ?> // OMI : <?= $omi ? $omi : "ALL"; ?></h5>
    <table class="table table-bordered table-hover text-sm">
        <thead>
            <tr>
                <th class="bg-primary text-light text-center">NO</th>
                <th class="bg-primary text-light text-center">OMI</th>
                <th class="bg-primary text-center text-light">IDFILE</th>
                <th class="bg-primary text-center text-light">NOBUKTI</th>
                <th class="bg-primary text-center text-light">TGLBUKTI</th>
                <th class="bg-primary text-center text-light">NODOC</th>
                <th class="bg-primary text-center text-light">TGLDOC</th>
                <th class="bg-primary text-center text-light">NOFAKTUR</th>
                <th class="bg-primary text-center text-light">TGLFAKTUR</th>
                <th class="bg-primary text-center text-light">NILAI_RPH</th>
                <th class="bg-primary text-center text-light">SUPPLIER</th>
                <th class="bg-primary text-center text-light">BY</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($rekapbkl)): ?>
                <?php $no = 1; ?>
                <?php foreach($rekapbkl as $rb): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $rb['BKL_KODEOMI']; ?></td>
                        <td><?= $rb['BKL_IDFILE']; ?></td>
                        <td><?= $rb['BKL_NOBUKTI']; ?></td>
                        <td><?= $rb['BKL_TGLBUKTI']; ?></td>
                        <td><?= $rb['MSTH_NODOC']; ?></td>
                        <td><?= $rb['MSTH_TGLDOC']; ?></td>
                        <td><?= $rb['MSTH_NOFAKTUR']; ?></td>
                        <td><?= $rb['MSTH_TGLFAKTUR']; ?></td>
                        <td><?= number_format($rb['NILAI']); ?></td>
                        <td><?= $rb['SUP_NAMASUPPLIER']; ?></td>
                        <td><?= $rb['BKL_CREATE_BY']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

    <script src="<?= $ip; ?>public/jquery-3.7.0.min.js"></script>
    <!-- <script src="jquery-3.7.0.min.js"></script> -->
    <script src="<?=$ip?>public/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="bootstrap/dist/js/bootstrap.bundle.min.js"></script> -->

    <script>
        $(function() {
           $('.dropdown').hover(function(){
            $(this).addClass('open');
           },
           function(){
            $(this).removeClass('open');
           }); 
        });
    </script>
</body>
</html>