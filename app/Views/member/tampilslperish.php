<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Data Evaluasi Sales</title>
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
            $no = $totalQtyInPcs = $totalHari = $totalKunjungan = $totalKunjunganMember = $totalMember = $totalStruk = $totalProduk = $totalPlu = $totalGross = $totalNetto = $totalMargin = $totalQtyHilang = $totalRphHilang = $totalQtyMpp = $totalRphMpp = $totalQtyRusak = $totalRphRusak = $totalCb      = 0;
            $kodePLU = $dvs = $dep = $katbr = $kodeMember = $namaMember = $kdmbr = $nmmbr = $mbr = "All"; 
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

            if(isset($_GET['awal'])) {if ($_GET['awal'] !=""){$tanggalMulai = $_GET['awal']; }}
            if(isset($_GET['akhir'])) {if ($_GET['akhir'] !=""){$tanggalSelesai = $_GET['akhir']; }}
            if(isset($_GET['jenisMember'])) {if ($_GET['jenisMember'] !=""){$jenisMember = $_GET['jenisMember']; }}
            if(isset($_GET['kdmbr'])) {if ($_GET['kdmbr'] !=""){$kodeMember = $_GET['kdmbr']; }}
            if ($kodeMember != "All" AND $kodeMember != "") {
                $kdmbr = $kodeMember;
            }
            if(isset($_GET['nmmbr'])) {if ($_GET['nmmbr'] !=""){$namaMember = $_GET['nmmbr']; }}
            if ($namaMember != "All" AND $namaMember != "") {
                $nmmbr = $namaMember;
            }
            if(isset($_GET['plu'])) {if ($_GET['plu'] !=""){$kodePLU = $_GET['plu']; }}
            if ($kodePLU != "All" AND $kodePLU != "") {
                $kodePLU = substr('00000000' . $kodePLU, -7);
            }
            if(isset($_GET['jenisLaporan'])) {if ($_GET['jenisLaporan'] !=""){$lap = $_GET['jenisLaporan']; }}
        ?>

        <?php if($lap == "1") { ?>
            <h4 class="fw-bold"><?= $jlap; ?> - Divisi : <?= $dvs; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr; ?></h4>
            <h5>Periode : <?= date('d M Y',strtotime($tanggalMulai)) ?> s/d <?= date('d M Y',strtotime($tanggalSelesai)) ?></h5>
            <h6>Jenis Member : <?= $jenisMember; ?>, PLU : <?= $kodePLU; ?>, Kode Member : <?= $kdmbr; ?>, Nama Member : <?= $nmmbr; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center text-nowrap bg-info">#</th>
                        <th colspan="3" class="fw-bold text-center text-nowrap bg-info">Divisi</th>
                        <th colspan="2" class="fw-bold text-center text-nowrap bg-info">Produk</th>
                        <th colspan="3" class="fw-bold text-center text-nowrap bg-info">Sales</th>
                        <th colspan="2" class="fw-bold text-center text-nowrap bg-info">NBH</th>
                        <th colspan="2" class="fw-bold text-center text-nowrap bg-info">MPP</th>
                        <th colspan="2" class="fw-bold text-center text-nowrap bg-info">PPBR</th>
                        <th rowspan="2" class="fw-bold text-center text-nowrap bg-info">CB / NK</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center text-nowrap bg-info">Div</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Dep</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Kat</th>
                        <th class="fw-bold text-center text-nowrap bg-info">PLU</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Deskripsi</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Qty</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Rupiah</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Margin</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Qty</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Rupiah</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Qty</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Rupiah</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Qty</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Rupiah</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php 
                        $no++;
                        foreach($evaluasiperish as $sl) :
                    ?>
                        <tr>
                            <td class="text-end text-nowrap"><?= $no++; ?></td>
                            <td class="text-center text-nowrap"><?= $sl['DTL_K_DIV']; ?></td>
                            <td class="text-center text-nowrap"><?= $sl['DTL_K_DEPT']; ?></td>
                            <td class="text-center text-nowrap"><?= $sl['DTL_K_KATB']; ?></td>
                            <td class="text-center text-nowrap"><?= $sl['DTL_PRDCD_CTN']; ?></td>
                            <td class="text-start text-nowrap"><?= $sl['DTL_NAMA_BARANG']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['QTY_IN_PCS'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['DTL_NETTO'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['DTL_MARGIN'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['QTY_HILANG'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['RPH_HILANG'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['QTY_MPP'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['RPH_MPP'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['QTY_RUSAK'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['RPH_RUSAK'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['CB'],0, '.', ','); ?></td>
                        </tr>
                        <?php
                            // $totalKunjungan += $sl['KUNJUNGAN'];
                            // $totalMember += $sl['JML_MEMBER'];
                            // $totalStruk += $sl['STRUK'];
                            $totalQtyInPcs += $sl['QTY_IN_PCS'];
                            $totalGross += $sl['DTL_GROSS'];
                            $totalNetto += $sl['DTL_NETTO'];
                            $totalMargin += $sl['DTL_MARGIN'];
                            $totalQtyHilang += $sl['QTY_HILANG'];
                            $totalRphHilang += $sl['RPH_HILANG'];
                            $totalQtyMpp += $sl['QTY_MPP'];
                            $totalRphMpp += $sl['RPH_MPP'];
                            $totalQtyRusak += $sl['QTY_RUSAK'];
                            $totalRphRusak += $sl['RPH_RUSAK'];
                            $totalCb += $sl['CB'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="6" class="fw-bold text-center">Total</td>
                        <td class="text-end fw-bold"><?= number_format($totalQtyInPcs,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalNetto,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalMargin,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalQtyHilang,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalRphHilang,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalQtyMpp,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalRphMpp,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalQtyRusak,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalRphRusak,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalCb,0, '.', ','); ?></td>
                    </tr>
                    <tr>
                        <td colspan="6" class="fw-bold text-center">Average per Produk</td>
                        <?php if ($no == 0) {$no = 1;} ?>
                            <td class="text-end fw-bold"><?= number_format($totalQtyInPcs / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalNetto / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalMargin / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalQtyHilang / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalRphHilang / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalQtyMpp / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalRphMpp / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalQtyRusak / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalRphRusak / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalCb / $no,0, '.', ','); ?></td>
                    </tr>
                </tfoot>
            </table>
        <?php } else if($lap == "1A") { ?>
            <h4 class="fw-bold"><?= $jlap; ?> - Divisi : <?= $dvs; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr; ?></h4>
            <h5>Periode : <?= date('d M Y',strtotime($tanggalMulai)) ?> s/d <?= date('d M Y',strtotime($tanggalSelesai)) ?></h5>
            <h6>Jenis Member : <?= $jenisMember; ?>, PLU : <?= $kodePLU; ?>, Kode Member : <?= $kdmbr; ?>, Nama Member : <?= $nmmbr; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center text-nowrap bg-info">#</th>
                        <th colspan="3" class="fw-bold text-center text-nowrap bg-info">Divisi</th>
                        <th colspan="2" class="fw-bold text-center text-nowrap bg-info">Produk</th>
                        <th colspan="8" class="fw-bold text-center text-nowrap bg-info">Sales</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center text-nowrap bg-info">Div</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Dep</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Kat</th>
                        <th class="fw-bold text-center text-nowrap bg-info">PLU</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Deskripsi</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Kunjungan</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Member</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Struk</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Qty in Pcs</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Rph Gross</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Rph Netto</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Rph Margin</th>
                        <th class="fw-bold text-center text-nowrap bg-info">% Margin</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php 
                        $no++;
                        foreach($evaluasiperish as $sl) :
                    ?>
                        <tr>
                            <td class="text-end text-nowrap"><?= $no++; ?></td>
                            <td class="text-center text-nowrap"><?= $sl['DTL_K_DIV']; ?></td>
                            <td class="text-center text-nowrap"><?= $sl['DTL_K_DEPT']; ?></td>
                            <td class="text-center text-nowrap"><?= $sl['DTL_K_KATB']; ?></td>
                            <td class="text-center text-nowrap"><?= $sl['DTL_PRDCD_CTN']; ?></td>
                            <td class="text-start text-nowrap"><?= $sl['DTL_NAMA_BARANG']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['KUNJUNGAN'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['JML_MEMBER'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['STRUK'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['QTY_IN_PCS'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['DTL_GROSS'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['DTL_NETTO'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['DTL_MARGIN'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['DTL_MARGIN_PERSEN'],2, '.', ','); ?> %</td>
                        </tr>
                        <?php
                            $totalKunjungan += $sl['KUNJUNGAN'];
                            $totalMember += $sl['JML_MEMBER'];
                            $totalStruk += $sl['STRUK'];
                            $totalQtyInPcs += $sl['QTY_IN_PCS'];
                            $totalGross += $sl['DTL_GROSS'];
                            $totalNetto += $sl['DTL_NETTO'];
                            $totalMargin += $sl['DTL_MARGIN'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="6" class="fw-bold text-center">Total</td>
                        <td class="text-end fw-bold"><?= number_format($totalKunjungan,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalMember,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalStruk,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalQtyInPcs,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalGross,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalNetto,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalMargin,0, '.', ','); ?></td>
                        <?php if ($totalNetto == 0) {$totalNetto = 1;} ?>
                        <td class="text-end fw-bold"><?= number_format($totalMargin / $totalNetto * 100, 2, '.', ','); ?> %</td>
                    </tr>
                    <tr>
                        <td colspan="6" class="fw-bold text-center">Average per Produk</td>
                        <?php if ($no == 0) {$no = 1;} ?>
                            <td class="text-end fw-bold"><?= number_format($totalKunjungan / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalMember / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalStruk / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalQtyInPcs / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalGross / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalNetto / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalMargin / $no,0, '.', ','); ?></td>
                            <?php if ($totalNetto == 0) {$totalNetto = 1;} ?>
                            <td class="text-end fw-bold"><?= number_format($totalMargin / $totalNetto * 100, 2, '.', ','); ?> %</td>
                    </tr>
                </tfoot>
            </table>
        <?php } else if($lap == "2") { ?>
            <h4 class="fw-bold"><?= $jlap; ?> - Divisi : <?= $dvs; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr; ?></h4>
            <h5>Periode : <?= date('d M Y',strtotime($tanggalMulai)) ?> s/d <?= date('d M Y',strtotime($tanggalSelesai)) ?></h5>
            <h6>Jenis Member : <?= $jenisMember; ?>, PLU : <?= $kodePLU; ?>, Kode Member : <?= $kdmbr; ?>, Nama Member : <?= $nmmbr; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center text-nowrap bg-info">#</th>
                        <th colspan="2" class="fw-bold text-center text-nowrap bg-info">Divisi</th>
                        <th colspan="9" class="fw-bold text-center text-nowrap bg-info">Sales</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center text-nowrap bg-info">Div</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Nama Div</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Kunjungan</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Member</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Struk</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Produk</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Qty in Pcs</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Rph Gross</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Rph Netto</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Rph Margin</th>
                        <th class="fw-bold text-center text-nowrap bg-info">% Margin</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php 
                        $no++;
                        foreach($evaluasiperish as $sl) :
                    ?>
                        <tr>
                            <td class="text-end text-nowrap"><?= $no++; ?></td>
                            <td class="text-center text-nowrap"><?= $sl['DTL_K_DIV']; ?></td>
                            <td class="text-center text-nowrap"><?= $sl['DTL_NAMA_DIV']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['KUNJUNGAN'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['JML_MEMBER'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['STRUK'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['PRODUK'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['QTY_IN_PCS'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['DTL_GROSS'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['DTL_NETTO'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['DTL_MARGIN'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['DTL_MARGIN_PERSEN'],2, '.', ','); ?> %</td>
                        </tr>
                        <?php
                            $totalKunjungan += $sl['KUNJUNGAN'];
                            $totalMember += $sl['JML_MEMBER'];
                            $totalStruk += $sl['STRUK'];
                            $totalProduk += $sl['PRODUK'];
                            $totalQtyInPcs += $sl['QTY_IN_PCS'];
                            $totalGross += $sl['DTL_GROSS'];
                            $totalNetto += $sl['DTL_NETTO'];
                            $totalMargin += $sl['DTL_MARGIN'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="3" class="fw-bold text-center">Total</td>
                        <td class="text-end fw-bold"><?= number_format($totalKunjungan,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalMember,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalStruk,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalProduk,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalQtyInPcs,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalGross,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalNetto,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalMargin,0, '.', ','); ?></td>
                        <?php if ($totalNetto == 0) {$totalNetto = 1;} ?>
                        <td class="text-end fw-bold"><?= number_format($totalMargin / $totalNetto * 100, 2, '.', ','); ?> %</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="fw-bold text-center">Average per Produk</td>
                        <?php if ($no == 0) {$no = 1;} ?>
                            <td class="text-end fw-bold"><?= number_format($totalKunjungan / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalMember / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalStruk / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalProduk / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalQtyInPcs / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalGross / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalNetto / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalMargin / $no,0, '.', ','); ?></td>
                            <?php if ($totalNetto == 0) {$totalNetto = 1;} ?>
                            <td class="text-end fw-bold"><?= number_format($totalMargin / $totalNetto * 100, 2, '.', ','); ?> %</td>
                    </tr>
                </tfoot>
            </table>
        <?php } else if($lap == "3") { ?>
            <h4 class="fw-bold"><?= $jlap; ?> - Divisi : <?= $dvs; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr; ?></h4>
            <h5>Periode : <?= date('d M Y',strtotime($tanggalMulai)) ?> s/d <?= date('d M Y',strtotime($tanggalSelesai)) ?></h5>
            <h6>Jenis Member : <?= $jenisMember; ?>, PLU : <?= $kodePLU; ?>, Kode Member : <?= $kdmbr; ?>, Nama Member : <?= $nmmbr; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center text-nowrap bg-info">#</th>
                        <th colspan="3" class="fw-bold text-center text-nowrap bg-info">Divisi</th>
                        <th colspan="9" class="fw-bold text-center text-nowrap bg-info">Sales</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center text-nowrap bg-info">Div</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Dep</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Nama Dep</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Kunjungan</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Member</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Struk</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Produk</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Qty in Pcs</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Rph Gross</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Rph Netto</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Rph Margin</th>
                        <th class="fw-bold text-center text-nowrap bg-info">% Margin</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php 
                        $no++;
                        foreach($evaluasiperish as $sl) :
                    ?>
                        <tr>
                            <td class="text-end text-nowrap"><?= $no++; ?></td>
                            <td class="text-center text-nowrap"><?= $sl['DTL_K_DIV']; ?></td>
                            <td class="text-center text-nowrap"><?= $sl['DTL_K_DEPT']; ?></td>
                            <td class="text-center text-nowrap"><?= $sl['DTL_NAMA_DEPT']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['KUNJUNGAN'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['JML_MEMBER'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['STRUK'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['PRODUK'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['QTY_IN_PCS'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['DTL_GROSS'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['DTL_NETTO'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['DTL_MARGIN'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['DTL_MARGIN_PERSEN'],2, '.', ','); ?> %</td>
                        </tr>
                        <?php
                            $totalKunjungan += $sl['KUNJUNGAN'];
                            $totalMember += $sl['JML_MEMBER'];
                            $totalStruk += $sl['STRUK'];
                            $totalProduk += $sl['PRODUK'];
                            $totalQtyInPcs += $sl['QTY_IN_PCS'];
                            $totalGross += $sl['DTL_GROSS'];
                            $totalNetto += $sl['DTL_NETTO'];
                            $totalMargin += $sl['DTL_MARGIN'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="4" class="fw-bold text-center">Total</td>
                        <td class="text-end fw-bold"><?= number_format($totalKunjungan,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalMember,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalStruk,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalProduk,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalQtyInPcs,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalGross,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalNetto,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalMargin,0, '.', ','); ?></td>
                        <?php if ($totalNetto == 0) {$totalNetto = 1;} ?>
                        <td class="text-end fw-bold"><?= number_format($totalMargin / $totalNetto * 100, 2, '.', ','); ?> %</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="fw-bold text-center">Average per Produk</td>
                        <?php if ($no == 0) {$no = 1;} ?>
                            <td class="text-end fw-bold"><?= number_format($totalKunjungan / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalMember / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalStruk / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalProduk / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalQtyInPcs / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalGross / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalNetto / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalMargin / $no,0, '.', ','); ?></td>
                            <?php if ($totalNetto == 0) {$totalNetto = 1;} ?>
                            <td class="text-end fw-bold"><?= number_format($totalMargin / $totalNetto * 100, 2, '.', ','); ?> %</td>
                    </tr>
                </tfoot>
            </table>
        <?php } else if($lap == "4") { ?>
            <h4 class="fw-bold"><?= $jlap; ?> - Divisi : <?= $dvs; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr; ?></h4>
            <h5>Periode : <?= date('d M Y',strtotime($tanggalMulai)) ?> s/d <?= date('d M Y',strtotime($tanggalSelesai)) ?></h5>
            <h6>Jenis Member : <?= $jenisMember; ?>, PLU : <?= $kodePLU; ?>, Kode Member : <?= $kdmbr; ?>, Nama Member : <?= $nmmbr; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center text-nowrap bg-info">#</th>
                        <th colspan="4" class="fw-bold text-center text-nowrap bg-info">Divisi</th>
                        <th colspan="9" class="fw-bold text-center text-nowrap bg-info">Sales</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center text-nowrap bg-info">Div</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Dep</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Kat</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Nama Kat</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Kunjungan</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Member</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Struk</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Produk</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Qty in Pcs</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Rph Gross</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Rph Netto</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Rph Margin</th>
                        <th class="fw-bold text-center text-nowrap bg-info">% Margin</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php 
                        $no++;
                        foreach($evaluasiperish as $sl) :
                    ?>
                        <tr>
                            <td class="text-end text-nowrap"><?= $no++; ?></td>
                            <td class="text-center text-nowrap"><?= $sl['DTL_K_DIV']; ?></td>
                            <td class="text-center text-nowrap"><?= $sl['DTL_K_DEPT']; ?></td>
                            <td class="text-center text-nowrap"><?= $sl['DTL_K_KATB']; ?></td>
                            <td class="text-center text-nowrap"><?= $sl['DTL_NAMA_KATB']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['KUNJUNGAN'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['JML_MEMBER'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['STRUK'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['PRODUK'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['QTY_IN_PCS'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['DTL_GROSS'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['DTL_NETTO'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['DTL_MARGIN'],0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($sl['DTL_MARGIN_PERSEN'],2, '.', ','); ?> %</td>
                        </tr>
                        <?php
                            $totalKunjungan += $sl['KUNJUNGAN'];
                            $totalMember += $sl['JML_MEMBER'];
                            $totalStruk += $sl['STRUK'];
                            $totalProduk += $sl['PRODUK'];
                            $totalQtyInPcs += $sl['QTY_IN_PCS'];
                            $totalGross += $sl['DTL_GROSS'];
                            $totalNetto += $sl['DTL_NETTO'];
                            $totalMargin += $sl['DTL_MARGIN'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="5" class="fw-bold text-center">Total</td>
                        <td class="text-end fw-bold"><?= number_format($totalKunjungan,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalMember,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalStruk,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalProduk,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalQtyInPcs,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalGross,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalNetto,0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($totalMargin,0, '.', ','); ?></td>
                        <?php if ($totalNetto == 0) {$totalNetto = 1;} ?>
                        <td class="text-end fw-bold"><?= number_format($totalMargin / $totalNetto * 100, 2, '.', ','); ?> %</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="fw-bold text-center">Average per Produk</td>
                        <?php if ($no == 0) {$no = 1;} ?>
                            <td class="text-end fw-bold"><?= number_format($totalKunjungan / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalMember / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalStruk / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalProduk / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalQtyInPcs / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalGross / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalNetto / $no,0, '.', ','); ?></td>
                            <td class="text-end fw-bold"><?= number_format($totalMargin / $no,0, '.', ','); ?></td>
                            <?php if ($totalNetto == 0) {$totalNetto = 1;} ?>
                            <td class="text-end fw-bold"><?= number_format($totalMargin / $totalNetto * 100, 2, '.', ','); ?> %</td>
                    </tr>
                </tfoot>
            </table>
        <?php } ?>
    </body>
</html>