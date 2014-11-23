<script type="text/javascript">
	$(function(){
		$('#selectbln').val('<?php echo $bulan;?>');
		$('#selectthn').val('<?php echo $tahun;?>');
	});	
	function addPengeluaran(){
		$('#addpengeluaran').toggle('fast');
	}
	function addPemasukan(){
		$('#addpemasukan').toggle('fast');
	}
</script>
<div class="container">
	<div class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="#">Admin</a></li>
			<li class="active">Penjurnalan</li>
		</ol>
	</div>
</div>
<div class="container">
	<?php $this->load->view('admin/menu')?>
	<div class="col-md-10">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Barang</h3>
				</div>
				<div class="panel-body">
					<!-- menu -->
					<div class="col-md-6">
						<!-- <strong>Buku Kas per : <?php echo $bulan.'/'.$tahun;?></strong> -->
						<a title="menampilkan buku kas untuk bulan ini" href="<?php echo site_url('dashboard/jurnal')?>" class="btn btn-primary btn-sm">Hari Ini</a>
					</div>
					<div class="col-md-6">
						<span><form style="float:right" class="form-inline" action="<?php echo site_url('dashboard/jurnal?act=sort')?>" method="get">

							<div class="form-group">
								<label class="sr-only" for="exampleInputEmail2">Email address</label>
								<select id="selectbln" name="bln" class="input-sm form-control">
									<option value="1">Januari</option>
									<option value="2">Februari</option>
									<option value="3">Maret</option>
									<option value="4">April</option>
									<option value="5">Mei</option>
									<option value="6">Juni</option>
									<option value="7">Juli</option>
									<option value="8">Agustus</option>
									<option value="9">September</option>
									<option value="10">Oktober</option>
									<option value="11">Nopember</option>
									<option value="12">Desember</option>
								</select>
							</div>
							<div class="form-group">
								<label class="sr-only" for="exampleInputPassword2">Password</label>
								<select id="selectthn" name="thn" class="input-sm form-control">
									<option value="2014">2014</option>
									<option value="2015">2015</option>
									<option value="2016">2016</option>
									<option value="2017">2017</option>
									<option value="2018">2018</option>
								</select>
							</div>
							<button type="submit" class="btn btn-sm btn-default">show</button>
						</form></span>
					</div>
					<br/>
					<br/>
					<div class="row">
						<div class="col-md-12">
						<table class="table table-striped">
							<tr>
								<th>Tgl</th>
								<th>Keterangan</th>
								<th>Ref</th>
								<th>Debit</th>
								<th>Kredit</th>
							</tr>
							<?php $totaldebit=0;$totalkredit=0;?>
							<?php $jurnalmerge = array_merge($pemasukan_bln_ini,$pengeluaran_bln_ini);
							//sortir berdasarkan tanggal
							function sort_array_by_value($key, &$array) {
							    $sorter = array();
							    $ret = array();

							    reset($array);

							    foreach($array as $ii => $value) {
							        $sorter[$ii] = $value[$key];
							    }

							    asort($sorter);

							    foreach($sorter as $ii => $value) {
							        $ret[$ii] = $array[$ii];
							    }

							    $array = $ret;
							}
							sort_array_by_value('tanggal',$jurnalmerge);
							?>
							<?php foreach($jurnalmerge as $jurnal):?>								
								<tr>
									<td><?php echo date('d',strtotime($jurnal['tanggal']));?></td>
									<td>
										<?php if($jurnal['det']=='masuk') {
											if($jurnal['status']=='piutang') { //jika masih piutang
												echo 'Kas<br/>';
												echo '<span style="padding-left:2em">Piutang '.$jurnal['keterangan'].'</span><br/>';
												echo '<span style="padding-left:2em">Pendapatan</span><br/>';
											} else {
												//jika sudah lunas
												echo 'Kas<br/><span style="padding-left:2em"> '.$jurnal['keterangan'].'</span>';
											}											
										} else if($jurnal['det']=='keluar'){ //keluar
											if($jurnal['status']=='hutang'){//jika masih hutang
												echo 'Pasokan : '.$jurnal['keterangan'].'<br/>';
												echo '<span style="padding-left:2em">Kas<span/><br/>';
												echo '<span style="padding-left:2em">Hutang</span>';												
											} else { //hutang sudah lunas
												echo $jurnal['keterangan'].'<br/><span style="padding-left:2em">Kas</span>';
											}
										}?>
									</td>
									<td>
										
									</td>
									<td>
										<?php
											if($jurnal['det']=='masuk') { //masuk
												if($jurnal['status']=='piutang'){ //jika masih piutang
													//cek detail transaksi
													$this->db->where('id_transaksi',$jurnal['id_transaksi']);
													$transaksi = $this->db->get('transaksi');
													$transaksi = $transaksi->row_array();
													echo 'Rp '.$this->cart->format_number($transaksi['bayar']).',-<br/>';
													echo 'Rp '.$this->cart->format_number($jurnal['rp']-$transaksi['bayar']).',-<br/>';
												} else { //sudah lunas
													echo 'Rp '.$this->cart->format_number($jurnal['rp']).',-';
												}
											} else if($jurnal['det']=='keluar'){ //keluar
												if($jurnal['status']=='hutang'){//jika masih hutang													
													echo 'Rp '.$this->cart->format_number($jurnal['rp']).',-';
												} else { //hutang sudah lunas
													echo 'Rp '.$this->cart->format_number($jurnal['rp']).',-';
												}
											} else {
												echo 'Rp '.$this->cart->format_number($jurnal['rp']).',-';
											}																					
											$totaldebit = $totaldebit + $jurnal['rp']; ?>
									</td>
									<td>
										<?php
											if($jurnal['det']=='masuk') {
												if($jurnal['status']=='piutang'){ //jika masih piutang
													echo '<br/><br/>';
													echo 'Rp '.$this->cart->format_number($jurnal['rp']).',-';	
												} else { //sudah lunas
													echo '<br/>Rp '.$this->cart->format_number($jurnal['rp']).',-';
												}
											} else if($jurnal['det']=='keluar'){ //keluar
												if($jurnal['status']=='hutang'){//jika masih hutang
													$this->db->where('id_pasokan',$jurnal['id_pasokan']);
													$pasokan = $this->db->get('pasokan');
													$pasokan = $pasokan->row_array();
													echo '<br/>Rp '.$this->cart->format_number($pasokan['rp_bayar']).',-';
													echo '<br/>Rp '.$this->cart->format_number($jurnal['rp'] - $pasokan['rp_bayar']).',-';
												} else { //hutang sudah lunas
													echo '<br/>Rp '.$this->cart->format_number($jurnal['rp']).',-';		
												}
											} else {
												echo '<br/>Rp '.$this->cart->format_number($jurnal['rp']).',-';		
											}															
											$totalkredit = $totalkredit + $jurnal['rp'];?>
									</td>
								</tr>																							
							<?php endforeach;?>
							<?php
							$akhir31 = array(1,3,5,7,8,10,12);
							//perhitungan gaji pegawai
							if($bulan == 2 && $tahun%4 == 0) {
								$tgl = 29;
							} else if($bulan == 2 && $tahun%4 != 0) {
								$tgl = 28;
							} else if(in_array($bulan, $akhir31)) {
								$tgl = 31;
							} else {
								$tgl = 30;
							} 
							?>
							<?php if($total_gaji != 0){?>
							<tr>
								<td><?php echo $tgl?></td>
								<td>Pemberian gaji karyawan<br/><span style="padding-left:2em">Kas</span></td>
								<td></td>
								<td><?php echo $this->cart->format_number($total_gaji * 30000);?></td>
								<td><?php echo '<br/>'.$this->cart->format_number($total_gaji * 30000);?></td>
							</tr>
							<?php } ?>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td><strong>Rp <?php echo $this->cart->format_number($totaldebit);?> </strong></td>
								<td><strong>Rp <?php echo $this->cart->format_number($totalkredit);?> </strong></td>
							</tr>
						</table>
						</div>
					</div>
					<div class="col-md-12">
						<br/><br/>
						<hr/>
					</div>				
				</div>
			</div>

		</div>
	</div>
</div>