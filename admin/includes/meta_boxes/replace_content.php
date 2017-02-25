<?php 
global $post;
$meta_arr = ihc_post_metas($post->ID);
if($meta_arr['ihc_mb_block_type']=='replace'){
	//display the box
	?>
	<style>
		#ihc_replace_content{
			display: block;	
		}
	</style>
	<?php 
}
$settings = array( 
					'media_buttons' => true,
					'textarea_name' => 'ihc_replace_content',
				 );
$meta_arr['ihc_replace_content'] = stripslashes($meta_arr['ihc_replace_content']);
$meta_arr['ihc_replace_content'] = htmlspecialchars_decode($meta_arr['ihc_replace_content']);
wp_editor( $meta_arr['ihc_replace_content'], 'ihc-replace-content', $settings );