<?php $ip = "http://".$_SERVER['SERVER_NAME']."/reportmenu/"; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Data Informasi Produk</title>

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
        </style>
    </head>
    <body>
        <?php if($divisi == "All") {
            $dvs = "ALL";
        } else if($divisi == "1") {
            $dvs = "FOOD";
        } else if($divisi == "2") {
            $dvs = "NON FOOD";
        } else if($divisi == "3") {
            $dvs = "GMS";
        } else if($divisi == "4") {
            $dvs = "PERISHABLE";
        } else if($divisi == "5") {
            $dvs = "COUNTER & PROMOTION";
        } else if($divisi == "6") {
            $dvs = "FAST FOOD";
        } else if($divisi == "7") {
            $dvs = "I-FASHION";
        } else if($divisi == "8") {
            $dvs = "I-TECH";
        } else if($divisi == "9") {
            $dvs = "I-TRONIC";
        } ?>

        <?php $dep = "ALL"; ?>
        <?php foreach($dept as $dp) : ?>
            <?php if($departemen == $dp['DEP_KODEDEPARTEMENT']) {
                $dep = $dp['DEP_NAMADEPARTEMENT'];
            }?>
        <?php endforeach ?>

        <?php if($tag == "All") {
            $kdtag = "ALL";
        } else if($tag == "1") {
            $kdtag = "DI LUAR TAG [NHOAXT]";
        } else if($tag == "2") {
            $kdtag = "HANYA TAG [NHOAXT]";
        } ?>

        <?php $no = 1; ?>

        <?php if($jnslap == "0") { ?>
            <h2>LAPORAN per PRODUK DETAIL</h2>
            <h4>Divisi <?= $dvs; ?> - Departement <?= $dep; ?> - Tag <?= $kdtag; ?></h4>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center bg-info">#</th>
                        <th colspan="3" class="fw-bold text-center bg-info">Divisi</th>
                        <th colspan="8" class="fw-bold text-center bg-info">Produk</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Minor</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Mindis</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">BKP</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Acost</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Lcost</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Harga_jual</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Harga_Beli</th>
                        <th colspan="2" class="fw-bold text-center bg-info">Discount</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Status</th>
                        <th colspan="9" class="fw-bold text-center bg-info">Stock</th>
                        <th colspan="2" class="fw-bold text-center bg-info">Supplier</th>
                        <th colspan="13" class="fw-bold text-center bg-info">Sales Per Bulan</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">PKM</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">MPLUS</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">PKMT</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">LT</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">PKM_AvgSales</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">DSI_Avg_Sls</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">DSI_Bln_Ini</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Last_PO</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">BPB_Pertama</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">BPB_Terakhir</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Qty_PO</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Jml_PO</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">MaxPallet</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Flag_jual</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Display_Reg</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">MaxPlano_Reg</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">MinPct</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Jenis_Item</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Jenis_CKS</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Dsiplay_DPD</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">MaxPlano_DPD</th>
                        <th colspan="3" class="fw-bold text-center bg-info">Average Sales</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Tgl_Discontinue</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center bg-info">Div</th>
                        <th class="fw-bold text-center bg-info">Dept</th>
                        <th class="fw-bold text-center bg-info">Katb</th>
                        <th class="fw-bold text-center bg-info">PLU_MCG</th>
                        <th class="fw-bold text-center bg-info">PLU_IGR</th>
                        <th class="fw-bold text-center bg-info">PLU_OMI</th>
                        <th class="fw-bold text-center bg-info">Deskripsi</th>
                        <th class="fw-bold text-center bg-info">Unit</th>
                        <th class="fw-bold text-center bg-info">Frac</th>
                        <th class="fw-bold text-center bg-info">Tag_IGR</th>
                        <th class="fw-bold text-center bg-info">Tag_OMI</th>
                        <th class="fw-bold text-center bg-info">Disc1</th>
                        <th class="fw-bold text-center bg-info">Disc2</th>
                        <th class="fw-bold text-center bg-info">Trf_IN</th>
                        <th class="fw-bold text-center bg-info">Trf_OUT</th>
                        <th class="fw-bold text-center bg-info">Sales</th>
                        <th class="fw-bold text-center bg-info">Retur</th>
                        <th class="fw-bold text-center bg-info">Adj.</th>
                        <th class="fw-bold text-center bg-info">Intransit</th>
                        <th class="fw-bold text-center bg-info">Stock_CTN</th>
                        <th class="fw-bold text-center bg-info">Stock_PCS</th>
                        <th class="fw-bold text-center bg-info">Stock_RPH</th>
                        <th class="fw-bold text-center bg-info">Kode_Sup</th>
                        <th class="fw-bold text-center bg-info">Supplier</th>
                        <th class="fw-bold text-center bg-info">JAN</th>
                        <th class="fw-bold text-center bg-info">FEB</th>
                        <th class="fw-bold text-center bg-info">MAR</th>
                        <th class="fw-bold text-center bg-info">APR</th>
                        <th class="fw-bold text-center bg-info">MEI</th>
                        <th class="fw-bold text-center bg-info">JUN</th>
                        <th class="fw-bold text-center bg-info">JUL</th>
                        <th class="fw-bold text-center bg-info">AGS</th>
                        <th class="fw-bold text-center bg-info">SEP</th>
                        <th class="fw-bold text-center bg-info">OKT</th>
                        <th class="fw-bold text-center bg-info">NOV</th>
                        <th class="fw-bold text-center bg-info">DES</th>
                        <th class="fw-bold text-center bg-info">Bulan_Ini</th>
                        <th class="fw-bold text-center bg-info">AvgSales_IGR</th>
                        <th class="fw-bold text-center bg-info">AvgSales_OMI</th>
                        <th class="fw-bold text-center bg-info">AvgSales_MM</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($stok as $st) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $st['DIV']; ?></td>
                            <td class="text-center"><?= $st['DEP']; ?></td>
                            <td class="text-center"><?= $st['KAT']; ?></td>
                            <td class="text-center"><?= $st['PLU_MCG']; ?></td>
                            <td class="text-center"><?= $st['PLU_IGR']; ?></td>
                            <td class="text-center"><?= $st['PLU_OMI']; ?></td>
                            <td class="text-start text-nowrap"><?= $st['DESKRIPSI']; ?></td>
                            <td class="text-center"><?= $st['UNIT']; ?></td>
                            <td class="text-center"><?= $st['FRAC']; ?></td>
                            <td class="text-center"><?= $st['TAG_IGR']; ?></td>
                            <td class="text-center"><?= $st['TAG_OMI']; ?></td>
                            <td class="text-end"><?= number_format($st['MINOR'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['MINDIS'],'0',',','.'); ?></td>
                            <td class="text-center"><?= $st['BKP']; ?></td>
                            <td class="text-end"><?= number_format($st['ACOST'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['LCOST'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['HRGJUAL'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['HRGBELI'],'0',',','.'); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($st['DISC1'],'2',',','.'); ?> %</td>
                            <td class="text-end text-nowrap"><?= number_format($st['DISC2'],'2',',','.'); ?> %</td>
                            <td class="text-center"><?= $st['STATUS']; ?></td>
                            <td class="text-end"><?= number_format($st['ST_TRFIN'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['ST_TRFOUT'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['ST_SALES'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['ST_RETUR'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['ST_ADJ'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['ST_INTRANSIT'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['STOCK_IN_CTN'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['STOCK_IN_PCS'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['STOCK_RPH'],'0',',','.'); ?></td>
                            <td class="text-center"><?= $st['KODESUP']; ?></td>
                            <td class="text-start text-nowrap"><?= $st['NAMASUPPLIER']; ?></td>
                            <td class="text-end"><?= number_format($st['JAN'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['PEB'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['MAR'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['APR'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['MEI'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['JUN'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['JUL'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['AGS'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['SEP'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['OKT'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['NOV'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['DES'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['BLN_INI'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['PKM'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['MPLUS'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['PKMT'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['LT'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['PKM_AVGSALES'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['DSI_AVG_SLS'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['DSI_BLN_INI'],'0',',','.'); ?></td>
                            <td class="text-center text-nowrap"><?= $st['LASTPO']; ?></td>
                            <td class="text-center text-nowrap"><?= $st['FIRSTBPB']; ?></td>
                            <td class="text-center text-nowrap"><?= $st['LASTBPB']; ?></td>
                            <td class="text-end"><?= number_format($st['QTY_PO'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['JML_PO'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['MAXPALET'],'0',',','.'); ?></td>
                            <td class="text-center text-nowrap"><?= $st['FLAG_JUAL']; ?></td>
                            <td class="text-center text-nowrap"><?= $st['DISPLAY_REG']; ?></td>
                            <td class="text-end"><?= number_format($st['MAXPLANO_REG'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['MINPCT_REG'],'0',',','.'); ?></td>
                            <td class="text-center text-nowrap"><?= $st['JENIS_ITEM']; ?></td>
                            <td class="text-center text-nowrap"><?= $st['JENIS_CKS']; ?></td>
                            <td class="text-center text-nowrap"><?= $st['DISPLAY_DPD']; ?></td>
                            <td class="text-end"><?= number_format($st['MAXPLANO_DPD'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['AVGSLS_IGR'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['AVGSLS_OMI'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['AVGSLS_MM'],'0',',','.'); ?></td>
                            <td class="text-center text-nowrap"><?= $st['PRD_TGLDISCONTINUE']; ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        <?php } else if($jnslap == "1") { ?>
            <h2>LAPORAN per DIVISI</h2>
            <h4>Divisi <?= $dvs; ?> - Tag <?= $kdtag; ?></h4>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th colspan="2" class="fw-bold text-center bg-info">Divisi</th>
                        <th colspan="3" class="fw-bold text-center bg-info">Jumlah Stock</th>
                        <th colspan="13" class="fw-bold text-center bg-info">Sales Qty</th>
                        <th colspan="3" class="fw-bold text-center bg-info">Average Sales Qty</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center bg-info">Div</th>
                        <th class="fw-bold text-center bg-info">Nama Divisi</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Stock in CTN</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Stock in PCS</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Stock in RPH</th>
                        <th class="fw-bold text-center bg-info">JAN</th>
                        <th class="fw-bold text-center bg-info">FEB</th>
                        <th class="fw-bold text-center bg-info">MAR</th>
                        <th class="fw-bold text-center bg-info">APR</th>
                        <th class="fw-bold text-center bg-info">MEI</th>
                        <th class="fw-bold text-center bg-info">JUN</th>
                        <th class="fw-bold text-center bg-info">JUL</th>
                        <th class="fw-bold text-center bg-info">AGS</th>
                        <th class="fw-bold text-center bg-info">SEP</th>
                        <th class="fw-bold text-center bg-info">OKT</th>
                        <th class="fw-bold text-center bg-info">NOV</th>
                        <th class="fw-bold text-center bg-info">DES</th>
                        <th class="fw-bold text-center bg-info text-nowrap">SALES BLN INI</th>
                        <th class="fw-bold text-center bg-info text-nowrap">AVG SALES IGR</th>
                        <th class="fw-bold text-center bg-info text-nowrap">AVG SALES OMI</th>
                        <th class="fw-bold text-center bg-info text-nowrap">AVG SALES MM</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($stok as $st) : ?>    
                        <tr>
                            <td class="text-center"><?= $st['DIV']; ?></td>
                            <td class="text-start text-nowrap"><?= $st['NMDIV']; ?></td>
                            <td class="text-end"><?= number_format($st['CTN'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['PCS'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['RPH'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['SJAN'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['SFEB'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['SMAR'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['SAPR'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['SMEI'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['SJUN'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['SJUL'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['SAGS'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['SSEP'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['SOKT'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['SNOV'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['SDES'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['SBLN'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['IGR'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['OMI'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['OMI'],'0',',','.'); ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        <?php } else if($jnslap == "2") { ?>
            <h2>LAPORAN per DEPARTEMENT</h2>
            <h4>Divisi <?= $dvs; ?> - Departement <?= $dep; ?> - Tag <?= $kdtag; ?></h4>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th colspan="4" class="fw-bold text-center bg-info">Divisi</th>
                        <th colspan="3" class="fw-bold text-center bg-info">Jumlah Stock</th>
                        <th colspan="13" class="fw-bold text-center bg-info">Sales Qty</th>
                        <th colspan="3" class="fw-bold text-center bg-info">Average Sales Qty</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center bg-info">Div</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nama Divisi</th>
                        <th class="fw-bold text-center bg-info">Dep</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nama Departemen</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Stock in CTN</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Stock in PCS</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Stock in RPH</th>
                        <th class="fw-bold text-center bg-info">JAN</th>
                        <th class="fw-bold text-center bg-info">FEB</th>
                        <th class="fw-bold text-center bg-info">MAR</th>
                        <th class="fw-bold text-center bg-info">APR</th>
                        <th class="fw-bold text-center bg-info">MEI</th>
                        <th class="fw-bold text-center bg-info">JUN</th>
                        <th class="fw-bold text-center bg-info">JUL</th>
                        <th class="fw-bold text-center bg-info">AGS</th>
                        <th class="fw-bold text-center bg-info">SEP</th>
                        <th class="fw-bold text-center bg-info">OKT</th>
                        <th class="fw-bold text-center bg-info">NOV</th>
                        <th class="fw-bold text-center bg-info">DES</th>
                        <th class="fw-bold text-center bg-info text-nowrap">SALES BLN INI</th>
                        <th class="fw-bold text-center bg-info text-nowrap">AVG SALES IGR</th>
                        <th class="fw-bold text-center bg-info text-nowrap">AVG SALES OMI</th>
                        <th class="fw-bold text-center bg-info text-nowrap">AVG SALES MM</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($stok as $st) : ?>    
                        <tr>
                            <td class="text-center"><?= $st['DIV']; ?></td>
                            <td class="text-start text-nowrap"><?= $st['NMDIV']; ?></td>
                            <td class="text-center"><?= $st['DEP']; ?></td>
                            <td class="text-start text-nowrap"><?= $st['NMDEP']; ?></td>
                            <td class="text-end"><?= number_format($st['CTN'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['PCS'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['RPH'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['SJAN'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['SFEB'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['SMAR'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['SAPR'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['SMEI'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['SJUN'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['SJUL'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['SAGS'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['SSEP'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['SOKT'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['SNOV'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['SDES'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['SBLN'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['IGR'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['OMI'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($st['OMI'],'0',',','.'); ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        <?php } ?>
    </body>
</html>