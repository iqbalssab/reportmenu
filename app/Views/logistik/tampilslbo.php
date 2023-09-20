<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Data Service Level 3 Periode</title>
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
            $no = 1;
            $dokumenPO = $dokumenBPB = $itemPO = $itemBPB = $qtyPO = $qtyBPB = $rphPO = $rphBPB = 0;
            $kodePLU = $dvs = $dep = $katbr = $kodeSupplier = $namaSupplier = $kdsup = $nmsup  = "All"; 
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
            if(isset($_GET['plu'])) {if ($_GET['plu'] !=""){$kodePLU = $_GET['plu']; }}
            if ($kodePLU != "All" AND $kodePLU != "") {
                $kodePLU = substr('00000000' . $kodePLU, -7);
            }
        ?>

        <?php if($lap == "4") { ?>
            <h4 class="fw-bold"><?= $jlap; ?> - Divisi : <?= $dvs; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr; ?></h4>
            <h6>Periode : <?= date('d M Y',strtotime($tanggalMulai1)) ?> s/d <?= date('d M Y',strtotime($tanggalSelesai3)) ?></h6>
            <h6>PLU : <?= $kodePLU; ?>, Supplier : <?= $kdsup; ?>, Nama Supplier : <?= $nmsup; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
            <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center bg-info">#</th>
                        <th colspan="3" class="fw-bold text-center bg-info">Divisi</th>
                        <th colspan="5" class="fw-bold text-center bg-info">Produk</th>
                        <th colspan="3" class="fw-bold text-center bg-info">Dokumen Periode 1</th>
                        <th colspan="3" class="fw-bold text-center bg-info">Quantity Periode 1</th>
                        <th colspan="3" class="fw-bold text-center bg-info">Rupiah Periode 1</th>
                        <th colspan="3" class="fw-bold text-center bg-info">Dokumen Periode 2</th>
                        <th colspan="3" class="fw-bold text-center bg-info">Quantity Periode 2</th>
                        <th colspan="3" class="fw-bold text-center bg-info">Rupiah Periode 2</th>
                        <th colspan="3" class="fw-bold text-center bg-info">Dokumen Periode 3</th>
                        <th colspan="3" class="fw-bold text-center bg-info">Quantity Periode 3</th>
                        <th colspan="3" class="fw-bold text-center bg-info">Rupiah Periode 3</th>
                        <th colspan="2" class="fw-bold text-center bg-info">Supplier</th>
                        <th colspan="5" class="fw-bold text-center bg-info text-nowrap">Tren Sales in Pcs</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Stock</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Acost</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Lcost</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center bg-info">Div</th>
                        <th class="fw-bold text-center bg-info">Dept</th>
                        <th class="fw-bold text-center bg-info">Katb</th>
                        <th class="fw-bold text-center bg-info">PLU</th>
                        <th class="fw-bold text-center bg-info">Deskripsi</th>
                        <th class="fw-bold text-center bg-info">Unit</th>
                        <th class="fw-bold text-center bg-info">Frac</th>
                        <th class="fw-bold text-center bg-info">Tag</th>
                        <!-- Periode 1 -->
                        <th class="fw-bold text-center bg-info">PO</th>
                        <th class="fw-bold text-center bg-info">BPB</th>
                        <th class="fw-bold text-center bg-info">%</th>
                        <th class="fw-bold text-center bg-info">PO</th>
                        <th class="fw-bold text-center bg-info">BPB</th>
                        <th class="fw-bold text-center bg-info">%</th>
                        <th class="fw-bold text-center bg-info">PO</th>
                        <th class="fw-bold text-center bg-info">BPB</th>
                        <th class="fw-bold text-center bg-info">%</th>
                        <!-- Periode 2 -->
                        <th class="fw-bold text-center bg-info">PO</th>
                        <th class="fw-bold text-center bg-info">BPB</th>
                        <th class="fw-bold text-center bg-info">%</th>
                        <th class="fw-bold text-center bg-info">PO</th>
                        <th class="fw-bold text-center bg-info">BPB</th>
                        <th class="fw-bold text-center bg-info">%</th>
                        <th class="fw-bold text-center bg-info">PO</th>
                        <th class="fw-bold text-center bg-info">BPB</th>
                        <th class="fw-bold text-center bg-info">%</th>
                        <!-- Periode 3 -->
                        <th class="fw-bold text-center bg-info">PO</th>
                        <th class="fw-bold text-center bg-info">BPB</th>
                        <th class="fw-bold text-center bg-info">%</th>
                        <th class="fw-bold text-center bg-info">PO</th>
                        <th class="fw-bold text-center bg-info">BPB</th>
                        <th class="fw-bold text-center bg-info">%</th>
                        <th class="fw-bold text-center bg-info">PO</th>
                        <th class="fw-bold text-center bg-info">BPB</th>
                        <th class="fw-bold text-center bg-info">%</th>
                        <th class="fw-bold text-center bg-info">Kode</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nama Supplier</th>
                        <th class="fw-bold text-center bg-info"><?php echo date("M", strtotime("-3 month")); ?></th>
                        <th class="fw-bold text-center bg-info"><?php echo date("M", strtotime("-2 month")); ?></th>
                        <th class="fw-bold text-center bg-info"><?php echo date("M", strtotime("-1 month")); ?></th>
                        <th class="fw-bold text-center bg-info text-nowrap">Avg 3 Bulan</th> 
                        <th class="fw-bold text-center bg-info"><?php echo date("M"); ?></th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($dataslbo as $sl) : ?>
                    <tr>
                        <td class="text-end"><?= $no++; ?></td>
                        <td class="text-center"><?= $sl['SL_DIV']; ?></td>
                        <td class="text-center"><?= $sl['SL_DEPT']; ?></td>
                        <td class="text-center"><?= $sl['SL_KATB']; ?></td>
                        <td class="text-center"><?= $sl['SL_PRDCD_PO']; ?></td>
                        <td class="text-start text-nowrap"><?= $sl['SL_NAMA_BARANG']; ?></td>
                        <td class="text-start"><?= $sl['SL_UNIT']; ?></td>
                        <td class="text-start"><?= $sl['SL_FRAC']; ?></td>
                        <td class="text-start"><?= $sl['SL_TAG']; ?></td>
                        <!-- Periode 1 -->
                        <td class="text-end"><?= number_format($sl['SL_NOMOR_PO'], 0, '.', ','); ?></td>
                        <td class="text-end"><?= number_format($sl['SL_NOMOR_BPB'], 0, '.', ','); ?></td>
                        <?php if($sl['SL_NOMOR_PO'] <> 0) { ?>
                            <td class="text-end text-nowrap"><?= number_format($sl['SL_NOMOR_BPB'] / $sl['SL_NOMOR_PO'] * 100, 0, '.', ','); ?> %</td>
                        <?php } else { ?>
                            <td class="text-end text-nowrap"><?= number_format(0, 0, '.', ','); ?></td>
                        <?php } ?>
                        <td class="text-end"><?= number_format($sl['SL_QTY_PO'], 0, '.', ','); ?></td>
                        <td class="text-end"><?= number_format($sl['SL_QTY_BPB'], 0, '.', ','); ?></td>
                        <?php if($sl['SL_QTY_PO'] <> 0) { ?>
                            <td class="text-end text-nowrap"><?= number_format($sl['SL_QTY_BPB'] / $sl['SL_QTY_PO'] * 100, 0, '.', ','); ?> %</td>
                        <?php } else { ?>
                            <td class="text-end text-nowrap"><?= number_format(0, 0, '.', ','); ?></td>
                        <?php } ?>
                        <td class="text-end"><?= number_format($sl['SL_RPH_PO'], 0, '.', ','); ?></td>
                        <td class="text-end"><?= number_format($sl['SL_RPH_BPB'], 0, '.', ','); ?></td>
                        <?php if($sl['SL_RPH_PO'] <> 0) { ?>
                            <td class="text-end text-nowrap"><?= number_format($sl['SL_RPH_BPB'] / $sl['SL_RPH_PO'] * 100, 0, '.', ','); ?> %</td>
                        <?php } else { ?>
                            <td class="text-end text-nowrap"><?= number_format(0, 0, '.', ','); ?></td>
                        <?php } ?>
                        
                        <!-- Periode 2 -->
                        <td class="text-end"><?= number_format($sl['SL_NOMOR_PO2'], 0, '.', ','); ?></td>
                        <td class="text-end"><?= number_format($sl['SL_NOMOR_BPB2'], 0, '.', ','); ?></td>
                        <?php if($sl['SL_NOMOR_PO2'] <> 0) { ?>
                            <td class="text-end text-nowrap"><?= number_format($sl['SL_NOMOR_BPB2'] / $sl['SL_NOMOR_PO2'] * 100, 0, '.', ','); ?> %</td>
                        <?php } else { ?>
                            <td class="text-end text-nowrap"><?= number_format(0, 0, '.', ','); ?></td>
                        <?php } ?>
                        <td class="text-end"><?= number_format($sl['SL_QTY_PO2'], 0, '.', ','); ?></td>
                        <td class="text-end"><?= number_format($sl['SL_QTY_BPB2'], 0, '.', ','); ?></td>
                        <?php if($sl['SL_QTY_PO2'] <> 0) { ?>
                            <td class="text-end text-nowrap"><?= number_format($sl['SL_QTY_BPB2'] / $sl['SL_QTY_PO2'] * 100, 0, '.', ','); ?> %</td>
                        <?php } else { ?>
                            <td class="text-end text-nowrap"><?= number_format(0, 0, '.', ','); ?></td>
                        <?php } ?>
                        <td class="text-end"><?= number_format($sl['SL_RPH_PO2'], 0, '.', ','); ?></td>
                        <td class="text-end"><?= number_format($sl['SL_RPH_BPB2'], 0, '.', ','); ?></td>
                        <?php if($sl['SL_RPH_PO2'] <> 0) { ?>
                            <td class="text-end text-nowrap"><?= number_format($sl['SL_RPH_BPB2'] / $sl['SL_RPH_PO2'] * 100, 0, '.', ','); ?> %</td>
                        <?php } else { ?>
                            <td class="text-end text-nowrap"><?= number_format(0, 0, '.', ','); ?></td>
                        <?php } ?>
                        
                        <!-- Periode 3 -->
                        <td class="text-end"><?= number_format($sl['SL_NOMOR_PO3'], 0, '.', ','); ?></td>
                        <td class="text-end"><?= number_format($sl['SL_NOMOR_BPB3'], 0, '.', ','); ?></td>
                        <?php if($sl['SL_NOMOR_PO3'] <> 0) { ?>
                            <td class="text-end text-nowrap"><?= number_format($sl['SL_NOMOR_BPB3'] / $sl['SL_NOMOR_PO3'] * 100, 0, '.', ','); ?> %</td>
                        <?php } else { ?>
                            <td class="text-end text-nowrap"><?= number_format(0, 0, '.', ','); ?></td>
                        <?php } ?>
                        <td class="text-end"><?= number_format($sl['SL_QTY_PO3'], 0, '.', ','); ?></td>
                        <td class="text-end"><?= number_format($sl['SL_QTY_BPB3'], 0, '.', ','); ?></td>
                        <?php if($sl['SL_QTY_PO3'] <> 0) { ?>
                            <td class="text-end text-nowrap"><?= number_format($sl['SL_QTY_BPB3'] / $sl['SL_QTY_PO3'] * 100, 0, '.', ','); ?> %</td>
                        <?php } else { ?>
                            <td class="text-end text-nowrap"><?= number_format(0, 0, '.', ','); ?></td>
                        <?php } ?>
                        <td class="text-end"><?= number_format($sl['SL_RPH_PO3'], 0, '.', ','); ?></td>
                        <td class="text-end"><?= number_format($sl['SL_RPH_BPB3'], 0, '.', ','); ?></td>
                        <?php if($sl['SL_RPH_PO3'] <> 0) { ?>
                            <td class="text-end text-nowrap"><?= number_format($sl['SL_RPH_BPB3'] / $sl['SL_RPH_PO3'] * 100, 0, '.', ','); ?> %</td>
                        <?php } else { ?>
                            <td class="text-end text-nowrap"><?= number_format(0, 0, '.', ','); ?></td>
                        <?php } ?>
                        
                        <td class="text-center"><?= $sl['SL_KODE_SUPPLIER']; ?></td>
                        <td class="text-start text-nowrap"><?= $sl['SL_NAMA_SUPPLIER']; ?></td>
                        <td class="text-end"><?= number_format($sl['SL_SPD_QTY_1'], 0, '.', ','); ?></td>
                        <td class="text-end"><?= number_format($sl['SL_SPD_QTY_2'], 0, '.', ','); ?></td>
                        <td class="text-end"><?= number_format($sl['SL_SPD_QTY_3'], 0, '.', ','); ?></td>
                        <td class="text-end"><?= number_format(($sl['SL_SPD_QTY_1'] + $sl['SL_SPD_QTY_2'] + $sl['SL_SPD_QTY_3']) / 3, 0, '.', ','); ?></td>
                        <td class="text-end"><?= number_format($sl['SL_SALES_BULAN_INI'], 0, '.', ','); ?></td>
                        <td class="text-end"><?= number_format($sl['SL_STOCK_QTY'], 0, '.', ','); ?></td>
                        <td class="text-end"><?= number_format($sl['SL_AVGCOST'], 0, '.', ','); ?></td>
                        <td class="text-end"><?= number_format($sl['SL_LASTCOST'], 0, '.', ','); ?></td>
                    </tr>
                    <?php
                        $dokumenPO   += $sl['SL_NOMOR_PO']; 
    					$dokumenBPB  += $sl['SL_NOMOR_BPB']; 
					  	$qtyPO       += $sl['SL_QTY_PO']; 
					    $qtyBPB      += $sl['SL_QTY_BPB']; 
					  	$rphPO       += $sl['SL_RPH_PO'];
					    $rphBPB      += $sl['SL_RPH_BPB'];
                    ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="9" class="text-center fw-bold">Total</td>
                        <?php 
                            if ($qtyPO != 0) {
								echo '<td class="text-end fw-bold">'  . number_format( $dokumenPO, 0, '.', ',') . '</td>';
								echo '<td class="text-end fw-bold">'  . number_format( $dokumenBPB, 0, '.', ',') . '</td>';
								echo '<td class="text-end fw-bold text-nowrap">'  . number_format( $dokumenBPB / $dokumenPO * 100, 0, '.', ',') . ' %</td>';
                                
                                echo '<td class="text-end fw-bold">'  . number_format( $qtyPO, 0, '.', ',') . '</td>';
								echo '<td class="text-end fw-bold">'  . number_format( $qtyBPB, 0, '.', ',') . '</td>';
								echo '<td class="text-end fw-bold text-nowrap">'  . number_format( $qtyBPB / $qtyPO * 100, 0, '.', ',') . ' %</td>';

								echo '<td class="text-end fw-bold">'  . number_format( $rphPO, 0, '.', ',') . '</td>';
								echo '<td class="text-end fw-bold">'  . number_format( $rphBPB, 0, '.', ',') . '</td>';
								echo '<td class="text-end fw-bold text-nowrap">'  . number_format( $rphBPB / $rphPO * 100, 0, '.', ',') . ' %</td>';
							}
                        ?>
                        <td colspan="10" class="text-center">&nbsp;</td>
                    </tr>
                </tfoot>
            </table>
        <?php } ?>
    </body>
</html>