<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Laporan Back Office</title>

        <link rel="stylesheet" href="<?= base_url('bootstrap/dist/css/bootstrap.min.css'); ?>">
        <style>
            .container {border:3px solid #666;padding:10px;margin:0 auto;width:500px}
            input {margin:5px;}
            table, th, td {
                border: 1px solid black;
                border-collapse: collapse;
                margin:0 0 10px;
                width:auto;
                font-size:12px;
            }
            th{
            background-color:#66CCFF;
            padding:5px;
            font-weight:400;
            }
            td{
            padding:2px 5px;
            }
        </style>

        <!-- Style CSS -->
        <link rel="stylesheet" href="/css/style.css">
        <!-- Kalo gapake Laragon/XAMPP -->
        <link rel="stylesheet" href="/bootstrap/dist/css/bootstrap.min.css">
    </head>
    <body>
        <?php $rphGross = $rphDiscount = $rphPPN = $jumlahItem = 0 ?>
        <?php $keterangan = $ket1 = $ket2 = "" ?>
        <?php $no = 1; ?>
        <?php if($lap == "1") { ?>
            <h2 class="fw-bold">LAPORAN PER PRODUK</h2>
            <h4>Periode : <?= date('d M Y',strtotime($awal)); ?> sd <?= date('d M Y',strtotime($akhir)); ?></h4>
            <br>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered border-dark">
                    <thead class="table-group-divider">
                        <tr class="">
                            <th rowspan="2" class="fw-bold text-center bg-info">#</th>
                            <th rowspan="2" class="fw-bold text-center bg-info">Tipe</th>
                            <th colspan="3" class="fw-bold text-center bg-info">Divisi</th>
                            <th colspan="5" class="fw-bold text-center bg-info">Produk</th>                            
                            <th colspan="3" class="fw-bold text-center bg-info">Quantity</th>
                            <th colspan="5" class="fw-bold text-center bg-info">Rupiah</th>
                            <th colspan="2" class="fw-bold text-center bg-info">Supplier</th>
                            <th rowspan="2" class="fw-bold text-center bg-info">Keterangan</th>
                        </tr>
                        <tr class="">
				            <th class="fw-bold text-center bg-info">Div</th>
                            <th class="fw-bold text-center bg-info">Dep</th>
                            <th class="fw-bold text-center bg-info">Kat</th>
                            <th class="fw-bold text-center bg-info">PLU</th>
                            <th class="fw-bold text-center bg-info">Nama</th>
                            <th class="fw-bold text-center bg-info">Unit</th>
                            <th class="fw-bold text-center bg-info">Frac</th>
                            <th class="fw-bold text-center bg-info">Tag</th>
                            <th class="fw-bold text-center bg-info">CTN</th>
                            <th class="fw-bold text-center bg-info">Pcs</th>
                            <th class="fw-bold text-center bg-info">Bonus</th>
                            <th class="fw-bold text-center bg-info">Harga</th>
                            <th class="fw-bold text-center bg-info">Discount</th>
                            <th class="fw-bold text-center bg-info">Netto</th>
                            <th class="fw-bold text-center bg-info">PPN</th>
                            <th class="fw-bold text-center bg-info">Total</th>
                            <th class="fw-bold text-center bg-info">Kode</th>
                            <th class="fw-bold text-center bg-info">Nama</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <?php foreach ($databo as $bo) : ?>
                            <tr>
                                <td class="text-end"><?= $no++; ?></td>
                                <td class="text-center"><?= $bo['TRN_TYPE']; ?></td>
                                <td class="text-center"><?= $bo['TRN_DIV']; ?></td>
                                <td class="text-center"><?= $bo['TRN_DEPT']; ?></td>
                                <td class="text-center"><?= $bo['TRN_KATB']; ?></td>
                                <td class="text-center"><?= $bo['TRN_PRDCD']; ?></td>
                                <td class="text-start text-nowrap"><?= $bo['TRN_NAMA_BARANG']; ?></td>
                                <td class="text-start"><?= $bo['TRN_UNIT']; ?></td>
                                <td class="text-start"><?= $bo['TRN_FRAC']; ?></td>
                                <td class="text-start"><?= $bo['TRN_TAG']; ?></td>
                                <td class="text-end"><?= number_format(($bo['TRN_QTY'] - $bo['TRN_QTY'] % $bo['TRN_FRAC']) / $bo['TRN_FRAC'],'0',',','.'); ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_QTY'] % $bo['TRN_FRAC'],'0',',','.'); ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_QTY_BONUS'],'0',',','.'); ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_GROSS'],'0',',','.'); ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_DISCOUNT'],'0',',','.'); ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_GROSS'] - $bo['TRN_DISCOUNT'],'0',',','.'); ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_PPN'],'0',',','.'); ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_GROSS'] - $bo['TRN_DISCOUNT'] + $bo['TRN_PPN'],'0',',','.'); ?></td>
                                <td class="text-start"><?= $bo['TRN_KODE_SUPPLIER']; ?></td>
                                <td class="text-start text-nowrap"><?= $bo['TRN_NAMA_SUPPLIER']; ?></td>
                                <td class="text-start"></td>
                            </tr>
                            <?php $rphGross     += $bo['TRN_GROSS']; 
    					    $rphDiscount  += $bo['TRN_DISCOUNT']; 
					  	    $rphPPN       += $bo['TRN_PPN']; ?>
                        <?php endforeach ?>
                    </tbody>
                    <tfoot class="table-group-divider">
                        <tr class="info">
                            <td colspan="13" class="fw-bold text-center">TOTAL</td>
                            <?php
                                if ($rphGross != 0) {

                                    echo '<td class="fw-bold text-end">'  . number_format( $rphGross, 0, '.', ',') . '</td>';
                                    echo '<td class="fw-bold text-end">'  . number_format( $rphDiscount, 0, '.', ',') . '</td>';
                                    echo '<td class="fw-bold text-end">'  . number_format( $rphGross - $rphDiscount, 0, '.', ',') . '</td>';
                                    echo '<td class="fw-bold text-end">'  . number_format( $rphPPN, 0, '.', ',') . '</td>';
                                    echo '<td class="fw-bold text-end">'  . number_format( $rphGross - $rphDiscount + $rphPPN, 0, '.', ',') . '</td>';
                                } 
                            ?>
                            <td colspan="3" class="text-center">&nbsp;</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php } else if($lap == "1B") { ?>
            <h2 class="fw-bold">LAPORAN PER PRODUK DETAIL</h2>
            <h4>Periode : <?= date('d M Y',strtotime($awal)); ?> sd <?= date('d M Y',strtotime($akhir)); ?></h4>
            <br>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered border-dark">
                    <thead class="table-group-divider">
                        <tr class="">
                            <th class="fw-bold text-center bg-info" rowspan="2">#</th>
                            <th class="fw-bold text-center bg-info" rowspan="2">Tipe</th>
                            <th class="fw-bold text-center bg-info" colspan="2">Dokumen</th>
                            <th class="fw-bold text-center bg-info" colspan="2">PO / Referensi</th>
                            <th class="fw-bold text-center bg-info" colspan="3">Divisi</th>
                            <th class="fw-bold text-center bg-info" colspan="5">Produk</th>
                            <th class="fw-bold text-center bg-info" colspan="3">Quantity</th>
                            <th class="fw-bold text-center bg-info" colspan="5">Rupiah</th>
                            <th class="fw-bold text-center bg-info" colspan="2">Supplier</th>
                            <th class="fw-bold text-center bg-info" rowspan="2">Keterangan</th>
                        </tr>
                        <tr class="bg-danger">
                            <th class="fw-bold text-center bg-info">Nomor</th>
                            <th class="fw-bold text-center bg-info">Tanggal</th>
                            <th class="fw-bold text-center bg-info">Nomor</th>
                            <th class="fw-bold text-center bg-info">Tanggal</th>
                            <th class="fw-bold text-center bg-info">Div</th>
                            <th class="fw-bold text-center bg-info">Dep</th>
                            <th class="fw-bold text-center bg-info">Kat</th>
                            <th class="fw-bold text-center bg-info">PLU</th>
                            <th class="fw-bold text-center bg-info">Nama</th>
                            <th class="fw-bold text-center bg-info">Unit</th>
                            <th class="fw-bold text-center bg-info">Frac</th>
                            <th class="fw-bold text-center bg-info">Tag</th>
                            <th class="fw-bold text-center bg-info">CTN</th>
                            <th class="fw-bold text-center bg-info">Pcs</th>
                            <th class="fw-bold text-center bg-info">Bonus</th>
                            <th class="fw-bold text-center bg-info">Harga</th>
                            <th class="fw-bold text-center bg-info">Discount</th>
                            <th class="fw-bold text-center bg-info">Netto</th>
                            <th class="fw-bold text-center bg-info">PPN</th>
                            <th class="fw-bold text-center bg-info">Total</th>
                            <th class="fw-bold text-center bg-info">Kode</th>
                            <th class="fw-bold text-center bg-info">Nama</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                    <?php foreach($databo as $bo) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $bo['TRN_TYPE']; ?></td>
                            <td class="text-center"><?= $bo['TRN_NODOC']; ?></td>
                            <td class="text-center text-nowrap"><?= $bo['TRN_TGLDOC']; ?></td>
                            <td class="text-center"><?= $bo['TRN_NOPO']; ?></td>
                            <td class="text-center text-nowrap"><?= $bo['TRN_TGLPO']; ?></td>
                            <td class="text-center"><?= $bo['TRN_DIV']; ?></td>
                            <td class="text-center"><?= $bo['TRN_DEPT']; ?></td>
                            <td class="text-center"><?= $bo['TRN_KATB']; ?></td>
                            <td class="text-center"><?= $bo['TRN_PRDCD']; ?></td>
                            <td class="text-start text-nowrap"><?= $bo['TRN_NAMA_BARANG']; ?></td>
                            <td class="text-start"><?= $bo['TRN_UNIT']; ?></td>
                            <td class="text-start"><?= $bo['TRN_FRAC']; ?></td>
                            <td class="text-start"><?= $bo['TRN_TAG']; ?></td>
                            <td class="text-end"><?= number_format(($bo['TRN_QTY'] - $bo['TRN_QTY'] % $bo['TRN_FRAC']) / $bo['TRN_FRAC'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($bo['TRN_QTY'] % $bo['TRN_FRAC'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($bo['TRN_QTY_BONUS'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($bo['TRN_GROSS'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($bo['TRN_DISCOUNT'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($bo['TRN_GROSS'] - $bo['TRN_DISCOUNT'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($bo['TRN_PPN'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($bo['TRN_GROSS'] - $bo['TRN_DISCOUNT'] + $bo['TRN_PPN'],'0',',','.'); ?></td>
                            <td class="text-start"><?= $bo['TRN_KODE_SUPPLIER']; ?></td>
                            <td class="text-start text-nowrap"><?= $bo['TRN_NAMA_SUPPLIER']; ?></td>
                            <?php if($bo['TRN_TYPE'] == "Z") {
                                switch($bo['TRN_FLAG1']) {
                                    case "B":
                                        $ket1 = "Baik";
                                        break;
                                    case "T":
                                        $ket1 = "Retur";
                                        break;
                                    case "R":
                                        $ket1 = "Rusak";
                                        break;
                                }

                                switch($bo['TRN_FLAG2']) {
                                    case "B":
                                        $ket2 = "Baik";
                                        break;
                                    case "T":
                                        $ket2 = "Retur";
                                        break;
                                    case "R":
                                        $ket2 = "Rusak";
                                        break;
                                }
                                $keterangan = "$ket1 ke $ket2";
                            } ?>
                            <td class="text-start text-nowrap"><?= $keterangan; ?></td>
                        </tr>
                        <?php 
                            $rphGross     += $bo['TRN_GROSS']; 
                            $rphDiscount  += $bo['TRN_DISCOUNT']; 
                            $rphPPN       += $bo['TRN_PPN']; 
                        ?>
                    <?php endforeach ?>
                    </tbody>
                    <tfoot class="table-group-divider">
                        <tr class="">
                            <td colspan="17" class="fw-bold text-center">TOTAL</td>
                            <?php if($rphGross != 0) {
                                echo '<td class="fw-bold text-end">'  . number_format( $rphGross, 0, '.', ',') . '</td>';
								echo '<td class="fw-bold text-end">'  . number_format( $rphDiscount, 0, '.', ',') . '</td>';
								echo '<td class="fw-bold text-end">'  . number_format( $rphGross - $rphDiscount, 0, '.', ',') . '</td>';
								echo '<td class="fw-bold text-end">'  . number_format( $rphPPN, 0, '.', ',') . '</td>';
								echo '<td class="fw-bold text-end">'  . number_format( $rphGross - $rphDiscount + $rphPPN, 0, '.', ',') . '</td>';
                            } ?>
                            <td colspan="3">&nbsp;</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php } else if($lap == "2") { ?>
            <h2 class="fw-bold">LAPORAN PER SUPPLIER</h2>
            <h4>Periode : <?= date('d M Y',strtotime($awal)); ?> sd <?= date('d M Y',strtotime($akhir)); ?></h4>
            <br>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered border-dark">
                    <thead class="table-group-divider">
                        <tr>
                            <th class="fw-bold text-center bg-info" rowspan="2">#</th>
                            <th class="fw-bold text-center bg-info" rowspan="2">Tipe</th>
                            <th class="fw-bold text-center bg-info" colspan="2">Supplier</th>
                            <th class="fw-bold text-center bg-info" rowspan="2">Item</th>
                            <th class="fw-bold text-center bg-info" colspan="2">Quantity</th>
                            <th class="fw-bold text-center bg-info" colspan="5">Rupiah</th>
                            <th class="fw-bold text-center bg-info" rowspan="2">Keterangan</th>
                        </tr>
                        <tr>
                            <td class="fw-bold text-center bg-info">Kode</td>
                            <td class="fw-bold text-center bg-info">Nama</td>
                            <td class="fw-bold text-center bg-info">Pcs</td>
                            <td class="fw-bold text-center bg-info">Bonus</td>
                            <td class="fw-bold text-center bg-info">Harga</td>
                            <td class="fw-bold text-center bg-info">Discount</td>
                            <td class="fw-bold text-center bg-info">Netto</td>
                            <td class="fw-bold text-center bg-info">PPN</td>
                            <td class="fw-bold text-center bg-info">Total</td>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <?php foreach($databo as $bo) : ?>
                            <tr>
                                <td class="text-end"><?= $no++; ?></td>
                                <td class="text-center"><?= $bo['TRN_TYPE']; ?></td>
                                <td class="text-center"><?= $bo['TRN_KODE_SUPPLIER']; ?></td>
                                <td class="text-start"><?= $bo['TRN_NAMA_SUPPLIER']; ?></td>
                                <td class="text-center"><?= number_format($bo['TRN_ITEM'],'0',',','.'); ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_QTY'],'0',',','.'); ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_QTYBONUS'],'0',',','.'); ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_GROSS'],'0',',','.'); ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_DISCOUNT'],'0',',','.'); ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_GROSS'] - $bo['TRN_DISCOUNT'],'0',',','.'); ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_PPN'],'0',',','.'); ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_GROSS'] - $bo['TRN_DISCOUNT'] + $bo['TRN_PPN'],'0',',','.'); ?></td>
                                <td class="text-start"></td>
                            </tr>
                            <?php 
                                $rphGross     += $bo['TRN_GROSS']; 
                                $rphDiscount  += $bo['TRN_DISCOUNT']; 
                                $rphPPN       += $bo['TRN_PPN']; 
                            ?>
                        <?php endforeach ?>
                    </tbody>
                    <tfoot class="table-group-divider">
                        <tr class="">
                            <td colspan="7" class="fw-bold text-center">TOTAL</td>
                            <?php if($rphGross != 0) {
                                echo '<td class="fw-bold text-end">'  . number_format( $rphGross, 0, '.', ',') . '</td>';
								echo '<td class="fw-bold text-end">'  . number_format( $rphDiscount, 0, '.', ',') . '</td>';
								echo '<td class="fw-bold text-end">'  . number_format( $rphGross - $rphDiscount, 0, '.', ',') . '</td>';
								echo '<td class="fw-bold text-end">'  . number_format( $rphPPN, 0, '.', ',') . '</td>';
								echo '<td class="fw-bold text-end">'  . number_format( $rphGross - $rphDiscount + $rphPPN, 0, '.', ',') . '</td>';
                            } ?>
                            <td colspan="3">&nbsp;</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php } else if($lap == "3") { ?>
            <h2 class="fw-bold">LAPORAN PER DIVISI</h2>
            <h4>Periode : <?= date('d M Y',strtotime($awal)); ?> sd <?= date('d M Y',strtotime($akhir)); ?></h4>
            <br>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered border-dark">
                    <thead class="table-group-divider">
                        <tr>
                            <th rowspan="2" class="text-center fw-bold bg-info">#</th>
                            <th rowspan="2" class="text-center fw-bold bg-info">Tipe</th>
                            <th colspan="2" class="text-center fw-bold bg-info">Divisi</th>
                            <th rowspan="2" class="text-center fw-bold bg-info">Jml. Supplier</th>
                            <th rowspan="2" class="text-center fw-bold bg-info">Item</th>
                            <th colspan="2" class="text-center fw-bold bg-info">Quantity</th>
                            <th colspan="5" class="text-center fw-bold bg-info">Rupiah</th>
                            <th rowspan="2" class="text-center fw-bold bg-info">Keterangan</th>
                        </tr>
                        <tr>
                            <th class="fw-bold text-center bg-info">Kode</th>
                            <th class="fw-bold text-center bg-info">Nama</th>
                            <th class="fw-bold text-center bg-info">Pcs</th>
                            <th class="fw-bold text-center bg-info">Bonus</th>
                            <th class="fw-bold text-center bg-info">Harga</th>
                            <th class="fw-bold text-center bg-info">Discount</th>
                            <th class="fw-bold text-center bg-info">Netto</th>
                            <th class="fw-bold text-center bg-info">PPN</th>
                            <th class="fw-bold text-center bg-info">Kode</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <?php foreach($databo as $bo) : ?>
                            <tr>
                                <td class="text-end"><?= $no++; ?></td>
                                <td class="text-center"><?= $bo['TRN_TYPE']; ?></td>
                                <td class="text-center"><?= $bo['TRN_DIV']; ?></td>
                                <td class="text-start"><?= $bo['TRN_DIV_NAMA']; ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_KODE_SUPPLIER'],'0',',','.'); ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_ITEM'],'0',',','.'); ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_QTY'],'0',',','.'); ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_QTYBONUS'],'0',',','.'); ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_GROSS'],'0',',','.'); ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_DISCOUNT'],'0',',','.'); ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_GROSS'] - $bo['TRN_DISCOUNT'],'0',',','.'); ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_PPN'],'0',',','.'); ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_GROSS'] - $bo['TRN_DISCOUNT'] + $bo['TRN_PPN'],'0',',','.'); ?></td>
                                <td></td>
                            </tr>
                            <?php
                                $rphGross     += $bo['TRN_GROSS']; 
                                $rphDiscount  += $bo['TRN_DISCOUNT']; 
                                $rphPPN       += $bo['TRN_PPN']; 
                                $jumlahItem   += $bo['TRN_ITEM'];
                            ?>
                        <?php endforeach ?>
                    </tbody>
                    <tfoot class="table-group-divider">
                        <tr>
                            <td colspan="5" class="fw-bold text-center">TOTAL</td>
                            <?php if($rphGross != 0) {
                                echo '<td class="fw-bold text-end">'  . number_format( $jumlahItem, 0, '.', ',') . '</td>';
								echo '<td colspan="1" align="right">'  . '&nbsp;' . '</td>';
								echo '<td colspan="1" align="right">'  . '&nbsp;' . '</td>';
                                echo '<td class="fw-bold text-end">'  . number_format( $rphGross, 0, '.', ',') . '</td>';
								echo '<td class="fw-bold text-end">'  . number_format( $rphDiscount, 0, '.', ',') . '</td>';
								echo '<td class="fw-bold text-end">'  . number_format( $rphGross - $rphDiscount, 0, '.', ',') . '</td>';
								echo '<td class="fw-bold text-end">'  . number_format( $rphPPN, 0, '.', ',') . '</td>';
								echo '<td class="fw-bold text-end">'  . number_format( $rphGross - $rphDiscount + $rphPPN, 0, '.', ',') . '</td>';
                            } ?>
                            <td colspan="3">&nbsp;</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php } else if($lap == "4") { ?>
            <h2 class="fw-bold">LAPORAN PER DEPARTEMENT</h2>
            <h4>Periode : <?= date('d M Y',strtotime($awal)); ?> sd <?= date('d M Y',strtotime($akhir)); ?></h4>
            <br>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered border-dark">
                    <thead class="table-group-divider">
                        <tr>
                            <th rowspan="2" class="fw-bold text-center bg-info">#</th>
                            <th rowspan="2" class="fw-bold text-center bg-info">Tipe</th>
                            <th colspan="2" class="fw-bold text-center bg-info">Divisi</th>
                            <th rowspan="2" class="fw-bold text-center bg-info">Jml. Supplier</th>
                            <th rowspan="2" class="fw-bold text-center bg-info">Item</th>
                            <th colspan="2" class="fw-bold text-center bg-info">Quantity</th>
                            <th colspan="5" class="fw-bold text-center bg-info">Rupiah</th>
                            <th rowspan="2" class="fw-bold text-center bg-info">Keterangan</th>
                        </tr>
                        <tr>
                            <th class="fw-bold text-center bg-info">Divisi</th>
                            <th class="fw-bold text-center bg-info">Dept</th>
                            <th class="fw-bold text-center bg-info">Pcs</th>
                            <th class="fw-bold text-center bg-info">Bonus</th>
                            <th class="fw-bold text-center bg-info">Harga</th>
                            <th class="fw-bold text-center bg-info">Discount</th>
                            <th class="fw-bold text-center bg-info">Netto</th>
                            <th class="fw-bold text-center bg-info">PPN</th>
                            <th class="fw-bold text-center bg-info">Total</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <?php foreach($databo as $bo) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $bo['TRN_TYPE']; ?></td>
                            <td class="text-center"><?= $bo['TRN_DIV']; ?></td>
                            <td class="text-center"><?= $bo['TRN_DEPT']; ?></td>
                            <td class="text-end"><?= number_format($bo['TRN_KODE_SUPPLIER'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($bo['TRN_ITEM'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($bo['TRN_QTY'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($bo['TRN_QTYBONUS'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($bo['TRN_GROSS'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($bo['TRN_DISCOUNT'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($bo['TRN_GROSS'] - $bo['TRN_DISCOUNT'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($bo['TRN_PPN'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($bo['TRN_GROSS'] - $bo['TRN_DISCOUNT'] + $bo['TRN_PPN'],'0',',','.'); ?></td>
                            <td class="text-center"></td>
                        </tr>
                        <?php
                            $rphGross     += $bo['TRN_GROSS']; 
                            $rphDiscount  += $bo['TRN_DISCOUNT']; 
                            $rphPPN       += $bo['TRN_PPN'];
                            $jumlahItem   += $bo['TRN_ITEM'];
                        ?>
                        <?php endforeach ?>
                    </tbody>
                    <tfoot class="table-group-divider">
                        <tr>
                        <td colspan="5" class="fw-bold text-center">TOTAL</td>
                            <?php if($rphGross != 0) {
                                echo '<td class="fw-bold text-end">'  . number_format( $jumlahItem, 0, '.', ',') . '</td>';
								echo '<td colspan="1" align="right">'  . '&nbsp;' . '</td>';
								echo '<td colspan="1" align="right">'  . '&nbsp;' . '</td>';
                                echo '<td class="fw-bold text-end">'  . number_format( $rphGross, 0, '.', ',') . '</td>';
								echo '<td class="fw-bold text-end">'  . number_format( $rphDiscount, 0, '.', ',') . '</td>';
								echo '<td class="fw-bold text-end">'  . number_format( $rphGross - $rphDiscount, 0, '.', ',') . '</td>';
								echo '<td class="fw-bold text-end">'  . number_format( $rphPPN, 0, '.', ',') . '</td>';
								echo '<td class="fw-bold text-end">'  . number_format( $rphGross - $rphDiscount + $rphPPN, 0, '.', ',') . '</td>';
                            } ?>
                            <td colspan="1">&nbsp;</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php } else if($lap == "5") { ?>
            <h2 class="fw-bold">LAPORAN PER KATEGORI</h2>
            <h4>Periode : <?= date('d M Y',strtotime($awal)); ?> sd <?= date('d M Y',strtotime($akhir)); ?></h4>
            <br>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered border-dark">
                    <thead class="table-group-divider">
                        <tr>
                            <th rowspan="2" class="fw-bold text-center bg-info">#</th>
                            <th rowspan="2" class="fw-bold text-center bg-info">Tipe</th>
                            <th colspan="3" class="fw-bold text-center bg-info">Divisi</th>
                            <th rowspan="2" class="fw-bold text-center bg-info">Jml. Supplier</th>
                            <th rowspan="2" class="fw-bold text-center bg-info">Jml. Item</th>
                            <th colspan="2" class="fw-bold text-center bg-info">Quantity</th>
                            <th colspan="5" class="fw-bold text-center bg-info">Rupiah</th>
                            <th rowspan="2" class="fw-bold text-center bg-info">Keterangan</th>
                        </tr>
                        <tr>
                            <th class="fw-bold text-center bg-info">Div</th>
                            <th class="fw-bold text-center bg-info">Dept</th>
                            <th class="fw-bold text-center bg-info">Katb</th>
                            <th class="fw-bold text-center bg-info">Pcs</th>
                            <th class="fw-bold text-center bg-info">Bonus</th>
                            <th class="fw-bold text-center bg-info">Harga</th>
                            <th class="fw-bold text-center bg-info">Discount</th>
                            <th class="fw-bold text-center bg-info">Netto</th>
                            <th class="fw-bold text-center bg-info">PPN</th>
                            <th class="fw-bold text-center bg-info">Total</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <?php foreach($databo as $bo) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $bo['TRN_TYPE']; ?></td>
                            <td class="text-center"><?= $bo['TRN_DIV']; ?></td>
                            <td class="text-center"><?= $bo['TRN_DEPT']; ?></td>
                            <td class="text-center"><?= $bo['TRN_KATB']; ?></td>
                            <td class="text-end"><?= number_format($bo['TRN_KODE_SUPPLIER'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($bo['TRN_ITEM'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($bo['TRN_QTY'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($bo['TRN_QTYBONUS'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($bo['TRN_GROSS'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($bo['TRN_DISCOUNT'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($bo['TRN_GROSS'] - $bo['TRN_DISCOUNT'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($bo['TRN_PPN'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($bo['TRN_GROSS'] - $bo['TRN_DISCOUNT'] + $bo['TRN_PPN'],'0',',','.'); ?></td>
                            <td></td>
                        </tr>
                        <?php
                            $rphGross     += $bo['TRN_GROSS']; 
                            $rphDiscount  += $bo['TRN_DISCOUNT']; 
                            $rphPPN       += $bo['TRN_PPN'];
                            $jumlahItem   += $bo['TRN_ITEM'];
                        ?>
                        <?php endforeach ?>
                    </tbody>
                    <tfoot class="table-group-divider">
                        <tr>
                            <td colspan="6" class="fw-bold text-center">TOTAL</td>
                            <?php if($rphGross != 0) {
                                echo '<td class="fw-bold text-end">'  . number_format( $jumlahItem, 0, '.', ',') . '</td>';
								echo '<td colspan="1" align="right">'  . '&nbsp;' . '</td>';
								echo '<td colspan="1" align="right">'  . '&nbsp;' . '</td>';
                                echo '<td class="fw-bold text-end">'  . number_format( $rphGross, 0, '.', ',') . '</td>';
								echo '<td class="fw-bold text-end">'  . number_format( $rphDiscount, 0, '.', ',') . '</td>';
								echo '<td class="fw-bold text-end">'  . number_format( $rphGross - $rphDiscount, 0, '.', ',') . '</td>';
								echo '<td class="fw-bold text-end">'  . number_format( $rphPPN, 0, '.', ',') . '</td>';
								echo '<td class="fw-bold text-end">'  . number_format( $rphGross - $rphDiscount + $rphPPN, 0, '.', ',') . '</td>';
                            } ?>
                            <td colspan="1">&nbsp;</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php } else if($lap == "6") { ?>
            <h2 class="fw-bold">LAPORAN PER HARI</h2>
            <h4>Periode : <?= date('d M Y',strtotime($awal)); ?> sd <?= date('d M Y',strtotime($akhir)); ?></h4>
            <br>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered border-dark">
                    <thead class="table-group-divider">
                        <tr>
                            <th rowspan="2" class="fw-bold text-center bg-info">#</th>
                            <th rowspan="2" class="fw-bold text-center bg-info">Tipe</th>
                            <th rowspan="2" class="fw-bold text-center bg-info">Tanggal</th>
                            <th rowspan="2" class="fw-bold text-center bg-info">Jml. Supplier</th>
                            <th rowspan="2" class="fw-bold text-center bg-info">Item</th>
                            <th colspan="2" class="fw-bold text-center bg-info">Quantity</th>
                            <th colspan="5" class="fw-bold text-center bg-info">Rupiah</th>
                            <th rowspan="2" class="fw-bold text-center bg-info">Keterangan</th>
                        </tr>
                        <tr>
                            <th class="fw-bold text-center bg-info">Pcs</th>
                            <th class="fw-bold text-center bg-info">Bonus</th>
                            <th class="fw-bold text-center bg-info">Harga</th>
                            <th class="fw-bold text-center bg-info">Discount</th>
                            <th class="fw-bold text-center bg-info">Netto</th>
                            <th class="fw-bold text-center bg-info">PPN</th>
                            <th class="fw-bold text-center bg-info">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($databo as $bo) : ?>
                            <tr>
                                <td class="text-end"><?= $no++; ?></td>
                                <td class="text-center"><?= $bo['TRN_TYPE']; ?></td>
                                <td class="text-center"><?= $bo['TRN_TGLDOC']; ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_KODE_SUPPLIER'],'0',',','.'); ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_ITEM'],'0',',','.'); ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_QTY'],'0',',','.'); ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_QTYBONUS'],'0',',','.'); ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_GROSS'],'0',',','.'); ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_DISCOUNT'],'0',',','.'); ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_GROSS'] - $bo['TRN_DISCOUNT'],'0',',','.'); ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_PPN'],'0',',','.'); ?></td>
                                <td class="text-end"><?= number_format($bo['TRN_GROSS'] - $bo['TRN_DISCOUNT'] + $bo['TRN_PPN'],'0',',','.'); ?></td>
                                <td></td>
                            </tr>
                            <?php
                                $rphGross     += $bo['TRN_GROSS']; 
                                $rphDiscount  += $bo['TRN_DISCOUNT']; 
                                $rphPPN       += $bo['TRN_PPN'];
                            ?>
                        <?php endforeach ?>
                    </tbody>
                    <tfoot class="table-group-divider">
                        <tr>
                            <td colspan="7" class="fw-bold text-center">TOTAL</td>
                            <?php if($rphGross != 0) {
                                echo '<td class="fw-bold text-end">'  . number_format( $rphGross, 0, '.', ',') . '</td>';
								echo '<td class="fw-bold text-end">'  . number_format( $rphDiscount, 0, '.', ',') . '</td>';
								echo '<td class="fw-bold text-end">'  . number_format( $rphGross - $rphDiscount, 0, '.', ',') . '</td>';
								echo '<td class="fw-bold text-end">'  . number_format( $rphPPN, 0, '.', ',') . '</td>';
								echo '<td class="fw-bold text-end">'  . number_format( $rphGross - $rphDiscount + $rphPPN, 0, '.', ',') . '</td>';
                            } ?>
                            <td colspan="3">&nbsp;</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php } else if($lap == "0") { ?>
            <?php if($jenis == "RETUROMI") { ?>
                <h2 class="fw-bold">DATA RETUR OMI</h2>
                <h4>Periode : <?= date('d M Y',strtotime($awal)); ?> sd <?= date('d M Y',strtotime($akhir)); ?></h4>
                <br>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered border-dark">
                        <thead class="table-group-divider">
                            <tr>
                                <th class="fw-bold text-center bg-info">#</th>
                                <th class="fw-bold text-center bg-info">Kode_OMI</th>
                                <th class="fw-bold text-center bg-info">Nama_OMI</th>
                                <th class="fw-bold text-center bg-info">No_Dokumen</th>
                                <th class="fw-bold text-center bg-info">Tgl_Dokumen</th>
                                <th class="fw-bold text-center bg-info">No_Referensi</th>
                                <th class="fw-bold text-center bg-info">Tgl_Referensi</th>
                                <th class="fw-bold text-center bg-info">Div</th>
                                <th class="fw-bold text-center bg-info">Dept</th>
                                <th class="fw-bold text-center bg-info">Katb</th>
                                <th class="fw-bold text-center bg-info">PLU</th>
                                <th class="fw-bold text-center bg-info">Deskripsi</th>
                                <th class="fw-bold text-center bg-info">BKP</th>
                                <th class="fw-bold text-center bg-info">Unit</th>
                                <th class="fw-bold text-center bg-info">Frac</th>
                                <th class="fw-bold text-center bg-info">Qty</th>
                                <th class="fw-bold text-center bg-info">Rupiah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($databo as $bo) : ?>
                                <tr>
                                    <td class="text-end"><?= $no++; ?></td>
                                    <td class="text-center"><?= $bo['KODEOMI']; ?></td>
                                    <td class="text-center text-nowrap"><?= $bo['NAMAOMI']; ?></td>
                                    <td class="text-center"><?= $bo['NODOKUMEN']; ?></td>
                                    <td class="text-center"><?= $bo['TGLDOKUMEN']; ?></td>
                                    <td class="text-center"><?= $bo['NOREFERENSI']; ?></td>
                                    <td class="text-center"><?= $bo['TGLREFERENSI']; ?></td>
                                    <td class="text-center"><?= $bo['DIV']; ?></td>
                                    <td class="text-center"><?= $bo['DEP']; ?></td>
                                    <td class="text-center"><?= $bo['KAT']; ?></td>
                                    <td class="text-center"><?= $bo['PLU']; ?></td>
                                    <td class="text-start text-nowrap"><?= $bo['DESKRIPSI']; ?></td>
                                    <td class="text-center"><?= $bo['BKP']; ?></td>
                                    <td class="text-center"><?= $bo['UNIT']; ?></td>
                                    <td class="text-center"><?= $bo['FRAC']; ?></td>
                                    <td class="text-end"><?= number_format($bo['QTY'],'0',',','.'); ?></td>
                                    <td class="text-end"><?= number_format($bo['RUPIAH'],'0',',','.'); ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php } else if($jenis == "SOIC") { ?>
                <h2 class="fw-bold">RESET SO IC</h2>
                <h4>Periode : <?= date('d M Y',strtotime($awal)); ?> sd <?= date('d M Y',strtotime($akhir)); ?></h4>
                <br>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered border-dark">
                        <thead class="table-group-divider">
                            <tr>
                                <th class="fw-bold text-center bg-info">#</th>
                                <th class="fw-bold text-center bg-info">Tanggal</th>
                                <th class="fw-bold text-center bg-info">No_SO</th>
                                <th class="fw-bold text-center bg-info">Div</th>
                                <th class="fw-bold text-center bg-info">Dept</th>
                                <th class="fw-bold text-center bg-info">Katb</th>
                                <th class="fw-bold text-center bg-info">PLU</th>
                                <th class="fw-bold text-center bg-info">Deskripsi</th>
                                <th class="fw-bold text-center bg-info">Unit</th>
                                <th class="fw-bold text-center bg-info">Frac</th>
                                <th class="fw-bold text-center bg-info">Tag</th>
                                <th class="fw-bold text-center bg-info">Flag_Jual</th>
                                <th class="fw-bold text-center bg-info">Qty</th>
                                <th class="fw-bold text-center bg-info">Rupiah</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <?php foreach($databo as $bo) : ?>
                                <tr>
                                    <td class="text-end"><?= $no++; ?></td>
                                    <td class="text-center"><?= $bo['TANGGAL']; ?></td>
                                    <td class="text-center"><?= $bo['NOMORSO']; ?></td>
                                    <td class="text-center"><?= $bo['DIV']; ?></td>
                                    <td class="text-center"><?= $bo['DEP']; ?></td>
                                    <td class="text-center"><?= $bo['KAT']; ?></td>
                                    <td class="text-center"><?= $bo['PLU']; ?></td>
                                    <td class="text-start text-nowrap"><?= $bo['DESKRIPSI']; ?></td>
                                    <td class="text-center"><?= $bo['UNIT']; ?></td>
                                    <td class="text-center"><?= $bo['FRAC']; ?></td>
                                    <td class="text-center"><?= $bo['TAG']; ?></td>
                                    <td class="text-center"><?= $bo['FLAGJUAL']; ?></td>
                                    <td class="text-end"><?= number_format($bo['QTY'],'0',',','.'); ?></td>
                                    <td class="text-end"><?= number_format($bo['RUPIAH'],'0',',','.'); ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php } else if($jenis == "POBTBSUP") { ?>
                <h2 class="fw-bold">PO vs BTB PER SUPPLIER</h2>
                <h4>Periode : <?= date('d M Y',strtotime($awal)); ?> sd <?= date('d M Y',strtotime($akhir)); ?></h4>
                <br>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered border-dark">
                        <thead class="table-group-divider">
                            <tr>
                                <th class="fw-bold text-center bg-info">#</th>
                                <th class="fw-bold text-center bg-info">Kd_Supplier</th>
                                <th class="fw-bold text-center bg-info">Nm_Supplier</th>
                                <th class="fw-bold text-center bg-info">Div</th>
                                <th class="fw-bold text-center bg-info">Dept</th>
                                <th class="fw-bold text-center bg-info">Katb</th>
                                <th class="fw-bold text-center bg-info">PLU</th>
                                <th class="fw-bold text-center bg-info">Deskripsi</th>
                                <th class="fw-bold text-center bg-info">Unit</th>
                                <th class="fw-bold text-center bg-info">Frac</th>
                                <th class="fw-bold text-center bg-info">Tag</th>
                                <th class="fw-bold text-center bg-info">No_PO</th>
                                <th class="fw-bold text-center bg-info">Tgl_PO</th>
                                <th class="fw-bold text-center bg-info">Qty_PO</th>
                                <th class="fw-bold text-center bg-info">Rph_PO</th>
                                <th class="fw-bold text-center bg-info">No_BTB</th>
                                <th class="fw-bold text-center bg-info">Tgl_BTB</th>
                                <th class="fw-bold text-center bg-info">Qty_BTB</th>
                                <th class="fw-bold text-center bg-info">Rph_BTB</th>
                                <th class="fw-bold text-center bg-info">Selisih_Qty</th>
                                <th class="fw-bold text-center bg-info">Selisih_Rph</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <?php foreach($databo as $bo) : ?>
                                <tr>
                                    <td class="text-end"><?= $no++; ?></td>
                                    <td class="text-center"><?= $bo['KODESUP']; ?></td>
                                    <td class="text-start text-nowrap"><?= $bo['NAMASUPPLIER']; ?></td>
                                    <td class="text-center"><?= $bo['DIV']; ?></td>
                                    <td class="text-center"><?= $bo['DEP']; ?></td>
                                    <td class="text-center"><?= $bo['KAT']; ?></td>
                                    <td class="text-center"><?= $bo['PLU']; ?></td>
                                    <td class="text-start text-nowrap"><?= $bo['DESKRIPSI']; ?></td>
                                    <td class="text-center"><?= $bo['UNIT']; ?></td>
                                    <td class="text-center"><?= $bo['FRAC']; ?></td>
                                    <td class="text-center"><?= $bo['TAG']; ?></td>
                                    <td class="text-center"><?= $bo['NOPO']; ?></td>
                                    <td class="text-center text-nowrap"><?= $bo['TGLPO']; ?></td>
                                    <td class="text-end"><?= number_format($bo['QTYPO'],'0',',','.'); ?></td>
                                    <td class="text-end"><?= number_format($bo['RPHPO'],'0',',','.'); ?></td>
                                    <td class="text-center"><?= $bo['NOBTB']; ?></td>
                                    <td class="text-center text-nowrap"><?= $bo['TGLBTB']; ?></td>
                                    <td class="text-end"><?= number_format($bo['QTYBTB'],'0',',','.'); ?></td>
                                    <td class="text-end"><?= number_format($bo['RPHBTB'],'0',',','.'); ?></td>
                                    <td class="text-end"><?= number_format($bo['QTYBTB'] - $bo['QTYPO'],'0',',','.'); ?></td>
                                    <td class="text-end"><?= number_format($bo['RPHBTB'] - $bo['RPHPO'],'0',',','.'); ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        <?php } ?>
    </body>
</html>