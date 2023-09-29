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
            $no = $marginPersen = $hargaNetto = $totalQtyInPcs = $totalKunjungan = $totalMember = $totalStruk = $totalProduk = $totalPlu = $totalGross = $totalNetto = $totalMargin       = 0;
            $kodePLU = $kodeSupplier = $namaSupplier = $kodeDivisi = $kodeDepartemen = $kodeKategoriBarang = $kodeTokoOMI = $jenisLaporan = $dvs = $dep = $katbr = $omi = $kdsp = $nmsp   = "All";

            if(isset($_GET['awal'])) {if ($_GET['awal'] !=""){$tglMulaiPromosi = $_GET['awal']; }}
            if(isset($_GET['akhir'])) {if ($_GET['akhir'] !=""){$tglSelesaiPromosi = $_GET['akhir']; }}
            if(isset($_GET['tglawalbefore'])) {if ($_GET['tglawalbefore'] !=""){$tglawalbefore = $_GET['tglawalbefore']; }}
            if(isset($_GET['tglakhirbefore'])) {if ($_GET['tglakhirbefore'] !=""){$tglakhirbefore = $_GET['tglakhirbefore']; }}
            if(isset($_GET['tglawalafter'])) {if ($_GET['tglawalafter'] !=""){$tglawalafter = $_GET['tglawalafter']; }}
            if(isset($_GET['tglakhirafter'])) {if ($_GET['tglakhirafter'] !=""){$tglakhirafter = $_GET['tglakhirafter']; }}

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

            if(isset($_GET['tokoomi'])) {if ($_GET['tokoomi'] !=""){$kodeTokoOMI = $_GET['tokoomi']; }}
            foreach($tokoomi as $omi) :
                if($kodeTokoOMI == $omi['TKO_KODEOMI']) {
                    $omi = $omi['TKO_NAMAOMI'];
                }
            endforeach;
            if(isset($_GET['kodePLU'])) {if ($_GET['kodePLU'] !=""){$kodePLU = $_GET['kodePLU']; }}
            if ($kodePLU != "All" AND $kodePLU != "") {
                $kodePLU = substr('00000000' . $kodePLU, -7);
            }
            if(isset($_GET['kodesupplier'])) {if ($_GET['kodesupplier'] !=""){$kodeSupplier = $_GET['kodesupplier']; }}
            if ($kodeSupplier != "All" AND $kodeSupplier != "") {
                $kdsp = $kodeSupplier;
            }
            if(isset($_GET['namasupplier'])) {if ($_GET['namasupplier'] !=""){$namaSupplier = $_GET['namasupplier']; }}
            if ($namaSupplier != "All" AND $namaSupplier != "") {
                $nmsp = $namaSupplier;
            }
            if(isset($_GET['jenisLaporan'])) {if ($_GET['jenisLaporan'] !=""){$jenisLaporan = $_GET['jenisLaporan']; }}
        ?>

        <?php if($jenisLaporan == "2") { ?>
        <?php } else if($jenisLaporan == "6") { ?>
            <h4 class="fw-bold"><?= $jlap; ?> - Divisi : <?= $dvs; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr; ?></h4>
            <h5>Periode Sebelum Promosi : <?= date('d M Y',strtotime($tglawalbefore)) ?> s/d <?= date('d M Y',strtotime($tglakhirbefore)) ?></h5>
            <h5>Periode Promosi : <?= date('d M Y',strtotime($tglawal)) ?> s/d <?= date('d M Y',strtotime($tglakhir)) ?></h5>
            <h5>Periode Setelah Promosi : <?= date('d M Y',strtotime($tglawalafter)) ?> s/d <?= date('d M Y',strtotime($tglakhirafter)) ?></h5>
            <h6>PLU : <?= $kodePLU; ?>, Kode Supplier : <?= $kdmbr; ?>, Nama Supplier : <?= $nmmbr; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider table-info">
                    <tr>
                        <th rowspan="3" class="fw-bold text-center text-nowrap">#</th>
                        <th rowspan="2" colspan="3" class="fw-bold text-center text-nowrap">Divisi</th>
                        <th rowspan="2" colspan="6" class="fw-bold text-center text-nowrap">Produk</th>
                        <th colspan="24" class="fw-bold text-center text-nowrap">Sales IGR to OMI</th>
                        <th colspan="6" class="fw-bold text-center text-nowrap">Service Level Periode 1 : <?= $tglawal; ?> s/d <?= $tglakhir; ?></th>
                        <th colspan="6" class="fw-bold text-center text-nowrap">Service Level Periode 1 : <?= $tglawalbefore; ?> s/d <?= $tglakhirbefore; ?></th>
                        <th colspan="6" class="fw-bold text-center text-nowrap">Service Level Periode 1 : <?= $tglawalafter; ?> s/d <?= $tglakhirafter; ?></th>
                        <th colspan="6" class="fw-bold text-center text-nowrap">Sales OMI to Konsumen</th>
                        <th rowspan="2" colspan="2" class="fw-bold text-center text-nowrap">Stock in Pcs</th>
                        <th rowspan="2" colspan="2" class="fw-bold text-center text-nowrap">Supplier</th>
                    </tr>
                    <tr>
                        <th colspan="8" class="fw-bold text-center text-nowrap">Service Level Periode 1 : <?= $tglawal; ?> s/d <?= $tglakhir; ?></th>
                        <th colspan="8" class="fw-bold text-center text-nowrap">Service Level Periode 1 : <?= $tglawalbefore; ?> s/d <?= $tglakhirbefore; ?></th>
                        <th colspan="8" class="fw-bold text-center text-nowrap">Service Level Periode 1 : <?= $tglawalafter; ?> s/d <?= $tglakhirafter; ?></th>
                        <th colspan="3" class="fw-bold text-center text-nowrap">Stock in Pcs</th>
                        <th colspan="3" class="fw-bold text-center text-nowrap">Rupiah</th>
                        <th colspan="3" class="fw-bold text-center text-nowrap">Stock in Pcs</th>
                        <th colspan="3" class="fw-bold text-center text-nowrap">Rupiah</th>
                    </tr>
                </thead>
            </table>
        <?php } else if($jenisLaporan == "6B") { ?>
        <?php } ?>
    </body>
</html>