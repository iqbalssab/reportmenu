<?php $ip = "http://".$_SERVER['SERVER_NAME']."/reportmenu/"; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Data LPP Saat Ini</title>

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
                overflow: auto;
                text-overflow: clip;
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
        $dep = $katbr = "ALL";
        // variable untuk menghitung total nilai
  	    $saldoQtyInPcs = $saldoQtyCtn = $saldoQtyPcs = $saldoRupiah = $saldoRupiahLCost = $jumlahProduk = $jumlahSupplier = 0;

        if($jenisLaporan == '1') {
            $lap = 'LAPORAN per DIVISI';
        } else if($jenisLaporan == '2') {
            $lap = 'LAPORAN per DEPARTEMEN';
        } else if($jenisLaporan == '3') {
            $lap = 'LAPORAN per KATEGORI';
        } else if($jenisLaporan == '4') {
            $lap = 'LAPORAN per PRODUK';
        } else if($jenisLaporan == '4B') {
            $lap = 'LAPORAN PRODUK DISKON 2';
        } else if($jenisLaporan == '4C') {
            $lap = 'LAPORAN PRODUK DETAIL';
        } else if($jenisLaporan == '5') {
            $lap = 'LAPORAN per SUPPLIER';
        } else if($jenisLaporan == '6') {
            $lap = 'LAPORAN per KODE TAG';
        } else if($jenisLaporan == '7') {
            $lap = 'LAPORAN per Group Sales';
        }

        if ($lokasiStock == '01') {
		    $lokasiStock = 'Barang Baik';
		} elseif ($lokasiStock == '02') {
		    $lokasiStock = 'Barang Retur';
		} else {
		    $lokasiStock = 'Barang Rusak';
		}

        if($kodeDivisi == "All") {
            $kodeDivisi = "ALL";
        } else if($kodeDivisi == "1") {
            $kodeDivisi = "Food";
        } else if($kodeDivisi == "2") {
            $kodeDivisi = "Non Food";
        } else if($kodeDivisi == "3") {
            $kodeDivisi = "GMS";
        } else if($kodeDivisi == "4") {
            $kodeDivisi = "Perishable";
        } else if($kodeDivisi == "5") {
            $kodeDivisi = "Counter & Promotion";
        } else if($kodeDivisi == "6") {
            $kodeDivisi = "Fast Food";
        } else if($kodeDivisi == "7") {
            $kodeDivisi = "I-Fashion";
        } else if($kodeDivisi == "8") {
            $kodeDivisi = "I-Tech";
        } else if($kodeDivisi == "9") {
            $kodeDivisi = "I-Tronic";
        }

        foreach($dept as $dp) :
            if($kodeDepartemen == $dp['DEP_KODEDEPARTEMENT']) {
                $dep = $dp['DEP_NAMADEPARTEMENT'];
            }
        endforeach;

        foreach($katb as $kt) :
            if($kodeKategoriBarang == $kt['KAT_KODEDEPARTEMENT'].$kt['KAT_KODEKATEGORI'] ) {
                $katbr = $kt['KAT_NAMAKATEGORI'];
            }
        endforeach;

        if($statusTag == "All") {
            $statusTag = "ALL";
        } else if($statusTag == "Active") {
            $statusTag = "Tag Aktif";
        } else if($statusTag == "Discontinue") {
            $statusTag = "Tag Discontinue [ARNHOTX]";
        }
        
        if($statusQty == "All") {
            $statusQty = "ALL";
        } else if($statusQty == "1") {
            $statusQty = "Qty Minus";
        } else if($statusQty == "2") {
            $statusQty = "Qty Kosong";
        } else if($statusQty == "3") {
            $statusQty = "Qty Ada";
        } else if($statusQty == "4") {
            $statusQty = "Qty Dibawah DSI 3 Hari";
        } else if($statusQty == "5") {
            $statusQty = "Qty Dibawah PKM";
        }
        ?>

        <?php if($jenisLaporan == "1") { ?>
            <h4 class="fw-bold"><?= $lap; ?> [LPP <?= $lokasiStock; ?> - Divisi : <?= $kodeDivisi; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr;?>]</h4>
            <h6>Periode : <?= date('d M Y') ?></h6>
            <h6>Tag : <?= $statusTag; ?></h6>
            <h6>Status Qty : <?= $statusQty; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th class="fw-bold text-center bg-info">#</th>
                        <th class="fw-bold text-center bg-info">Div</th>
                        <th class="fw-bold text-center bg-info">Nama Divisi</th>
                        <th class="fw-bold text-center bg-info">Item</th>
                        <th class="fw-bold text-center bg-info">Saldo in Pcs</th>
                        <th class="fw-bold text-center bg-info">Saldo in Rph (by Acost)</th>
                        <th class="fw-bold text-center bg-info">Saldo in Rph (by Lcost)</th>
                        <th class="fw-bold text-center bg-info">Jumlah Supplier</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($lppSaatIni as $lpp) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $lpp['ST_DIV']; ?></td>
                            <td class="text-start"><?= $lpp['ST_DIV_NAMA']; ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_ITEM_PRODUK'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SALDO_IN_PCS'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SALDO_RPH'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SALDO_RPH_LASTCOST'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SUPP_JUMLAH'],'0',',','.'); ?></td>
                        </tr>
                        <?php
                            $jumlahProduk      += $lpp['ST_ITEM_PRODUK'];
                            $saldoQtyInPcs     += $lpp['ST_SALDO_IN_PCS'];
                            $saldoRupiah       += $lpp['ST_SALDO_RPH'];
                            $jumlahSupplier    += $lpp['ST_SUPP_JUMLAH'];
                            $saldoRupiahLCost  += $lpp['ST_SALDO_RPH_LASTCOST'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="3" class="fw-bold text-center">Total</td>
                        <td class="fw-bold text-end"><?= number_format($jumlahProduk,'0',',','.'); ?></td>
                        <td class="fw-bold text-end"><?= number_format($saldoQtyInPcs,'0',',','.'); ?></td>
                        <td class="fw-bold text-end"><?= number_format($saldoRupiah,'0',',','.'); ?></td>
                        <td class="fw-bold text-end"><?= number_format($saldoRupiahLCost,'0',',','.'); ?></td>
                        <td class="fw-bold text-end"><?= number_format($jumlahSupplier,'0',',','.'); ?></td>
                    </tr>
                </tfoot>
            </table>
        <?php } else if($jenisLaporan == "2") { ?>
            <h4 class="fw-bold"><?= $lap; ?> [LPP <?= $lokasiStock; ?> - Divisi : <?= $kodeDivisi; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr;?>]</h4>
            <h6>Periode : <?= date('d M Y') ?></h6>            
            <h6>Tag : <?= $statusTag; ?></h6>
            <h6>Status Qty : <?= $statusQty; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th class="fw-bold text-center bg-info">#</th>
                        <th class="fw-bold text-center bg-info">Div</th>
                        <th class="fw-bold text-center bg-info">Nama Divisi</th>
                        <th class="fw-bold text-center bg-info">Dep</th>
                        <th class="fw-bold text-center bg-info">Nama Departemen</th>
                        <th class="fw-bold text-center bg-info">Item</th>
                        <th class="fw-bold text-center bg-info">Saldo in Pcs</th>
                        <th class="fw-bold text-center bg-info">Saldo in Rph (by Acost)</th>
                        <th class="fw-bold text-center bg-info">Saldo in Rph (by Lcost)</th>
                        <th class="fw-bold text-center bg-info">Jumlah Supplier</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($lppSaatIni as $lpp) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $lpp['ST_DIV']; ?></td>
                            <td class="text-start"><?= $lpp['ST_DIV_NAMA']; ?></td>
                            <td class="text-center"><?= $lpp['ST_DEPT']; ?></td>
                            <td class="text-start"><?= $lpp['ST_DEPT_NAMA']; ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_ITEM_PRODUK'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SALDO_IN_PCS'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SALDO_RPH'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SALDO_RPH_LASTCOST'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SUPP_JUMLAH'],'0',',','.'); ?></td>
                        </tr>
                        <?php
                            $jumlahProduk      += $lpp['ST_ITEM_PRODUK'];
                            $saldoQtyInPcs     += $lpp['ST_SALDO_IN_PCS'];
                            $saldoRupiah       += $lpp['ST_SALDO_RPH'];
                            $jumlahSupplier    += $lpp['ST_SUPP_JUMLAH'];
                            $saldoRupiahLCost  += $lpp['ST_SALDO_RPH_LASTCOST'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="5" class="fw-bold text-center">Total</td>
                        <td class="fw-bold text-end"><?= number_format($jumlahProduk,'0',',','.'); ?></td>
                        <td class="fw-bold text-end"><?= number_format($saldoQtyInPcs,'0',',','.'); ?></td>
                        <td class="fw-bold text-end"><?= number_format($saldoRupiah,'0',',','.'); ?></td>
                        <td class="fw-bold text-end"><?= number_format($saldoRupiahLCost,'0',',','.'); ?></td>
                        <td class="fw-bold text-end"><?= number_format($jumlahSupplier,'0',',','.'); ?></td>
                    </tr>
                </tfoot>
            </table>
        <?php } else if($jenisLaporan == "3") { ?>
            <h4 class="fw-bold"><?= $lap; ?> [LPP <?= $lokasiStock; ?> - Divisi : <?= $kodeDivisi; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr;?>]</h4>
            <h6>Periode : <?= date('d M Y') ?></h6>            
            <h6>Tag : <?= $statusTag; ?></h6>
            <h6>Status Qty : <?= $statusQty; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th class="fw-bold text-center bg-info">#</th>
                        <th class="fw-bold text-center bg-info">Div</th>
                        <th class="fw-bold text-center bg-info">Nama Divisi</th>
                        <th class="fw-bold text-center bg-info">Dep</th>
                        <th class="fw-bold text-center bg-info">Nama Departemen</th>
                        <th class="fw-bold text-center bg-info">Kat</th>
                        <th class="fw-bold text-center bg-info">Nama Kategori</th>
                        <th class="fw-bold text-center bg-info">Item</th>
                        <th class="fw-bold text-center bg-info">Saldo in Pcs</th>
                        <th class="fw-bold text-center bg-info">Saldo in Rph (by Acost)</th>
                        <th class="fw-bold text-center bg-info">Saldo in Rph (by Lcost)</th>
                        <th class="fw-bold text-center bg-info">Jumlah Supplier</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($lppSaatIni as $lpp) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $lpp['ST_DIV']; ?></td>
                            <td class="text-start"><?= $lpp['ST_DIV_NAMA']; ?></td>
                            <td class="text-center"><?= $lpp['ST_DEPT']; ?></td>
                            <td class="text-start"><?= $lpp['ST_DEPT_NAMA']; ?></td>
                            <td class="text-center"><?= $lpp['ST_KATB']; ?></td>
                            <td class="text-start"><?= $lpp['ST_KATB_NAMA']; ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_ITEM_PRODUK'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SALDO_IN_PCS'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SALDO_RPH'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SALDO_RPH_LASTCOST'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SUPP_JUMLAH'],'0',',','.'); ?></td>
                        </tr>
                        <?php
                            $jumlahProduk      += $lpp['ST_ITEM_PRODUK'];
                            $saldoQtyInPcs     += $lpp['ST_SALDO_IN_PCS'];
                            $saldoRupiah       += $lpp['ST_SALDO_RPH'];
                            $jumlahSupplier    += $lpp['ST_SUPP_JUMLAH'];
                            $saldoRupiahLCost  += $lpp['ST_SALDO_RPH_LASTCOST'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="7" class="fw-bold text-center">Total</td>
                        <td class="fw-bold text-end"><?= number_format($jumlahProduk,'0',',','.'); ?></td>
                        <td class="fw-bold text-end"><?= number_format($saldoQtyInPcs,'0',',','.'); ?></td>
                        <td class="fw-bold text-end"><?= number_format($saldoRupiah,'0',',','.'); ?></td>
                        <td class="fw-bold text-end"><?= number_format($saldoRupiahLCost,'0',',','.'); ?></td>
                        <td class="fw-bold text-end"><?= number_format($jumlahSupplier,'0',',','.'); ?></td>
                    </tr>
                </tfoot>
            </table>
        <?php } else if($jenisLaporan == "4") { ?>
            <h4 class="fw-bold"><?= $lap; ?> [LPP <?= $lokasiStock; ?> - Divisi : <?= $kodeDivisi; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr;?>]</h4>
            <h6>Periode : <?= date('d M Y') ?></h6>            
            <h6>Tag : <?= $statusTag; ?></h6>
            <h6>Status Qty : <?= $statusQty; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center bg-info">#</th>
                        <th colspan="3" class="fw-bold text-center bg-info">Divisi</th>
                        <th colspan="5" class="fw-bold text-center bg-info">Produk</th>
                        <th colspan="6" class="fw-bold text-center bg-info">Stok</th>
                        <th colspan="3" class="fw-bold text-center bg-info">Supplier</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Flag</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center bg-info">Div</th>
                        <th class="fw-bold text-center bg-info">Dep</th>
                        <th class="fw-bold text-center bg-info">Kat</th>
                        <th class="fw-bold text-center bg-info">PLU</th>
                        <th class="fw-bold text-center bg-info">Deskripsi</th>
                        <th class="fw-bold text-center bg-info">Unit</th>
                        <th class="fw-bold text-center bg-info">Frac</th>
                        <th class="fw-bold text-center bg-info">Tag</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Qty in Ctn</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Qty in Pcs</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Acost in Pcs</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Stock Rph (by Acost)</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Lcost in Pcs</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Stock Rph (by Lcost)</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Kd Supplier</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nama Supplier</th>
                        <th class="fw-bold text-center bg-info">Status</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($lppSaatIni as $lpp) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $lpp['ST_DIV']; ?></td>
                            <td class="text-center"><?= $lpp['ST_DEPT']; ?></td>
                            <td class="text-center"><?= $lpp['ST_KATB']; ?></td>
                            <td class="text-center"><?= $lpp['ST_PRDCD']; ?></td>
                            <td class="text-start text-nowrap"><?= $lpp['ST_DESKRIPSI']; ?></td>
                            <td class="text-center"><?= $lpp['ST_UNIT']; ?></td>
                            <td class="text-center"><?= $lpp['ST_FRAC']; ?></td>
                            <td class="text-center"><?= $lpp['ST_KODETAG']; ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SALDO_CTN'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SALDO_PCS'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_AVGCOST'],'2',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SALDO_RPH'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_LASTCOST'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SALDO_RPH_LASTCOST'],'0',',','.'); ?></td>
                            <td class="text-center"><?= $lpp['ST_SUPP_KODE']; ?></td>
                            <td class="text-start text-nowrap"><?= $lpp['ST_SUPP_NAMA']; ?></td>
                            <td class="text-center"><?= $lpp['ST_PERLAKUAN_BARANG']; ?></td>
                            <td class="text-center text-nowrap"><?= $lpp['ST_IGR_IDM']; ?></td>
                        </tr>
                        <?php
                            $saldoQtyCtn       += $lpp['ST_SALDO_CTN']; 
                            $saldoQtyPcs       += $lpp['ST_SALDO_PCS']; 
                            $saldoRupiah       += $lpp['ST_SALDO_RPH'];    
                            $saldoRupiahLCost  += $lpp['ST_SALDO_RPH_LASTCOST'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="9" class="fw-bold text-center">Total</td>
                        <td class="fw-bold text-end"><?= number_format($saldoQtyCtn,'0',',','.'); ?></td>
                        <td class="fw-bold text-end"><?= number_format($saldoQtyPcs,'0',',','.'); ?></td>
                        <td>&nbsp;</td>
                        <td class="fw-bold text-end"><?= number_format($saldoRupiah,'0',',','.'); ?></td>
                        <td>&nbsp;</td>
                        <td class="fw-bold text-end"><?= number_format($saldoRupiahLCost,'0',',','.'); ?></td>
                    </tr>
                </tfoot>
            </table>
        <?php } else if($jenisLaporan == "4B") { ?>
            <h4 class="fw-bold"><?= $lap; ?> [LPP <?= $lokasiStock; ?> - Divisi : <?= $kodeDivisi; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr;?>]</h4>
            <h6>Periode : <?= date('d M Y') ?></h6>            
            <h6>Tag : <?= $statusTag; ?></h6>
            <h6>Status Qty : <?= $statusQty; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center bg-info">#</th>
                        <th colspan="3" class="fw-bold text-center bg-info">Divisi</th>
                        <th colspan="6" class="fw-bold text-center bg-info">Produk</th>
                        <th colspan="3" class="fw-bold text-center bg-info">Stok</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Flag</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">PKM</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">PO Out</th>
                        <th colspan="4" class="fw-bold text-center bg-info">Trend Sales</th>
                        <th colspan="3" class="fw-bold text-center bg-info">Supplier</th>
                        <th colspan="3" class="fw-bold text-center bg-info">Harga Beli</th>
                        <th colspan="5" class="fw-bold text-center bg-info">Diskon 1 Harga Beli</th>
                        <th colspan="5" class="fw-bold text-center bg-info">Diskon 2 Harga Beli</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center bg-info">Div</th>
                        <th class="fw-bold text-center bg-info">Dep</th>
                        <th class="fw-bold text-center bg-info">Kat</th>
                        <th class="fw-bold text-center bg-info">PLU</th>
                        <th class="fw-bold text-center bg-info">Deskripsi</th>
                        <th class="fw-bold text-center bg-info">Unit</th>
                        <th class="fw-bold text-center bg-info">Frac</th>
                        <th class="fw-bold text-center bg-info">Tag</th>
                        <th class="fw-bold text-center bg-info">Acost</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Qty in Ctn</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Qty in Pcs</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Qty in Rph</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Bln 1</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Bln 2</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Bln 3</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Bln Ini</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Kode Supplier</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nama Supplier</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Status</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Rph Gross</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Rph Net</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Rph OMI</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Mulai</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Selesai</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Persen</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Rupiah</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Flag</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Mulai</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Selesai</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Persen</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Rupiah</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Flag</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($lppSaatIni as $lpp) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $lpp['ST_DIV']; ?></td>
                            <td class="text-center"><?= $lpp['ST_DEPT']; ?></td>
                            <td class="text-center"><?= $lpp['ST_KATB']; ?></td>
                            <td class="text-center"><?= $lpp['ST_PRDCD']; ?></td>
                            <td class="text-start text-nowrap"><?= $lpp['ST_DESKRIPSI']; ?></td>
                            <td class="text-center"><?= $lpp['ST_UNIT']; ?></td>
                            <td class="text-center"><?= $lpp['ST_FRAC']; ?></td>
                            <td class="text-center"><?= $lpp['ST_KODETAG']; ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_AVGCOST'],'2',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SALDO_CTN'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SALDO_PCS'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SALDO_RPH'],'0',',','.'); ?></td>
                            <td class="text-center text-nowrap"><?= $lpp['ST_IGR_IDM']; ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_PKM'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_PO_QTY'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SALES_BLN_1'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SALES_BLN_2'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SALES_BLN_3'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SALES_BLN_INI'],'0',',','.'); ?></td>
                            <td class="text-center"><?= $lpp['ST_SUPP_KODE']; ?></td>
                            <td class="text-start text-nowrap"><?= $lpp['ST_SUPP_NAMA']; ?></td>
                            <td class="text-center"><?= $lpp['ST_PERLAKUAN_BARANG']; ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_HARGA_BELI'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_HARGA_BELI_NETTO'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_HARGA_BELI_OMI'],'0',',','.'); ?></td>
                            <td class="text-center text-nowrap"><?= $lpp['ST_DISC_1_MULAI']; ?></td>
                            <td class="text-center text-nowrap"><?= $lpp['ST_DISC_1_SELESAI']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($lpp['ST_DISC_1_PERSEN'],'2',',','.'); ?> %</td>
                            <td class="text-end"><?= number_format($lpp['ST_DISC_1_RPH'],'0',',','.'); ?></td>
                            <td class="text-center"><?= $lpp['ST_DISC_1_FLAG']; ?></td>
                            <td class="text-center text-nowrap"><?= $lpp['ST_DISC_2_MULAI']; ?></td>
                            <td class="text-center text-nowrap"><?= $lpp['ST_DISC_2_SELESAI']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($lpp['ST_DISC_2_PERSEN'],'2',',','.'); ?> %</td>
                            <td class="text-end"><?= number_format($lpp['ST_DISC_2_RPH'],'0',',','.'); ?></td>
                            <td class="text-center"><?= $lpp['ST_DISC_2_FLAG']; ?></td>
                        </tr>
                        <?php
                            $saldoQtyCtn       += $lpp['ST_SALDO_CTN']; 
                            $saldoQtyPcs       += $lpp['ST_SALDO_PCS']; 
                            $saldoRupiah       += $lpp['ST_SALDO_RPH'];    
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="10" class="fw-bold text-center">Total</td>
                        <td class="fw-bold text-end"><?= number_format($saldoQtyCtn,'0',',','.'); ?></td>
                        <td class="fw-bold text-end"><?= number_format($saldoQtyPcs,'0',',','.'); ?></td>
                        <td class="fw-bold text-end"><?= number_format($saldoRupiah,'0',',','.'); ?></td>
                        <td colspan="4">&nbsp;</td>
                    </tr>
                </tfoot>
            </table>
        <?php } else if($jenisLaporan == "5") { ?>
            <h4 class="fw-bold"><?= $lap; ?> [LPP <?= $lokasiStock; ?> - Divisi : <?= $kodeDivisi; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr;?>]</h4>
            <h6>Periode : <?= date('d M Y') ?></h6>
            <h6>Tag : <?= $statusTag; ?></h6>
            <h6>Status Qty : <?= $statusQty; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th class="fw-bold text-center bg-info">#</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Kode Supplier</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nama Supplier</th>
                        <th class="fw-bold text-center bg-info">Item</th>
                        <th class="fw-bold text-center bg-info">Saldo in Pcs</th>
                        <th class="fw-bold text-center bg-info">Saldo in Rph (by Acost)</th>
                        <th class="fw-bold text-center bg-info">Saldo in Rph (by Lcost)</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($lppSaatIni as $lpp) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $lpp['ST_SUPP_KODE']; ?></td>
                            <td class="text-start text-nowrap"><?= $lpp['ST_SUPP_NAMA']; ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_ITEM_PRODUK'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SALDO_IN_PCS'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SALDO_RPH'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SALDO_RPH_LASTCOST'],'0',',','.'); ?></td>
                        </tr>
                        <?php
                            $jumlahProduk      += $lpp['ST_ITEM_PRODUK'];
                            $saldoQtyInPcs     += $lpp['ST_SALDO_IN_PCS'];
                            $saldoRupiah       += $lpp['ST_SALDO_RPH'];
                            $saldoRupiahLCost  += $lpp['ST_SALDO_RPH_LASTCOST'];
                            $jumlahSupplier    += 1;
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="3" class="fw-bold text-center">Total</td>
                        <td class="fw-bold text-end"><?= number_format($jumlahProduk,'0',',','.'); ?></td>
                        <td class="fw-bold text-end"><?= number_format($saldoQtyInPcs,'0',',','.'); ?></td>
                        <td class="fw-bold text-end"><?= number_format($saldoRupiah,'0',',','.'); ?></td>
                        <td class="fw-bold text-end"><?= number_format($saldoRupiahLCost,'0',',','.'); ?></td>
                    </tr>
                </tfoot>
            </table>
        <?php } else if($jenisLaporan == "6") { ?>
            <h4 class="fw-bold"><?= $lap; ?> [LPP <?= $lokasiStock; ?> - Divisi : <?= $kodeDivisi; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr;?>]</h4>
            <h6>Periode : <?= date('d M Y') ?></h6>
            <h6>Tag : <?= $statusTag; ?></h6>
            <h6>Status Qty : <?= $statusQty; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th class="fw-bold text-center bg-info">#</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Kode Tag</th>
                        <th class="fw-bold text-center bg-info">Item</th>
                        <th class="fw-bold text-center bg-info">Saldo in Pcs</th>
                        <th class="fw-bold text-center bg-info">Saldo in Rph</th>
                        <th class="fw-bold text-center bg-info">Jumlah Supplier</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($lppSaatIni as $lpp) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $lpp['ST_KODETAG']; ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_ITEM_PRODUK'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SALDO_IN_PCS'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SALDO_RPH'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SUPP_JUMLAH'],'0',',','.'); ?></td>
                        </tr>
                        <?php
                            $jumlahProduk      += $lpp['ST_ITEM_PRODUK'];
                            $saldoQtyInPcs     += $lpp['ST_SALDO_IN_PCS'];
                            $saldoRupiah       += $lpp['ST_SALDO_RPH'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="2" class="fw-bold text-center">Total</td>
                        <td class="fw-bold text-end"><?= number_format($jumlahProduk,'0',',','.'); ?></td>
                        <td class="fw-bold text-end"><?= number_format($saldoQtyInPcs,'0',',','.'); ?></td>
                        <td class="fw-bold text-end"><?= number_format($saldoRupiah,'0',',','.'); ?></td>
                        <td class="fw-bold text-end">&nbsp;</td>
                    </tr>
                </tfoot>
            </table>
        <?php } else if($jenisLaporan == "7") { ?>
            <h4 class="fw-bold"><?= $lap; ?> [LPP <?= $lokasiStock; ?> - Divisi : <?= $kodeDivisi; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr;?>]</h4>
            <h6>Periode : <?= date('d M Y') ?></h6>
            <h6>Tag : <?= $statusTag; ?></h6>
            <h6>Status Qty : <?= $statusQty; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th class="fw-bold text-center bg-info">#</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Group Sales</th>
                        <th class="fw-bold text-center bg-info">Item</th>
                        <th class="fw-bold text-center bg-info">Saldo in Pcs</th>
                        <th class="fw-bold text-center bg-info">Saldo in Rph</th>
                        <th class="fw-bold text-center bg-info">Jumlah Supplier</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($lppSaatIni as $lpp) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $lpp['ST_IGR_IDM']; ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_ITEM_PRODUK'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SALDO_IN_PCS'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SALDO_RPH'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($lpp['ST_SUPP_JUMLAH'],'0',',','.'); ?></td>
                        </tr>
                        <?php
                            $jumlahProduk      += $lpp['ST_ITEM_PRODUK'];
                            $saldoQtyInPcs     += $lpp['ST_SALDO_IN_PCS'];
                            $saldoRupiah       += $lpp['ST_SALDO_RPH'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="2" class="fw-bold text-center">Total</td>
                        <td class="fw-bold text-end"><?= number_format($jumlahProduk,'0',',','.'); ?></td>
                        <td class="fw-bold text-end"><?= number_format($saldoQtyInPcs,'0',',','.'); ?></td>
                        <td class="fw-bold text-end"><?= number_format($saldoRupiah,'0',',','.'); ?></td>
                        <td class="fw-bold text-end">&nbsp;</td>
                    </tr>
                </tfoot>
            </table>
        <?php } ?>
    </body>
</html>