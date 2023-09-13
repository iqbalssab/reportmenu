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
    <h3 class="fw-bold">Data Sales Per <?= $jenislaporan; ?> || Periode : <?= $tglawal; ?> s/d <?= $tglakhir; ?></h3>
    <p class="text-secondary">Jenis Transaksi : <b><?= $jenistransaksi; ?></b> || Jenis Member : <b><?= $jenismember; ?></b> || Pilihan Produk : <b><?= $jenisproduk; ?></b></p>

    <!-- PEr Tipe outlet -->
    <?php if(!empty($tipeoutlet)): ?>
        <table class="table">
            <thead>
                <tr>
                    <th class="">Outlet</th>
                    <th class="">SubOutlet</th>
                    <th class="">Hari</th>
                    <th class="">Member</th>
                    <th class="">Kunj</th>
                    <th class="">Slip</th>
                    <th class="">Produk</th>
                    <th class="">RphGross</th>
                    <th class="">RphNett</th>
                    <th class="">RphMargin</th>
                    <th class="">%</th>
                </tr>
            </thead>
            <tbody>
                <?php $totalMember = $totalKunj = $totalSlip = $totalProduk = $totalGross = $totalNett = $totalMargin = $totalPersen = 0; ?>
                <?php foreach($tipeoutlet as $out): ?>
                <tr>
                    <td><?= $out['KDOUTLET']." - ".$out['NAMAOUTLET']; ?></td>
                    <td><?= $out['KDSUBOUTLET']." - ".$out['NAMASUBOUTLET']; ?></td>
                    <td><?= $out['HARISALES']; ?></td>
                    <td><?= $out['JML_MEMBER']; ?></td>
                    <td><?= $out['KUNJUNGAN']; ?></td>
                    <td><?= $out['SLIP']; ?></td>
                    <td><?= $out['PRODUK']; ?></td>
                    <td><?= number_format($out['RPH_GROSS']); ?></td>
                    <td><?= number_format($out['S_NETT']); ?></td>
                    <td><?= number_format($out['MARGIN']); ?></td>
                    <td><?= number_format($out['MARGIN']/$out['S_NETT']*100,2,".",","); ?></td>
                </tr>
                <?php 
                    $totalMember += $out['JML_MEMBER'];
                    $totalKunj +=  $out['KUNJUNGAN'];
                    $totalSlip += $out['SLIP'];
                    $totalProduk += $out['PRODUK'];
                    $totalGross += $out['RPH_GROSS'];
                    $totalNett += $out['S_NETT'];
                    $totalMargin += $out['MARGIN'];
                    $totalPersen = $totalMargin/$totalNett*100;
                ?>
                <?php endforeach; ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="fw-bold"><?= number_format($totalMember); ?></td>
                    <td class="fw-bold"><?= number_format($totalKunj); ?></td>
                    <td class="fw-bold"><?= number_format($totalSlip); ?></td>
                    <td class="fw-bold"><?= number_format($totalProduk); ?></td>
                    <td class="fw-bold"><?= number_format($totalGross); ?></td>
                    <td class="fw-bold"><?= number_format($totalNett); ?></td>
                    <td class="fw-bold"><?= number_format($totalMargin); ?></td>
                    <?php if($totalMargin != 0): ?>
                    <td class="fw-bold"><?= number_format($totalPersen,2,",",","); ?></td>
                    <?php else: ?>
                    <td class="fw-bold"><?= number_format(0,2,",",","); ?></td>
                    <?php endif; ?>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>
    <!-- Per Member -->
    <?php if(!empty($member)): ?>
        <table class="table table-responsive-md table-striped mb-4">
            <thead>
                <tr>
                    <th class="">No.</th>
                    <th class="">KdMember</th>
                    <th class="">NamaMember</th>
                    <th class="">MM</th>
                    <th class="">Jenis</th>
                    <th class="">Outlet</th>
                    <th class="">SubOutlet</th>
                    <th class="">Segmentasi</th>
                    <th class="">Kunj</th>
                    <th class="">Slip</th>
                    <th class="">Produk</th>
                    <th class="">RphGross</th>
                    <th class="">RphNett</th>
                    <th class="">RphMargin</th>
                    <th class="">%</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $i = 1; 
                $totalKunj = 0;
                $totalSlip = 0;
                $totalProduk = 0;
                $totalGross = 0;
                $totalNett = 0;
                $totalMargin = 0;
                $totalPersen = 0;
                ?>

                <?php foreach($member as $mbr): ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= $mbr['KODEMEMBER']; ?></td>
                    <td><?= $mbr['NAMAMEMBER']; ?></td>
                    <td><?= $mbr['MM']; ?></td>
                    <td><?= $mbr['JENIS']; ?></td>
                    <td><?= $mbr['OUTLET']; ?></td>
                    <td><?= $mbr['SUBOUTLET']; ?></td>
                    <td><?= $mbr['IDSEGMEN']." - ".$mbr['SEGMENTASI']; ?></td>
                    <td><?= $mbr['KUNJUNGAN']; ?></td>
                    <td><?= $mbr['SLIP']; ?></td>
                    <td><?= $mbr['PRODUK']; ?></td>
                    <td><?= number_format($mbr['S_GROSS']); ?></td>
                    <td><?= number_format($mbr['S_NETT']); ?></td>
                    <td><?= number_format($mbr['MARGIN']); ?></td>
                    <td><?= number_format($mbr['MARGIN']/$mbr['S_NETT']*100,2,".",","); ?></td>
                </tr>
                <?php 
                    $totalKunj += $mbr['KUNJUNGAN'];
                    $totalSlip += $mbr['SLIP'];
                    $totalProduk += $mbr['PRODUK'];
                    $totalGross += $mbr['S_GROSS'];
                    $totalNett += $mbr['S_NETT'];
                    $totalMargin += $mbr['MARGIN'];
                    $totalPersen = $totalMargin/$totalNett;
                ; ?>
                <?php endforeach; ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="fw-bold"><?= number_format($totalKunj); ?></td>
                    <td class="fw-bold"><?= number_format($totalSlip); ?></td>
                    <td class="fw-bold"><?= number_format($totalProduk); ?></td>
                    <td class="fw-bold"><?= number_format($totalGross); ?></td>
                    <td class="fw-bold"><?= number_format($totalNett); ?></td>
                    <td class="fw-bold"><?= number_format($totalMargin); ?></td>
                    <td class="fw-bold"><?= number_format($totalPersen*100,2,".",","); ?></td>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>
    <!-- Per Produk -->
    <?php if(!empty($produk)): ?>
        <table class="table table-responsive-md table-striped mb-4">
            <thead>
                <tr>
                    <th class="">Div</th>
                    <th class="">Dep</th>
                    <th class="">Kat</th>
                    <th class="">PLU</th>
                    <th class="">Deskripsi</th>
                    <th class="">Hari</th>
                    <th class="">Slip</th>
                    <th class="">Member</th>
                    <th class="">QtySales</th>
                    <th class="">RphGross</th>
                    <th class="">RphNett</th>
                    <th class="">RphMargin</th>
                    <th class="">%</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $totalMember = 0; 
                $totalQty = 0; 
                $totalGross = 0; 
                $totalNett = 0; 
                $totalMargin = 0; 
                $totalPersen = 0; 
                ?>
                <?php foreach($produk as $prd): ?>
                <tr>
                    <td><?= $prd['DIV']; ?></td>
                    <td><?= $prd['DEP']; ?></td>
                    <td><?= $prd['KATB']; ?></td>
                    <td><?= $prd['PLU']; ?></td>
                    <td><?= $prd['DESKRIPSI']; ?></td>
                    <td><?= $prd['HARISALES']; ?></td>
                    <td><?= $prd['SLIP']; ?></td>
                    <td><?= $prd['JML_MEMBER']; ?></td>
                    <td><?= number_format($prd['QTY_SALES']); ?></td>
                    <td><?= number_format($prd['RPH_GROSS']); ?></td>
                    <td><?= number_format($prd['S_NETT']); ?></td>
                    <td><?= number_format($prd['MARGIN']); ?></td>
                    <td><?= number_format($prd['MARGIN']/$prd['S_NETT']*100,2,".",","); ?></td>
                </tr>
                <?php 
                    $totalMember += $prd['JML_MEMBER'];
                    $totalQty += $prd['QTY_SALES'];
                    $totalGross += $prd['RPH_GROSS'];
                    $totalNett += $prd['S_NETT'];
                    $totalMargin += $prd['MARGIN'];
                    $totalPersen = $totalMargin/$totalNett*100;
                ?>
                <?php endforeach; ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="fw-bold"><?= number_format($totalMember); ?></td>
                    <td class="fw-bold"><?= number_format($totalQty); ?></td>
                    <td class="fw-bold"><?= number_format($totalGross); ?></td>
                    <td class="fw-bold"><?= number_format($totalNett); ?></td>
                    <td class="fw-bold"><?= number_format($totalMargin); ?></td>
                    <td class="fw-bold"><?= number_format($totalPersen,2,".",","); ?></td>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>
    <!-- Per Bulan member tertentu -->
    <?php if(!empty($bulan) && !empty($membertertentu)): ?>
        <table class="table table-responsive-md table-striped mb-4">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>KdMember</th>
                    <th>NamaMember</th>
                    <th>Periode</th>
                    <th>Kunj</th>
                    <th>Slip</th>
                    <th>Produk</th>
                    <th>RphGross</th>
                    <th>RphNett</th>
                    <th>RphMargin</th>
                    <th>%</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $i = 1; 
                $totalKunj = 0;
                $totalSlip = 0;
                $totalProduk = 0;
                $totalGross = 0;
                $totalNett = 0;
                $totalMargin = 0;
                $totalPersen = 0;
                ?>
                <?php foreach($bulan as $bln): ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= $bln['KODEMEMBER']; ?></td>
                    <td><?= $bln['NAMAMEMBER']; ?></td>
                    <td><?= $bln['PERIODE']; ?></td>
                    <td><?= $bln['KUNJUNGAN']; ?></td>
                    <td><?= $bln['SLIP']; ?></td>
                    <td><?= $bln['PRODUK']; ?></td>
                    <td><?= number_format($bln['S_GROSS']); ?></td>
                    <td><?= number_format($bln['S_NETT']); ?></td>
                    <td><?= number_format($bln['MARGIN']); ?></td>
                    <td><?= number_format($bln['MARGIN']/$bln['S_NETT']*100,2,".",","); ?></td>
                </tr>
                <?php 
                $totalKunj += $bln['KUNJUNGAN']; 
                $totalSlip += $bln['SLIP']; 
                $totalProduk += $bln['PRODUK']; 
                $totalGross += $bln['S_GROSS']; 
                $totalNett += $bln['S_NETT']; 
                $totalMargin += $bln['MARGIN']; 
                $totalPersen = $totalMargin/$totalNett*100; 
                ?>
                <?php endforeach; ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="fw-bold"><?= number_format($totalKunj); ?></td>
                    <td class="fw-bold"><?= number_format($totalSlip); ?></td>
                    <td class="fw-bold"><?= number_format($totalProduk); ?></td>
                    <td class="fw-bold"><?= number_format($totalGross); ?></td>
                    <td class="fw-bold"><?= number_format($totalNett); ?></td>
                    <td class="fw-bold"><?= number_format($totalMargin); ?></td>
                    <td class="fw-bold"><?= number_format($totalPersen,2,".",","); ?></td>
                </tr>
            </tbody>
        </table>
    <?php elseif(!empty($bulan) && empty($membertertentu)): ?>
        <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
        <strong class="d-block">KODE MEMBER KOSONG!</strong> Anda Wajib Input Kode Member.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if(!empty($struk) && !empty($membertertentu)): ?>
        <table class="table table-responsive-md table-striped mb-4">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>KdMember</th>
                    <th>NamaMember</th>
                    <th>Tanggal</th>
                    <th>NoStruk</th>
                    <th>Kunj</th>
                    <th>Slip</th>
                    <th>Produk</th>
                    <th>RphGross</th>
                    <th>RphNett</th>
                    <th>Margin</th>
                    <th>%</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $i = 0; 
                $totalKunj = 0; 
                $totalSlip = 0; 
                $totalProduk = 0; 
                $totalGross = 0; 
                $totalNett = 0; 
                $totalMargin = 0; 
                $totalPersen = 0; 
                ?>

                <?php foreach($struk as $st): ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= $st['KODEMEMBER']; ?></td>
                    <td><?= $st['NAMAMEMBER']; ?></td>
                    <td><?= $st['TANGGAL']; ?></td>
                    <td><?= $st['NOSTRUK']; ?></td>
                    <td><?= $st['KUNJUNGAN']; ?></td>
                    <td><?= $st['SLIP']; ?></td>
                    <td><?= $st['PRODUK']; ?></td>
                    <td><?= number_format($st['S_GROSS']); ?></td>
                    <td><?= number_format($st['S_NETT']); ?></td>
                    <td><?= number_format($st['MARGIN']); ?></td>
                    <td><?= number_format($st['MARGIN']/$st['S_NETT']*100,2,".",","); ?></td>
                </tr>
                <?php 
                    $totalKunj += $st['KUNJUNGAN'];
                    $totalSlip += $st['SLIP'];
                    $totalProduk += $st['PRODUK'];
                    $totalGross += $st['S_GROSS'];
                    $totalNett += $st['S_NETT'];
                    $totalMargin += $st['MARGIN'];
                    $totalPersen = $totalMargin/$totalNett*100
                ?>
                <?php endforeach; ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="fw-bold"><?= $totalKunj; ?></td>
                    <td class="fw-bold"><?= $totalSlip; ?></td>
                    <td class="fw-bold"><?= $totalProduk; ?></td>
                    <td class="fw-bold"><?= number_format($totalGross); ?></td>
                    <td class="fw-bold"><?= number_format($totalNett); ?></td>
                    <td class="fw-bold"><?= number_format($totalMargin); ?></td>
                    <?php if($totalMargin !=0): ?>
                    <td class="fw-bold"><?= number_format($totalPersen,2,".",","); ?></td>
                    <?php else: ?>
                    <td>0</td>
                    <?php endif; ?>
                </tr>
            </tbody>
        </table>
    <?php elseif(!empty($struk) && empty($membertertentu)): ?>
        <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
        <strong class="d-block">KODE MEMBER KOSONG!</strong> Anda Wajib Input Kode Member.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
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