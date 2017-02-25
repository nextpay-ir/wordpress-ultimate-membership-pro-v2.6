<?php 
	ihc_save_block_urls();//save/update block url
	ihc_delete_block_urls();//delete block url

	$posible_values = array('all'=> __('All', 'ihc'), 'reg'=> __('Registered Users', 'ihc'), 'unreg'=> __('Unregistered Users', 'ihc') );
	$levels = get_option('ihc_levels');
	if($levels){
		foreach($levels as $id=>$level){
			$posible_values[$id] = $level['name'];
		}
	}
	$pages = ihc_get_all_pages();//getting pages
?>
<div class="ihc-subtab-menu">
	<a class="ihc-subtab-menu-item" href="<?php echo $url.'&tab='.$tab.'&subtab=entire_url';?>"><?php _e('Entire URL', 'ihc');?></a>
	<a class="ihc-subtab-menu-item" href="<?php echo $url.'&tab='.$tab.'&subtab=keyword';?>"><?php _e('Based on Keywords', 'ihc');?></a>
</div>	

<?php 
	echo ihc_check_default_pages_set();//set default pages message
	echo ihc_check_payment_gateways();
?>
<div class="iump-page-title">Ultimate Membership Pro - 
							<span class="second-text">
								<?php _e('Blocked URL', 'ihc');?>
							</span>
</div>
<form method="post" action="" id="block_url_form">
	<?php 
		$subtab = 'entire_url';
		if (isset($_REQUEST['subtab'])) $subtab = $_REQUEST['subtab'];
			
		if ($subtab=='entire_url'){
					?>
			<div class="ihc-stuffbox">
				<h3><?php _e('Add new Restriction', 'ihc');?></h3>
				<div class="inside">
						<div class="iump-form-line">
							<label class="iump-labels-special"><?php _e('URL:', 'ihc');?></label>
							<input type="text" value="" name="ihc_block_url_entire-url" style="width: 500px;"/>				
						</div>
						
						<div class="iump-form-line iump-special-line">
							<label class="iump-labels-special"><?php _e('Target Users:', 'ihc');?></label>
							<select id="ihc-change-target-user-set" onChange="ihc_writeTagValue(this, '#ihc_block_url_entire-target_users', '#ihc_tags_field1', 'ihc_select_tag_' );" style="width: auto;">
								<option value="-1" selected>...</option>
								<?php 
									foreach($posible_values as $k=>$v){
									?>
										<option value="<?php echo $k;?>"><?php echo $v;?></option>	
									<?php 
									}
								?>
							</select>	
							<input type="hidden" value="" name="ihc_block_url_entire-target_users" id="ihc_block_url_entire-target_users" />
							<div id="ihc_tags_field1">
							</div>		
						</div>
						
						<div class="iump-form-line">
							<label class="iump-labels-special"><?php _e('Redirect to:', 'ihc');?></label> 
							<select name="ihc_block_url_entire-redirect">
								<option value="-1" selected >...</option>
								<?php 
									$pages = $pages + ihc_get_redirect_links_as_arr_for_select();
									if ($pages){
										foreach ($pages as $k=>$v){
											?>
												<option value="<?php echo $k;?>" ><?php echo $v;?></option>
											<?php 
										}						
									}
								?>
							</select>				
						</div>
				
					<input type="hidden" value="" name="delete_block_url" id="delete_block_url" />
					
					<div style="margin-top: 15px;">
						<input type="submit" value="<?php _e('Add New', 'ihc');?>" name="ihc_save_block_url" class="button button-primary button-large" />
					</div>				
				</div>	
			</div>	
			<?php 
				$data = get_option('ihc_block_url_entire');
				if ($data && count($data)){
					?>
					<div class="ihc-dashboard-form-wrap">
					<table class="wp-list-table widefat fixed tags" style="margin-bottom: 20px;">
						<thead>
						<tr>
							<th class="manage-column"><?php _e('Blocked URL', 'ihc');?></th>
							<th class="manage-column"><?php _e('Target Users', 'ihc');?></th>
							<th class="manage-column"><?php _e('Redirect To', 'ihc');?></th>
							<th class="manage-column" style="width:80px;  text-align: center;"><?php _e('Delete', 'ihc');?></th>
						</tr>
						</thead>
					<?php 
						$i = 1;
						foreach ($data as $key=>$arr){
						?>
						<tr class="<?php if ($i%2==0) echo 'alternate';?>">
							<td><?php echo $arr['url'];?></td>
							<td>							
								<?php
									if ($arr['target_users']){
										$levels = explode(',', $arr['target_users']);
										if ($levels && count($levels)){
											foreach ($levels as $val){
												if ($val!='reg' && $val!='unreg' && $val!='all'){
													$temp_data = ihc_get_level_by_id($val);
													if (!empty($temp_data['name'])){
														echo '<div class="level-type-list">' . $temp_data['name'] . '</div>';	
													}										
												} else {
													echo '<div class="level-type-list">' . $val . '</div>';	
												}
											}									
										}								
									}
								?>
							</td>
							<td style="color: #21759b; font-weight:bold;">
								<?php 
									if ($arr['redirect']!=-1){
										$redirect_link = ihc_get_redirect_link_by_label($arr['redirect']);
										if ($redirect_link){
											echo $redirect_link;
										} else {
											echo get_the_title($arr['redirect']);
										}										
									} else {									
										echo '-';						
									}
								?>
							</td>
							<td align="center">
								<i class="fa-ihc ihc-icon-remove-e" style="cursor:pointer;" onClick="jQuery('#delete_block_url').val('<?php echo $key;?>');jQuery('#block_url_form').submit();"></i>
							</td>
						</tr>
						<?php 
						}
						?>
					</table>
					</div>
					<?php 
				}
		} else {
			?>
				<div class="ihc-stuffbox">
					<h3><?php _e('Add new Restriction', 'ihc');?></h3>
					<div class="inside">
							<div class="iump-form-line">
								<label class="iump-labels-special"><?php _e('Keyword:', 'ihc');?></label>
								<input type="text" value="" name="ihc_block_url_word-url" />				
							</div>
			
							<div class="iump-form-line iump-special-line">
								<label class="iump-labels-special"><?php _e('Target Users:', 'ihc');?></label>
								<select id="ihc-change-target-user-set-regex" onChange="ihc_writeTagValue(this, '#ihc_block_url_word-target_users', '#ihc_tags_field2', 'ihc_select_tag_regex_' );" style="width: auto;">
									<option value="-1" selected>...</option>
									<?php 
										foreach($posible_values as $k=>$v){
										?>
											<option value="<?php echo $k;?>"><?php echo $v;?></option>	
										<?php 
										}
									?>
								</select>	
								<input type="hidden" value="" name="ihc_block_url_word-target_users" id="ihc_block_url_word-target_users" />
								<div id="ihc_tags_field2">
								</div>			
							</div>
							
							<div class="iump-form-line">
								<label class="iump-labels-special"><?php _e('Redirect to:', 'ihc');?></label> 
								<select name="ihc_block_url_word-redirect">
									<option value="-1" selected >...</option>
									<?php 
										$pages = $pages + ihc_get_redirect_links_as_arr_for_select();									
										if ($pages){
											foreach($pages as $k=>$v){
												?>
													<option value="<?php echo $k;?>"><?php echo $v;?></option>
												<?php 
											}						
										}
									?>
								</select>	
							</div>	
							<input type="hidden" value="" name="delete_block_regex" id="delete_block_regex" />		
						<div style="margin-top: 15px;">
							<input type="submit" value="<?php _e('Add New', 'ihc');?>" name="ihc_save_block_url" class="button button-primary button-large" />
						</div>		
					</div>
				</div>		
		<?php 				
				$data = get_option('ihc_block_url_word');
				if ($data && count($data)){
					?>
						<div class="ihc-dashboard-form-wrap">
						<table class="wp-list-table widefat fixed tags" style="margin-top: 20px" >
							<thead>
							<tr>
								<th class="manage-column"><?php _e('Blocked URL That Contains', 'ihc');?></th>
								<th class="manage-column"><?php _e('Target Users', 'ihc');?></th>
								<th class="manage-column"><?php _e('Redirect To', 'ihc');?></th>
								<th class="manage-column" style="width:80px;  text-align: center;"><?php _e('Delete', 'ihc');?></th>
							</tr>
							</thead>
						<?php 
							$i = 1;
							foreach ($data as $key=>$arr){
							?>
								<tr class="<?php if ($i%2==0) echo 'alternate';?>">
									<td><?php echo $arr['url'];?></td>
									<td>
										<?php
											if ($arr['target_users']){
												$levels = explode(',', $arr['target_users']);
												if ($levels && count($levels)){
													foreach ($levels as $val){
														if ($val!='reg' && $val!='unreg' && $val!='all'){
															$temp_data = ihc_get_level_by_id($val);
															if (!empty($temp_data['name'])){
																echo '<div class="level-type-list">' . $temp_data['name'] . '</div>';
															}																										
														} else {
															echo  '<div class="level-type-list">' . $val . '</div>';	
														}
													}									
												}								
											}
										?>
									</td>
									<td style="color: #21759b; font-weight:bold;">
										<?php 
											if ($arr['redirect']!=-1){
												$redirect_link = ihc_get_redirect_link_by_label($arr['redirect']);
												if ($redirect_link){
													echo $redirect_link;
												} else {
													echo get_the_title($arr['redirect']);
												}
											} else {
												echo '-';
											}
										?>
									</td>
									<td align="center">
										<i class="fa-ihc ihc-icon-remove-e" style="cursor:pointer;" onClick="jQuery('#delete_block_regex').val('<?php echo $key;?>');jQuery('#block_url_form').submit();"></i>
									</td>
								</tr>
							<?php 
							}
							?>
						</table>
						</div>
			<?php 
				}
		}	
	?>
</form>