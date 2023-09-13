<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid mt-3 overflow-scroll">
    <?= $judul; ?>
    <?php if(!empty($rekaporder)): ?>
        <table class="table table-responsive table-bordered">
            <thead>
                <tr>
                    <th class="bg-primary text-light text-center">NO</th>
                    <th class="bg-primary text-light text-center">KODE OMI</th>
                    <th class="bg-primary text-light text-center">NAMA OMI</th>
                    <th class="bg-primary text-light text-center">NO PB</th>
                    <th class="bg-primary text-light text-center">JML ITEM</th>
                    <th class="bg-primary text-light text-center">ITEM REAL</th>
                    <th class="bg-primary text-light text-center">QTY ORDER</th>
                    <th class="bg-primary text-light text-center">RPH ORDER</th>
                    <th class="bg-primary text-light text-center">QTY PICKING</th>
                    <th class="bg-primary text-light text-center">RPH PICKING</th>
                    <th class="bg-primary text-light text-center">QTY REALISASI</th>
                    <th class="bg-primary text-light text-center">RPH REALISASI</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;?>
                <?php foreach($rekaporder as $ro): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $ro['KODEOMI']; ?></td>
                    <td><?= $ro['NAMAOMI']; ?></td>
                    <td><?= $ro['NOPB']; ?></td>
                    <td><?= number_format($ro['JMLITEM']); ?></td>
                    <td><?= number_format($ro['JMLITEM_REAL']); ?></td>
                    <td><?= number_format($ro['QTY_ORDER']); ?></td>
                    <td><?= number_format($ro['RPH_ORDER']); ?></td>
                    <td><?= number_format($ro['QTY_PICKING']); ?></td>
                    <td><?= number_format($ro['RPH_PICKING']); ?></td>
                    <td><?= number_format($ro['QTY_REALISASI']); ?></td>
                    <td><?= number_format($ro['RPH_REALISASI']); ?></td>
                </tr>
               
                <?php endforeach; ?>
                <?php foreach($hitungtotal as $ht): ?>
                    
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="fw-bold"><?= number_format($ht['JMLITEM']); ?></td>
                    <td class="fw-bold"><?= number_format($ht['JMLITEM_REAL']); ?></td>
                    <td class="fw-bold"><?= number_format($ht['QTY_ORDER']); ?></td>
                    <td class="fw-bold"><?= number_format($ht['RPH_ORDER']); ?></td>
                    <td class="fw-bold"><?= number_format($ht['QTY_PICKING']); ?></td>
                    <td class="fw-bold"><?= number_format($ht['RPH_PICKING']); ?></td>
                    <td class="fw-bold"><?= number_format($ht['QTY_REALISASI']); ?></td>
                    <td class="fw-bold"><?= number_format($ht['RPH_REALISASI']); ?></td>
                    
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <?php if(!empty($listorder)): ?>
        <table class="table table-sm table-responsive table-bordered">
            <thead>
                <tr>
                    <th class="bg-primary text-center text-light">TANGGAL</th>
                    <th class="bg-primary text-center text-light">DIV</th>
                    <th class="bg-primary text-center text-light">DEP</th>
                    <th class="bg-primary text-center text-light">KAT</th>
                    <th class="bg-primary text-center text-light">PLUIGR</th>
                    <th class="bg-primary text-center text-light">PLUOMI</th>
                    <th class="bg-primary text-center text-light">DESKRIPSI</th>
                    <th class="bg-primary text-center text-light">UNIT</th>
                    <th class="bg-primary text-center text-light">FRAC</th>
                    <th class="bg-primary text-center text-light">TAGIGR</th>
                    <th class="bg-primary text-center text-light">TAGOMI</th>
                    <th class="bg-primary text-center text-light">HRG SATUAN</th>
                    <th class="bg-primary text-center text-light">QTY ORDER</th>
                    <th class="bg-primary text-center text-light">QTY REALISASI</th>
                    <th class="bg-primary text-center text-light">QTY SELISIH</th>
                    <th class="bg-primary text-center text-light">RPH ORDER</th>
                    <th class="bg-primary text-center text-light">RPH REALISASI</th>
                    <th class="bg-primary text-center text-light">RPH SELISIH</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($listorder as $lo): ?>
                    <tr>
                        <td><?= $lo['TGL']; ?></td>
                        <td><?= $lo['DIV']; ?></td>
                        <td><?= $lo['DEPT']; ?></td>
                        <td><?= $lo['KATB']; ?></td>
                        <td><?= $lo['PLUIGR']; ?></td>
                        <td><?= $lo['PLUOMI']; ?></td>
                        <td><?= $lo['DESKRIPSI']; ?></td>
                        <td><?= $lo['UNIT']; ?></td>
                        <td><?= $lo['FRAC']; ?></td>
                        <td><?= $lo['TAGIGR']; ?></td>
                        <td><?= $lo['TAGOMI']; ?></td>
                        <td><?= $lo['HRGSATUAN']; ?></td>
                        <td><?= $lo['QTY_ORDER']; ?></td>
                        <td><?= $lo['QTY_REALISASI']; ?></td>
                        <td><?= $lo['QTY_SELISIH']; ?></td>
                        <td><?= $lo['RPH_ORDER']; ?></td>
                        <td><?= $lo['RPH_REALISASI']; ?></td>
                        <td><?= $lo['RPH_SELISIH']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <?php if(!empty($tolakanpb)): ?>
        <table class="table table-sm table-responsive table-bordered">
            <thead>
                <tr>
                    <th class="bg-primary text-light text-center">TANGGAL</th>
                    <th class="bg-primary text-light text-center">PIC</th>
                    <th class="bg-primary text-light text-center">DIV</th>
                    <th class="bg-primary text-light text-center">DEPT</th>
                    <th class="bg-primary text-light text-center">KATB</th>
                    <th class="bg-primary text-light text-center">PLUIGR</th>
                    <th class="bg-primary text-light text-center">PLUOMI</th>
                    <th class="bg-primary text-light text-center">DESKRIPSI</th>
                    <th class="bg-primary text-light text-center">UNIT</th>
                    <th class="bg-primary text-light text-center">FRAC</th>
                    <th class="bg-primary text-light text-center">TAGIGR</th>
                    <th class="bg-primary text-light text-center">TAGOMI</th>
                    <th class="bg-primary text-light text-center">JML TOKO PB</th>
                    <th class="bg-primary text-light text-center">JML TOKO REAL</th>
                    <th class="bg-primary text-light text-center">QTY ORDER</th>
                    <th class="bg-primary text-light text-center">QTY REAL</th>
                    <th class="bg-primary text-light text-center">QTY SELISIH</th>
                    <th class="bg-primary text-light text-center">RPH ORDER</th>
                    <th class="bg-primary text-light text-center">RPH REAL</th>
                    <th class="bg-primary text-light text-center">RPH SELISIH</th>
                    <th class="bg-primary text-light text-center">ALAMAT</th>
                    <th class="bg-primary text-light text-center">STOK</th>
                    <th class="bg-primary text-light text-center">KETERANGAN TOLAKAN</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($tolakanpb as $tp): ?>
                    <tr>
                        <td><?= $tp['TANGGAL']; ?></td>
                        <td><?= $tp['PIC']; ?></td>
                        <td><?= $tp['DIV']; ?></td>
                        <td><?= $tp['DEPT']; ?></td>
                        <td><?= $tp['KATB']; ?></td>
                        <td><?= $tp['PLUIGR']; ?></td>
                        <td><?= $tp['PLUOMI']; ?></td>
                        <td><?= $tp['DESKRIPSI']; ?></td>
                        <td><?= $tp['FRAC']; ?></td>
                        <td><?= $tp['UNIT']; ?></td>
                        <td><?= $tp['TAGIGR']; ?></td>
                        <td><?= $tp['TAGOMI']; ?></td>
                        <td><?= $tp['JMLHTOKO_PB']; ?></td>
                        <td><?= $tp['JMLHTOKO_REAL']; ?></td>
                        <td><?= $tp['QTY_ORDER']; ?></td>
                        <td><?= $tp['QTY_REAL']; ?></td>
                        <td><?= $tp['QTY_SLSH']; ?></td>
                        <td><?= $tp['RPH_ORDER']; ?></td>
                        <td><?= $tp['RPH_REAL']; ?></td>
                        <td><?= $tp['RPH_SLSH']; ?></td>
                        <td><?= $tp['RAK'].".".$tp['SR'].".".$tp['TR'].".".$tp['SH'].".".$tp['NU']; ?></td>
                        <td><?= $tp['STOK']; ?></td>
                        <td><?= $tp['KETERANGAN']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <?php if(!empty($pickingitem)): ?>
        <table class="table table-sm table-responsive table-bordered">
            <thead>
                <tr>
                    <th class="bg-primary text-light text-center">NO</th>
                    <th class="bg-primary text-light text-center">OMI</th>
                    <th class="bg-primary text-light text-center">NOPB</th>
                    <th class="bg-primary text-light text-center">RECID</th>
                    <th class="bg-primary text-light text-center">PLUIGR</th>
                    <th class="bg-primary text-light text-center">DESKRIPSI</th>
                    <th class="bg-primary text-light text-center">UNIT</th>
                    <th class="bg-primary text-light text-center">FRAC</th>
                    <th class="bg-primary text-light text-center">JALUR</th>
                    <th class="bg-primary text-light text-center">QTY ORDER</th>
                    <th class="bg-primary text-light text-center">QTY REALISASI</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php foreach($pickingitem as $pi): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $pi['KODEOMI']; ?></td>
                        <td><?= $pi['NOPB']; ?></td>
                        <td><?= $pi['RECID']; ?></td>
                        <td><?= $pi['PLUIGR']; ?></td>
                        <td><?= $pi['DESKRIPSI']; ?></td>
                        <td><?= $pi['UNIT']; ?></td>
                        <td><?= $pi['FRAC']; ?></td>
                        <td><?= $pi['JALURPICKING']; ?></td>
                        <td><?= $pi['QTYORDER']; ?></td>
                        <td><?= $pi['QTYREAL']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <?php if(!empty($masteritem)): ?>
        <table class="table table-sm table-responsive table-bordered">
            <thead>
                <tr>
                    <th class="bg-primary text-center text-light">DIV</th>
                    <th class="bg-primary text-center text-light">DEPT</th>
                    <th class="bg-primary text-center text-light">KATB</th>
                    <th class="bg-primary text-center text-light">PLU IGR</th>
                    <th class="bg-primary text-center text-light">PLU OMI</th>
                    <th class="bg-primary text-center text-light">DESKRIPSI</th>
                    <th class="bg-primary text-center text-light">FRAC</th>
                    <th class="bg-primary text-center text-light">UNIT</th>
                    <th class="bg-primary text-center text-light">TAG IGR</th>
                    <th class="bg-primary text-center text-light">TAG OMI</th>
                    <th class="bg-primary text-center text-light">ALAMAT</th>
                    <th class="bg-primary text-center text-light">STOK</th>
                    <th class="bg-primary text-center text-light">AVGSLS IGR</th>
                    <th class="bg-primary text-center text-light">AVG PBOMI</th>
                    <th class="bg-primary text-center text-light">6XAVG PBOMI</th>
                    <th class="bg-primary text-center text-light">LPP < 6XAVG PBOMI</th>
                    <th class="bg-primary text-center text-light">MINOR OMI</th>
                    <th class="bg-primary text-center text-light">MINOR IGR</th>
                    <th class="bg-primary text-center text-light">SL SUPP BLN LALU</th>
                    <th class="bg-primary text-center text-light">AVGSL SUPP 3BLN</th>
                    <th class="bg-primary text-center text-light">PKM</th>
                    <th class="bg-primary text-center text-light">MPLUS</th>
                    <th class="bg-primary text-center text-light">PKMT</th>
                    <th class="bg-primary text-center text-light">MAXPLANO DPD</th>
                    <th class="bg-primary text-center text-light">QTY PLANO DPD</th>
                    <th class="bg-primary text-center text-light">MAXPLANO TOKO</th>
                    <th class="bg-primary text-center text-light">QTY PLANO TOKO</th>
                    <th class="bg-primary text-center text-light">PO KD</th>
                    <th class="bg-primary text-center text-light">QTY PO</th>
                    <th class="bg-primary text-center text-light">QTY BPB</th>
                    <th class="bg-primary text-center text-light">SL SUPP SAAT INI</th>
                    <th class="bg-primary text-center text-light">KODE SUPP</th>
                    <th class="bg-primary text-center text-light">NAMA SUPP</th>
                    <th class="bg-primary text-center text-light">PO VIA</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($masteritem as $mi): ?>
                    <tr>
                        <td><?= $mi['DIV']; ?></td>
                        <td><?= $mi['DEPT']; ?></td>
                        <td><?= $mi['KATB']; ?></td>
                        <td><?= $mi['PLU_IGR']; ?></td>
                        <td><?= $mi['PLU_OMI']; ?></td>
                        <td><?= $mi['DESKRIPSI']; ?></td>
                        <td><?= $mi['FRAC']; ?></td>
                        <td><?= $mi['UNIT']; ?></td>
                        <td><?= $mi['TAG_IGR']; ?></td>
                        <td><?= $mi['TAG_OMI']; ?></td>
                        <td><?= $mi['RAK'].".".$mi['SR'].".".$mi['TR'].".".$mi['SH'].".".$mi['NU']; ?></td>
                        <td><?= number_format($mi['STOK']); ?></td>
                        <td><?= number_format($mi['AVG_SLSIGR']); ?></td>
                        <td><?= number_format($mi['AVG_PBOMI']); ?></td>
                        <td><?= number_format($mi['AVG_6X_PBOMI']); ?></td>
                        <td><?= number_format($mi['STOCK_VS_AVG_6X_PBOMI']); ?></td>
                        <td><?= number_format($mi['MINOR_OMI']); ?></td>
                        <td><?= number_format($mi['MINOR_IGR']); ?></td>
                        <td><?= number_format($mi['SL']); ?></td>
                        <td><?= number_format($mi['AVG_SL_3BLN']); ?></td>
                        <td><?= number_format($mi['PKM']); ?></td>
                        <td><?= number_format($mi['MPLUS']); ?></td>
                        <td><?= number_format($mi['PKMT']); ?></td>
                        <td><?= number_format($mi['MAXPLANO_DPD']); ?></td>
                        <td><?= number_format($mi['QTY_PLANODPD']); ?></td>
                        <td><?= number_format($mi['MAXPLANO_TOKO']); ?></td>
                        <td><?= number_format($mi['QTY_PLANOTOKO']); ?></td>
                        <td><?= $mi['PO_KADALUARSA']; ?></td>
                        <td><?= number_format($mi['QTY_PO']); ?></td>
                        <td><?= number_format($mi['QTY_BPB']); ?></td>
                        <td><?= number_format($mi['SL_SUPP_SAATINI']); ?></td>
                        <td><?= $mi['KODE_SUPP']; ?></td>
                        <td><?= $mi['NAMA_SUPP']; ?></td>
                        <td><?= $mi['PO_VIA']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <?php if(!empty($masterpb)): ?>
        <table class="table table-sm vw-100 table-bordered">
            <thead>
                <th class="bg-primary text-light text-center">TANGGAL</th>
                <th class="bg-primary text-light text-center">DIV</th>
                <th class="bg-primary text-light text-center">DEPT</th>
                <th class="bg-primary text-light text-center">KATB</th>
                <th class="bg-primary text-light text-center">PLUOMI</th>
                <th class="bg-primary text-light text-center">PLUIGR</th>
                <th class="bg-primary text-light text-center">DESKRIPSI</th>
                <th class="bg-primary text-light text-center">FRAC</th>
                <th class="bg-primary text-light text-center">UNIT</th>
                <th class="bg-primary text-light text-center">TAG IGR</th>
                <th class="bg-primary text-light text-center">TAG OMI</th>
                <th class="bg-primary text-light text-center">JMLH TOKO PB</th>
                <th class="bg-primary text-light text-center">JMLH TOKO REAL</th>
                <th class="bg-primary text-light text-center">QTY ORDER</th>
                <th class="bg-primary text-light text-center">QTY REAL</th>
                <th class="bg-primary text-light text-center">QTY SELISIH</th>
                <th class="bg-primary text-light text-center">RPH ORDER</th>
                <th class="bg-primary text-light text-center">RPH REAL</th>
                <th class="bg-primary text-light text-center">RPH SELISIH</th>
                <th class="bg-primary text-light text-center">ALAMAT</th>
                <th class="bg-primary text-light text-center">STOK</th>
                <th class="bg-primary text-light text-center">AVGSLS IGR</th>
                <th class="bg-primary text-light text-center">AVGSLS PBOMI</th>
                <th class="bg-primary text-light text-center">6XAVG PBOMI</th>
                <th class="bg-primary text-light text-center">LPP < 6XAVG PBOMI</th>
                <th class="bg-primary text-light text-center">MINOR OMI</th>
                <th class="bg-primary text-light text-center">MINOR IGR</th>
                <th class="bg-primary text-light text-center">SL SUPP BLN LALU</th>
                <th class="bg-primary text-light text-center">AVGSL SUPP 3BLN</th>
                <th class="bg-primary text-light text-center">PKM</th>
                <th class="bg-primary text-light text-center">MPLUS</th>
                <th class="bg-primary text-light text-center">PKMT</th>
                <th class="bg-primary text-light text-center">MAXPLANO DPD</th>
                <th class="bg-primary text-light text-center">QTY PLANO DPD</th>
                <th class="bg-primary text-light text-center">MAXPLANO TOKO</th>
                <th class="bg-primary text-light text-center">QTY PLANO TOKO</th>
                <th class="bg-primary text-light text-center">PO KD</th>
                <th class="bg-primary text-light text-center">QTY PO</th>
                <th class="bg-primary text-light text-center">QTY BTB</th>
                <th class="bg-primary text-light text-center">SL SUPP SAAT INI</th>
                <th class="bg-primary text-light text-center">KODE SUPP</th>
                <th class="bg-primary text-light text-center">NAMA SUPP</th>
            </thead>
            <tbody>
                <?php foreach($masterpb as $mp): ?>
                    <tr>
                        <td><?= $mp['TGL']; ?></td>
                        <td><?= $mp['DIV']; ?></td>
                        <td><?= $mp['DEPT']; ?></td>
                        <td><?= $mp['KATB']; ?></td>
                        <td><?= $mp['PLU_OMI']; ?></td>
                        <td><?= $mp['PLU_IGR']; ?></td>
                        <td><?= $mp['DESKRIPSI']; ?></td>
                        <td><?= $mp['FRAC']; ?></td>
                        <td><?= $mp['UNIT']; ?></td>
                        <td><?= $mp['TAG_IGR']; ?></td>
                        <td><?= $mp['TAG_OMI']; ?></td>
                        <td><?= number_format($mp['JML_TOKO_YANG_PB']); ?></td>
                        <td><?= number_format($mp['JML_TOKO_YANG_PB_REALISASI']); ?></td>
                        <td><?= number_format($mp['QTY_ORDER']); ?></td>
                        <td><?= number_format($mp['QTY_REALISASI']); ?></td>
                        <td><?= number_format($mp['QTY_SELISIH']); ?></td>
                        <td><?= number_format($mp['RPH_ORDER']); ?></td>
                        <td><?= number_format($mp['RPH_REALISASI']) ; ?></td>
                        <td><?= number_format($mp['RPH_SELISIH']); ?></td>
                        <td><?= $mp['RAK'].".".$mp['SR'].".".$mp['TR'].".".$mp['SH'].".".$mp['NU']; ?></td>
                        <td><?= number_format($mp['STOKLPP']); ?></td>
                        <td><?= number_format($mp['AVGSLS_IGR']); ?></td>
                        <td><?= number_format($mp['AVG_PB_OMI']); ?></td>
                        <td><?= number_format($mp['AVG_6X_PBOMI']); ?></td>
                        <td><?= number_format($mp['STOCK_VS_AVG_6X_PBOMI']); ?></td>
                        <td><?= number_format($mp['MINOR_OMI']); ?></td>
                        <td><?= number_format($mp['MINOR_IGR']); ?></td>
                        <td><?= number_format($mp['SL']); ?></td>
                        <td><?= number_format($mp['AVG_SL_3BLN']); ?></td>
                        <td><?= number_format($mp['PKM']); ?></td>
                        <td><?= number_format($mp['MPLUS']); ?></td>
                        <td><?= number_format($mp['PKMT']); ?></td>
                        <td><?= number_format($mp['MAXPLANO_DPD']); ?></td>
                        <td><?= number_format($mp['QTYPLANO_DPD']); ?></td>
                        <td><?= number_format($mp['MAXPLANO_TOKO']); ?></td>
                        <td><?= number_format($mp['QTYPLANO_TOKO']); ?></td>
                        <td><?= number_format($mp['QTY_PO']); ?></td>
                        <td><?= $mp['PO_KADALUARSA']; ?></td>
                        <td><?= number_format($mp['QTY_BPB']); ?></td>
                        <td><?= number_format($mp['SL_SUPP_SAATINI']); ?></td>
                        <td><?= $mp['KODE_SUPP']; ?></td>
                        <td><?= $mp['NAMA_SUPP']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?= $this->endSection(); ?>