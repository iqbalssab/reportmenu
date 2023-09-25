<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Data Sales Per Jam</title>
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
            $totgross = $totnet = $totmrg = $no = 0;
            $jenisMbr = "biru";
            if(isset($_GET['akhir'])) {if ($_GET['akhir'] !=""){$tanggalSelesai = $_GET['akhir']; }}
            if(isset($_GET['jenisLaporan'])) {if ($_GET['jenisLaporan'] !=""){$jenisLaporan = $_GET['jenisLaporan']; }}
            if(isset($_GET['mbr'])) {if ($_GET['mbr'] !=""){$jenisMbr = $_GET['mbr']; }}
            if ($jenisMbr != "biru" AND $jenisMbr != "") {
                $mbr = " Merah ";
            } else {
                $mbr = " Biru ";
            }
        ?>

        <?php if($jenisLaporan == "1") { ?>
            <h3 class="fw-bold">Sales per Jam - Member <?= $mbr; ?></h3>
            <h4>Tanggal : <?= date('d M Y',strtotime($tanggalSelesai)) ?></h4>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th class="fw-bold text-center text-nowrap bg-info">Periode</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Tgl</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Jam</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Jml. Struk</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Jml. Member</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Sales Gross</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Sales Nett</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Margin Rph</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php 
                        $no++;
                        foreach($perjam as $sl) : ?>
                        <tr>
                            <td class="text-center text-nowrap"><?= $sl['PERIODE']; ?></td>
                            <td class="text-center text-nowrap"><?= $sl['TANGGAL']; ?></td>
                            <td class="text-center text-nowrap"><?= $sl['JAM']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['JLMBR'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['GROSS'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['NETTO'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['MGN'], 0, '.', ','); ?></td>
                        </tr>
                        <?php
                            $totgross         += $sl['GROSS'];
                            $totnet         += $sl['NETTO'];
                            $totmrg           += $sl['MGN'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="5" class="fw-bold text-center">Total</td>
                        <td class="fw-bold text-end"><?= number_format($totgross, 0, '.', ','); ?></td>
                        <td class="fw-bold text-end"><?= number_format($totnet, 0, '.', ','); ?></td>
                        <td class="fw-bold text-end"><?= number_format($totmrg, 0, '.', ','); ?></td>
                    </tr>
                </tfoot>
            </table>
        <?php } else if($jenisLaporan == "2") { ?>
            <h3 class="fw-bold">Sales per Jam per Hari - Member <?= $mbr; ?></h3>
            <h4>Bulan : <?= date('M',strtotime($tanggalSelesai)) ?></h4>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center text-nowrap bg-info">Jam</th>
                        <?php
							for ($x=1; $x<=31; $x++) {
								echo '<th colspan="3" class="fw-bold text-center bg-info">Tgl ' . $x . '</th>';
							}
						?> 
                    </tr>
                    <tr>
                        <?php
							for ($x=1; $x<=31; $x++) {
								//echo "The number is: $x <br>";
								echo '<th class="fw-bold text-center bg-info">Struk</th><th class="fw-bold text-center bg-info">Member</th><th class="fw-bold text-center bg-info">Sales</th>';
							}
						?>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php 
                        foreach($perjam as $sl) : ?>
                        <tr>
                            <td class="text-center text-nowrap"><?= $sl['JAM']; ?>.00 - <?= $sl['JAM']; ?>.59</td>
                            <td class="text-end text-nowrap"><?= number_format($sl['1_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['1_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['1_BELANJA'], 0, '.', ','); ?></td>
                            
                            <td class="text-end text-nowrap"><?= number_format($sl['2_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['2_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['2_BELANJA'], 0, '.', ','); ?></td>
                            
                            <td class="text-end text-nowrap"><?= number_format($sl['3_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['3_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['3_BELANJA'], 0, '.', ','); ?></td>
                            
                            <td class="text-end text-nowrap"><?= number_format($sl['4_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['4_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['4_BELANJA'], 0, '.', ','); ?></td>
                            
                            <td class="text-end text-nowrap"><?= number_format($sl['5_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['5_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['5_BELANJA'], 0, '.', ','); ?></td>
                            
                            <td class="text-end text-nowrap"><?= number_format($sl['6_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['6_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['6_BELANJA'], 0, '.', ','); ?></td>
                            
                            <td class="text-end text-nowrap"><?= number_format($sl['7_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['7_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['7_BELANJA'], 0, '.', ','); ?></td>
                            
                            <td class="text-end text-nowrap"><?= number_format($sl['8_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['8_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['8_BELANJA'], 0, '.', ','); ?></td>
                        
                            <td class="text-end text-nowrap"><?= number_format($sl['9_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['9_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['9_BELANJA'], 0, '.', ','); ?></td>
                            
                            <td class="text-end text-nowrap"><?= number_format($sl['10_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['10_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['10_BELANJA'], 0, '.', ','); ?></td>
                            
                            <td class="text-end text-nowrap"><?= number_format($sl['11_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['11_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['11_BELANJA'], 0, '.', ','); ?></td>
                            
                            <td class="text-end text-nowrap"><?= number_format($sl['12_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['12_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['12_BELANJA'], 0, '.', ','); ?></td>
                            
                            <td class="text-end text-nowrap"><?= number_format($sl['13_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['13_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['13_BELANJA'], 0, '.', ','); ?></td>
                            
                            <td class="text-end text-nowrap"><?= number_format($sl['14_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['14_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['14_BELANJA'], 0, '.', ','); ?></td>
                            
                            <td class="text-end text-nowrap"><?= number_format($sl['15_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['15_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['15_BELANJA'], 0, '.', ','); ?></td>
                            
                            <td class="text-end text-nowrap"><?= number_format($sl['16_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['16_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['16_BELANJA'], 0, '.', ','); ?></td>
                            
                            <td class="text-end text-nowrap"><?= number_format($sl['17_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['17_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['17_BELANJA'], 0, '.', ','); ?></td>
                            
                            <td class="text-end text-nowrap"><?= number_format($sl['18_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['18_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['18_BELANJA'], 0, '.', ','); ?></td>
                            
                            <td class="text-end text-nowrap"><?= number_format($sl['19_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['19_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['19_BELANJA'], 0, '.', ','); ?></td>
                            
                            <td class="text-end text-nowrap"><?= number_format($sl['20_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['20_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['20_BELANJA'], 0, '.', ','); ?></td>
                            
                            <td class="text-end text-nowrap"><?= number_format($sl['21_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['21_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['21_BELANJA'], 0, '.', ','); ?></td>
                            
                            <td class="text-end text-nowrap"><?= number_format($sl['22_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['22_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['22_BELANJA'], 0, '.', ','); ?></td>
                            
                            <td class="text-end text-nowrap"><?= number_format($sl['23_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['23_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['23_BELANJA'], 0, '.', ','); ?></td>
                            
                            <td class="text-end text-nowrap"><?= number_format($sl['24_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['24_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['24_BELANJA'], 0, '.', ','); ?></td>
                            
                            <td class="text-end text-nowrap"><?= number_format($sl['25_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['25_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['25_BELANJA'], 0, '.', ','); ?></td>
                            
                            <td class="text-end text-nowrap"><?= number_format($sl['26_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['26_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['26_BELANJA'], 0, '.', ','); ?></td>
                            
                            <td class="text-end text-nowrap"><?= number_format($sl['27_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['27_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['27_BELANJA'], 0, '.', ','); ?></td>
                            
                            <td class="text-end text-nowrap"><?= number_format($sl['28_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['28_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['28_BELANJA'], 0, '.', ','); ?></td>
                            
                            <td class="text-end text-nowrap"><?= number_format($sl['29_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['29_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['29_BELANJA'], 0, '.', ','); ?></td>
                            
                            <td class="text-end text-nowrap"><?= number_format($sl['30_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['30_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['30_BELANJA'], 0, '.', ','); ?></td>
                            
                            <td class="text-end text-nowrap"><?= number_format($sl['31_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['31_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['31_BELANJA'], 0, '.', ','); ?></td>

                        </tr>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    
                </tfoot>
            </table>
        <?php } ?>
    </body>
</html>