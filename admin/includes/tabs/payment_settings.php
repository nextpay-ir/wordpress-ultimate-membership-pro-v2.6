<div class="ihc-subtab-menu">
	<a class="ihc-subtab-menu-item" href="<?php echo $url.'&tab='.$tab.'&subtab=nextpay';?>"><?php _e('Nextpay', 'ihc');?></a>
	<a class="ihc-subtab-menu-item" href="<?php echo $url.'&tab='.$tab.'&subtab=bank_transfer';?>"><?php _e('Bank transfer', 'ihc');?></a>
</div>
<?php 

if (empty($_GET['subtab'])){
	//listing payment methods
	$pages = ihc_get_all_pages();//getting pages
	echo ihc_check_default_pages_set();//set default pages message
	echo ihc_check_payment_gateways();
	?>
	<div class="iump-page-title">Ultimate Membership Pro - 
		<span class="second-text">
			<?php _e('Payments Services', 'ihc');?>
		</span>
	</div>
	<div class="iump-payment-list-wrapper">
		<div class="iump-payment-box-wrap">
		<?php $pay_stat = ihc_check_payment_status('nextpay'); ?>
		  <a href="<?php echo $url.'&tab='.$tab.'&subtab=nextpay';?>">
			<div class="iump-payment-box <?php echo $pay_stat['active']; ?>">
				<div class="iump-payment-box-title">nextpay</div>
				<div class="iump-payment-box-bottom">Settings: <span><?php echo $pay_stat['settings']; ?></span></div>
			</div>
		 </a>	
		</div>
		<div class="iump-payment-box-wrap">
		   <?php $pay_stat = ihc_check_payment_status('bank_transfer'); ?>
		   <a href="<?php echo $url.'&tab='.$tab.'&subtab=bank_transfer';?>"> 	
			<div class="iump-payment-box <?php echo $pay_stat['active']; ?>">
				<div class="iump-payment-box-title">Bank Transfer</div>
				<div class="iump-payment-box-bottom">Settings: <span><?php echo $pay_stat['settings']; ?></span></div>
			</div>
		   </a>	
		</div>					
	</div>
	<?php 
} else {
	switch ($_GET['subtab']){
		case 'nextpay':
			ihc_save_update_metas('payment_nextpay');//save update metas
			$meta_arr = ihc_return_meta_arr('payment_nextpay');//getting metas
			$pages = ihc_get_all_pages();//getting pages
			echo ihc_check_default_pages_set();//set default pages message
			echo ihc_check_payment_gateways();
			?>
			<div class="iump-page-title">Ultimate Membership Pro - 
				<span class="second-text">
					<?php _e('Payments Services', 'ihc');?>
				</span>
			</div>
			<form action="" method="post">
					<div class="ihc-stuffbox">
						<h3><?php _e('Nextpay Activation:', 'ihc');?></h3>
						<div class="inside">		
							<div class="iump-form-line">
								<h4><?php _e('Once all the Settings are properly done, the Payment Option can be activated to be available for further use.', 'ihc');?> </h4>
								<label class="iump_label_shiwtch" style="margin:10px 0 10px -10px;">
								<?php $checked = ($meta_arr['ihc_nextpay_status']) ? 'checked' : '';?>
								<input type="checkbox" class="iump-switch" onClick="iump_check_and_h(this, '#ihc_nextpay_status');" <?php echo $checked;?> />
								<div class="switch" style="display:inline-block;"></div>
							</label>
							<input type="hidden" value="<?php echo $meta_arr['ihc_nextpay_status'];?>" name="ihc_nextpay_status" id="ihc_nextpay_status" />
							</div>
							<div class="ihc-wrapp-submit-bttn iump-submit-form">
								<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save" class="button button-primary button-large" />
							</div>			
						</div>	
					</div>
					<div class="ihc-stuffbox">
					
						<h3><?php _e('Nextpay Settings:', 'ihc');?></h3>
						
						<div class="inside">
							<div class="iump-form-line">
								<label class="iump-labels"><?php _e('Api Key:', 'ihc');?></label> <input type="text" value="<?php echo $meta_arr['ihc_nextpay_key'];?>" name="ihc_nextpay_key" style="width: 300px;" />
							</div>

							<div class="iump-form-line iump-special-line">
								<label class="iump-labels-special"><?php _e('Redirect Page after Payment:', 'ihc');?></label>
								<select name="ihc_nextpay_return_page">
									<option value="-1" <?php if($meta_arr['ihc_nextpay_return_page']==-1)echo 'selected';?> >...</option>
									<?php 
										if($pages){
											foreach($pages as $k=>$v){
												?>
													<option value="<?php echo $k;?>" <?php if ($meta_arr['ihc_nextpay_return_page']==$k) echo 'selected';?> ><?php echo $v;?></option>
												<?php 
											}						
										}
									?>
								</select>
							</div>
							<div class="ihc-wrapp-submit-bttn iump-submit-form">
								<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save" class="button button-primary button-large" />
							</div>				
						</div>
					</div>
					
					<div class="ihc-stuffbox">
						<h3><?php _e('Multi-Payment Selection:', 'ihc');?></h3>
						<div class="inside">
							<div class="iump-form-line iump-no-border">
								<label class="iump-labels"><?php _e('Label:', 'ihc');?></label>
								<input type="text" name="ihc_nextpay_label" value="<?php echo $meta_arr['ihc_nextpay_label'];?>" />
							</div>
							
							<div class="iump-form-line iump-no-border">
								<label class="iump-labels"><?php _e('Order:', 'ihc');?></label>
								<input type="number" min="1" name="ihc_nextpay_select_order" value="<?php echo $meta_arr['ihc_nextpay_select_order'];?>" />
							</div>						
																																
							<div class="ihc-wrapp-submit-bttn iump-submit-form">
								<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save" class="button button-primary button-large" />
							</div>						
						</div>
					</div>						
					
			</form>
			<?php 		
		break;
			
		case 'bank_transfer':
			ihc_save_update_metas('payment_bank_transfer');//save update metas
			$meta_arr = ihc_return_meta_arr('payment_bank_transfer');//getting metas
			echo ihc_check_default_pages_set();//set default pages message
			echo ihc_check_payment_gateways();
			?>
				<div class="iump-page-title">Ultimate Membership Pro - 
					<span class="second-text">
						<?php _e('Bank Transfer Services', 'ihc');?>
					</span>
				</div>		
			<form action="" method="post">
				<div class="ihc-stuffbox">
					<h3><?php _e('Bank Transfer Activation:', 'ihc');?></h3>
					<div class="inside">		
						<div class="iump-form-line">
							<h4><?php _e('Once all the Settings are properly done, the Payment Option can be activated to be available for further use.', 'ihc');?> </h4>
							<label class="iump_label_shiwtch" style="margin:10px 0 10px -10px;">
								<?php $checked = ($meta_arr['ihc_bank_transfer_status']) ? 'checked' : '';?>
								<input type="checkbox" class="iump-switch" onClick="iump_check_and_h(this, '#ihc_bank_transfer_status');" <?php echo $checked;?> />
								<div class="switch" style="display:inline-block;"></div>
							</label>
							<input type="hidden" value="<?php echo $meta_arr['ihc_bank_transfer_status'];?>" name="ihc_bank_transfer_status" id="ihc_bank_transfer_status" /> 				
						</div>
						<div class="ihc-wrapp-submit-bttn iump-submit-form">
							<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save" class="button button-primary button-large" />
						</div>			
					</div>	
				</div>
				<div class="ihc-stuffbox">
					<h3><?php _e('Bank Transfer Message:', 'ihc');?></h3>
					<div class="inside">
							<div style="padding-left: 5px; width: 70%;display:inline-block;">
								<?php wp_editor( $meta_arr['ihc_bank_transfer_message'], 'ihc_bank_transfer_message', array('textarea_name'=>'ihc_bank_transfer_message', 'quicktags'=>TRUE) );?>
							</div>
							<div style="width: 25%; display: inline-block; vertical-align: top;margin-left: 10px; color: #333;">
								<div>{siteurl}</div>
								<div>{username}</div>
								<div>{first_name}</div>
								<div>{last_name}</div>
								<div>{user_id}</div>
								<div>{level_id}</div>
								<div>{level_name}</div>
								<div>{amount}</div>
								<div>{currency}</div>
							</div>																							
						<div class="ihc-wrapp-submit-bttn iump-submit-form">
							<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save" class="button button-primary button-large" />
						</div>	
					</div>			
				</div>
				
				<div class="ihc-stuffbox">
					<h3><?php _e('Multi-Payment Selection:', 'ihc');?></h3>
					<div class="inside">
						<div class="iump-form-line iump-no-border">
							<label class="iump-labels"><?php _e('Label:', 'ihc');?></label>
							<input type="text" name="ihc_bank_transfer_label" value="<?php echo $meta_arr['ihc_bank_transfer_label'];?>" />
						</div>
						
						<div class="iump-form-line iump-no-border">
							<label class="iump-labels"><?php _e('Order:', 'ihc');?></label>
							<input type="number" min="1" name="ihc_bank_transfer_select_order" value="<?php echo $meta_arr['ihc_bank_transfer_select_order'];?>" />
						</div>						
																															
						<div class="ihc-wrapp-submit-bttn iump-submit-form">
							<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save" class="button button-primary button-large" />
						</div>						
					</div>
				</div>
				
			</form>					
						
			<?php 		
			break;
	}

}//end of switch
