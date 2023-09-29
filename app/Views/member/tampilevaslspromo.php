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
            $kodePLU = $dvs = $dep = $katbr = $kodeMember = $namaMember = $kdmbr = $nmmbr = $mbr = $kodeOutlet = $kodeOutlet = $kodeSubOutlet = $jnmbr = $namaBarang = $kodeBarcode = $kodeSupplier = $namaSupplier = $jenisMember = "All"; 
            $jenisLaporan = "0";
            foreach($divisi as $dv) :
                if($kodeDivisi == $dv['DIV_KODEDIVISI']) {
                    $dvs = $dv['DIV_NAMADIVISI'];
                }
            endforeach;

            foreach($departement as $dp) :
                if($kodeDepartemen == $dp['DEP_KODEDEPARTEMENT']) {
                    $dep = $dp['DEP_NAMADEPARTEMENT'];
                }
            endforeach;
    
            if(isset($_GET['tglawalbefore'])) {if ($_GET['tglawalbefore'] !=""){$tglawalbefore = $_GET['tglawalbefore']; }}
            if(isset($_GET['tglakhirbefore'])) {if ($_GET['tglakhirbefore'] !=""){$tglakhirbefore = $_GET['tglakhirbefore']; }}
            if(isset($_GET['awal'])) {if ($_GET['awal'] !=""){$tglawal = $_GET['awal']; }}
            if(isset($_GET['akhir'])) {if ($_GET['akhir'] !=""){$tglakhir = $_GET['akhir']; }}
            if(isset($_GET['tglawalafter'])) {if ($_GET['tglawalafter'] !=""){$tglawalafter = $_GET['tglawalafter']; }}
            if(isset($_GET['tglakhirafter'])) {if ($_GET['tglakhirafter'] !=""){$tglakhirafter = $_GET['tglakhirafter']; }}
            if(isset($_GET['kodemember'])) {if ($_GET['kodemember'] !=""){$kodeMember = $_GET['kodemember']; }}
            if ($kodeMember != "All" AND $kodeMember != "") {
                $kdmbr = $kodeMember;
            }
            if(isset($_GET['namamember'])) {if ($_GET['namamember'] !=""){$namaMember = $_GET['namamember']; }}
            if ($namaMember != "All" AND $namaMember != "") {
                $nmmbr = $namaMember;
            }
            if(isset($_GET['jenisMember'])) {if ($_GET['jenisMember'] !=""){$jenisMember = $_GET['jenisMember']; }}
            if ($jenisMember == 'MERAH') {
                $jnmbr = "MERAH";
              } elseif ($jenisMember == 'BIRU') {
                $jnmbr = "BIRU";
              } elseif ($jenisMember == 'MERAHBIRU') {
                $jnmbr = "MERAH & BIRU";
              } elseif ($jenisMember == 'OMI') {
                $jnmbr = "OMI";
              } elseif ($jenisMember == 'IDM') {
                $jnmbr = "IDM";	
              } elseif ($jenisMember == 'KLIK') {
                $jnmbr = "KLIK";	
              } elseif ($jenisMember == 'KLIKMERAH') {
                $jnmbr = "KLIK-MERAH";	
              } elseif ($jenisMember == 'KLIKBIRU') {
                $jnmbr = "KLIK-BIRU";	
              } elseif ($jenisMember == 'OMIHJK') {
                $jnmbr = "Diluar OMI & HJK";
              } elseif ($jenisMember == 'IDMOMIHJK') {
                $jnmbr = "Diluar OMI, IDM & HJK";
              } elseif($jenisMember == "ALL") {
                $jnmbr = "All";
              }

            if(isset($_GET['outlet'])) {if ($_GET['outlet'] !=""){$kodeOutlet = $_GET['outlet']; }}
            if($kodeOutlet == "0") {
                $kodeOutlet = "KARYAWAN";
            } else if($kodeOutlet == "1") {
                $kodeOutlet = "WHOLESALER";
            } else if($kodeOutlet == "2") {
                $kodeOutlet = "RETAILER";
            } else if($kodeOutlet == "3") {
                $kodeOutlet = "HCO";
            } else if($kodeOutlet == "4") {
                $kodeOutlet = "OMI";
            } else if($kodeOutlet == "5") {
                $kodeOutlet = "INSTITUSI";
            } else if($kodeOutlet == "6") {
                $kodeOutlet = "PRIBADI";
            }
            if(isset($_GET['kodeSubOutlet'])) {if ($_GET['kodeSubOutlet'] !=""){$kodeSubOutlet = $_GET['kodeSubOutlet']; }}
            if($kodeSubOutlet == "F") {
                $kodeSubOutlet = "FREE PASS";
            } else if($kodeSubOutlet == "G") {
                $kodeSubOutlet = "GROUP";
            } else if($kodeSubOutlet == "I") {
                $kodeSubOutlet = "INDOMARET";
            } else if($kodeSubOutlet == "L") {
                $kodeSubOutlet = "KLIK INDOGROSIR";
            } else if($kodeSubOutlet == "O") {
                $kodeSubOutlet = "OMI";
            } else if($kodeSubOutlet == "T") {
                $kodeSubOutlet = "TMI";
            } else if($kodeSubOutlet == "Z") {
                $kodeSubOutlet = "RETUR SUPPLIER";
            } else if($kodeSubOutlet == "") {
                $kodeSubOutlet = "BIASA";
            }
            if(isset($_GET['namabarang'])) {if ($_GET['namabarang'] !=""){$namaBarang = $_GET['namabarang']; }}
            if(isset($_GET['plu'])) {if ($_GET['plu'] !=""){$kodePLU = $_GET['plu']; }}
            if ($kodePLU != "All" AND $kodePLU != "") {
                $kodePLU = substr('00000000' . $kodePLU, -7);
            }
            if(isset($_GET['barcode'])) {if ($_GET['barcode'] !=""){$kodeBarcode = $_GET['barcode']; }}
            if(isset($_GET['kodesupplier'])) {if ($_GET['kodesupplier'] !=""){$kodeSupplier = $_GET['kodesupplier']; }}
            if(isset($_GET['namasupplier'])) {if ($_GET['namasupplier'] !=""){$namaSupplier = $_GET['namasupplier']; }}
            if(isset($_GET['jenisLaporan'])) {if ($_GET['jenisLaporan'] !=""){$jenisLaporan = $_GET['jenisLaporan']; }}
        ?>

        <?php if($jenisLaporan == "1") { ?>
            <h4 class="fw-bold"><?= $jlap; ?> - Divisi : <?= $dvs; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr; ?></h4>
            <h5>Periode Sebelum Promosi : <?= date('d M Y',strtotime($tglawalbefore)) ?> s/d <?= date('d M Y',strtotime($tglakhirbefore)) ?></h5>
            <h5>Periode Promosi : <?= date('d M Y',strtotime($tglawal)) ?> s/d <?= date('d M Y',strtotime($tglakhir)) ?></h5>
            <h5>Periode Setelah Promosi : <?= date('d M Y',strtotime($tglawalafter)) ?> s/d <?= date('d M Y',strtotime($tglakhirafter)) ?></h5>
            <h6>Jenis Member : <?= $jnmbr; ?>, Kode Member : <?= $kdmbr; ?>, Nama Member : <?= $nmmbr; ?>, Outlet : <?= $kodeOutlet; ?>, Sub Outlet : <?= $kodeSubOutlet; ?>, Nama Member : <?= $nmmbr; ?></h6>
            <h6>Nama Barang : <?= $namaBarang; ?>, PLU : <?= $kodePLU; ?>, Barcode : <?= $kodeBarcode; ?></h6>
            <h6>Nama Supplier : <?= $namaSupplier; ?>, Kode Supplier : <?= $kodeSupplier; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center text-nowrap bg-info">#</th>
                        <th colspan="2" class="fw-bold text-center text-nowrap bg-info">Outlet</th>
                        <th colspan="2" class="fw-bold text-center text-nowrap bg-info">Sub Outlet</th>
                        <th colspan="8" class="fw-bold text-center text-nowrap bg-info">Sebelum Promosi : <?= date('d M Y',strtotime($tglawalbefore)) ?> s/d <?= date('d M Y',strtotime($tglakhirbefore)) ?></th>
                        <th colspan="8" class="fw-bold text-center text-nowrap bg-info">Promosi : <?= date('d M Y',strtotime($tglawal)) ?> s/d <?= date('d M Y',strtotime($tglakhir)) ?></th>
                        <th colspan="8" class="fw-bold text-center text-nowrap bg-info">Setelah Promosi : <?= date('d M Y',strtotime($tglawalafter)) ?> s/d <?= date('d M Y',strtotime($tglakhirafter)) ?></th>
                        <th rowspan="2" class="fw-bold text-center text-nowrap bg-info">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center text-nowrap bg-info">Kode</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Nama</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Kode</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Nama</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Kunjungan</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Member</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Struk</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Qty</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Gross</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Netto</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Margin</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Margin %</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Kunjungan</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Member</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Struk</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Qty</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Gross</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Netto</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Margin</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Margin %</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Kunjungan</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Member</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Struk</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Qty</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Gross</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Netto</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Margin</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Margin %</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php 
                        $no++;
                        foreach($tipeoutlet as $tp) : ?>
                        <tr>
                            <td class="text-end text-nowrap"><?= $no++; ?></td>
                            <td class="text-center text-nowrap"><?= $tp['DTL_OUTLET']; ?></td>
                            <td class="text-center text-nowrap"><?= $tp['DTL_NAMA_OUTLET']; ?></td>
                            <td class="text-center text-nowrap"><?= $tp['DTL_SUBOUTLET']; ?></td>
                            <td class="text-center text-nowrap"><?= $tp['DTL_NAMA_SUBOUTLET']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tp['DTL_KUNJUNGAN'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tp['DTL_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tp['DTL_STRUK'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tp['DTL_QTY_IN_PCS'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tp['DTL_GROSS'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tp['DTL_NETTO'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tp['DTL_MARGIN'], 0, '.', ','); ?></td>
                            <?php if($tp['DTL_NETTO'] == 0)  { ?>
                                <td class="text-end text-nowrap"><?= number_format(0 , 2, '.', ','); ?></td>
                            <?php } else { ?>
                                <td class="text-end text-nowrap"><?= number_format($tp['DTL_MARGIN'] / $tp['DTL_NETTO'] * 100,0 , 2, '.', ','); ?></td>
                            <?php } ?>
                            <td class="text-end text-nowrap"><?= number_format($tp['DTL_KUNJUNGAN_2'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tp['DTL_MEMBER_2'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tp['DTL_STRUK_2'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tp['DTL_QTY_IN_PCS_2'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tp['DTL_GROSS_2'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tp['DTL_NETTO_2'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tp['DTL_MARGIN_2'], 0, '.', ','); ?></td>
                            <?php if($tp['DTL_NETTO_2'] == 0)  { ?>
                                <td class="text-end text-nowrap"><?= number_format(0 , 2, '.', ','); ?></td>
                            <?php } else { ?>
                                <td class="text-end text-nowrap"><?= number_format($tp['DTL_MARGIN_2'] / $tp['DTL_NETTO_2'] * 100,0 , 2, '.', ','); ?></td>
                            <?php } ?>
                            <td class="text-end text-nowrap"><?= number_format($tp['DTL_KUNJUNGAN_3'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tp['DTL_MEMBER_3'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tp['DTL_STRUK_3'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tp['DTL_QTY_IN_PCS_3'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tp['DTL_GROSS_3'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tp['DTL_NETTO_3'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($tp['DTL_MARGIN_3'], 0, '.', ','); ?></td>
                            <?php if($tp['DTL_NETTO_3'] == 0)  { ?>
                                <td class="text-end text-nowrap"><?= number_format(0 , 2, '.', ','); ?></td>
                            <?php } else { ?>
                                <td class="text-end text-nowrap"><?= number_format($tp['DTL_MARGIN_3'] / $tp['DTL_NETTO_3'] * 100,0 , 2, '.', ','); ?></td>
                            <?php } ?>
                            <td></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        <?php } else if($jenisLaporan == "2") {?>
        <?php } else if($jenisLaporan == "3") {?>
        <?php } else if($jenisLaporan == "4") {?>
        <?php } else if($jenisLaporan == "5") {?>
        <?php } else if($jenisLaporan == "6") {?>
        <?php } ?>
    </body>
</html>