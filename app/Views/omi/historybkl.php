<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container mt-4">
    <div class="row d-flex justify-content-center">
        <div class="col-md-7 p-2 border border-2 border-secondary overflow-auto">
            <div id="bagian-form" class="mb-3"> 
            <form action="historybkl/tampilbkl" method="post">
            <h5 class="fw-bold d-block text-center">Rekap Data BKL OMI (H-1)</h5>
            <label for="tglawa" class="d-block">Tanggal Proses BKL :</label>
            <input type="date" name="tglawal" id="tglawal" class="w-25"> s/d <input type="date" name="tglakhir" id="tglakhir" class="w-25 mb-3">
            <br>
            <label for="supplier" class="d-block">Kode Supplier :</label>
            <select name="supplier" class="form-select w-75 mb-2 border border-secondary-subtle" aria-label="Default select example">
                <option value="all">ALL</option>
                <?php foreach($supplier as $sp): ?>
                    <option value="<?= $sp['BKL_KODESUPPLIER']; ?>"><?= $sp['BKL_KODESUPPLIER']." - ".$sp['SUP_NAMASUPPLIER']; ?></option>
                <?php endforeach; ?>
            </select>
            <label for="kodeomi" class="d-block">Kode OMI :</label>
            <input type="text" name="kodeomi" id="kodeomi" class="w-25 me-2">
            <button type="submit" name="btn" value="tampil" class="btn btn-outline-primary">Tampil</button>
            <button type="submit" name="btn" value="xls" class="btn btn-outline-success">Export XLS</button>
            <button type="reset" class="btn btn-outline-danger">Clear</button>
            </form>
            <hr class="border border-dark">
            </div>
            <div id="bagian-cek" class="mt-2">
                <form action="historybkl" method="get">
                    <h6 class="fw-bold d-block">Cek Data BKL</h6>
                    <label for="kodeomi2">KODE OMI :</label>
                    <input type="text" name="kodeomi2" id="kodeomi2" class="w-25">
                    <label for="nobukti">NO BUKTI :</label>
                    <input type="text" name="nobukti" id="nobukti" class="mb-3">
                    <button type="submit" class="btn btn-primary">Tampil</button>
                </form>
            </div>
            <?php if(!empty($cekbkl)): ?>
            <div id="tabel-hasil">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="bg-primary text-center text-light">OMI</th>
                            <th class="bg-primary text-center text-light">IDFILE</th>
                            <th class="bg-primary text-center text-light">TGLBUKTI</th>
                            <th class="bg-primary text-center text-light">NOBUKTI</th>
                            <th class="bg-primary text-center text-light">NODOC</th>
                            <th class="bg-primary text-center text-light">NILAI FAKTUR</th>
                            <th class="bg-primary text-center text-light">SUPPLIER</th>
                            <th class="bg-primary text-center text-light">TGLSTRUK</th>
                            <th class="bg-primary text-center text-light">RPHSTRUK</th>
                            <th class="bg-primary text-center text-light">BY</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($cekbkl as $cb): ?>
                            <tr>
                                <td><?= $cb['OMI']; ?></td>
                                <td><?= $cb['IDFILE']; ?></td>
                                <td><?= $cb['TGLBUKTI']; ?></td>
                                <td><?= $cb['NOBUKTI']; ?></td>
                                <td><?= $cb['NODOC']; ?></td>
                                <td><?= number_format($cb['NILAIFAKTUR']); ?></td>
                                <td><?= $cb['SUPPLIER']; ?></td>
                                <td><?= $cb['TGLSTRUK']; ?></td>
                                <td><?= number_format($cb['NILAISTRUKBKL']); ?></td>
                                <td><?= $cb['PROSESBY']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>