<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<?php $ip = "http://".$_SERVER['SERVER_NAME']."/reportmenu"; ?>
<div id="mainpage" class="container" style="background:#aed2ff;padding:5px;">
	<div class="col-md-12">
		<div style="background:#ffffff;color:#122473;border-top:24px solid #3085c3;border-bottom:24px solid #c41010;padding:1px;">
			<div class="row">
			<div class="col-md-3">
				<img src="<?= $ip; ?>/public/img/igr2.png" style="margin-top: 33px;" width="103%" class="ms-2  align-self-center" />
			</div>
			<div class="col-md-9">
				<div style="font-size:24px"><b><br></b></div>
				<div style="color:#122473;border:5px solid #3085c3;padding:3px 10px;font-size:15px;font-weight:700;text-align:center;">
				<div style="text-align:center;padding:4px;">
				<a style="color:c41010;font-weight:800; font-size:16px;" class="text-dark" href="http://192.168.240.190:81/login" target="_blank">
				<i class="fa-solid fa-right-to-bracket me-1"></i>
				Login ke IAS IGR-PWT
				</div>
				<div style="padding:0px;margin:0px;font-size:17px;"> 
				</div>
				</a>
				</div>
				<div style="color:#122473;border:5px solid #c41010;padding:3px 10px;font-size:15px;font-weight:700;text-align:center;">
				<div style="text-align:center;padding:4px;">
				<div style="padding:0px;margin:0px;font-size:17px;"><span class="glyphicon glyphicon-cloud-download""></span> <a style="color:c41010;font-weight:800;" href="#" target="_blank">DOWNLOAD DATA EDP </a></div>
					</div>
				</div>
			</div>
			</div>
		</div></div>
	
			
<div class="row" style="padding-top:10px">
			<div class="col-md-12">
				<div style="background:#aed2ff;color:black;border-bottom:3px solid #122473;">
				<div style="font-size:16px;font-weight:700;"><span class="glyphicon glyphicon-flash"></span>Link External</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<div style="background:#aed2ff;color:black;padding:10px;">
				<div style="font-size:14px;font-weight:700;">ESS</div>
				<div class="w-100" style="margin-bottom:2px"> 
				<a href="http://172.20.30.3/ess/homeportal" target="_blank" class='btn btn-xs text-center w-100 btn-block btn-success' style="text-align:left"><span class="glyphicon glyphicon-ok-circle"></span> 172.20.30.3 </a> 
				</div>
				<div style="margin-bottom:2px"> <a href="http://172.20.30.4/ess/homeportal" target="_blank" class='btn btn-xs w-100 text-center btn-block btn-info' style="text-align:left"><span class="glyphicon glyphicon-ok-circle"></span> 172.20.30.4 </a> </div>
				</div>
			</div>
			<div class="col-md-3">
				<div style="background:#aed2ff;color:black;padding:10px;">
				<div style="font-size:14px;font-weight:700;">TSM</div>
				<div style="margin-bottom:2px"> <a href="http://172.20.30.3/tsm" target="_blank" class='btn btn-xs w-100 text-center btn-block btn-success' style="text-align:left"><span class="glyphicon glyphicon-ok-circle"></span>  172.20.30.3</a> </div>
				<div style="margin-bottom:2px"> <a href="http://172.20.30.4/tsm" target="_blank" class='btn btn-xs w-100 text-center btn-block btn-info' style="text-align:left"><span class="glyphicon glyphicon-ok-circle"></span>  172.20.30.4</a> </div>
				</div>
			</div>
			<div class="col-md-3">
				<div style="background:#aed2ff;color:black;padding:10px;">
				<div style="font-size:14px;font-weight:700;">Report HO</div>
				<div style="margin-bottom:2px;"> <a href="http://172.31.16.71/Reports/Pages/Folder.aspx?ItemPath=%2fEISIGR&ViewMode=List" target="_blank" title="userpass:igr 1grHO" class='btn btn-xs w-100 text-center btn-block btn-success' style="text-align:left"><span class="glyphicon glyphicon-ok-circle"></span>  Eis Report</a> </div>
				<div style="margin-bottom:2px;"> <a href="http://172.20.28.17/monitoring-plano/public/laporan" target="_blank" class='btn btn-xs w-100 text-center btn-block btn-info' style="text-align:left"><span class="glyphicon glyphicon-ok-circle"></span>  Monitoring Plano</a> </div>
				</div>
			</div>
			<div class="col-md-3">
				<div style="background:#aed2ff;color:black;padding:10px;">
				<div style="font-size:14px;font-weight:700;">Web Online</div>
				<div style="margin-bottom:2px"> <a href="http://172.20.28.28/#/account/login" target="_blank" class='btn btn-xs w-100 text-center btn-block btn-success' style="text-align:left"><span class="glyphicon glyphicon-ok-circle"></span> Tara Efaktur Pajakku</a> </div>
				<div style="margin-bottom:2px"> <a <a href="https://vpn.klikbca.com/+CSCOE+/logon.html" target="_blank" class='btn btn-xs w-100 text-center btn-block btn-info' style="text-align:left"><span class="glyphicon glyphicon-ok-circle"></span> Klik BCA</a> </div>
				</div>
			</div>
		</div>
		<div style="height:10px;border-top:3px solid #122473;"></div>
		
			
		<div class="row">
			<div class="col-md-12">
				<div style="background:#2e4374;padding:50px;text-align:center;font-size:25zpx;color:#ffffff;">
				<?php
				echo "Indogrosir Purwokerto, ".date('d M Y');
				?>
				</div>
			</div>
	<div class="row">
	<div class="col-md-12">
		<div style="padding:5px;">
		<p style="font-size:11px;color:#666666;text-align:center;"><?= $title." | Modified by edp@igrpwt | ".date("Y");?></p>
		</div>
	</div>
</div>

		</div>
</div>

<?= $this->endSection(); ?>