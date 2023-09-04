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

<div class="container-fluid mt-3">
    <h3 class="fw-bold"><?= $judul; ?></h3>
    <?php if($stokdep): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>SUPPLIER</th>
                    <th>DIV</th>
                    <th>DEP</th>
                    <th>KAT</th>
                    <th>PLU</th>
                    <th>DESKRIPSI</th>
                    <th>FRAC</th>
                    <th>TAG</th>
                    <th>BKP</th>
                    <th>STOK AWAL</th>
                    <th>TRFIN</th>
                    <th>TRFOUT</th>
                    <th>SALES</th>
                    <th>RETUR</th>
                    <th>ADJ</th>
                    <th>INTRANSIT</th>
                    <th>STOK AKHIR <?= $tglskg; ?></th>
                    <th>PICKING OMI <?= $tgl; ?></th>
                    <th>AVGSALES</th>
                    <th>DSI</th>
                    <th>ACOST</th>
                    <th>HRG NORMAL</th>
                    <th>% MRG</th>
                    <th>HRG PROMO</th>
                    <th>% MRG</th>
                    <th>DISPLAY</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($stokdep as $sd): ?>
                <tr>
                    <td><?= $sd['KDSUPPLIER']." - ".$sd['NAMASUPPLIER']; ?></td>
                    <td><?= $sd['DIV']; ?></td>
                    <td><?= $sd['DEPT']; ?></td>
                    <td><?= $sd['KATB']; ?></td>
                    <td><?= $sd['PLU']; ?></td>
                    <td><?= $sd['DESKRIPSI']; ?></td>
                    <td><?= $sd['FRAC']; ?></td>
                    <td><?= $sd['TAG']; ?></td>
                    <td><?= $sd['BKP']; ?></td>
                    <td><?= $sd['STOCKAWAL']; ?></td>
                    <td><?= $sd['TRFIN']; ?></td>
                    <td><?= $sd['TRFOUT']; ?></td>
                    <td><?= $sd['SALES']; ?></td>
                    <td><?= $sd['RETUR']; ?></td>
                    <td><?= $sd['ADJ']; ?></td>
                    <td><?= $sd['INTRANSIT']; ?></td>
                    <td><?= $sd['STOCKAKHIR']; ?></td>
                    <td><?= $sd['PICKING_OMI']; ?></td>
                    <td><?= $sd['AVGSALES']; ?></td>
                        <?php if($sd['SALES']==0){
                            $dsi = 0;
                        }else{
                            $dsi = ($sd['STOCKAWAL']+$sd['STOCKAKHIR']/2)/$sd['SALES']*$jmlhari;
                        }
                        ?>
                    <td><?= $dsi; ?></td>
                    <td><?= $sd['ACOST']; ?></td>
                    <td><?= $sd['HRGNORMAL']; ?></td>
                    <td><?= $sd['MRG1']; ?></td>
                    <td><?= $sd['HRGPROMO']; ?></td>
                    <td><?= $sd['MRG2']; ?></td>
                    <td><?= $sd['LKS_DISPLAY']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
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