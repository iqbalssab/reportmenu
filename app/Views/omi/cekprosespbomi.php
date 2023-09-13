<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container mt-3">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary">
                    <p class="fw-bold text-light">DETAIL PB OMI</p>
                </div>
                <div class="card-body p-2">
                    <form action="cekprosespbomi" method="post">
                    <label for="kodeomi" class="fw-bold d-block">KODE OMI:</label>
                    <input type="text" name="kodeomi" id="kodeomi" class="w-100 mb-2" value="<?= old('kodeomi'); ?>">
                    <label for="nopb" class="fw-bold d-block">NOMOR PB:</label>
                    <input type="text" name="nopb" id="nopb" class="w-100 mb-4" value="<?= old('nopb'); ?>">

                    <button type="submit" name="btn" value="cekpicking" class="btn btn-primary w-100 mb-1"><i class="fa-solid fa-check-to-slot me-2"></i>Cek Picking (Sebelum SOO)</button>
                    <button type="submit" name="btn" value="dspvskoli" class="btn btn-danger w-100 mb-1"><i class="fa-solid fa-check-to-slot me-2"></i>Cek Dsp vs Koli (Sebelum SOO)</button>
                    <button type="submit" name="btn" value="dspcostnull" class="btn btn-warning w-100 text-light mb-1"><i class="fa-solid fa-check-to-slot me-2"></i>Cek Dsp Cost Null (Sebelum SPH)</button>
                    <button type="submit" name="btn" value="jalurkarton" class="btn btn-info w-100 mb-1"><i class="fa-solid fa-magnifying-glass"></i> Jalur Karton</button>
                    <button type="submit" name="btn" value="blmpicking" class="btn btn-info w-100 mb-1"><i class="fa-solid fa-magnifying-glass"></i>Item Belum Picking</button>
                    </form>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-body p-2">
                    <a href="awbipp" class="btn btn-success text-light w-100 mb-1"><i class="fa-solid fa-paper-plane me-1"></i>View AWB IPP</a>
                    <a href="progress" class="btn btn-secondary text-light w-100 mb-1"><i class="fa-solid fa-procedures me-1"></i>View Progress</a>
                    <a href="cektolakan" class="btn btn-secondary text-light w-100 mb-1"><i class="fa-solid fa-magnifying-glass-arrow-right me-1"></i>Cek Tolakan</a>
                    <a href="historyplu" class="btn btn-secondary text-light w-100 mb-1"><i class="fa-solid fa-magnifying-glass-arrow-right me-1"></i>History Per PLU</a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card overflow-auto">
                <div class="card-header">
                    <?php if(isset($judulkanan)): ?>
                        <p class="fw-lighter"><?= $judulkanan; ?></p>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if(!empty($cekpicking)): ?>
                        <table class="table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th class="bg-success text-light">NO</th>
                                    <th class="bg-success text-light">PLU</th>
                                    <th class="bg-success text-light">Deskripsi</th>
                                    <th class="bg-success text-light">Tag</th>
                                    <th class="bg-success text-light">PB</th>
                                    <th class="bg-success text-light">Picking</th>
                                    <th class="bg-success text-light">LPP</th>
                                    <th class="bg-success text-light">Plano</th>
                                    <th class="bg-success text-light">Display</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach($cekpicking as $cp): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $cp['PLU']; ?></td>
                                        <td><?= $cp['DESKRIPSI']; ?></td>
                                        <td><?= $cp['TAG']; ?></td>
                                        <td><?= $cp['QTYPB']; ?></td>
                                        <td><?= $cp['QTYPICKING']; ?></td>
                                        <td><?= $cp['QTYLPP']; ?></td>
                                        <td><?= $cp['QTYPLANO']; ?></td>
                                        <td><?= $cp['DISPLAY']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php elseif(!empty($dspvskoli)): ?>
                        <table class="table me-2 table-bordered">
                            <thead>
                                <tr>
                                    <th class="bg-success text-light">No</th>
                                    <th class="bg-success text-light">KodeOMI</th>
                                    <th class="bg-success text-light">NoPB</th>
                                    <th class="bg-success text-light">TglProses</th>
                                    <th class="bg-success text-light">NoKoli</th>
                                    <th class="bg-success text-light">Checker</th>
                                    <th class="bg-success text-light">ItemPB</th>
                                    <th class="bg-success text-light">ItemChecker</th>
                                    <th class="bg-success text-light">ItemStruk</th>
                                    <th class="bg-success text-light">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach($dspvskoli as $dvk): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $dvk['KODEOMI']; ?></td>
                                        <td><?= $dvk['NOPB']; ?></td>
                                        <td><?= $dvk['TGLPROSES']; ?></td>
                                        <td><?= $dvk['NOKOLI']; ?></td>
                                        <td><?= $dvk['CHECKER']; ?></td>
                                        <td><?= $dvk['ITEMPB']; ?></td>
                                        <td><?= $dvk['ITEMCHECKER']; ?></td>
                                        <td><?= $dvk['ITEMSTRUK']; ?></td>
                                        <?php if($dvk['ITEMCHECKER']==$dvk['ITEMSTRUK']): ?>
                                        <td class="text-center">v</td>
                                        <?php else: ?>
                                        <td class="text-danger text-center">Ada Selisih</td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php elseif(!empty($dspcostnull)): ?>
                        <table class="table me-2 table-bordered">
                            <thead>
                                <tr>
                                    <th class="bg-success text-light">No</th>
                                    <th class="bg-success text-light">TANGGAL</th>
                                    <th class="bg-success text-light">OMI</th>
                                    <th class="bg-success text-light">NoPB</th>
                                    <th class="bg-success text-light">NoKoli</th>
                                    <th class="bg-success text-light">PLU</th>
                                    <th class="bg-success text-light">RpbCost</th>
                                    <th class="bg-success text-light">NoSPH</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach($dspcostnull as $dcn): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $dcn['RPB_CREATE_DT']; ?></td>
                                        <td><?= $dcn['RPB_KODEOMI']; ?></td>
                                        <td><?= $dcn['RPB_NODOKUMEN']; ?></td>
                                        <td><?= $dcn['RPB_NOKOLI']; ?></td>
                                        <td><?= $dcn['RPB_PLU2']; ?></td>
                                        <td><?= $dcn['RPB_COST']; ?></td>
                                        <td><?= $dcn['RPB_NOSPH']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php elseif(!empty($jalurkarton)): ?>
                        <table class="table me-2 table-bordered">
                            <thead>
                                <tr>
                                    <th class="bg-success text-light">No</th>
                                    <th class="bg-success text-light">GroupRak</th>
                                    <th class="bg-success text-light">Display</th>
                                    <th class="bg-success text-light">PLU</th>
                                    <th class="bg-success text-light">Deskripsi</th>
                                    <th class="bg-success text-light">Tag</th>
                                    <th class="bg-success text-light">Unit</th>
                                    <th class="bg-success text-light">Qty Order</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach($jalurkarton as $jk): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $jk['GROUPRAK']; ?></td>
                                        <td><?= $jk['DISPLAY']; ?></td>
                                        <td><?= $jk['PBO_PLUIGR']; ?></td>
                                        <td><?= $jk['PRD_DESKRIPSIPANJANG']; ?></td>
                                        <td><?= $jk['PRD_KODETAG']; ?></td>
                                        <td><?= $jk['PRD_UNIT']."/".$jk['PRD_FRAC']; ?></td>
                                        <td><?= $jk['PBO_QTYORDER']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php elseif(!empty($blmpicking)): ?>
                        <table class="table me-2 table-bordered">
                            <thead>
                                <tr>
                                    <th class="bg-success text-light">No</th>
                                    <th class="bg-success text-light">PLU</th>
                                    <th class="bg-success text-light">Deskripsi</th>
                                    <th class="bg-success text-light">Tag</th>
                                    <th class="bg-success text-light">PB</th>
                                    <th class="bg-success text-light">Picking</th>
                                    <th class="bg-success text-light">LPP</th>
                                    <th class="bg-success text-light">Plano</th>
                                    <th class="bg-success text-light">Display</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach($blmpicking as $bp): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $bp['PLU']; ?></td>
                                        <td><?= $bp['DESKRIPSI']; ?></td>
                                        <td><?= $bp['TAG']; ?></td>
                                        <td><?= $bp['QTYPB']; ?></td>
                                        <td><?= $bp['QTYPICKING']; ?></td>
                                        <td><?= $bp['QTYLPP']; ?></td>
                                        <td><?= $bp['QTYPLANO']; ?></td>
                                        <td><?= $bp['DISPLAY']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php elseif(empty($kodeomi) && empty($nopb)): ?>
                        <p class="text-warning fw-bold text-center text-uppercase">kode omi dan nomor pb wajib diisi</p>
                    <?php else: ?>
                        <p class="text-danger fw-bold text-center text-capitalize">data tidak ada !</p>
                    <?php endif; ?>
                   
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>