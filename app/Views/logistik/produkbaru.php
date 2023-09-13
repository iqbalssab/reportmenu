<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="container-fluid mt-3 overflow-auto">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card w-40 mb-3 mx-auto">
                <div class="card-header text-center " style="background-color: #0040ff;">
                    <h5 class="text-light fw-bold">TARIK DATA PRODUK BARU</h5>
                    <a class="text-warning fw-bold" style="font-size: 14px;">** Data Terbaru / Hari ini **</a>
                </div>
                <div class="card-body">
                    <form method="post" action="tampilproduk" target="_blank" role="form" class="form-inline">
                        <?= csrf_field(); ?>
                        <table style="width: 100%;">
                            <thead>
                                <tr>
                                    <th colspan="3" class="text-center">Berdasarkan Per Divisi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td></td></tr>
                                <tr>
                                    <td class="text-end">DIVISI :</td>
                                    <td colspan="2"> 
                                        <select class="form-select form-select-sm mx-auto" name="divisi" aria-label="Small select example" style="font-size: 15px; width: 250px;">
                                            <option value="0">-- ALL --</option>
                                            <option value="1">1 - FOOD</option>
                                            <option value="2">2 - NON FOOD</option>
                                            <option value="3">3 - GMS</option>
                                            <option value="4">4 - PERISHABLE</option>
                                            <option value="5">5 - COUNTER & PROMOTION</option>
                                            <option value="6">6 - FAST FOOD</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end">TAG :</td>
                                    <td colspan="2"> 
                                        <select class="form-select form-select-sm mx-auto" name="tag" aria-label="Small select example" style="font-size: 15px; width: 250px;">
                                            <option value="0">-- ALL --</option>
                                            <option value="1">DI LUAR TAG [HOAXNT]</option>
                                            <option value="2">HANYA TAG [HOAXNT]</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3"><br>
                                        <button type="submit" name="tombol" value="btndiv" class="btn w-50 mb-2 mx-auto d-block text-light fw-bold" style="background-color: #00b300;">Download Data</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                    <hr>
                    <form method="post" action="tampilprodukbtb" target="_blank" role="form" class="form-inline">
                        <?= csrf_field(); ?>
                        <table style="width: 100%;" class=" text-center">
                            <thead>
                                <tr>
                                    <th colspan="3">Berdasarkan Periode BPB</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Tanggal Awal :</td>
                                    <td colspan="2">
                                        <input type="date" name="awal" id="awal" class="w-80 form-control" value="<?= old('awal'); ?>" style="font-size: 15px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tanggal Akhir :</td>
                                    <td colspan="2">
                                        <input type="date" name="akhir" id="akhir" class="w-100 form-control" value="<?= old('akhir'); ?>" style="font-size: 15px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3"><br>
                                        <button type="submit" name="tombol" value="btnbpb" class="btn w-50 mb-2 mx-auto d-block text-light fw-bold" style="background-color: #00b300;">Download Data</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>