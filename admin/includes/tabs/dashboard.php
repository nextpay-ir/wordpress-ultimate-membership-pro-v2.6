<?php 
$currency = get_option('ihc_currency');
?>
<div style="width: 97%">
	<div class="ihc-dashboard-title">
		Ultimate Membership Pro - 
		<span class="second-text">
			<?php _e('Dashboard Overall', 'ihc');?>
		</span>
	</div>
	
	<div class="row-fluid">
	
		<div class="span3">
			<div class="ihc-dashboard-top-box">
				<i class="fa-ihc fa-ihc-dashboard ihc-dashboard-color-1 fa-users-ihc"></i>
				<div class="stats">
					<h4>
						<strong><?php echo ihc_get_users_counts(1);?></strong>
					</h4>
					<span><?php _e('Total Users', 'ihc');?></span>
				</div>
			</div>
		</div>
		
		<div class="span3">
			<div class="ihc-dashboard-top-box">
				<i class="fa-ihc fa-ihc-dashboard ihc-dashboard-color-2 fa-levels-ihc"></i>
				<div class="stats">
					<h4><strong><?php _e('Top Level', 'ihc');?></strong></h4>
					<span>
							<?php 
								$top_level = ihc_get_top_level();
								if ($top_level) echo $top_level;
								else _e('N/A', 'ihc');
							?>					
					</span>
				</div>
			</div>
		</div>
		
		<div class="span3">
			<div class="ihc-dashboard-top-box">
				<i class="fa-ihc fa-ihc-dashboard ihc-dashboard-color-3 fa-payments-ihc"></i>
				<div class="stats">
					<h4>
						<strong>
							<?php 
								echo ihc_get_transactions_count();
							?>			
						</strong>
					</h4>
					<span><?php _e('Total Number of Transactions', 'ihc');?></span>
				</div>
			</div>
		</div>
	
		<div class="span3">
			<div class="ihc-dashboard-top-box">
				<i class="fa-ihc fa-ihc-dashboard ihc-dashboard-color-4 fa-payment_settings-ihc"></i>
				<div class="stats">
					<h4>
						<strong>
							<?php 
								echo ihc_get_total_amount() . ' ' . $currency;
							?>			
						</strong>
					</h4>
					<span><?php _e('Total Amount of Transactions', 'ihc');?></span>
				</div>		
			</div>
		</div>		
		
	</div>

	<?php 
		$levels_arr =  ihc_get_level_user_counts();
		$levels_by_transactions = ihc_get_levels_top_by_transactions();
	?>	
	<div class="row-fluid" style="height: 380px;">

				<div class="span8">
					<div class="ihc-box-content-dashboard">
						<div style="padding: 20px;">
							<div><?php _e('Total Members per Level', 'ihc');?></div>		
						<?php if ($levels_arr){ ?>
							<div id="ihc-chart-1" class='ihc-flot'></div>
						<?php }else { ?>
							<div><h3><?php _e('Not enough data available.', 'ihc');?></h3></div>
						<?php }?>
						</div>
					</div>
				</div>
		<div class="span4">

					<div class="ihc-box-content-dashboard">
						<div style="padding: 20px;">
							<div><?php _e('Levels By Transactions', 'ihc');?></div>
							<?php if ($levels_by_transactions){ ?>
								<div id="ihc-pie-1" class='ihc-flot'></div>
							<?php }else { ?>
								<div><h3><?php _e('Not enough data available.', 'ihc');?></h3></div>
						<?php }?>	
						</div>
					</div>
		</div>
	</div>
	
	<div class="row-fluid">
		<div class="span6">
			<div class="ihc-box-content-dashboard ihc-color-box-green">
				<div style="padding: 20px;">
					<div class="info-title">
						<i class="fa-ihc fa-list-ihc"></i>
						<div style="display:inline-block;">
							<?php _e('Last 5 Registered Users:', 'ihc');?>
						</div>
					</div>
					<div class="ihc-pops-list">
					<?php 
						$users = ihc_get_last_five_users();
						if ($users){
						?>
							<ul>
								<?php 
								foreach ($users as $user){
									?>
										<li>
											<i class="fa-ihc ihc-icon-pop-list"></i>
											<div class="list-cont">
												<a href="<?php echo $url.'&tab=users&ihc-edit-user='.$user->data->ID;?>">
													<?php 
														echo $user->data->user_login;
													?>
												</a>													
											</div>
											<span>
												<?php 
													if (isset($user->user_registered) && $user->user_registered){
														?>
														<?php _e('Register on', 'ihc');?> <?php echo $user->user_registered;?>
														<?php 
													}	
												?>								
											</span>
										</li>
									<?php 
									}
								?>
							</ul>
						<?php 			
						} else { ?>
							<div><h3 style="color:#fff;"><?php _e('Not Registered Users available.', 'ihc');?></h3></div>
					<?php }
					?>	
					</div>				
				</div>
			</div>		
		</div>	
		<?php 
			$approved_users = ihc_get_users_counts(3);
			$pending_users = ihc_get_users_counts(2);
				?>
				<div class="span6">
					<div class="ihc-box-content-dashboard">
						<div style="padding: 20px;">
							<div class="info-title">
								<i class="fa-ihc fa-list-ihc"></i>
								<div style="display:inline-block;">
									<?php _e('Last 5 Transactions:', 'ihc');?>
								</div>
							</div>	
								<div class="ihc-pops-list">
									<ul>
									<?php 
									if ($approved_users || $pending_users){
										$last_five = ihc_get_last_five_transactions();
										if ($last_five){
											foreach ($last_five as $obj){
												?>
												<li>
													<i class="fa-ihc ihc-icon-pop-list-black"></i>
													<div class="list-cont"><?php 
															$user_info = get_userdata($obj->u_id);
															$first_name = get_user_meta($obj->u_id, 'first_name', true);
															$last_name = get_user_meta($obj->u_id, 'last_name', true);
															if($first_name || $last_name){
																echo $first_name .' '.$last_name;
															}else{
																if (isset($user_info->user_nicename)){
																	echo $user_info->user_nicename;
																}else{
																	_e('Unknown Name', 'ihc');
																}
															}	
															$payment_data = json_decode($obj->payment_data, true);
															if (!empty($payment_data['mc_gross'])){
																echo ' ' . $payment_data['mc_gross'].$payment_data['mc_currency'];
															} 
															
														?></div>
													<span style="color: #c9c9c9;"><?php _e('Payment on', 'ihc');?> <?php echo $obj->paydate;?></span>												
												</li>
												<?php 									
											}
										} else { echo '<div><h3>' . __('Not enough data available.', 'ihc') . '</h3></div>'; }
									}else { echo '<div><h3>' . __('Not enough data available.', 'ihc') . '</h3></div>'; }
									?>									
									</ul>
							</div>													
						</div>
					</div>
				</div>

	</div>
	
</div>


<script>

<?php 
	if ($levels_arr){
		?>
		if (jQuery("#ihc-chart-1").length > 0) {
			var ihc_ticks = [];
			var ihc_chart_stats = [];
		<?php 
		$i = 0;
		foreach ($levels_arr as $k=>$v){
			echo 'ihc_ticks['.$i.']=['.$i.', "'.$k.'"];';
			echo 'ihc_chart_stats['.$i.']={0:'.$i.',1:'.$v.'};';
			$i++;
		}
		if (count($levels_arr)<10){
			for($j=count($levels_arr);$j<11;$j++){
			echo 'ihc_ticks['.$i.']=['.$i.', ""];';
			echo 'ihc_chart_stats['.$i.']={0:'.$i.',1:0};';
			$i++;
			}
		}
		?>
		var options = {
			    bars: { show: true, barWidth: 0.75, fillColor: '#7ebffc', lineWidth: 0 },
				grid: { hoverable: false, backgroundColor: "#fff", minBorderMargin: 0,  borderWidth: {top: 0, right: 0, bottom: 1, left: 1}, borderColor: "#aaa" },
				xaxis: { ticks: ihc_ticks, tickLength:0 },
				yaxis: { tickDecimals: 0, tickColor: "#eee"},
				legend: {
				    show: true,
				    position: "ne",
				    }
			  };		
			jQuery.plot(jQuery("#ihc-chart-1"), [ {
				color: "#669ccf",	
				data: ihc_chart_stats,
			} ], options
			);
		}
		<?php 
	}
	
	if ($levels_by_transactions){
		?>
			if (jQuery("#ihc-pie-1").length > 0) {
				var d = [];
		<?php 
		$i = 0;
		
		///generate colors for pie
		$colors = array('#fa8564', '#9972b5', '#1fb5ac', '#ffc333', '#466baf', '#FDB45C');
		if (count($levels_by_transactions)>count($colors)){
			for($j=count($colors);$j<count($levels_by_transactions);$j++){
				$color = ihc_generate_color();
				if (!in_array($color, $colors)){
					$colors[] = $color;
				} else {
					do {
						$color = ihc_generate_color();
					} while (in_array($color, $colors));
					$colors[] = $color;
				}				
			}	
		}

		foreach ($levels_by_transactions as $k=>$v){
			?>
				d[<?php echo $i;?>] = { label: '<?php echo $k;?>', data: "<?php echo $v;?>",  color: '<?php echo $colors[$i];?>'};//, color: 'if(isset($colors[$i])) echo $colors[$i];'
			<?php 
			$i++;
		}
		?>
			jQuery.plot(jQuery("#ihc-pie-1"), d, {
				series: {
			        pie: {
			            show: true,			              
			        },
			    }, 			    	
			});		
			}
		<?php 
	}
?>
</script>
<?php 

