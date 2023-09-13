<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container mt-3">
    <div class="row d-flex justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header bg-primary">
                    <p class="fw-bolder text-center text-light fs-5">SERVICE LEVEL PEMENUHAN PBOMI</p>
                </div>
                <div class="card-body">
                    <div class="row ">
                        <div class="col-md-4">
                            <label for="tglawal" class="d-block fw-bold mb-3">Periode Tanggal</label>
                            <label for="jenis" class="d-block fw-bold mb-4">Jenis Laporan</label>
                            <label for="omi" class="d-block fw-bold mb-3">Kode OMI</label>
                            <label for="plu" class="d-block fw-bold mb-3">Kode PLU</label>
                        </div>
                        <div class="col">
                            <form action="slomi/tampilslomi" method="post" target="_blank">
                            <input type="date" name="tglawal" id="tglawal" style="width: 40%;">
                            s/d
                            <input type="date" name="tglakhir" id="tglakhir" style="width: 40%;" class="mb-2">
                            <select name="jenis" class="form-select border border-secondary mb-2" aria-label="Default select example">
                                <option value='rekaptoko'>Rekap Order per Toko</option>
                                <option value='rekapnopb'>Rekap Order per Nomor PB</option>
                                <option value='listorder'>Item List Order</option>
                                <option value='detailplu'>Detail Order per PLU</option>
                                <option value='itemrefund'>Item Dikembalikan</option>
                                <option value='-'>---</option>
                                <option value='tolakanlengkap'>Item Tolakan - Lengkap</option>
                                <option value='tolakanplu'>Item Tolakan - Subtotal per PLU</option>
                                <option value='tidaktersuplai'>Item Tidak Ter<i>supply</i></option>
                            </select>
                            <input type="text" name="omi" id="omi" class="mb-3">
                            <input type="text" name="plu" id="plu" class="mb-3">
                            <small style="font-size: 12px;" class="font-monospace text-sm-left">* hanya untuk detail perPLU</small>
                            <br>
                            <button type="submit" name="btn" value="tampil" class="btn btn-primary"><i class="fa-solid fa-file me-1"></i>Tampil</button>
                            <button type="submit" name="btn" value="xls" class="btn btn-success"><i class="fa-solid fa-download me-1"></i>Export XLS</button>
                            </form>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col border border-secondary bg-warning-subtle">
                            <p class="text-danger fw-bold text-center fs-6">WARNING !!!</p>
                            <p class="text-danger text-center">Gunakan sumber daya dengan bijak. Untuk meminimalisir kesalahan sistem hindari penarikan banyak data pada jam-jam sibuk!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>