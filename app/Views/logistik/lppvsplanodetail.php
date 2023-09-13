<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="container-fluid mt-3 overflow-auto">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mb-3 mx-auto" style="width: 550px;">
                <div class="card-header text-light" style="background-color: #0040ff;">
                    <h6 class="text-start fw-bold">LPP vs PLano Detail</h6>
                </div>
                <div class="card-body">
                    <form method="get" action="tampildatalppplanodetail" target="_blank">
                        <?= csrf_field(); ?>
                        <table class="table table-borderless">
							<tr>
								<td class="text-end mb-2" style="width: 200px;">
									<label class="fw-bold" for="txtdept" style="font-size: 15px;">Pilih Departement :</label>
								</td>
								<td colspan="2" style="width: 300px;" class=" mb-2">
									<select class="form-select form-select-sm" name="dept" aria-label="Small select example" style="font-size: 15px;" autofocus>
										<option value="">Pilih Divisi</option>
										<option value="1">1. FOOD</option>
										<option value="2">2. NON-FOOD</option>
										<option value="3">3. GENERAL MERCHANDISHING</option>
										<option value="4">4. PERISHABLE</option>
										<option value="5">5. COUNTER & PROMOTION</option>
										<option value="6">6. FAST FOOD</option>
										<option value="7">7. I-FASHION</option>
										<option value="8">8. I-TECH</option>
										<option value="9">9. I-TRONIK</option>
									</select>
								</td>
							</tr>
							<tr>
								<td class="text-end mb-2" style="font-size: 15px;">
									<label class="fw-bold" for="txtsort">Sort By :</label>
								</td>
								<td class=" mb-2" style="font-size: 15px;">
									<select class="form-select form-select-sm" name="sortby" id="sortby" aria-label="Small select example" style="width: 150px;">
										<option value="qty">QTY SELISIH</option>
										<option value="rph">RPH SELISIH</option>
									</select>
								</td>
								<td></td>
							</tr>
							<tr>
								<td class="text-end mb-2" style="font-size: 15px;">
									<label class="fw-bold" for="txtver">Versi :</label>
								</td>
								<td class=" mb-2" style="font-size: 15px;">
									<select class="form-select form-select-sm" name="ver" id="ver" aria-label="Small select example" style="width: 150px;">
										<option value="ver1">VERSI 1</option>
										<option value="ver2">VERSI 2</option>
									</select>
								</td>
								<td></td>
							</tr>
							<tr>
								<td></td>
								<td class=" mb-2">
									<button type="submit" name="tombol" value="btnlpp1" class="btn w-40 d-block text-light fw-bold" style="background-color: #6528F7; width: 150px;">Tampilkan</button>
								</td>
								<td class=" mb-2">
									<button type="submit" name="tombol" value="btnlpp2" class="btn w-40 d-block text-light fw-bold" style="background-color: #33cc33; width: 150px;">Download</button>
								</td>
							</tr>
						</table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
	function myFunction () {
		var vers = document.getElementsByName("ver").options[2];
		var sorts = document.getElementsByName("sortby")[0].options;
		if(vers == "ver2") {
			document.getElementsById("sortby").options[1].disabled = true;
			document.getElementsById("sortby").options[2].disabled = true;
		}
	}
</script>

<?= $this->endSection(); ?>