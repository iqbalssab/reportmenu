<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Data Informasi Produk</title>

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
            background:#66CCFF;
            padding:5px;
            font-weight:400;
            }
            td{
            padding:2px 5px;
            }
        </style>
    </head>
    <body>
        <?php
            $marginPersen = $hargaNetto = $avgSalesCTN = 0;
            $no = 1;
            $jlap = "";
            $dvs = $dep = $katbr = "ALL";
            $barkosToko = $barkosIdm = $barkos_1 = $barkos_2 = $barkos_3 = 'N';

            if (strtoupper($itemOMI) == "ON") {
                echo '<span class="badge">Item OMI saja</span>';
            }
    
            if (strtoupper($discount2) == "ON") {
                echo '<span class="badge">Ada Discount 2</span>';
            }
        
            if (strtoupper($promoMD) == "ON") {
                echo '<span class="badge">Ada Promosi MD</span>';
            }
        
            if (strtoupper($marginNegatif) == "ON") {
                echo '<span class="badge">Margin Negatif</span>';
            }
        
            if (strtoupper($hargaJualNol) == "ON") {
                echo '<span class="badge">Harga Jual Belum Ada</span>';
            }
        
            if (strtoupper($promoMahal) == "ON") {
                echo '<span class="badge">Harga Promo lebih mahal</span>';
            }
    
            if (strtoupper($poOutstanding) == "ON") {
                echo '<span class="badge">Ada PO Outstanding</span>';
            }
        
            if (strtoupper($stockKosong) == "ON") {
                echo '<span class="badge">Stock Kosong atau Minus</span>';
            }

            foreach($divisi as $dv) :
                if($kodeDivisi == $dv['DIV_KODEDIVISI']) {
                    $dvs = $dv['DIV_NAMADIVISI'];
                }
            endforeach;

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

            if($lap == '1A') {
                $jlap = 'LAPORAN per PRODUK';
            } else if($lap == '1B') {
                $jlap = 'LAPORAN per PRODUK IC';
            } else if($lap == '1C') {
                $jlap = 'LAPORAN BARKOS';
            }
        ?>

        <?php if($lap == "1A") { ?>
            <h4 class="fw-bold"><?= $jlap; ?> - Divisi : <?= $dvs; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr; ?></h4>
            <h6>Periode : <?= date('d M Y') ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center bg-info">#</th>
                        <th colspan="3" class="fw-bold text-center bg-info">Divisi</th>
                        <th colspan="11" class="fw-bold text-center bg-info">Produk</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">Harga Jual</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-nowrap">Average cost</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-nowrap">Last cost</th>
                        <th colspan="4" class="fw-bold text-center bg-info text-nowrap">Promo MD</th>
                        <th colspan="4" class="fw-bold text-center bg-info text-nowrap">Trend Sales in Pcs</th>
                        <th colspan="4" class="fw-bold text-center bg-info text-nowrap">Trend Sales in Rph</th>
                        <th colspan="4" class="fw-bold text-center bg-info text-nowrap">Avg Sales in Pcs</th>
                        <th colspan="3" class="fw-bold text-center bg-info">Stock</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-nowrap">Expired Info</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">PKM</th>
                        <th colspan="2" class="fw-bold text-center bg-info">DSI</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">PO Out</th>
                        <th colspan="6" class="fw-bold text-center bg-info">Supplier</th>
                        <th colspan="4" class="fw-bold text-center bg-info">Harga Beli</th>
                        <th colspan="5" class="fw-bold text-center bg-info text-nowrap">Discount 1</th>
                        <th colspan="5" class="fw-bold text-center bg-info text-nowrap">Discount 2</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-nowrap">Tgl BPB</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">Tgl Tag Discontinue</th>
                        <th colspan="5" class="fw-bold text-center bg-info text-nowrap">Rak Pajangan</th>
                        <th colspan="6" class="fw-bold text-center bg-info text-nowrap">Rak DPD</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center bg-info">Div</th>
                        <th class="fw-bold text-center bg-info">Dep</th>
                        <th class="fw-bold text-center bg-info">Kat</th>
                        <th class="fw-bold text-center bg-info text-nowrap">PLU MCG</th>
                        <th class="fw-bold text-center bg-info text-nowrap">PLU OMI</th>
                        <th class="fw-bold text-center bg-info text-nowrap">PLU IGR</th>
                        <th class="fw-bold text-center bg-info">Deskripsi</th>
                        <th class="fw-bold text-center bg-info">Unit</th>
                        <th class="fw-bold text-center bg-info">Frac</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Tag IGR</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Tag OMI</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Item OMI</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Flag IGR</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Flag Jual</th>
                        <th class="fw-bold text-center bg-info">Rupiah</th>
                        <th class="fw-bold text-center bg-info">Persen</th>
                        <th class="fw-bold text-center bg-info">Rupiah</th>
                        <th class="fw-bold text-center bg-info">Persen</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Harga Promo</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Margin %</th>
                        <th class="fw-bold text-center bg-info">Mulai</th>
                        <th class="fw-bold text-center bg-info">Selesai</th>
                        <th class="fw-bold text-center bg-info"><?php echo date('M',strtotime('-3 month')); ?></th>
                        <th class="fw-bold text-center bg-info"><?php echo date('M',strtotime('-2 month')); ?></th>
                        <th class="fw-bold text-center bg-info"><?php echo date('M',strtotime('-1 month')); ?></th>
                        <th class="fw-bold text-center bg-info"><?php echo date('M'); ?></th>
                        <th class="fw-bold text-center bg-info"><?php echo date('M',strtotime('-3 month')); ?></th>
                        <th class="fw-bold text-center bg-info"><?php echo date('M',strtotime('-2 month')); ?></th>
                        <th class="fw-bold text-center bg-info"><?php echo date('M',strtotime('-1 month')); ?></th>
                        <th class="fw-bold text-center bg-info"><?php echo date('M'); ?></th>
                        <th class="fw-bold text-center bg-info text-nowrap">Member Biru</th>
                        <th class="fw-bold text-center bg-info text-nowrap">OMI</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Member Merah</th>
                        <th class="fw-bold text-center bg-info">Total</th>
                        <th class="fw-bold text-center bg-info">Ctn</th>
                        <th class="fw-bold text-center bg-info">Pcs</th>
                        <th class="fw-bold text-center bg-info">Rupiah</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Expired Date</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Expired Qty</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Avg Sales</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Bln Ini</th>
                        <th class="fw-bold text-center bg-info">Kode</th>
                        <th class="fw-bold text-center bg-info">Nama</th>
                        <th class="fw-bold text-center bg-info">Status</th>
                        <th class="fw-bold text-center bg-info">TOP</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Lead Time</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Minor Rph</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Rph Gross</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Rph Nett</th>
                        <th class="fw-bold text-center bg-info">OMI</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Minor Pcs</th>
                        <th class="fw-bold text-center bg-info">Mulai</th>
                        <th class="fw-bold text-center bg-info">Selesai</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Disc %</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Disc Rp</th>
                        <th class="fw-bold text-center bg-info">Flag</th>
                        <th class="fw-bold text-center bg-info">Mulai</th>
                        <th class="fw-bold text-center bg-info">Selesai</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Disc %</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Disc Rp</th>
                        <th class="fw-bold text-center bg-info">Flag</th>
                        <th class="fw-bold text-center bg-info">Pertama</th>
                        <th class="fw-bold text-center bg-info">Terakhir</th>
                        <th class="fw-bold text-center bg-info">Rak</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Sub Rak</th>
                        <th class="fw-bold text-center bg-info">Tipe</th>
                        <th class="fw-bold text-center bg-info">Shelving</th>
                        <th class="fw-bold text-center bg-info text-nowrap">No Urut</th>
                        <th class="fw-bold text-center bg-info">Rak</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Sub Rak</th>
                        <th class="fw-bold text-center bg-info">Tipe</th>
                        <th class="fw-bold text-center bg-info">Shelving</th>
                        <th class="fw-bold text-center bg-info text-nowrap">No Urut</th>
                        <th class="fw-bold text-center bg-info text-nowrap">No ID DPD</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($infoproduk as $pr) : ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td class="text-center"><?= $pr['ST_DIV']; ?></td>
                            <td class="text-center"><?= $pr['ST_DEPT']; ?></td>
                            <td class="text-center"><?= $pr['ST_KATB']; ?></td>
                            <td class="text-center"><?= $pr['ST_PLUMCG']; ?></td>
                            <?php
                                if ($pr['ST_PLUOMI'] != '0000000') {
                                    echo '<td align="right">' . $pr['ST_PLUOMI'] . '</td>';
                                } else{
                                        echo '<td align="right">' . ' ' . '</td>';
                                } 
                            ?>
                            <td class="text-center"><?= $pr['ST_PRDCD']; ?></td>
                            <td class="text-start text-nowrap"><?= $pr['ST_NAMA_BARANG']; ?></td>
                            <td class="text-center"><?= $pr['ST_UNIT']; ?></td>
                            <td class="text-center"><?= $pr['ST_FRAC']; ?></td>
                            <td class="text-center"><?= $pr['ST_TAG']; ?></td>
                            <td class="text-center"><?= $pr['ST_TAG_OMI']; ?></td>
                            <?php 
                                if ($pr['ST_PLUOMI'] != '0000000') {
                                    echo '<td align="center">' . 'Y' . '</td>';
                                } else{
                                        echo '<td align="center">' . ' ' . '</td>';
                                }
                            ?>
                            <td class="text-center"><?= $pr['ST_FLAGIGR']; ?></td>
                            <td class="text-center text-nowrap"><?= $pr['ST_IGR_IDM']; ?></td>
                            <td class="text-end"><?= number_format($pr['ST_HARGA_JUAL'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($pr['ST_AVGCOST'],'0',',','.'); ?></td>
                            <?php
                                if ($pr['ST_HARGA_NETTO'] == 0){
                                    $hargaNetto = 1 / 1000000;
                                } else {
                                        $hargaNetto = $pr['ST_HARGA_NETTO'];
                                }
                            ?>
                            <?php
                                if ($pr['ST_HARGA_JUAL'] == 0)  {
                                    $marginPersen = 0;	
                                } elseif ($pr['ST_AVGCOST'] == 0)  {
                                        $marginPersen = 100;
                                } else {
                                        $marginPersen = ($hargaNetto - $pr['ST_AVGCOST']) / $hargaNetto * 100 ;
                                }     
                            ?>
                            <td class="text-end text-nowrap"><?= number_format($marginPersen,'2',',','.'); ?> %</td>
                            <td class="text-end"><?= number_format($pr['ST_LASTCOST'],'2',',','.'); ?></td>
                            <?php
                                if ($pr['ST_HARGA_JUAL'] == 0)  {
                                    $marginPersen = 0;	
                                } elseif ($pr['ST_AVGCOST'] == 0)  {
                                        $marginPersen = 100;
                                } else {
                                        $marginPersen = ($hargaNetto - $pr['ST_AVGCOST']) / $hargaNetto * 100 ;
                                }     
                            ?>
                            <td class="text-end text-nowrap"><?= number_format($marginPersen,'2',',','.'); ?> %</td>
                            <td class="text-end"><?= number_format($pr['ST_PROMOMD_HARGA'],'0',',','.'); ?></td>
                            <?php
                                if ($pr['ST_PROMOMD_HARGA_NETTO'] == 0) {
                                    $marginPersen = 0;
                                } else {
                                        $marginPersen = ($pr['ST_PROMOMD_HARGA_NETTO'] - $pr['ST_AVGCOST']) / $pr['ST_PROMOMD_HARGA_NETTO'] * 100 ;
                                }     
                            ?>
                            <td class="text-end text-nowrap"><?= number_format($marginPersen,'2',',','.'); ?> %</td>
                            <td class="text-center text-nowrap"><?= $pr['ST_PROMOMD_MULAI']; ?></td>
                            <td class="text-center text-nowrap"><?= $pr['ST_PROMOMD_SELESAI']; ?></td>
                            <?php 
                                if (substr($pr['ST_PRDCD'],-1) == '0') {
                                    echo '<td class="text-end">' .number_format($pr['ST_SALES_BLN_1'],'0',',','.'). '</td>';
                                    echo '<td class="text-end">' .number_format($pr['ST_SALES_BLN_2'],'0',',','.'). '</td>';
                                    echo '<td class="text-end">' .number_format($pr['ST_SALES_BLN_3'],'0',',','.'). '</td>';
                                    echo '<td class="text-end">' .number_format($pr['ST_SALES_BLN_INI'],'0',',','.'). '</td>';
                                    echo '<td class="text-end">' .number_format($pr['ST_SALES_RPH_BLN_1'],'0',',','.'). '</td>';
                                    echo '<td class="text-end">' .number_format($pr['ST_SALES_RPH_BLN_2'],'0',',','.'). '</td>';
                                    echo '<td class="text-end">' .number_format($pr['ST_SALES_RPH_BLN_3'],'0',',','.'). '</td>';
                                    echo '<td class="text-end">' .number_format($pr['ST_SALES_BLN_INI'] * $pr['ST_AVGCOST'] / $pr['ST_FRAC'],'0',',','.'). '</td>';
                                    echo '<td align="right">'  . number_format($pr['ST_REKAP_BIRU'] , 0, '.', ',') . '</td>';
                                    echo '<td align="right">'  . number_format($pr['ST_REKAP_OMI'] , 0, '.', ',') . '</td>';
                                    echo '<td align="right">'  . number_format($pr['ST_REKAP_MERAH'] , 0, '.', ',') . '</td>';
                                    echo '<td align="right">'  . number_format(($pr['ST_SALES_BLN_1'] + $pr['ST_SALES_BLN_2'] + $pr['ST_SALES_BLN_3']) / 3, 0, '.', ',') . '</td>';

                                    if ($pr['ST_FRAC'] == 0){
                                        $stFrac = 1;
                                    } else {
                                            $stFrac = $pr['ST_FRAC'];
                                    }
                                    echo '<td align="right">'  . number_format(($pr['ST_SALDO_IN_PCS'] - $pr['ST_SALDO_IN_PCS'] % $stFrac) / $stFrac, 0, '.', ',') . '</td>';
                                    echo '<td align="right">'  . number_format($pr['ST_SALDO_IN_PCS'] % $stFrac, 0, '.', ',') . '</td>';
                                    echo '<td align="right">'  . number_format($pr['ST_SALDO_RPH'], 0, '.', ',') . '</td>';

                                    echo '<td align="left" class="text-nowrap">'  . $pr['ST_EXP_TANGGAL'] . '</td>';
                                    echo '<td align="right">'  . number_format($pr['ST_EXP_QTY'], 0, '.', ',') . '</td>';

                                    echo '<td align="right">'  . number_format($pr['ST_PKM'], 0, '.', ',') . '</td>';
                                    echo '<td align="right">'  . number_format($pr['ST_DSI_AVGSALES'], 0, '.', ',') . '</td>';
                                    echo '<td align="right">'  . number_format($pr['ST_DSI_BULAN_INI'], 0, '.', ',') . '</td>';
                                    echo '<td align="right">'  . number_format($pr['ST_PO_QTY'], 0, '.', ',') . '</td>';

                                    // supplier
                                    echo '<td align="left">'  . $pr['ST_KODE_SUPPLIER'] . '</td>';
                                    echo '<td align="left" class="text-nowrap">'  . $pr['ST_NAMA_SUPPLIER'] . '</td>';
                                    echo '<td align="left">'  . $pr['ST_PERLAKUAN_BARANG'] . '</td>';
                                    echo '<td align="right">'  . $pr['ST_TOP'] . '</td>';
                                    echo '<td align="right">'  . $pr['ST_LEAD_TIME'] . '</td>';
                                    echo '<td align="right">'  . number_format($pr['ST_MINRPH'], 0, '.', ',') . '</td>';

                                    //harga beli
                                    echo '<td align="right">'  . number_format($pr['ST_HARGA_BELI'], 0, '.', ',') . '</td>';
                                    echo '<td align="right">'  . number_format($pr['ST_HARGA_BELI_NETTO'], 0, '.', ',') . '</td>';
                                    echo '<td align="right">'  . number_format($pr['ST_HARGA_BELI_OMI'], 0, '.', ',') . '</td>';
                                    echo '<td align="right">'  . number_format($pr['ST_MINIMUM_ORDER'], 0, '.', ',') . '</td>';

                                    //diskon 1
                                    echo '<td align="left" class="text-nowrap">'  . $pr['ST_DISC_1_MULAI'] . '</td>';
                                    echo '<td align="left" class="text-nowrap">'  . $pr['ST_DISC_1_SELESAI'] . '</td>';
                                    echo '<td align="right">'  . number_format($pr['ST_DISC_1_PERSEN'], 2, '.', ',') . '</td>';
                                    echo '<td align="right">'  . number_format($pr['ST_DISC_1_RPH'], 2, '.', ',') . '</td>';
                                    echo '<td align="left">'  . $pr['ST_DISC_1_FLAG'] . '</td>';

                                    //diskon 2
                                    echo '<td align="left" class="text-nowrap">'  . $pr['ST_DISC_2_MULAI'] . '</td>';
                                    echo '<td align="left" class="text-nowrap">'  . $pr['ST_DISC_2_SELESAI'] . '</td>';
                                    echo '<td align="right">'  . number_format($pr['ST_DISC_2_PERSEN'], 2, '.', ',') . '</td>';
                                    echo '<td align="right">'  . number_format($pr['ST_DISC_2_RPH'], 2, '.', ',') . '</td>';
                                    echo '<td align="left">'  . $pr['ST_DISC_2_FLAG'] . '</td>';

                                    //tanggal bpb	
                                    echo '<td align="left" class="text-nowrap">'  . $pr['MSTD_BPB_PERTAMA'] . '</td>';
                                    echo '<td align="left" class="text-nowrap">'  . $pr['MSTD_BPB_TERAKHIR'] . '</td>';

                                    echo '<td align="left">'  . $pr['ST_TGLDISCONTINUE'] . '</td>';

                                    // lokasi display toko	
                                    echo '<td align="left">'  . $pr['ST_LKS_KODERAK'] . '</td>';
                                    echo '<td align="left">'  . $pr['ST_LKS_KODESUBRAK'] . '</td>';
                                    echo '<td align="left">'  . $pr['ST_LKS_TIPERAK'] . '</td>';
                                    echo '<td align="left">'  . $pr['ST_LKS_SHELVINGRAK'] . '</td>';
                                    echo '<td align="left">'  . $pr['ST_LKS_NOURUT'] . '</td>';

                                    // lokasi display dpd	
                                    echo '<td align="left">'  . $pr['ST_DPD_KODERAK'] . '</td>';
                                    echo '<td align="left">'  . $pr['ST_DPD_KODESUBRAK'] . '</td>';
                                    echo '<td align="left">'  . $pr['ST_DPD_TIPERAK'] . '</td>';
                                    echo '<td align="left">'  . $pr['ST_DPD_SHELVINGRAK'] . '</td>';
                                    echo '<td align="left">'  . $pr['ST_DPD_NOURUT'] . '</td>';
                                    echo '<td align="left">'  . $pr['ST_DPD_NOID'] . '</td>';

                                    echo '<td align="center">&nbsp;</td>';
                                }
                            ?>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        <?php } else if($lap == "1B") { ?>
            <h4 class="fw-bold"><?= $jlap; ?> - Divisi : <?= $dvs; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr; ?></h4>
            <h6>Periode : <?= date('d M Y') ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center bg-info">#</th>
                        <th colspan="3" class="fw-bold text-center bg-info">Divisi</th>
                        <th colspan="6" class="fw-bold text-center bg-info">Produk</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">Item OMI</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Avg Cost</th>
                        <th colspan="2" class="fw-bold text-center bg-info">Stock</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">Avg Sales</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Margin</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">DSI</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">PKM</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">TOP</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center bg-info">Div</th>
                        <th class="fw-bold text-center bg-info">Dep</th>
                        <th class="fw-bold text-center bg-info">Kat</th>
                        <th class="fw-bold text-center bg-info text-nowrap">PLU IGR</th>
                        <th class="fw-bold text-center bg-info">Deskripsi</th>
                        <th class="fw-bold text-center bg-info">Unit</th>
                        <th class="fw-bold text-center bg-info">Frac</th>
                        <th class="fw-bold text-center bg-info">Tag</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Flag IGR</th>
                        <th class="fw-bold text-center bg-info">Ctn</th>
                        <th class="fw-bold text-center bg-info">Rph</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($infoproduk as $pr) : ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td class="text-center"><?= $pr['ST_DIV']; ?></td>
                            <td class="text-center"><?= $pr['ST_DEPT']; ?></td>
                            <td class="text-center"><?= $pr['ST_KATB']; ?></td>
                            <td class="text-center"><?= $pr['ST_PRDCD']; ?></td>
                            <td class="text-start text-nowrap"><?= $pr['ST_NAMA_BARANG']; ?></td>
                            <td class="text-center"><?= $pr['ST_UNIT']; ?></td>
                            <td class="text-center"><?= $pr['ST_FRAC']; ?></td>
                            <td class="text-center"><?= $pr['ST_TAG']; ?></td>
                            <td class="text-center"><?= $pr['ST_FLAGIGR']; ?></td>
                            <!-- item OMI -->
                            <?php
                                if ($pr['ST_PLUOMI'] != '0000000') {
                                    echo '<td align="center">' . 'Y' . '</td>';
                                } else{
                                        echo '<td align="center">' . ' ' . '</td>';
                                }
                            ?>
                            <td class="text-end"><?= number_format($pr['ST_AVGCOST'],'2',',','.'); ?></td>
                            <td class="text-end"><?= number_format($pr['ST_SALDO_IN_PCS'] / $pr['ST_FRAC'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($pr['ST_SALDO_RPH'],'0',',','.'); ?></td>
                            <?php
                                // avg sales ctn
                                $avgSalesCTN = FLOOR($pr['ST_SALES_BLN_1'] / $pr['ST_FRAC']);
                                $avgSalesCTN += FLOOR($pr['ST_SALES_BLN_2'] / $pr['ST_FRAC']);
                                $avgSalesCTN += FLOOR($pr['ST_SALES_BLN_3'] / $pr['ST_FRAC']);
                                // sales per 2 minggu
                                $avgSalesCTN = FLOOR($avgSalesCTN / 6);
                            ?>
                            <td class="text-end"><?= number_format($avgSalesCTN,'0',',','.'); ?></td>
                            <!-- margin -->
                            <?php
                                if ($pr['ST_HARGA_JUAL'] == 0)  {
                                    $marginPersen = 0;	
                                } elseif ($pr['ST_AVGCOST'] == 0)  {
                                        $marginPersen = 100;
                                } else {
                                        $marginPersen = ($pr['ST_HARGA_NETTO'] - $pr['ST_AVGCOST']) / $pr['ST_HARGA_NETTO'] * 100 ;
                                }
                            ?>
                            <td class="text-end"><?= number_format($marginPersen,'2',',','.'); ?></td>
                            <td class="text-end"><?= number_format($pr['ST_DSI_BULAN_INI'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($pr['ST_PKM'],'0',',','.'); ?></td>
                            <td class="text-end"><?= number_format($pr['ST_TOP'],'0',',','.'); ?></td>
                            <td class="text-end">&nbsp;</td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        <?php } else if($lap == "1C") { ?>
            <h4 class="fw-bold"><?= $jlap; ?> - Divisi : <?= $dvs; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr; ?></h4>
            <h6>Periode : <?= date('d M Y') ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center bg-info">#</th>
                        <th colspan="3" class="fw-bold text-center bg-info">Divisi</th>
                        <th colspan="7" class="fw-bold text-center bg-info">Produk</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">Status Flag</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">SPD</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">PO Out</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">PKM</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">Stock LPP</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-nowrap">KPH Mean</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-nowrap">Status Barkos</th>
                        <th colspan="3" class="fw-bold text-center bg-info text-nowrap">Status Barkos 2</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center bg-info">Div</th>
                        <th class="fw-bold text-center bg-info">Dep</th>
                        <th class="fw-bold text-center bg-info">Kat</th>
                        <th class="fw-bold text-center bg-info text-nowrap">PLU MCG</th>
                        <th class="fw-bold text-center bg-info text-nowrap">PLU IGR</th>
                        <th class="fw-bold text-center bg-info">Deskripsi</th>
                        <th class="fw-bold text-center bg-info">Unit</th>
                        <th class="fw-bold text-center bg-info">Frac</th>
                        <th class="fw-bold text-center bg-info">Tag</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Flag IGR</th>
                        <th class="fw-bold text-center bg-info">1x</th>
                        <th class="fw-bold text-center bg-info">4x</th>
                        <th class="fw-bold text-center bg-info text-nowrap">LPP < 3x SPD</th>
                        <th class="fw-bold text-center bg-info text-nowrap">LPP < 4x KPH Mean</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Stock < DSI 3 hari</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Stock < 1x KPH Mean</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Stock < DSI 3 hari + 1x KPH MEAN</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($infoproduk as $pr) : ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td class="text-center"><?= $pr['ST_DIV']; ?></td>
                            <td class="text-center"><?= $pr['ST_DEPT']; ?></td>
                            <td class="text-center"><?= $pr['ST_KATB']; ?></td>
                            <td class="text-center"><?= $pr['ST_PLUMCG']; ?></td>
                            <td class="text-center"><?= $pr['ST_PRDCD']; ?></td>
                            <td class="text-start text-nowrap"><?= $pr['ST_NAMA_BARANG']; ?></td>
                            <td class="text-center"><?= $pr['ST_UNIT']; ?></td>
                            <td class="text-center"><?= $pr['ST_FRAC']; ?></td>
                            <td class="text-center"><?= $pr['ST_TAG']; ?></td>
                            <td class="text-center"><?= $pr['ST_FLAGIGR']; ?></td>
                            <td class="text-center text-nowrap"><?= $pr['ST_IGR_IDM']; ?></td>
                            <td class="text-end"><?= number_format($pr['ST_SPD'], 0, '.', ','); ?></td>
                            <td class="text-end"><?= number_format($pr['ST_PO_QTY'], 0, '.', ','); ?></td>
                            <td class="text-end"><?= number_format($pr['ST_PKM'], 0, '.', ','); ?></td>
                            <td class="text-end"><?= number_format($pr['ST_SALDO_IN_PCS'], 0, '.', ','); ?></td>
                            <td class="text-end"><?= number_format($pr['ST_KPH_MEAN'], 0, '.', ','); ?></td>
                            <td class="text-end"><?= number_format($pr['ST_KPH_MEAN'] * 4, 0, '.', ','); ?></td>
                            <?php
                                // status barkos 1
                                if ($pr['ST_SALDO_IN_PCS'] <  $pr['ST_SPD'] * 3){
                                    $barkosToko = 'IGR';
                                } else {
                                        $barkosToko = ' ';
                                }

                                if ($pr['ST_SALDO_IN_PCS'] <  $pr['ST_KPH_MEAN'] * 4){
                                    $barkosIdm = 'IDM';
                                } else {
                                        $barkosIdm = ' ';
                                }
                            ?>
                            <td class="text-center"><?= $barkosToko; ?></td>
                            <td class="text-center"><?= $barkosIdm; ?></td>
                            <?php
                                // status barkos 2
                                if ($pr['ST_SALDO_IN_PCS'] <  $pr['ST_SPD'] * 3){
                                    $barkos_1 = 'BARKOS IGR';
                                } else {
                                        $barkos_1 = ' ';
                                }
        
                                if ($pr['ST_SALDO_IN_PCS'] <  $pr['ST_KPH_MEAN'] * 1){
                                        $barkos_2 = 'BARKOS IDM ONLY';
                                } else {
                                        $barkos_2 = ' ';
                                }
        
                                if ($pr['ST_SALDO_IN_PCS'] <  $pr['ST_SPD'] * 3 + $pr['ST_KPH_MEAN'] * 1){
                                        $barkos_3 = 'BARKOS IGR IDM';
                                } else {
                                        $barkos_3 = ' ';
                                }
                            ?>
                            <td class="text-center"><?= $barkos_1; ?></td>
                            <td class="text-center"><?= $barkos_2; ?></td>
                            <td class="text-center"><?= $barkos_3; ?></td>
                            <td class="text-center">&nbsp;</td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        <?php } ?>
    </body>
</html>