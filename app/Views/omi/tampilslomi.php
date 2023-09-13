<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid mt-3">
    <h4 class="fw-bold"><?= $judul; ?></h4>
    <br>
    <p class="text-secondary">Toko OMI : <?= $judulomi; ?></p>
    <?php if(empty($tglawal) or empty($tglakhir)): ?>
        <p class="fw-bold text-center text-warning fs-5">Tanggal Belum Dipilih!</p>
    <?php endif; ?>
    <?php if(!empty($rekaptoko)): ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th colspan="3"></th>
                <th colspan="3" class="bg-primary text-light text-center">ORDER UPLOAD</th>
                <th colspan="3" class="bg-warning text-light text-center">PICKING</th>
                <th colspan="3" class="bg-success text-light text-center">DSP</th>
                <th colspan="3">Persen</th>
            </tr>
            <tr>
                <th>No</th>
                <th>KodeOMI</th>
                <th>NamaTokoOMI</th>
                <th>Item</th>
                <th>Qty</th>
                <th>Rupiah</th>
                <th>Item</th>
                <th>Qty</th>
                <th>Rupiah</th>
                <th>Item</th>
                <th>Qty</th>
                <th>Rupiah</th>
                <th>Qty</th>
                <th>Rupiah</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            $ttlitemorder    = 0;
            $ttlrphorder     = 0;
            $ttlqtyorder     = 0;
            $ttlitempicking  = 0;
            $ttlqtypicking   = 0;
            $ttlrphpicking   = 0;
            $ttlitemdsp      = 0;
            $ttlqtydsp       = 0;
            $ttlrphdsp       = 0;
            $persenqtyttl    = 0;
            $persenrphttl    = 0; 
            ?>
            <?php foreach($rekaptoko as $rt): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $rt['KODEOMI']; ?></td>
                    <td><?= $rt['NAMAOMI']; ?></td>
                    <td><?= number_format($rt['ITEMORDER']); ?></td>
                    <td><?= number_format($rt['QTYORDER']); ?></td>
                    <td><?= number_format($rt['RPHORDER']); ?></td>
                    <td><?= number_format($rt['ITEMPICK']); ?></td>
                    <td><?= number_format($rt['QTYPICK']); ?></td>
                    <td><?= number_format($rt['RPHPICK']); ?></td>
                    <td><?= number_format($rt['ITEMDSP']); ?></td>
                    <td><?= number_format($rt['QTYDSP']); ?></td>
                    <td><?= number_format($rt['RPHDSP']); ?></td>
                    <td><?= number_format(($rt['QTYDSP']/$rt['QTYORDER'])*100,1,",","."); ?>%</td>
                    <td><?= number_format(($rt['RPHDSP']/$rt['RPHORDER'])*100,1,",","."); ?>%</td>
                </tr>
                <?php 
                    $ttlitemorder    += $rt['ITEMORDER'] ;
                    $ttlqtyorder     += $rt['QTYORDER'] ;
                    $ttlrphorder     += $rt['RPHORDER'] ;
                    $ttlitempicking  += $rt['ITEMPICK'] ;
                    $ttlqtypicking   += $rt['QTYPICK'] ;
                    $ttlrphpicking   += $rt['RPHPICK'] ;
                    $ttlitemdsp      += $rt['ITEMDSP'] ;
                    $ttlqtydsp       += $rt['QTYDSP'] ;
                    $ttlrphdsp       += $rt['RPHDSP'] ;
                    $persenqtyttl    = ($ttlqtydsp / $ttlqtyorder)*100;
                    $persenrphttl    = ($ttlrphdsp / $ttlrphorder)*100;
                ?>
            <?php endforeach; ?>
            <tr>
                <td class="fw-bold">TOTAL</td>
                <td class="fw-bold"></td>
                <td class="fw-bold"></td>
                <td class="fw-bold"><?= number_format($ttlitemorder); ?></td>
                <td class="fw-bold"><?= number_format($ttlqtyorder); ?></td>
                <td class="fw-bold"><?= number_format($ttlrphorder); ?></td>
                <td class="fw-bold"><?= number_format($ttlitempicking); ?></td>
                <td class="fw-bold"><?= number_format($ttlqtypicking); ?></td>
                <td class="fw-bold"><?= number_format($ttlrphpicking); ?></td>
                <td class="fw-bold"><?= number_format($ttlitemdsp); ?></td>
                <td class="fw-bold"><?= number_format($ttlqtydsp); ?></td>
                <td class="fw-bold"><?= number_format($ttlrphdsp); ?></td>
                <td class="fw-bold"><?= number_format($persenqtyttl,1,",","."); ?>%</td>
                <td class="fw-bold"><?= number_format($persenrphttl,1,",","."); ?>%</td>
            </tr>
        </tbody>
    </table>
    <?php elseif(!empty($rekappb)): ?>
        <table class="table table-striped">
        <thead>
            <tr>
                <th colspan="3"></th>
                <th colspan="3" class="bg-primary text-light text-center">ORDER UPLOAD</th>
                <th colspan="3" class="bg-warning text-light text-center">PICKING</th>
                <th colspan="3" class="bg-success text-light text-center">DSP</th>
                <th colspan="3" class="text-center">Persen</th>
            </tr>
            <tr>
                <th>KodeOMI</th>
                <th>NamaTokoOMI</th>
                <th>NomorPB</th>
                <th>Item</th>
                <th>Qty</th>
                <th>Rupiah</th>
                <th>Item</th>
                <th>Qty</th>
                <th>Rupiah</th>
                <th>Item</th>
                <th>Qty</th>
                <th>Rupiah</th>
                <th>Qty</th>
                <th>Rupiah</th>
            </tr>
        </thead>
        <tbody>
        <?php 
            $ttlitemorder    = 0;
            $ttlrphorder     = 0;
            $ttlqtyorder     = 0;
            $ttlitempicking  = 0;
            $ttlqtypicking   = 0;
            $ttlrphpicking   = 0;
            $ttlitemdsp      = 0;
            $ttlqtydsp       = 0;
            $ttlrphdsp       = 0;
            $persenqtyttl    = 0;
            $persenrphttl    = 0; 
        ?>
        <?php foreach($rekappb as $rp): ?>
            <tr>
                <td><?= $rp['KODEOMI']; ?></td>
                <td><?= $rp['NAMAOMI']; ?></td>
                <td><?= $rp['NOMORPB']; ?></td>
                <td><?= $rp['ITEMORDER']; ?></td>
                <td><?= $rp['QTYORDER']; ?></td>
                <td><?= $rp['RPHORDER']; ?></td>
                <td><?= number_format($rp['ITEMPICK']); ?></td>
                <td><?= number_format($rp['QTYPICK']); ?></td>
                <td><?= number_format($rp['RPHPICK']); ?></td>
                <td><?= number_format($rp['ITEMDSP']); ?></td>
                <td><?= number_format($rp['QTYDSP']); ?></td>
                <td><?= number_format($rp['RPHDSP']); ?></td>
                <td><?= number_format(($rp['QTYDSP']/$rp['QTYORDER'])*100,1,",","."); ?>%</td>
                <td><?= number_format(($rp['RPHDSP']/$rp['RPHORDER'])*100,1,",","."); ?>%</td>
            </tr>
            <?php 
                $ttlitemorder    += $rp['ITEMORDER'] ;
                $ttlqtyorder     += $rp['QTYORDER'] ;
                $ttlrphorder     += $rp['RPHORDER'] ;
                $ttlitempicking  += $rp['ITEMPICK'] ;
                $ttlqtypicking   += $rp['QTYPICK'] ;
                $ttlrphpicking   += $rp['RPHPICK'] ;
                $ttlitemdsp      += $rp['ITEMDSP'] ;
                $ttlqtydsp       += $rp['QTYDSP'] ;
                $ttlrphdsp       += $rp['RPHDSP'] ;
                $persenqtyttl    = ($ttlqtydsp / $ttlqtyorder)*100;
                $persenrphttl    = ($ttlrphdsp / $ttlrphorder)*100;
            ?>
        <?php endforeach; ?>
            <tr>
                <td class="fw-bold">TOTAL</td>
                <td colspan="2"></td>
                <td class="fw-bold"><?= number_format($ttlitemorder); ?></td>
                <td class="fw-bold"><?= number_format($ttlqtyorder); ?></td>
                <td class="fw-bold"><?= number_format($ttlrphorder); ?></td>
                <td class="fw-bold"><?= number_format($ttlitempicking); ?></td>
                <td class="fw-bold"><?= number_format($ttlqtypicking); ?></td>
                <td class="fw-bold"><?= number_format($ttlrphpicking); ?></td>
                <td class="fw-bold"><?= number_format($ttlitemdsp); ?></td>
                <td class="fw-bold"><?= number_format($ttlqtydsp); ?></td>
                <td class="fw-bold"><?= number_format($ttlrphdsp); ?></td>
                <td class="fw-bold"><?= number_format($persenqtyttl,1,",","."); ?>%</td>
                <td class="fw-bold"><?= number_format($persenrphttl,1,",","."); ?>%</td>
            </tr>
        </tbody>
        </table>
    <?php elseif(!empty($listorder)): ?>
        <table class="table text-small table-striped">
            <thead>
                <tr>
                    <th colspan="9"></th>
                    <th colspan="3" class="bg-primary text-light text-center">ORDER UPLOAD</th>
                    <th colspan="3" class="bg-warning text-light text-center">PICKING</th>
                    <th colspan="3" class="bg-success text-light text-center">DSP</th>
                </tr>
                <tr>
                    <th>No</th>
                    <th>Div</th>
                    <th>Dep</th>
                    <th>Kat</th>
                    <th>PLU</th>
                    <th>Deskripsi</th>
                    <th>Unit</th>
                    <th>Frac</th>
                    <th>Tag</th>
                    <th>Toko</th>
                    <th>Qty</th>
                    <th>Rupiah</th>
                    <th>Toko</th>
                    <th>Qty</th>
                    <th>Rupiah</th>
                    <th>Toko</th>
                    <th>Qty</th>
                    <th>Rupiah</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no              = 1;
                $ttlqtyorder     = 0;
                $ttlrphorder     = 0;
                $ttlqtypicking   = 0;
                $ttlrphpicking   = 0;
                $ttlqtydsp       = 0;
                $ttlrphdsp       = 0;
                ?>
                <?php foreach($listorder as $lo): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $lo['DIV']; ?></td>
                        <td><?= $lo['DEP']; ?></td>
                        <td><?= $lo['KAT']; ?></td>
                        <td><?= $lo['PLUIGR']; ?></td>
                        <td><?= $lo['DESKRIPSI']; ?></td>
                        <td><?= $lo['UNIT']; ?></td>
                        <td><?= $lo['FRAC']; ?></td>
                        <td><?= $lo['TAGIGR']; ?></td>
                        <td><?= number_format($lo['TOKOORDER']); ?></td>
                        <td><?= number_format($lo['QTYORDER']); ?></td>
                        <td><?= number_format($lo['RPHORDER']); ?></td>
                        <td><?= number_format($lo['TOKOPICK']); ?></td>
                        <td><?= number_format($lo['QTYPICK']); ?></td>
                        <td><?= number_format($lo['RPHPICK']); ?></td>
                        <td><?= number_format($lo['TOKODSP']); ?></td>
                        <td><?= number_format($lo['QTYDSP']); ?></td>
                        <td><?= number_format($lo['RPHDSP']); ?></td>
                    </tr>
                <?php 
                $ttlqtyorder     += $lo['QTYORDER'];
                $ttlrphorder     += $lo['RPHORDER'];
                $ttlqtypicking   += $lo['QTYPICK'];
                $ttlrphpicking   += $lo['RPHPICK'];
                $ttlqtydsp       += $lo['QTYDSP'];
                $ttlrphdsp       += $lo['RPHDSP'];
                ?>
                <?php endforeach; ?>
                <tr>
                    <td class="fw-bold">TOTAL</td>
                    <td colspan="8"></td>
                    <td class="fw-bold">#</td>
                    <td class="fw-bold"><?= number_format($ttlqtyorder); ?></td>
                    <td class="fw-bold"><?= number_format($ttlrphorder); ?></td>
                    <td class="fw-bold">#</td>
                    <td class="fw-bold"><?= number_format($ttlqtypicking); ?></td>
                    <td class="fw-bold"><?= number_format($ttlrphpicking); ?></td>
                    <td class="fw-bold">#</td>
                    <td class="fw-bold"><?= number_format($ttlqtydsp); ?></td>
                    <td class="fw-bold"><?= number_format($ttlrphdsp); ?></td>
                </tr>
            </tbody>
        </table>
    <?php elseif(!empty($detailplu)): ?>
        <table class="table text-small table-striped">
            <thead>
                <tr>
                    <th colspan="7"></th>
                    <th colspan="2" class="bg-primary-subtle text-center">ORDER</th>
                    <th colspan="2" class="bg-warning-subtle text-center">PICKING</th>
                    <th colspan="2" class="bg-success-subtle text-center">DSP</th>
                    <th colspan="2" class="bg-danger-subtle text-center">REFUND</th>
                </tr>
                <tr>
                    <th>PLU</th>
                    <th>DESKRIPSI</th>
                    <th>FRAC</th>
                    <th>UNIT</th>
                    <th>TGLPROSES</th>
                    <th>TOKOOMI</th>
                    <th>NOMORPB</th>
                    <th>QTY</th>
                    <th>RUPIAH</th>
                    <th>QTY</th>
                    <th>RUPIAH</th>
                    <th>QTY</th>
                    <th>RUPIAH</th>
                    <th>QTY</th>
                    <th>RUPIAH</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no              = 0;
                $ttlqtyorder     = 0;
                $ttlrphorder     = 0;
                $ttlqtypicking   = 0;
                $ttlrphpicking   = 0;
                $ttlqtydsp       = 0;
                $ttlrphdsp       = 0;
                $ttlqtyrefund    = 0;
                $ttlrphrefund    = 0;
                ?>
                <?php foreach($detailplu as $dp): ?>
                    <tr>
                        <td><?= $dp['PLUIGR']; ?></td>
                        <td><?= $dp['DESKRIPSI']; ?></td>
                        <td><?= $dp['FRAC']; ?></td>
                        <td><?= $dp['UNIT']; ?></td>
                        <td><?= $dp['TGLPROSES']; ?></td>
                        <td><?= $dp['KODEOMI']; ?></td>
                        <td><?= $dp['NOMORPB']; ?></td>
                        <td><?= number_format($dp['QTYORDER']); ?></td>
                        <td><?= number_format($dp['RPHORDER']); ?></td>
                        <td><?= number_format($dp['QTYPICK']); ?></td>
                        <td><?= number_format($dp['RPHPICK']); ?></td>
                        <td><?= number_format($dp['QTYDSP']); ?></td>
                        <td><?= number_format($dp['RPHDSP']); ?></td>
                        <td><?= number_format($dp['QTYREFUND'],0,",","."); ?></td>
                        <td><?= number_format($dp['RPHREFUND']); ?></td>
                    </tr>
                <?php 
                $ttlqtyorder     += $dp['QTYORDER'] ;
                $ttlrphorder     += $dp['RPHORDER'] ;
                $ttlqtypicking   += $dp['QTYPICK'] ;
                $ttlrphpicking   += $dp['RPHPICK'] ;;
                $ttlqtydsp       += $dp['QTYDSP'] ;
                $ttlrphdsp       += $dp['RPHDSP'] ;
                $ttlqtyrefund    += $dp['QTYREFUND'];
                $ttlrphrefund    += $dp['RPHREFUND'];
                ?>
                <?php endforeach; ?>
                <tr>
                    <td class="fw-bold">TOTAL</td>
                    <td colspan="6"></td>
                    <td class="fw-bold"><?= number_format($ttlqtyorder); ?></td>
                    <td class="fw-bold"><?= number_format($ttlrphorder); ?></td>
                    <td class="fw-bold"><?= number_format($ttlqtypicking); ?></td>
                    <td class="fw-bold"><?= number_format($ttlrphpicking); ?></td>
                    <td class="fw-bold"><?= number_format($ttlqtydsp); ?></td>
                    <td class="fw-bold"><?= number_format($ttlrphdsp); ?></td>
                    <td class="fw-bold"><?= number_format($ttlqtyrefund); ?></td>
                    <td class="fw-bold"><?= number_format($ttlrphrefund); ?></td>
                </tr>
            </tbody>
        </table>
    <?php elseif(!empty($itemrefund)): ?>
        <table class="table text-small table-striped">
            <thead class="border-top border-dark">
                <tr>
                    <th>No</th>
                    <th>DIV</th>
                    <th>DEP</th>
                    <th>KAT</th>
                    <th>PLURPB</th>
                    <th>PLU</th>
                    <th>Deskripsi</th>
                    <th>Unit</th>
                    <th>Frac</th>
                    <th>Tag</th>
                    <th>KodeOMI</th>
                    <th>NoPBOMI</th>
                    <th>TanggalDSP</th>
                    <th>QtyOrder</th>
                    <th>QtyReal</th>
                    <th>QtyRefund</th>
                    <th>Kode</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php foreach($itemrefund as $ir): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $ir['DIV']; ?></td>
                        <td><?= $ir['DEP']; ?></td>
                        <td><?= $ir['KAT']; ?></td>
                        <td><?= $ir['PLURPB']; ?></td>
                        <td><?= $ir['PLUIGR']; ?></td>
                        <td><?= $ir['DESKRIPSI']; ?></td>
                        <td><?= $ir['FRAC']; ?></td>
                        <td><?= $ir['UNIT']; ?></td>
                        <td><?= $ir['TAGIGR']; ?></td>
                        <td><?= $ir['KODEOMI']; ?></td>
                        <td><?= $ir['NOPBOMI']; ?></td>
                        <td><?= $ir['TGLDSP']; ?></td>
                        <td><?= $ir['QTYORDER']; ?></td>
                        <td><?= $ir['QTYREALISASI']; ?></td>
                        <td><?= $ir['QTYREFUND']; ?></td>
                        <td><?= $ir['KETERANGANV']; ?></td>
                        <td><?= $ir['KETERANGAN']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif(!empty($tolakanlengkap)): ?>
        <table class="table text-small table-striped mb-4">
            <thead class="border-top border-dark">
                <tr>
                    <th>No</th>
                    <th>PLUIGR</th>
                    <th>PLUOMI</th>
                    <th>Deskripsi</th>
                    <th>TagIGR</th>
                    <th>Ket. Tolakan</th>
                    <th>TglProses</th>
                    <th>KodeOMI</th>
                    <th>NomorPB</th>
                    <th>QtyOrder</th>
                    <th>LastCost</th>
                    <th>Nilai</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no              = 1;
                $ttlqtyorder     = 0;
                $ttlrphorder     = 0;
                ?>
                <?php foreach($tolakanlengkap as $tl): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $tl['TLKO_PLUIGR']; ?></td>
                        <td><?= $tl['TLKO_PLUOMI']; ?></td>
                        <td><?= $tl['TLKO_DESC']; ?></td>
                        <td><?= $tl['TLKO_TAG_IGR']; ?></td>
                        <td><?= $tl['TLKO_KETTOLAKAN']; ?></td>
                        <td><?= $tl['TLKO_CREATE_DT']; ?></td>
                        <td><?= $tl['TLKO_KODEOMI']; ?></td>
                        <td><?= $tl['TLKO_NOPB']; ?></td>
                        <td><?= number_format($tl['TLKO_QTYORDER']); ?></td>
                        <td><?= number_format($tl['TLKO_LASTCOST']); ?></td>
                        <td><?= number_format($tl['TOTAL_NILAI']); ?></td>
                    </tr>
                    <?php  
                        $ttlqtyorder += $tl['TLKO_QTYORDER'];
                        $ttlrphorder += $tl['TOTAL_NILAI'];
                    ?>
                <?php endforeach; ?>
                    <tr>
                        <td colspan="4"></td>
                        <td colspan="5" class="fw-bold">TOTAL</td>
                        <td class="fw-bold"><?= number_format($ttlqtyorder); ?></td>
                        <td></td>
                        <td class="fw-bold"><?= number_format($ttlrphorder); ?></td>
                    </tr>
            </tbody>
        </table>
    <?php elseif(!empty($tolakanplu)): ?>
        <table class="table text-small table-striped mb-4">
            <thead>
                <tr>
                    <th>No</th>
                    <th>PLUIGR</th>
                    <th>PLUOMI</th>
                    <th>Deskripsi</th>
                    <th>JmlToko</th>
                    <th>JmlPB</th>
                    <th>QtyOrder</th>
                    <th>Nilai</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                 $no = 1;
                 $ttlqtyorder     = 0;
                 $ttlrphorder     = 0;
                 ?>
                <?php foreach($tolakanplu as $tp): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $tp['TLKO_PLUIGR']; ?></td>
                        <td><?= $tp['TLKO_PLUOMI']; ?></td>
                        <td><?= $tp['TLKO_DESC']; ?></td>
                        <td><?= number_format($tp['JMLTOKO']); ?></td>
                        <td><?= number_format($tp['JMLPB']); ?></td>
                        <td><?= number_format($tp['QTYORDER']); ?></td>
                        <td><?= number_format($tp['TOTAL_NILAI']); ?></td>
                    </tr>
                    <?php 
                        $ttlqtyorder += $tp['QTYORDER'];
                        $ttlrphorder += $tp['TOTAL_NILAI'];
                    ?>
                <?php endforeach; ?>
                    <tr>
                        <td colspan="3"></td>
                        <td class="fw-bold text-center">TOTAL</td>
                        <td colspan="2"></td>
                        <td class="fw-bold text-center"><?= number_format($ttlqtyorder); ?></td>
                        <td class="fw-bold text-center"><?= number_format($ttlrphorder); ?></td>
                    </tr>
            </tbody>
        </table>
    <?php elseif(!empty($tidaktersuplai)): ?>
        <table class="table text-small table-striped mb-4">
            <thead class="border-top border-dark">
                <tr>
                    <th>No</th>
                    <th>Div</th>
                    <th>Dep</th>
                    <th>Kat</th>
                    <th>PLUIGR</th>
                    <th>Deskripsi</th>
                    <th>Unit</th>
                    <th>Frac</th>
                    <th>Tag</th>
                    <th>PLUOMI</th>
                    <th>Acost</th>
                    <th>AvgSales</th>
                    <th>PBOMI</th>
                    <th>REAL</th>
                    <th>QtySlsh</th>
                    <th>RphSlsh</th>
                    <th>Stok</th>
                    <th>TglPB</th>
                    <th>QtyPB</th>
                    <th>BPB</th>
                    <th>TglPO</th>
                    <th>QtyPO</th>
                    <th>PKMT</th>
                    <th>MPLUS</th>
                    <th>KodeSupplier</th>
                    <th>NamaSupplier</th>
                </tr>
            </thead>
            <tbody>
                <?php  
                    $no = 1;
                    $ttlqtyorder     = 0;
                    $ttlrphorder     = 0;
                    $ttlqtypicking   = 0;
                    $ttlrphpicking   = 0;
                    $ttlqtydsp       = 0;
                    $ttlrphdsp       = 0;
                ?>
                <?php foreach($tidaktersuplai as $tt): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $tt['DIV']; ?></td>
                        <td><?= $tt['DEP']; ?></td>
                        <td><?= $tt['KAT']; ?></td>
                        <td><?= $tt['PLUIGR']; ?></td>
                        <td><?= $tt['DESKRIPSI']; ?></td>
                        <td><?= $tt['UNIT']; ?></td>
                        <td><?= $tt['FRAC']; ?></td>
                        <td><?= $tt['TAGIGR']; ?></td>
                        <td><?= $tt['PLUOMI']; ?></td>
                        <td><?= $tt['ACOST']; ?></td>
                        <td><?= $tt['AVGSALES_OMI']; ?></td>
                        <td><?= $tt['QTYORDER']; ?></td>
                        <td><?= $tt['QTYDSP']; ?></td>
                        <td><?= $tt['SELISIH_QTY']; ?></td>
                        <td><?= $tt['SELISIH_RPH']; ?></td>
                        <td><?= $tt['STOK']; ?></td>
                        <td><?= $tt['TGLPB']; ?></td>
                        <td><?= $tt['QTYPB']; ?></td>
                        <td><?= $tt['BPB']; ?></td>
                        <td><?= $tt['TGLPO']; ?></td>
                        <td><?= $tt['QTYPO']; ?></td>
                        <td><?= $tt['PKMT']; ?></td>
                        <td><?= $tt['MPLUS']; ?></td>
                        <td><?= $tt['KDSUPPLIER']; ?></td>
                        <td><?= $tt['NAMASUPPLIER']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="fw-bold text-center text-danger fs-5">Pilih JENIS LAPORAN yang valid!!</p>
    <?php endif; ?>
</div>

<?= $this->endSection(); ?>