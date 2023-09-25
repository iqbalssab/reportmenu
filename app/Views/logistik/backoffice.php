<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="container-fluid mt-3 overflow-auto">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card w-60 mb-3 mx-auto" style="width: 600px;">
                <div class="card-header text-light" style="background-color: #0040ff;">
                    <h5 class="text-start fw-bold">Laporan Back Office</h5>
                </div>
                <div class="card-body">
                    <form method="get" action="tampildatabo" target="_blank" role="form" class="form-inline">
                        <?= csrf_field(); ?>
                        <table class="">
                            <tr>
                                <td>
                                    <label class="fw-bold text-end" style="font-size: 16px;">Tanggal Transaksi :</label>
                                </td>
                                <td>
                                    <input type="date" name="awal" id="awal" class="form-control" value="<?= old('awal'); ?>" style="font-size: 14px; width: 200px;">
                                </td>
                                <td>sd</td>
                                <td>
                                    <input type="date" name="akhir" id="akhir" class="form-control" value="<?= old('akhir'); ?>" style="font-size: 14px; width: 200px;">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <br><label class="fw-bold text-end" style="font-size: 16px;">Jenis Transaksi :</label>
                                </td>
                                <td colspan="3">
                                    <br><select class="form-select form-select-sm" name="jnstrx" aria-label="Small select example" style="font-size: 14px; width: 250px;">
                                        <option value="B">BPB (Penerimaan)</option>
                                        <option value="L">BPB Lain Lain</option>
                                        <option value="K">Retur Supplier</option>
                                        <option value="I">Terima TAC</option>
                                        <option value="O">Kirim TAC</option>
                                        <option value="X">MPP</option>
                                        <option value="H">Barang Hilang</option>
                                        <option value="P">Repacking</option>
                                        <option value="Z">Perubahan Status</option>
                                        <option value="F">Pemusnahan</option>
                                        <option value="RETUROMI">Retur OMI</option>
                                        <option value="SOIC">Reset SOIC</option>
                                        <option value="POBTBSUP">PO vs BTB per Supplier</option>
                                        <option value="POBTBSUPITEM">PO vs BTB per Supplier per Item</option>
                                    </select>
                                </td>
                                <tr>
                                    <td>
                                        <br><label class="fw-bold text-end" style="font-size: 16px;">Divisi :</label>
                                    </td>
                                    <td colspan="3">
                                        <br><select class="form-select form-select-sm" name="divisi" id="divisi" style="font-size: 14px; width: 250px;">
                                            <option value="All">All Divisi</option>
                                            <option value="1">1. Food</option>
                                            <option value="2">2. Non Food</option>
                                            <option value="3">3. GMS</option>
                                            <option value="4">4. Perishable</option>
                                            <option value="5">5. Counter</option>
                                            <option value="6">6. Fast Food</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <br><label class="fw-bold text-end" style="font-size: 16px;">Pilihan :</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class=" text-end" style="font-size: 14px;">-. PLU</label>
                                    </td>
                                    <td colspan="3">
                                        <input type="number" min="1" max="9999999" class="form-control" name="kodePLU" id="kodePLU" placeholder="PLU" style="font-size: 14px; width: 250px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class=" text-end" style="font-size: 14px;">-. Kode Supplier</label>
                                    </td>
                                    <td colspan="3">
                                        <input type="text" class="form-control" name="kodesup" id="kodesup" placeholder="Kode Supplier" style="font-size: 14px; width: 250px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <br><label class="fw-bold text-end" style="font-size: 16px;">Jenis Laporan :</label>
                                    </td>
                                    <td colspan="3">
                                        <br><select class="form-select form-select-sm" name="jenisLaporan" id="jenisLaporan" style="width: 250px;">
                                            <option value="0"></option>
                                            <option value="1">1. Laporan per Produk</option>
                                            <option value="1B">1B. Laporan per Produk Detail</option>
                                            <option value="2">2. Laporan per Supplier</option>
                                            <option value="3">3. Laporan per Divisi</option>
                                            <option value="4">4. Laporan per Departemen</option>
                                            <option value="5">5. Laporan per Kategori</option>
                                            <option value="6">6. Laporan per Hari</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4"><br><label class="fw-bold text-danger" style="font-size: 12px;">** Untuk RETUR OMI, RESET SO IC, dan PO vs BTB, kosongkan pilihan <b>Jenis Laporan</b>**</label></td>
                                </tr>
                                <tr>
                                    <td colspan="4"><hr></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <button type="submit" name="tombol" value="btnview" class="text-light btn w-100 mb-2 d-block fw-bold" style=" background-color: #6528F7;">Tampil</button>
                                    </td>
                                    <td></td>
                                    <td>
                                        <button type="submit" name="tombol" value="btnxls" class="text-light btn w-100 mb-2 d-block fw-bold" style="background-color: #00b300;">Download</button>
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!--  onclick="window.print()" -->
<?= $this->endSection(); ?>