<?php 
//add custom fields to object
add_filter( 'wp_setup_nav_menu_item','ihc_nav_items_custom' );
function ihc_nav_items_custom($obj) {
	$obj->ihc_mb_who_menu_type = get_post_meta( $obj->ID, 'ihc_mb_who_menu_type', true );
	$obj->ihc_menu_mb_type = get_post_meta( $obj->ID, 'ihc_menu_mb_type', true );
	return $obj;
}

// Saves new field to postmeta for navigation
add_action('wp_update_nav_menu_item', 'ihc_nav_menu_update', 10, 3);
function ihc_nav_menu_update($menu_id, $menu_db_id, $args ) {
	if ( isset($_REQUEST['ihc_mb_who_menu_type-'.$menu_db_id]) && isset($_REQUEST['ihc_menu_mb_type-'.$menu_db_id]) ) {
		update_post_meta( $menu_db_id, 'ihc_mb_who_menu_type', $_REQUEST['ihc_mb_who_menu_type-'.$menu_db_id]);
		update_post_meta( $menu_db_id, 'ihc_menu_mb_type', $_REQUEST['ihc_menu_mb_type-'.$menu_db_id]);
	}
}

//create custom walker class
add_filter( 'wp_edit_nav_menu_walker', 'indeed_create_walker_menu_class', 10, 2);
function indeed_create_walker_menu_class($walker,$menu_id) {
	return 'IndeedWalkerMenu_IHC';
}

class IndeedWalkerMenu_IHC extends Walker_Nav_Menu{
	public function start_lvl(&$output, $depth = 0, $args = array()){}
	public function end_lvl(&$output, $depth = 0, $args = array()){}
	
	public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		global $_wp_nav_menu_max_depth;
		$_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;
		
		ob_start();
		$item_id = esc_attr( $item->ID );
		$removed_args = array(
				'action',
				'customlink-tab',
				'edit-menu-item',
				'menu-item',
				'page-tab',
				'_wpnonce',
		);
		
		$original_title = '';
		if ( 'taxonomy' == $item->type ) {
			$original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
			if ( is_wp_error( $original_title ) )
				$original_title = false;
		} elseif ( 'post_type' == $item->type ) {
			$original_object = get_post( $item->object_id );
			$original_title = get_the_title( $original_object->ID );
		}
		
		$classes = array(
				'menu-item menu-item-depth-' . $depth,
				'menu-item-' . esc_attr( $item->object ),
				'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
		);
		
		$title = $item->title;
		
		if ( ! empty( $item->_invalid ) ) {
			$classes[] = 'menu-item-invalid';
			/* translators: %s: title of menu item which is invalid */
			$title = sprintf( __( '%s (Invalid)' ), $item->title );
		} elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
			$classes[] = 'pending';
			/* translators: %s: title of menu item in draft status */
			$title = sprintf( __('%s (Pending)'), $item->title );
		}
		
		$title = ( ! isset( $item->label ) || '' == $item->label ) ? $title : $item->label;
		
		$submenu_text = '';
		if ( 0 == $depth )
			$submenu_text = 'style="display: none;"';
		
		?>
				<li id="menu-item-<?php echo $item_id; ?>" class="<?php echo implode(' ', $classes ); ?>">
					<dl class="menu-item-bar">
						<dt class="menu-item-handle">
							<span class="item-title"><span class="menu-item-title"><?php echo esc_html( $title ); ?></span> <span class="is-submenu" <?php echo $submenu_text; ?>><?php _e( 'sub item' ); ?></span></span>
							<span class="item-controls">
								<span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
								<span class="item-order hide-if-js">
									<a href="<?php
										echo wp_nonce_url(
											add_query_arg(
												array(
													'action' => 'move-up-menu-item',
													'menu-item' => $item_id,
												),
												remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
											),
											'move-menu_item'
										);
									?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up'); ?>">&#8593;</abbr></a>
									|
									<a href="<?php
										echo wp_nonce_url(
											add_query_arg(
												array(
													'action' => 'move-down-menu-item',
													'menu-item' => $item_id,
												),
												remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
											),
											'move-menu_item'
										);
									?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down'); ?>">&#8595;</abbr></a>
								</span>
								<a class="item-edit" id="edit-<?php echo $item_id; ?>" title="<?php esc_attr_e('Edit Menu Item'); ?>" href="<?php
									echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
								?>"><?php _e( 'Edit Menu Item' ); ?></a>
							</span>
						</dt>
					</dl>
		
					<div class="menu-item-settings" id="menu-item-settings-<?php echo $item_id; ?>">
						<?php if( 'custom' == $item->type ) : ?>
							<p class="field-url description description-wide">
								<label for="edit-menu-item-url-<?php echo $item_id; ?>">
									<?php _e( 'URL' ); ?><br />
									<input type="text" id="edit-menu-item-url-<?php echo $item_id; ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
								</label>
							</p>
						<?php endif; ?>
						<p class="description description-thin">
							<label for="edit-menu-item-title-<?php echo $item_id; ?>">
								<?php _e( 'Navigation Label' ); ?><br />
								<input type="text" id="edit-menu-item-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
							</label>
						</p>
						<p class="description description-thin">
							<label for="edit-menu-item-attr-title-<?php echo $item_id; ?>">
								<?php _e( 'Title Attribute' ); ?><br />
								<input type="text" id="edit-menu-item-attr-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
							</label>
						</p>
						<p class="field-link-target description">
							<label for="edit-menu-item-target-<?php echo $item_id; ?>">
								<input type="checkbox" id="edit-menu-item-target-<?php echo $item_id; ?>" value="_blank" name="menu-item-target[<?php echo $item_id; ?>]"<?php checked( $item->target, '_blank' ); ?> />
								<?php _e( 'Open link in a new window/tab' ); ?>
							</label>
						</p>
						<p class="field-css-classes description description-thin">
							<label for="edit-menu-item-classes-<?php echo $item_id; ?>">
								<?php _e( 'CSS Classes (optional)' ); ?><br />
								<input type="text" id="edit-menu-item-classes-<?php echo $item_id; ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo $item_id; ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
							</label>
						</p>
						<p class="field-xfn description description-thin">
							<label for="edit-menu-item-xfn-<?php echo $item_id; ?>">
								<?php _e( 'Link Relationship (XFN)' ); ?><br />
								<input type="text" id="edit-menu-item-xfn-<?php echo $item_id; ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
							</label>
						</p>
						<p class="field-description description description-wide">
							<label for="edit-menu-item-description-<?php echo $item_id; ?>">
								<?php _e( 'Description' ); ?><br />
								<textarea id="edit-menu-item-description-<?php echo $item_id; ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo $item_id; ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
								<span class="description"><?php _e('The description will be displayed in the menu if the current theme supports it.'); ?></span>
							</label>
						</p>
		
						<p class="field-move hide-if-no-js description description-wide">
							<label>
								<span><?php _e( 'Move' ); ?></span>
								<a href="#" class="menus-move-up"><?php _e( 'Up one' ); ?></a>
								<a href="#" class="menus-move-down"><?php _e( 'Down one' ); ?></a>
								<a href="#" class="menus-move-left"></a>
								<a href="#" class="menus-move-right"></a>
								<a href="#" class="menus-move-top"><?php _e( 'To the top' ); ?></a>
							</label>
						</p>

			
<!---------------------------- INDEED CUSTOM SECTION  -------------------------------------------->
						<script>
							function ihc_writeTagValue(id, hiddenId, viewDivId, prevDivPrefix){
							    if(id.value==-1) return;
							    hidden_i = jQuery(hiddenId).val();
							    
							    if(hidden_i!='') show_arr = hidden_i.split(',');
							    else show_arr = new Array();
							    
							    if(show_arr.indexOf(id.value)==-1){
							        show_arr.push(id.value);
								    
								    str = show_arr.join(',');
								    jQuery(hiddenId).val(str);
								
									label = jQuery(id).find("option:selected").text();
									jQuery(viewDivId).append('<div id="'+prevDivPrefix+id.value+'" class="ihc-tag-item">'+label+'<div class="ihc-remove-tag" onclick="ihcremoveTag(\''+id.value+'\', \'#'+prevDivPrefix+'\', \''+hiddenId+'\');" title="<?php _e('Removing tag', 'ihc');?>">x</div></div>');
							    }
							    jQuery(id).val(-1);
							}
							function ihcremoveTag(removeVal, prevDivPrefix, hiddenId){
								jQuery(prevDivPrefix+removeVal).fadeOut(200, function(){
									jQuery(this).remove();
								});	
							    
							    hidden_i = jQuery(hiddenId).val();
							    show_arr = hidden_i.split(',');
							    
							    show_arr = removeArrayElement(removeVal, show_arr);
							    str = show_arr.join(',');
								jQuery(hiddenId).val(str);
							}
							function removeArrayElement(elem, arr){
								for (i=0;i<arr.length;i++) {
								    if(arr[i]==elem){
								    	arr.splice(i, 1);
								    }
								}
								return arr;
							}
						</script>
						<div>
							<h5>Membership Pro Ultimate WP - <?php _e('Section', 'ihc');?></h5>
							<div class="ihc-class ihc-padding">
								<select class="ihc-fullwidth ihc-select" name="ihc_menu_mb_type-<?php echo $item_id; ?>">
									<option value="block" <?php if($item->ihc_menu_mb_type=='block')echo 'selected';?> ><?php _e('Block Menu Item Only', 'ihc');?></option>
									<option value="show" <?php if($item->ihc_menu_mb_type=='show')echo 'selected';?> ><?php _e('Show Menu Item Only', 'ihc');?></option>									
								</select>
							</div>
							<div  class="ihc-padding"  style="text-align:right; margin-bottom:10px;">
								<label class="ihc-bold">...<?php _e('for', 'ihc');?></label>
								<?php
									$posible_values = array('all'=>__('All', 'ihc'), 'reg'=>__('Registered Users', 'ihc'), 'unreg'=>__('Unregistered Users', 'ihc') );
									$levels = get_option('ihc_levels');
									if($levels){
										foreach($levels as $id => $level){
											$posible_values[$id] = $level['name'];
										}
									}
									?>
									<select id="" onChange="ihc_writeTagValue(this, '#ihc_mb_who_hidden-<?php echo $item_id;?>', '#ihc_tags_field-<?php echo $item_id;?>', '<?php echo $item_id;?>_ihc_select_tag_' );" style="width: auto; min-width:80%;">
										<option value="-1" selected>...</option>
										<?php 
											foreach($posible_values as $k=>$v){
												?>
												<option value="<?php echo $k;?>"><?php echo $v;?></option>	
												<?php 
											}
										?>
									</select>
							</div>
							<div id="ihc_tags_field-<?php echo $item_id;?>">
				            	<?php
				            		
				                    if($item->ihc_mb_who_menu_type){
				                    	if(strpos($item->ihc_mb_who_menu_type, ',')!==FALSE){
				                    		$values = explode(',', $item->ihc_mb_who_menu_type);
				                    	}
				                        else{
				                        	$values[] = $item->ihc_mb_who_menu_type;			
				                        }
				                        foreach($values as $value){ ?>
				                        	<div id="<?php echo $item_id;?>_ihc_select_tag_<?php echo $value;?>" class="ihc-tag-item">
				                        		<?php echo $posible_values[$value];?>
				                        		<div class="ihc-remove-tag" onclick="ihcremoveTag('<?php echo $value;?>', '#<?php echo $item_id;?>_ihc_select_tag_', '#ihc_mb_who_hidden-<?php echo $item_id;?>');" title="<?php _e('Removing tag', 'ihc');?>">x</div>
				                        	</div>
				                            <?php
				                        }//end of foreach ?>
				                    <div class="ihc-clear"></div>
				                    <?php }//end of if ?>
												
							</div>
							<div class="ihc-clear"></div>
							<input type="hidden" id="ihc_mb_who_hidden-<?php echo $item_id;?>" name="ihc_mb_who_menu_type-<?php echo $item_id; ?>" value="<?php echo $item->ihc_mb_who_menu_type;?>" />
							<div class="clear"></div>															
						</div>
<!---------------------------- END OF INDEED CUSTOM SECTION  -------------------------------------------->						
						
						
		
						<div class="menu-item-actions description-wide submitbox">
							<?php if( 'custom' != $item->type && $original_title !== false ) : ?>
								<p class="link-to-original">
									<?php printf( __('Original: %s'), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
								</p>
							<?php endif; ?>
							<a class="item-delete submitdelete deletion" id="delete-<?php echo $item_id; ?>" href="<?php
							echo wp_nonce_url(
								add_query_arg(
									array(
										'action' => 'delete-menu-item',
										'menu-item' => $item_id,
									),
									admin_url( 'nav-menus.php' )
								),
								'delete-menu_item_' . $item_id
							); ?>"><?php _e( 'Remove' ); ?></a> <span class="meta-sep hide-if-no-js"> | </span> <a class="item-cancel submitcancel hide-if-no-js" id="cancel-<?php echo $item_id; ?>" href="<?php echo esc_url( add_query_arg( array( 'edit-menu-item' => $item_id, 'cancel' => time() ), admin_url( 'nav-menus.php' ) ) );
								?>#menu-item-settings-<?php echo $item_id; ?>"><?php _e('Cancel'); ?></a>
						</div>
		
						<input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo $item_id; ?>]" value="<?php echo $item_id; ?>" />
						<input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
						<input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
						<input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
						<input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
						<input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
					</div><!-- .menu-item-settings-->
					<ul class="menu-item-transport"></ul>
				<?php
				$output .= ob_get_clean();		
	}
}
