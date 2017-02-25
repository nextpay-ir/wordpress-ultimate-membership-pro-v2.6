<div class="ihc-subtab-menu">
	<a class="ihc-subtab-menu-item" href="<?php echo $url.'&tab='.$tab.'&ihc-new-user=true';?>"><?php _e('Add New User', 'ihc');?></a>
	<a class="ihc-subtab-menu-item" href="<?php echo $url.'&tab='.$tab;?>"><?php _e('Manage Users', 'ihc');?></a>	
</div>
<?php 

//delete
ihc_delete_users();

$form = '';
include_once IHC_PATH . 'classes/UserAddEdit.class.php';
$obj = new UserAddEdit;
if (isset($_REQUEST['Update'])){
	//update
	$args = array(
			'type' => 'edit',
			'tos' => false,
			'captcha' => false,
			'action' => $url . '&tab=users',
			'is_public' => false,
			'user_id' => $_REQUEST['user_id'],
	);
	$obj->setVariable($args);//setting the object variables
	$obj->save_update_user();
	
} else if (isset($_REQUEST['Submit'])){
	//create
	$args = array(
			'user_id' => false,
			'type' => 'create',
			'tos' => false,
			'captcha' => false,
			'action' => $url . '&tab=users',
			'is_public' => false,
	);
	$obj->setVariable($args);//setting the object variables
	$obj->save_update_user();	
}

$obj_form = new UserAddEdit;
if (isset($_REQUEST['ihc-edit-user'])){
	///EDIT USER FORM
	$args = array(
			'user_id' => $_REQUEST['ihc-edit-user'],
			'type' => 'edit',
			'tos' => false,
			'captcha' => false,
			'action' => $url . '&tab=users',
			'is_public' => false,
	);
	$obj_form->setVariable($args);//setting the object variables
	$form = $obj_form->form();
} else {
	/// CREATE USER FORM
	$args = array(
			'user_id' => false,
			'type' => 'create',
			'tos' => false,
			'captcha' => false,
			'action' => $url . '&tab=users',
			'is_public' => false,
	);	
	$obj_form->setVariable($args);//setting the object variables
	$form = $obj_form->form();	
}

global $ihc_error_register;
if (!empty($ihc_error_register) && count($ihc_error_register)>0){
	echo '<div class="ihc-wrapp-the-errors">';
	foreach ($ihc_error_register as $key=>$err){
		echo __('Field ', 'ihc') . $key . ': ' . $err;	
	}
	echo '</div>';
}


//set default pages message
echo ihc_check_default_pages_set();
echo ihc_check_payment_gateways();

	if (isset($_REQUEST['ihc-edit-user']) || isset($_REQUEST['ihc-new-user'])){
		//add edit user
		if (isset($_REQUEST['ihc-edit-user'])){
			?>
			<script>
				jQuery(document).ready(function() {
				    jQuery('.expire_input_text, .start_input_text').datepicker({
				        dateFormat : 'yy-mm-dd',
				        onSelect: function(datetext){
				            var d = new Date();
				            datetext = datetext+" "+d.getHours()+":"+ihc_add_zero(d.getMinutes())+":"+ihc_add_zero(d.getSeconds());
				            jQuery(this).val(datetext);
				        }
				    });
				});
			</script>
			<?php 
		}
		?>
			<div class="ihc-stuffbox" style="margin-top: 20px;">
				<h3><?php _e('Add/Edit User', 'ihc');?></h3>
				<div class="inside">
					<?php echo $form;?>
				</div>
			</div>		
		<?php 		
	} else {
		
?>
<div class="iump-wrapper">
	<div id="col-right" style="vertical-align:top; width: 100%;">
		
		<a href="<?php echo $url.'&tab=users&ihc-new-user=true';?>" class="indeed-add-new-like-wp">
			<?php _e('Add New', 'ihc');?>
		</a>
		<div class="iump-page-title">Ultimate Membership Pro - 
							<span class="second-text">
								<?php _e('MemberShip Users', 'ihc');?>
							</span>
						</div>
		<div class="ihc-special-buttons-users">
			<div class="ihc-special-button" onclick="ihc_show_hide('.ihc-filters-wrapper');"><i class="fa-ihc fa-export-csv"></i>Add Filters</div>
			<div class="ihc-special-button" style="background-color:#38cbcb;" onClick="ihc_make_user_csv();"><i class="fa-ihc fa-export-csv"></i>Export CSV</div>
			<div class="ihc-hidden-download-link" style="display: none;float: right; padding: 20px 20px 0px 0px;"><a href="" target="_blank"><?php _e("Click on this if download doesn't start automatically in 20 seconds!");?></a></div>		
			<div class="ihc-clear"></div>
		</div>

		<?php
		$hidded = 'style="display:none;"';
		if(isset($_REQUEST['search_user']) || isset($_REQUEST['filter_role']) || isset($_REQUEST['ordertype_level']) || isset($_REQUEST['orderby_user']) || isset($_REQUEST['ordertype_user']) ) $hidded ='';
		?>		
		<div class="ihc-filters-wrapper" <?php echo $hidded; ?>>
			<form method="post" action="">
			<div class="row-fluid">
				<div class="span4">
					<div class="iump-form-line iump-no-border">
						<input name="search_user" type="text" value="<?php echo (isset($_REQUEST['search_user']) ? $_REQUEST['search_user'] : '') ?>" placeholder="<?php _e('Search by Name or Username', 'ihc');?>..."/>
					</div>
				</div>
				<div class="span2">
					<div class="iump-form-line iump-no-border">
						<select name="filter_role" style="min-width:70%;">
							<option value="">...</option>
							<?php 
										$filter_roles = ihc_get_wp_roles_list();
										if ($filter_roles){
											foreach ($filter_roles as $k=>$v){
												$selected = (isset($_REQUEST['filter_role']) && $_REQUEST['filter_role']==$k) ? 'selected' : '';
												?>
													<option value="<?php echo $k;?>" <?php echo $selected;?> ><?php echo $v;?></option>
												<?php 
											}	
										}
									?>
						</select>
					</div>
				</div>
				<div class="span2">
					<div class="iump-form-line iump-no-border">
						<select name="ordertype_level">
							<option value="">...</option>
							<?php 
								$levels_arr = get_option('ihc_levels');
								if ($levels_arr!==FALSE){
									foreach ($levels_arr as $k=>$v){
										$selected = (isset($_REQUEST['ordertype_level']) && $_REQUEST['ordertype_level']==$k) ? 'selected' : '';
										?>
										<option value="<?php echo $k;?>" <?php echo $selected;?> ><?php echo $v['name'];?></option>
										<?php 
									}
								}
							?>
						</select>				
					</div>
				</div>
				<div class="span3">
					<div class="iump-form-line iump-no-border">
						<select name="orderby_user">
							<option value="display_name" <?php echo (isset($_REQUEST['orderby_user']) && $_REQUEST['orderby_user']=='display_name') ? 'selected' : ''; ?>><?php _e('Name', 'ihc');?></option>
							<option value="name" <?php echo (isset($_REQUEST['orderby_user']) && $_REQUEST['orderby_user']=='name') ? 'selected' : ''; ?>><?php _e('Username', 'ihc');?></option>
							<option value="email" <?php echo (isset($_REQUEST['orderby_user']) && $_REQUEST['orderby_user']=='email') ? 'selected' : ''; ?>><?php _e('Email', 'ihc');?></option>
							<option value="ID" <?php echo (isset($_REQUEST['orderby_user']) && $_REQUEST['orderby_user']=='ID') ? 'selected' : ''; ?>><?php _e('ID', 'ihc');?></option>
							<option value="registered" <?php echo (isset($_REQUEST['orderby_user']) && $_REQUEST['orderby_user']=='registered') ? 'selected' : ''; ?>><?php _e('Registered Time', 'ihc');?></option>
						</select>
						<select name="ordertype_user">
							<option value="ASC" <?php echo (isset($_REQUEST['ordertype_user']) && $_REQUEST['ordertype_user']=='ASC') ? 'selected' : ''; ?>><?php _e('ASC', 'ihc');?></option>
							<option value="DESC" <?php echo (isset($_REQUEST['ordertype_user']) && $_REQUEST['ordertype_user']=='DESC') ? 'selected' : ''; ?>><?php _e('DESC', 'ihc');?></option>
						</select>
					</div>
				</div>
				<div class="span1" style="padding:30px 10px 0 0;">
					<input type="submit" value="Search" name="search" class="button button-primary button-large">
				</div>
			</div>
			</form>
		</div>		
		<form method="post" action="" style="margin-top:20px;">
			<?php 
				$limit = 25;
				if(isset($_REQUEST['ihc_limit'])) $limit = $_REQUEST['ihc_limit'];
				$start = 0;
				if(isset($_REQUEST['ihcdu_page'])){
					$pg = $_REQUEST['ihcdu_page'] - 1;
					$start = (int)$pg * $limit;
				}
				
				$filter_role = '';
				if(isset($_REQUEST['filter_role']))
					$filter_role = $_REQUEST['filter_role'];
				
				$orderby = 'registered';
				if(isset($_REQUEST['orderby_user']))
					$orderby = $_REQUEST['orderby_user'];
				
				$ordertype = 'DESC';
				if(isset($_REQUEST['ordertype_user']))
					$ordertype = $_REQUEST['ordertype_user'];
					
				$search_term = '';
				if(isset($_REQUEST['search_user']))
					$search_term = $_REQUEST['search_user'];
				
			
			?>
			<div>
				<?php 					
					global $wpdb;
					$current_time = time();
					if ($search_term != ''){
						function ihc_pre_user_query($user_query){
							$user_query->query_fields = 'DISTINCT ' . $user_query->query_fields;
						}
						add_action( 'pre_user_query', 'ihc_pre_user_query');
						global $wp_query;
						$users_obj = new WP_User_Query(array(
														    'role' => $filter_role,
															'meta_query' => array(
																'relation' => 'AND',
														        array(
														            'key' => $wpdb->get_blog_prefix() . 'capabilities',
														            'value' => 'administrator',
														            'compare' => 'NOT LIKE'
														        ),
																array(
																	'relation' => 'OR',
																	array(
																		'key'     => 'first_name',
																		'value'   => $search_term,
																		'compare' => 'LIKE'
																	),
																	array(
																		'key'     => 'last_name',
																		'value'   => $search_term,
																		'compare' => 'LIKE'
																	),
																	array(
																		'key' => 'nickname',
																		'value' => $search_term ,
																		'compare' => 'LIKE'
																	)
																)
														    ),
															'offset' => $start,
															'number' => $limit,
															'orderby' => $orderby, 
															'order' => $ordertype,
														));
						
						//////////////////PAGINATION
						$all_users = new WP_User_Query(array(
														    'role' => $filter_role,
															'meta_query' => array(
																'relation' => 'AND',
														        array(
														            'key' => $wpdb->get_blog_prefix() . 'capabilities',
														            'value' => 'administrator',
														            'compare' => 'NOT LIKE'
														        ),
																array(
																		'relation' => 'OR',
																		array(
																			'key'     => 'first_name',
																			'value'   => $search_term,
																			'compare' => 'LIKE'
																		),
																		array(
																			'key'     => 'last_name',
																			'value'   => $search_term,
																			'compare' => 'LIKE'
																		),
																		array(
																			'key' => 'nickname',
																			'value' => $search_term ,
																			'compare' => 'LIKE'
																		)
																	)
														    )
														));	
						
					} else {
						$users_obj = new WP_User_Query(array(
														    'role' => $filter_role,
															'meta_query' => array(
																array(
														            'key' => $wpdb->get_blog_prefix() . 'capabilities',
														            'value' => 'administrator',
														            'compare' => 'NOT LIKE'
														        )
																
														    ),
															'offset' => $start,
															'number' => $limit,
															'orderby' => $orderby, 
															'order' => $ordertype,
														));
						
						
						//////////////////PAGINATION
						$all_users = new WP_User_Query(array(
														    'role' => $filter_role,
														    'meta_query' => array(
																array(
														            'key' => $wpdb->get_blog_prefix() . 'capabilities',
														            'value' => 'administrator',
														            'compare' => 'NOT LIKE'
														        )
														    ),
															'orderby' => $orderby,
															'order' => $ordertype,
														));
					}
					$users = $users_obj->results;
					$all_users = $all_users->results;
					
					//SEARCH FILTER BY USER LEVELS
					if (isset($_REQUEST['ordertype_level']) && $_REQUEST['ordertype_level']!=''){
						if ($all_users){
							$all_users_arr = $all_users;							
							unset($all_users);
							foreach ($all_users_arr as $k=>$v){
								if (ihc_user_has_level_admin($v->data->ID, $_REQUEST['ordertype_level'])){
									$all_users[] = $v;
								}
							}
							if (count($all_users)){
								unset($users);
								for ($i=$start;$i<($start+$limit);$i++){
									if (!empty($all_users[$i])){
										$users[] = $all_users[$i];
									} else {
										break;
									}
								}			
							}
						}
					}
					//SEARCH FILTER BY USER LEVELS
					
					$no_users = count($all_users);
					$pagination = '';
					if($no_users>$limit){
						$no_pag = ceil($no_users/$limit);
						if($start==0) $current_page = 1;
						else $current_page = $_REQUEST['ihcdu_page'];
						$admin_url = admin_url('admin.php');
						for($i=1;$i<=$no_pag;$i++){
							$selected = '';
							$visible = '';
							if($i==$current_page){
								$selected ='selected';
								$visible = 'visible';
							}
							$pag_link = $admin_url . '?page=ihc_manage&tab=users&ihc_limit=' . $limit . '&ihcdu_page=' . $i;
							if (isset($_REQUEST['ordertype_level'])){
								$pag_link .= '&ordertype_level=' . $_REQUEST['ordertype_level'];	
							}
							$pagination .= '<a href="' . $pag_link . '" class="ihc-user-pagination '.$selected.' '.$visible.'">'.$i.'</a>';
						}
						
					}
					
					if ($users){
						?>
							<div style="margin: 10px 0px;">
								<div style="display: inline-block;float: left;" >
									<input type="submit" value="<?php _e('Delete', 'ihc');?>" name="delete"  class="button button-primary button-large"/>
								</div>
								
								<div style="display: inline-block;float: right;margin-right:10px;">
									<strong><?php _e('Number of Users to Display:', 'ihc');?></strong> 
									<select name="ihc_limit" onChange="window.location = '<?php echo admin_url('admin.php');?>?page=ihc_manage&tab=users&ihc_limit='+this.value;">
										<?php 
											foreach(array(5,25,50,100) as $v){
												?>
													<option value="<?php echo $v;?>" <?php if($limit==$v) echo 'selected';?> ><?php echo $v;?></option>
												<?php 
											}
										?>
									</select>
								</div>
								<div class="iump-pagination-wrapper">
									<?php //////////////////PAGINATION
											echo $pagination;
									?>
								</div>
								<div class="clear"></div>							
							</div>
							
						   <table class="wp-list-table widefat fixed tags">
							  <thead>
								<tr>
									  <th style="width: 30px;">
									  	<input type="checkbox" onClick="ihc_select_all_checkboxes( this, '.ihc-delete-user' );" />
									  </th>								
									  <th class="manage-column">
											<?php _e('Username', 'ihc');?>
									  </th>								
									  <th class="manage-column">
											<?php _e('Name', 'ihc');?>
									  </th>
									  <th class="manage-column">
											<?php _e('E-mail', 'ihc');?>
									  </th>									  
									  <th class="manage-column">
											<?php _e('Level', 'ihc');?>
									  </th>
									  <th class="manage-column">
											<?php _e('WP User Role', 'ihc');?>
									  </th>	
									  <th class="manage-column">
											<?php _e('E-mail Status', 'ihc');?>
									  </th>	
									  <th class="manage-column">
											<?php _e('Join Date', 'ihc');?>
									  </th>										  								  									  
							    </tr>
							  </thead>						  
							  <tfoot>
								<tr>
									  <th style="width: 30px;">
									  	<input type="checkbox" onClick="ihc_select_all_checkboxes( this, '.ihc-delete-user' );" />
									  </th>														
									  <th class="manage-column">
											<?php _e('Username', 'ihc');?>
									  </th>								
									  <th class="manage-column">
											<?php _e('Name', 'ihc');?>
									  </th>									  
									  <th class="manage-column">
											<?php _e('E-mail', 'ihc');?>
									  </th>										  
									  <th class="manage-column">
											<?php _e('Level', 'ihc');?>
									  </th>
									  <th class="manage-column">
											<?php _e('WP User Role', 'ihc');?>
									  </th>	
									  <th class="manage-column">
											<?php _e('E-mail Status', 'ihc');?>
									  </th>	
									  <th class="manage-column">
											<?php _e('Join Date', 'ihc');?>
									  </th>											  								  									  
							    </tr>
							  </tfoot>
							  <?php 
							  		$i = 1;
							  		$available_roles = ihc_get_wp_roles_list();
							  		foreach ($users as $user){								  			
							  			$verified_email =  get_user_meta($user->data->ID, 'ihc_verification_status', TRUE);
							  			?>
			    						   		<tr class="<?php if($i%2==0) echo 'alternate';?>" onMouseOver="ihc_dh_selector('#user_tr_<?php echo $user->data->ID;?>', 1);" onMouseOut="ihc_dh_selector('#user_tr_<?php echo $user->data->ID;?>', 0);">
			    						   			<th>
									  					<input type="checkbox" class="ihc-delete-user" name="delete_users[]" value="<?php echo $user->data->ID;?>" />
									 				</th>
			    						   			<td>
														<?php echo $user->data->user_login;?>
														<div style="visibility: hidden;" id="user_tr_<?php echo $user->data->ID;?>">
															<a href="<?php echo $url.'&tab=users&ihc-edit-user='.$user->data->ID;?>"><?php _e('Edit', 'ihc');?></a> 
															| 
															<a onClick="ihc_delete_user_prompot('<?php echo $url.'&tab=users&ihc_delete_user-id='.$user->data->ID;?>');" href="javascript:return false;" style="color: red;"><?php _e('Delete', 'ihc');?></a>
															<?php 
																if (isset($user->roles[0]) && $user->roles[0]=='pending_user'){
																	?>																	
																	<span id="approveUserLNK<?php echo $user->data->ID;?>" onClick="ihc_approve_user(<?php echo $user->data->ID;?>);">
																	| <span style="cursor:pointer; color: #0074a2;"><?php _e('Approve', 'ihc');?></span>
																	</span>
																	<?php 	
																}
																if ($verified_email==-1){
																	?>
																	<span id="approve_email_<?php echo $user->data->ID;?>" onClick="ihc_approve_email(<?php echo $user->data->ID;?>, '<?php _e("Verified", "ihc");?>');">
																	| <span style="cursor:pointer; color: #0074a2;"><?php _e('Approve E-mail', 'ihc');?></span>
																	</span>
																	<?php 
																}
															?>
														</div>
			    						   			</td>
			    						   			<td style="color: #21759b; font-weight:bold; width:120px;font-family: 'Oswald', arial, sans-serif !important;font-size: 14px;font-weight: 400;">
			    						   				<?php 
			    						   					$first_name = get_user_meta($user->data->ID, 'first_name', true);
			    						   					$last_name = get_user_meta($user->data->ID, 'last_name', true);
			    						   					if ($first_name || $last_name){
			    						   						echo $first_name .' '.$last_name;
			    						   					} else {
			    						   						echo $user->data->user_nicename;
			    						   					}
			    						   				?>
			    						   			</td>
			    						   			<td>
			    						   				<?php echo $user->user_email;?>
			    						   			</td>
			    						   			<td style="font-weight:bold;">
			    						   				<?php 
			    						   					$user_levels = get_user_meta($user->data->ID, 'ihc_user_levels', true);
			    						   					if($user_levels!=''){
			    						   						if (strpos($user_levels, ',')!==FALSE){
			    						   							//multiple level
			    						   							$levels = explode(',', $user_levels);
			    						   							foreach ($levels as $level_id){
			    						   								$temp_data = ihc_get_level_by_id($level_id);
			    						   								if ($temp_data && isset($temp_data['name'])){			    						   									
			    						   									$time_arr = ihc_get_start_expire_date_for_user_level($user->data->ID, $level_id);
					    						   							$is_expired_class = '';
					    						   							if (isset($time_arr['expire_time'])){			    						   								
					    						   								$is_expired_class = ($current_time>strtotime( $time_arr['expire_time'] )) ? 'ihc-expired-level' : '' ;
					    						   							}
			    						   									?>
			    						   									<div class="level-type-list <?php echo $is_expired_class;?>"><?php echo $temp_data['name'];?></div>
			    						   									<?php 	
			    						   								}
			    						   							}
			    						   						} else {
			    						   							//single level
			    						   							$temp_data = ihc_get_level_by_id($user_levels);
			    						   							if ($temp_data){
			    						   								//Level Exists
			    						   								$time_arr = ihc_get_start_expire_date_for_user_level($user->data->ID, $user_levels);
			    						   								$is_expired_class = '';
			    						   								if (isset($time_arr['expire_time'])){
			    						   									$is_expired_class = ($current_time>strtotime( $time_arr['expire_time'] )) ? 'ihc-expired-level' : '' ;
			    						   								}
			    						   								echo '<div class="level-type-list ' . $is_expired_class . '">'.$temp_data['name'].'</div>';			    						   								
			    						   							}
			    						   						}	
			    						   					}
			    						   				?>
			    						   			</td>
			    						   			<td>
			    						   				<div id="user-<?php echo $user->data->ID;?>-status">
				    						   				<?php 
				    						   					if ( isset($user->roles[0]) && $user->roles[0]=='pending_user'){
				    						   						 ?>
				    						   						 	<span class="subcr-type-list iump-pending"><?php _e('Pending', 'ihc');?></span>
				    						   						 <?php
				    						   					} else {
				    						   						 ?>
				    						   						 	<span class="subcr-type-list"><?php 
				    						   						 		if (isset($user->roles[0]) && isset($available_roles[$user->roles[0]])){
				    						   						 			echo $available_roles[$user->roles[0]];
				    						   						 		} else {
																				echo '-';	
				    						   						 		}
				    						   						 	?></span>
				    						   						 <?php
				    						   					}
				    						   				?>			    						   				
			    						   				</div>
			    						   			</td>
			    						   			<td><?php 
			    						   				$div_id = "user_email_" . $user->data->ID . "_status";
			    						   				$class = 'subcr-type-list';
			    						   				if ($verified_email==1){
			    						   					$label = __('Verified', 'ihc');
			    						   				} else if ($verified_email==-1){
			    						   					$label = __('Unapproved', 'ihc');
			    						   					$class = 'subcr-type-list iump-pending';
			    						   				} else {
			    						   					$label = __('Unverified', 'ihc');			    						   			
			    						   				}
			    						   				?>
			    						   					<div id="<?php echo $div_id;?>">
			    						   						<span class="<?php echo $class;?>"><?php echo $label;?></span>
			    						   					</div>			    						   					
			    						   				<?php 	
			    						   			?></td>
			    						   			<td style="color: #396;">
			    						   				<?php 
			    						   					echo $user->user_registered;
			    						   				?>
			    						   			</td>
			    						   		</tr>
							  			<?php
							  			$i++; 
							  		}
							  ?>
						   </table>
						   <div style="margin-top: 10px;">
						   		<input type="submit" value="<?php _e('Delete', 'ihc');?>" name="delete"  class="button button-primary button-large"/>
						   </div>
						<?php 
					}else{ ?>
					<div  class="ihc-warning-message"><?php _e('No Users Available.', 'ihc');?></div>
					<?php }
				?>
			</div>
		</form>	
	</div>
</div>
<div class="clear"></div>
<?php 
}
