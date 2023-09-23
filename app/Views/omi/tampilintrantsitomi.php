<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Data Intransit OMI</title>
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
            $realToko = $realItem = $realQty = $realRph = $realPPN = $realDF = $realDFPPN    = 0;
            $kodeTokoOMI = $kodeTokoIDM = $$kodePLU = $kodeSupplier = $namaSupplier = $jenisLaporan = $dvs = $dep = $katbr = $kdsup = $nmsup = $tkomi = $tkidm = "All";
            $no = 1;
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

            if(isset($_GET['tokoOmi'])) {if ($_GET['tokoOmi'] !=""){$kodeTokoOMI = $_GET['tokoOmi']; }}
            foreach($tokoOmi as $tk) :
                if($kodeTokoOMI == $tk['TKO_KODEOMI']) {
                    $tkomi = $tk['TKO_NAMAOMI'];
                }
            endforeach;
            
            if(isset($_GET['tokoIdm'])) {if ($_GET['tokoIdm'] !=""){$kodeTokoIDM = $_GET['tokoIdm']; }}
            foreach($tokoIdm as $id) :
                if($kodeTokoIDM == $id['TKO_KODEOMI']) {
                    $tkidm = $id['TKO_NAMAOMI'];
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
            if(isset($_GET['plu'])) {if ($_GET['plu'] !=""){$kodePLU = $_GET['plu']; }}
            if ($kodePLU != "All" AND $kodePLU != "") {
                $kodePLU = substr('00000000' . $kodePLU, -7);
            } 
            if(isset($_GET['jenisLaporan'])) {if ($_GET['jenisLaporan'] !=""){$jenisLaporan = $_GET['jenisLaporan']; }}
        ?>

        <?php if($jenisLaporan == "1") { ?>
            <h4 class="fw-bold"><?= $jlap; ?> - Divisi : <?= $dvs; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr; ?></h4>
            <h5>Periode : <?= date('d M Y') ?></h5>
            <h6>Toko OMI : <?= $tkomi; ?>, Toko IDM : <?= $tkidm; ?></h6>
            <h6>PLU : <?= $kodePLU; ?>, Supplier : <?= $kdsup; ?>, Nama Supplier : <?= $nmsup; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">#</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-no-wrap">Toko OMI</th>
                        <th colspan="7" class="fw-bold text-center bg-info text-no-wrap">Intransit</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center bg-info text-nowrap">Kode</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nama</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Item</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Qty</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Rupiah</th>
                        <th class="fw-bold text-center bg-info text-nowrap">PPN</th>
                        <th class="fw-bold text-center bg-info text-nowrap">DisFee</th>
                        <th class="fw-bold text-center bg-info text-nowrap">DisFee PPN</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Total</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($intransitomi as $omi) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $omi['RPB_KODE_OMI']; ?></td>
                            <td class="text-start text-nowrap"><?= $omi['RPB_NAMA_OMI']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_ITEM'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_QTY'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_TOTAL'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_PPN'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_DF'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_DF_PPN'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_TOTAL'] + $omi['RPB_RPH_PPN'] + $omi['RPB_RPH_DF'] + $omi['RPB_RPH_DF_PPN'], 0, '.', ','); ?></td>
                            <td></td>
                        </tr>
                        <?php
                            $realItem      += $omi['RPB_ITEM'];
                            $realQty      += $omi['RPB_QTY'];
                            $realRph      += $omi['RPB_RPH_TOTAL'];    
                            $realPPN      += $omi['RPB_RPH_PPN'];
                            $realDF       += $omi['RPB_RPH_DF'];
                            $realDFPPN       += $omi['RPB_RPH_DF_PPN'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="3" class="text-center fw-bold">Total</td>
                        <?php
                            if ($realQty > 0) {
								echo '<td class="text-end fw-bold">'  . number_format($realItem, 0, '.', ',') . '</td>';						
								echo '<td class="text-end fw-bold">'  . number_format($realQty, 0, '.', ',') . '</td>';
								echo '<td class="text-end fw-bold">'  . number_format($realRph, 0, '.', ',') . '</td>';
								echo '<td class="text-end fw-bold">'  . number_format($realPPN, 0, '.', ',') . '</td>';						
								echo '<td class="text-end fw-bold">'  . number_format($realDF, 0, '.', ',') . '</td>';	
								echo '<td class="text-end fw-bold">'  . number_format($realDFPPN, 0, '.', ',') . '</td>';	

								echo '<td class="text-end fw-bold">'  . number_format($realRph + $realPPN + $realDF + $realDFPPN, 0, '.', ',') . '</td>';							
							}
                        ?>
                        <td colspan="1" class="text-center fw-bold"></td>
                    </tr>
                </tfoot>
            </table>
        <?php } else if($jenisLaporan == "2") { ?>
            <h4 class="fw-bold"><?= $jlap; ?> - Divisi : <?= $dvs; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr; ?></h4>
            <h5>Periode : <?= date('d M Y') ?></h5>
            <h6>Toko OMI : <?= $tkomi; ?>, Toko IDM : <?= $tkidm; ?></h6>
            <h6>PLU : <?= $kodePLU; ?>, Supplier : <?= $kdsup; ?>, Nama Supplier : <?= $nmsup; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">#</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Tanggal</th>
                        <th colspan="8" class="fw-bold text-center bg-info text-no-wrap">Intransit</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center bg-info text-nowrap">Toko</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Item</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Qty</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Rupiah</th>
                        <th class="fw-bold text-center bg-info text-nowrap">PPN</th>
                        <th class="fw-bold text-center bg-info text-nowrap">DisFee</th>
                        <th class="fw-bold text-center bg-info text-nowrap">DisFee PPN</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Total</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($intransitomi as $omi) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $omi['RPB_TANGGAL']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_TOKO'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_ITEM'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_QTY'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_TOTAL'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_PPN'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_DF'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_DF_PPN'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_TOTAL'] + $omi['RPB_RPH_PPN'] + $omi['RPB_RPH_DF'] + $omi['RPB_RPH_DF_PPN'], 0, '.', ','); ?></td>
                            <td></td>
                        </tr>
                        <?php
                            $realItem      += $omi['RPB_ITEM'];
                            $realQty      += $omi['RPB_QTY'];
                            $realRph      += $omi['RPB_RPH_TOTAL'];    
                            $realPPN      += $omi['RPB_RPH_PPN'];
                            $realDF       += $omi['RPB_RPH_DF'];
                            $realDFPPN       += $omi['RPB_RPH_DF_PPN'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="3" class="text-center fw-bold">Total</td>
                        <?php
                            if ($realQty > 0) {
								echo '<td class="text-end fw-bold">'  . number_format($realItem, 0, '.', ',') . '</td>';						
								echo '<td class="text-end fw-bold">'  . number_format($realQty, 0, '.', ',') . '</td>';
								echo '<td class="text-end fw-bold">'  . number_format($realRph, 0, '.', ',') . '</td>';
								echo '<td class="text-end fw-bold">'  . number_format($realPPN, 0, '.', ',') . '</td>';						
								echo '<td class="text-end fw-bold">'  . number_format($realDF, 0, '.', ',') . '</td>';	
								echo '<td class="text-end fw-bold">'  . number_format($realDFPPN, 0, '.', ',') . '</td>';	

								echo '<td class="text-end fw-bold">'  . number_format($realRph + $realPPN + $realDF + $realDFPPN, 0, '.', ',') . '</td>';							
							}
                        ?>
                        <td colspan="1" class="text-center fw-bold"></td>
                    </tr>
                </tfoot>
            </table>
        <?php } else if($jenisLaporan == "3") { ?>
            <h4 class="fw-bold"><?= $jlap; ?> - Divisi : <?= $dvs; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr; ?></h4>
            <h5>Periode : <?= date('d M Y') ?></h5>
            <h6>Toko OMI : <?= $tkomi; ?>, Toko IDM : <?= $tkidm; ?></h6>
            <h6>PLU : <?= $kodePLU; ?>, Supplier : <?= $kdsup; ?>, Nama Supplier : <?= $nmsup; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">#</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-no-wrap">DSPB / NPB</th>
                        <th colspan="3" class="fw-bold text-center bg-info text-no-wrap">Toko OMI / IDM</th>
                        <th colspan="7" class="fw-bold text-center bg-info text-no-wrap">Intransit</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center bg-info text-nowrap">Tanggal</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nomor</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Kode</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nama</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nomor PB</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Item</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Qty</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Rupiah</th>
                        <th class="fw-bold text-center bg-info text-nowrap">PPN</th>
                        <th class="fw-bold text-center bg-info text-nowrap">DisFee</th>
                        <th class="fw-bold text-center bg-info text-nowrap">DisFee PPN</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Total</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($intransitomi as $omi) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $omi['RPB_CREATE_DT']; ?></td>
                            <td class="text-center"><?= $omi['RPB_IDSURATJALAN']; ?></td>
                            <td class="text-center"><?= $omi['RPB_KODE_OMI']; ?></td>
                            <td class="text-start text-nowrap"><?= $omi['RPB_NAMA_OMI']; ?></td>
                            <td class="text-center"><?= $omi['RPB_NOPB']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_ITEM'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_QTY'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_TOTAL'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_PPN'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_DF'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_DF_PPN'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_TOTAL'] + $omi['RPB_RPH_PPN'] + $omi['RPB_RPH_DF'] + $omi['RPB_RPH_DF_PPN'], 0, '.', ','); ?></td>
                            <td></td>
                        </tr>
                        <?php
                            $realItem      += $omi['RPB_ITEM'];
                            $realQty      += $omi['RPB_QTY'];
                            $realRph      += $omi['RPB_RPH_TOTAL'];    
                            $realPPN      += $omi['RPB_RPH_PPN'];
                            $realDF       += $omi['RPB_RPH_DF'];
                            $realDFPPN       += $omi['RPB_RPH_DF_PPN'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="6" class="text-center fw-bold">Total</td>
                        <?php
                            if ($realQty > 0) {
								echo '<td class="text-end fw-bold">'  . number_format($realItem, 0, '.', ',') . '</td>';						
								echo '<td class="text-end fw-bold">'  . number_format($realQty, 0, '.', ',') . '</td>';
								echo '<td class="text-end fw-bold">'  . number_format($realRph, 0, '.', ',') . '</td>';
								echo '<td class="text-end fw-bold">'  . number_format($realPPN, 0, '.', ',') . '</td>';						
								echo '<td class="text-end fw-bold">'  . number_format($realDF, 0, '.', ',') . '</td>';	
								echo '<td class="text-end fw-bold">'  . number_format($realDFPPN, 0, '.', ',') . '</td>';	

								echo '<td class="text-end fw-bold">'  . number_format($realRph + $realPPN + $realDF + $realDFPPN, 0, '.', ',') . '</td>';							
							}
                        ?>
                        <td colspan="1" class="text-center fw-bold"></td>
                    </tr>
                </tfoot>
            </table>
        <?php } else if($jenisLaporan == "4") { ?>
            <h4 class="fw-bold"><?= $jlap; ?> - Divisi : <?= $dvs; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr; ?></h4>
            <h5>Periode : <?= date('d M Y') ?></h5>
            <h6>Toko OMI : <?= $tkomi; ?>, Toko IDM : <?= $tkidm; ?></h6>
            <h6>PLU : <?= $kodePLU; ?>, Supplier : <?= $kdsup; ?>, Nama Supplier : <?= $nmsup; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">#</th>
                        <th colspan="3" class="fw-bold text-center bg-info text-no-wrap">Divisi</th>
                        <th colspan="5" class="fw-bold text-center bg-info text-no-wrap">Produk</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Toko</th>
                        <th colspan="6" class="fw-bold text-center bg-info text-no-wrap">Intransit</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-no-wrap">Supplier</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center bg-info text-nowrap">Div</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Dept</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Katb</th>
                        <th class="fw-bold text-center bg-info text-nowrap">PLU</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Deskripsi</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Unit</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Frac</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Tag</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Qty</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Rupiah</th>
                        <th class="fw-bold text-center bg-info text-nowrap">PPN</th>
                        <th class="fw-bold text-center bg-info text-nowrap">DisFee</th>
                        <th class="fw-bold text-center bg-info text-nowrap">DisFee PPN</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Total</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Kode</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nama</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($intransitomi as $omi) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $omi['RPB_DIV']; ?></td>
                            <td class="text-center"><?= $omi['RPB_DEPT']; ?></td>
                            <td class="text-center"><?= $omi['RPB_KATB']; ?></td>
                            <td class="text-center"><?= $omi['RPB_PRDCD']; ?></td>
                            <td class="text-start text-nowrap"><?= $omi['RPB_NAMA_BARANG']; ?></td>
                            <td class="text-center"><?= $omi['RPB_UNIT']; ?></td>
                            <td class="text-center"><?= $omi['RPB_FRAC']; ?></td>
                            <td class="text-center"><?= $omi['RPB_TAG']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_TOKO'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_QTY'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_TOTAL'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_PPN'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_DF'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_DF_PPN'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_TOTAL'] + $omi['RPB_RPH_PPN'] + $omi['RPB_RPH_DF'] + $omi['RPB_RPH_DF_PPN'], 0, '.', ','); ?></td>
                            <td class="text-center"><?= $omi['RPB_KODE_SUPPLIER']; ?></td>
                            <td class="text-start text-nowrap"><?= $omi['RPB_NAMA_SUPPLIER']; ?></td>
                            <td></td>
                        </tr>
                        <?php
                            $realQty      += $omi['RPB_QTY'];
                            $realRph      += $omi['RPB_RPH_TOTAL'];    
                            $realPPN      += $omi['RPB_RPH_PPN'];
                            $realDF       += $omi['RPB_RPH_DF'];
                            $realDFPPN       += $omi['RPB_RPH_DF_PPN'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="10" class="text-center fw-bold">Total</td>
                        <?php
                            if ($realQty > 0) {	
								echo '<td class="text-end fw-bold">'  . number_format($realQty, 0, '.', ',') . '</td>';
								echo '<td class="text-end fw-bold">'  . number_format($realRph, 0, '.', ',') . '</td>';
								echo '<td class="text-end fw-bold">'  . number_format($realPPN, 0, '.', ',') . '</td>';						
								echo '<td class="text-end fw-bold">'  . number_format($realDF, 0, '.', ',') . '</td>';	
								echo '<td class="text-end fw-bold">'  . number_format($realDFPPN, 0, '.', ',') . '</td>';	

								echo '<td class="text-end fw-bold">'  . number_format($realRph + $realPPN + $realDF + $realDFPPN, 0, '.', ',') . '</td>';							
							}
                        ?>
                        <td colspan="1" class="text-center fw-bold"></td>
                    </tr>
                </tfoot>
            </table>
        <?php } else if($jenisLaporan == "5") { ?>
            <h4 class="fw-bold"><?= $jlap; ?> - Divisi : <?= $dvs; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr; ?></h4>
            <h5>Periode : <?= date('d M Y') ?></h5>
            <h6>Toko OMI : <?= $tkomi; ?>, Toko IDM : <?= $tkidm; ?></h6>
            <h6>PLU : <?= $kodePLU; ?>, Supplier : <?= $kdsup; ?>, Nama Supplier : <?= $nmsup; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">#</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-no-wrap">Divisi</th>
                        <th colspan="8" class="fw-bold text-center bg-info text-no-wrap">Intransit</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center bg-info text-nowrap">Div</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nama</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Toko</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Item</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Qty</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Rupiah</th>
                        <th class="fw-bold text-center bg-info text-nowrap">PPN</th>
                        <th class="fw-bold text-center bg-info text-nowrap">DisFee</th>
                        <th class="fw-bold text-center bg-info text-nowrap">DisFee PPN</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Total</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($intransitomi as $omi) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $omi['RPB_DIV']; ?></td>
                            <td class="text-start text-nowrap"><?= $omi['RPB_DIV_NAMA']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_TOKO'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_ITEM'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_QTY'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_TOTAL'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_PPN'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_DF'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_DF_PPN'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_TOTAL'] + $omi['RPB_RPH_PPN'] + $omi['RPB_RPH_DF'] + $omi['RPB_RPH_DF_PPN'], 0, '.', ','); ?></td>
                            <td></td>
                        </tr>
                        <?php
                            $realItem      += $omi['RPB_ITEM'];
                            $realQty      += $omi['RPB_QTY'];
                            $realRph      += $omi['RPB_RPH_TOTAL'];    
                            $realPPN      += $omi['RPB_RPH_PPN'];
                            $realDF       += $omi['RPB_RPH_DF'];
                            $realDFPPN       += $omi['RPB_RPH_DF_PPN'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="4" class="text-center fw-bold">Total</td>
                        <?php
                            if ($realQty > 0) {
								echo '<td class="text-end fw-bold">'  . number_format($realItem, 0, '.', ',') . '</td>';						
								echo '<td class="text-end fw-bold">'  . number_format($realQty, 0, '.', ',') . '</td>';
								echo '<td class="text-end fw-bold">'  . number_format($realRph, 0, '.', ',') . '</td>';
								echo '<td class="text-end fw-bold">'  . number_format($realPPN, 0, '.', ',') . '</td>';						
								echo '<td class="text-end fw-bold">'  . number_format($realDF, 0, '.', ',') . '</td>';	
								echo '<td class="text-end fw-bold">'  . number_format($realDFPPN, 0, '.', ',') . '</td>';	

								echo '<td class="text-end fw-bold">'  . number_format($realRph + $realPPN + $realDF + $realDFPPN, 0, '.', ',') . '</td>';							
							}
                        ?>
                        <td colspan="1" class="text-center fw-bold"></td>
                    </tr>
                </tfoot>
            </table>
        <?php } else if($jenisLaporan == "5B") { ?>
            <h4 class="fw-bold"><?= $jlap; ?> - Divisi : <?= $dvs; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr; ?></h4>
            <h5>Periode : <?= date('d M Y') ?></h5>
            <h6>Toko OMI : <?= $tkomi; ?>, Toko IDM : <?= $tkidm; ?></h6>
            <h6>PLU : <?= $kodePLU; ?>, Supplier : <?= $kdsup; ?>, Nama Supplier : <?= $nmsup; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">#</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-no-wrap">Divisi</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-no-wrap">Departemen</th>
                        <th colspan="8" class="fw-bold text-center bg-info text-no-wrap">Intransit</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center bg-info text-nowrap">Div</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nama</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Dep</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nama</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Toko</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Item</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Qty</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Rupiah</th>
                        <th class="fw-bold text-center bg-info text-nowrap">PPN</th>
                        <th class="fw-bold text-center bg-info text-nowrap">DisFee</th>
                        <th class="fw-bold text-center bg-info text-nowrap">DisFee PPN</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Total</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($intransitomi as $omi) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $omi['RPB_DIV']; ?></td>
                            <td class="text-start text-nowrap"><?= $omi['RPB_DIV_NAMA']; ?></td>
                            <td class="text-center"><?= $omi['RPB_DEPT']; ?></td>
                            <td class="text-start text-nowrap"><?= $omi['RPB_DEPT_NAMA']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_TOKO'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_ITEM'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_QTY'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_TOTAL'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_PPN'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_DF'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_DF_PPN'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_TOTAL'] + $omi['RPB_RPH_PPN'] + $omi['RPB_RPH_DF'] + $omi['RPB_RPH_DF_PPN'], 0, '.', ','); ?></td>
                            <td></td>
                        </tr>
                        <?php
                            $realItem      += $omi['RPB_ITEM'];
                            $realQty      += $omi['RPB_QTY'];
                            $realRph      += $omi['RPB_RPH_TOTAL'];    
                            $realPPN      += $omi['RPB_RPH_PPN'];
                            $realDF       += $omi['RPB_RPH_DF'];
                            $realDFPPN       += $omi['RPB_RPH_DF_PPN'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="6" class="text-center fw-bold">Total</td>
                        <?php
                            if ($realQty > 0) {
								echo '<td class="text-end fw-bold">'  . number_format($realItem, 0, '.', ',') . '</td>';						
								echo '<td class="text-end fw-bold">'  . number_format($realQty, 0, '.', ',') . '</td>';
								echo '<td class="text-end fw-bold">'  . number_format($realRph, 0, '.', ',') . '</td>';
								echo '<td class="text-end fw-bold">'  . number_format($realPPN, 0, '.', ',') . '</td>';						
								echo '<td class="text-end fw-bold">'  . number_format($realDF, 0, '.', ',') . '</td>';	
								echo '<td class="text-end fw-bold">'  . number_format($realDFPPN, 0, '.', ',') . '</td>';	

								echo '<td class="text-end fw-bold">'  . number_format($realRph + $realPPN + $realDF + $realDFPPN, 0, '.', ',') . '</td>';							
							}
                        ?>
                        <td colspan="1" class="text-center fw-bold"></td>
                    </tr>
                </tfoot>
            </table>
        <?php } else if($jenisLaporan == "5C") { ?>
            <h4 class="fw-bold"><?= $jlap; ?> - Divisi : <?= $dvs; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr; ?></h4>
            <h5>Periode : <?= date('d M Y') ?></h5>
            <h6>Toko OMI : <?= $tkomi; ?>, Toko IDM : <?= $tkidm; ?></h6>
            <h6>PLU : <?= $kodePLU; ?>, Supplier : <?= $kdsup; ?>, Nama Supplier : <?= $nmsup; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">#</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-no-wrap">Divisi</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-no-wrap">Departemen</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-no-wrap">Kategori</th>
                        <th colspan="8" class="fw-bold text-center bg-info text-no-wrap">Intransit</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center bg-info text-nowrap">Div</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nama</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Dep</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nama</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Katb</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nama</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Toko</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Item</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Qty</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Rupiah</th>
                        <th class="fw-bold text-center bg-info text-nowrap">PPN</th>
                        <th class="fw-bold text-center bg-info text-nowrap">DisFee</th>
                        <th class="fw-bold text-center bg-info text-nowrap">DisFee PPN</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Total</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($intransitomi as $omi) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $omi['RPB_DIV']; ?></td>
                            <td class="text-start text-nowrap"><?= $omi['RPB_DIV_NAMA']; ?></td>
                            <td class="text-center"><?= $omi['RPB_DEPT']; ?></td>
                            <td class="text-start text-nowrap"><?= $omi['RPB_DEPT_NAMA']; ?></td>
                            <td class="text-center"><?= $omi['RPB_KATB']; ?></td>
                            <td class="text-start text-nowrap"><?= $omi['RPB_KATB_NAMA']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_TOKO'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_ITEM'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_QTY'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_TOTAL'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_PPN'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_DF'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_DF_PPN'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_TOTAL'] + $omi['RPB_RPH_PPN'] + $omi['RPB_RPH_DF'] + $omi['RPB_RPH_DF_PPN'], 0, '.', ','); ?></td>
                            <td></td>
                        </tr>
                        <?php
                            $realItem      += $omi['RPB_ITEM'];
                            $realQty      += $omi['RPB_QTY'];
                            $realRph      += $omi['RPB_RPH_TOTAL'];    
                            $realPPN      += $omi['RPB_RPH_PPN'];
                            $realDF       += $omi['RPB_RPH_DF'];
                            $realDFPPN       += $omi['RPB_RPH_DF_PPN'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="6" class="text-center fw-bold">Total</td>
                        <?php
                            if ($realQty > 0) {
								echo '<td class="text-end fw-bold">'  . number_format($realItem, 0, '.', ',') . '</td>';						
								echo '<td class="text-end fw-bold">'  . number_format($realQty, 0, '.', ',') . '</td>';
								echo '<td class="text-end fw-bold">'  . number_format($realRph, 0, '.', ',') . '</td>';
								echo '<td class="text-end fw-bold">'  . number_format($realPPN, 0, '.', ',') . '</td>';						
								echo '<td class="text-end fw-bold">'  . number_format($realDF, 0, '.', ',') . '</td>';	
								echo '<td class="text-end fw-bold">'  . number_format($realDFPPN, 0, '.', ',') . '</td>';	

								echo '<td class="text-end fw-bold">'  . number_format($realRph + $realPPN + $realDF + $realDFPPN, 0, '.', ',') . '</td>';							
							}
                        ?>
                        <td colspan="1" class="text-center fw-bold"></td>
                    </tr>
                </tfoot>
            </table>
        <?php } else if($jenisLaporan == "6") { ?>
            <h4 class="fw-bold"><?= $jlap; ?> - Divisi : <?= $dvs; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr; ?></h4>
            <h5>Periode : <?= date('d M Y') ?></h5>
            <h6>Toko OMI : <?= $tkomi; ?>, Toko IDM : <?= $tkidm; ?></h6>
            <h6>PLU : <?= $kodePLU; ?>, Supplier : <?= $kdsup; ?>, Nama Supplier : <?= $nmsup; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">#</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-no-wrap">Supplier</th>
                        <th colspan="8" class="fw-bold text-center bg-info text-no-wrap">Intransit</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center bg-info text-nowrap">Kode</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nama Supplier</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Toko</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Item</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Qty</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Rupiah</th>
                        <th class="fw-bold text-center bg-info text-nowrap">PPN</th>
                        <th class="fw-bold text-center bg-info text-nowrap">DisFee</th>
                        <th class="fw-bold text-center bg-info text-nowrap">DisFee PPN</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Total</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($intransitomi as $omi) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $omi['RPB_KODE_SUPPLIER']; ?></td>
                            <td class="text-start text-nowrap"><?= $omi['RPB_NAMA_SUPPLIER']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_TOKO'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_ITEM'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_QTY'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_TOTAL'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_PPN'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_DF'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_DF_PPN'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['RPB_RPH_TOTAL'] + $omi['RPB_RPH_PPN'] + $omi['RPB_RPH_DF'] + $omi['RPB_RPH_DF_PPN'], 0, '.', ','); ?></td>
                            <td></td>
                        </tr>
                        <?php
                            $realItem      += $omi['RPB_ITEM'];
                            $realQty      += $omi['RPB_QTY'];
                            $realRph      += $omi['RPB_RPH_TOTAL'];    
                            $realPPN      += $omi['RPB_RPH_PPN'];
                            $realDF       += $omi['RPB_RPH_DF'];
                            $realDFPPN       += $omi['RPB_RPH_DF_PPN'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="4" class="text-center fw-bold">Total</td>
                        <?php
                            if ($realQty > 0) {
								echo '<td class="text-end fw-bold">'  . number_format($realItem, 0, '.', ',') . '</td>';						
								echo '<td class="text-end fw-bold">'  . number_format($realQty, 0, '.', ',') . '</td>';
								echo '<td class="text-end fw-bold">'  . number_format($realRph, 0, '.', ',') . '</td>';
								echo '<td class="text-end fw-bold">'  . number_format($realPPN, 0, '.', ',') . '</td>';						
								echo '<td class="text-end fw-bold">'  . number_format($realDF, 0, '.', ',') . '</td>';	
								echo '<td class="text-end fw-bold">'  . number_format($realDFPPN, 0, '.', ',') . '</td>';	

								echo '<td class="text-end fw-bold">'  . number_format($realRph + $realPPN + $realDF + $realDFPPN, 0, '.', ',') . '</td>';							
							}
                        ?>
                        <td colspan="1" class="text-center fw-bold"></td>
                    </tr>
                </tfoot>
            </table>
        <?php } ?>
    </body>
</html>