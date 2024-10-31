<?php
/*
 Plugin Name: ProThemesWP Fade-In Shortcode
 Plugin URI: https://wordpress.org/support/plugin/prothemeswp-fade-in-shortcode
 Description: Adds a fade-in shortcode and a background shortcode.
 Author: ProThemesWP
 Author URI: https://prothemeswp.com
 Text Domain: prothemeswp-fade-in-shortcode
 Domain Path: /languages/
 Version: 1.0
 Copyright: Copyright 2019 (c) ProThemesWP - info@prothemeswp.com
*/

//Make plugin translatable
add_action( 'plugins_loaded', 'prothemeswp_fade_in_shortcode_load_textdomain' );

if( !function_exists( 'prothemeswp_fade_in_shortcode_load_textdomain' ) ) {

	function prothemeswp_fade_in_shortcode_load_textdomain() {
	  load_plugin_textdomain( 'prothemeswp-fade-in-shortcode', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
	}

}

//Add support and reviews links
add_filter( 'plugin_row_meta', function( $plugin_meta, $plugin_file, $plugin_data, $status ) {
		if( plugin_basename( __FILE__ ) == $plugin_file ) {
			$plugin_meta[] = '<a href="https://wordpress.org/support/plugin/' . dirname( plugin_basename( __FILE__ ) ) . '/">' . __( 'Support', 'prothemeswp-fade-in-shortcode' ) . '</a>';
			$plugin_meta[] = '<a href="https://wordpress.org/support/plugin/' . dirname( plugin_basename( __FILE__ ) ) . '/reviews/">' . __( 'Reviews', 'prothemeswp-fade-in-shortcode' ) . '</a>';
		}
		return $plugin_meta;	
}, 10, 4 );


//In case there is another plugin with the [fadein] or [fadeinbackground] shortcode
if( !function_exists( 'prothemeswp_fade_in_shortcode_registered_notice' ) ) {
	
	function prothemeswp_fade_in_shortcode_registered_notice() {
		echo '<div class="notice notice-info is-dismissible">
			  <p>' . __( 'You have more than one plugin that uses the [fadein] or [fadeinbackground] shortcode. Please use the shortcode [prothemeswpfadein] instead of [fadein] and [prothemeswpfadeinbackground] instead of [fadeinbackground] to use ProThemesWP jQuery Shortcode\'s.',
				'prothemeswp-fade-in-shortcode' ) . '</p>
			 </div>';
	}
	
}

//Add shortcode(s)
add_action( 'plugins_loaded', 'prothemeswp_fade_in_shortcode_plugins_loaded' );

if( !function_exists( 'prothemeswp_fade_in_shortcode_plugins_loaded' ) ) {
	
	function prothemeswp_fade_in_shortcode_plugins_loaded() {
		if( shortcode_exists( 'fadein' ) || shortcode_exists( 'fadeinbackground' ) ) {
			add_action( 'admin_notices', 'prothemeswp_fade_in_shortcode_registered_notice' );
		} else {
			add_shortcode( 'fadein', 'prothemeswp_fade_in_shortcode' );
			add_shortcode( 'fadeinbackground', 'prothemeswp_fade_in_background_shortcode' );
		}
		add_shortcode( 'prothemeswpfadein', 'prothemeswp_fade_in_shortcode' );
		add_shortcode( 'prothemeswpfadeinbackground', 'prothemeswp_fade_in_background_shortcode' );
	}
	
}

//Used by [fadeinbackground] and [fadein]
if( !function_exists( 'prothemeswp_fade_in_background_image_and_color' ) ) {
	function prothemeswp_fade_in_background_image_and_color( $atts ) {
		$background_fit = 'cover';
		if( isset( $atts['backgroundfit'] ) ) :
			$background_fit = $atts['backgroundfit'];
		endif;
		$parallax_class = 'prothemeswp-fade-in-shortcode-background-parallax';
		if( isset( $atts['parallax'] ) && !in_array( $atts['parallax'], array( '1', 'true', 'yes' ) ) ) :
			$parallax_class = '';
		endif;
		if( !empty( $atts['backgroundimage'] ) ) :
			$background_image_opacity = '';
			if( !empty( $atts['backgroundimageopacity'] ) ) :
				$atts['backgroundimageopacity'] = str_replace( '%', '', $atts['backgroundimageopacity'] );
				if( !is_numeric( $atts['backgroundimageopacity'] ) ) :
					echo sprintf( __( 'Background image opacity %s is not a number', 'prothemeswp-fade-in-shortcode' ), $atts['backgroundimageopacity'] );
				endif;
				$background_image_opacity = 'opacity:' . floatval( $atts['backgroundimageopacity'] )/100 . ';';
			endif;
			?><div class="prothemeswp-fade-in-shortcode-background-image prothemeswp-fade-in-shortcode-background-<?php 
				echo $background_fit; ?> <?php echo $parallax_class ?>" 
				style="background-image:url('<?php echo $atts['backgroundimage']?>');<?php echo $background_image_opacity; ?>">
				</div><!-- prothemeswp-fade-in-shortcode-background-image -->
			<?php
		endif;
		if( !empty( $atts['backgroundcolor'] ) ) :
			$background_color_opacity = '';
			if( !empty( $atts['backgroundcoloropacity'] ) ) :
				$atts['backgroundcoloropacity'] = str_replace( '%', '', $atts['backgroundcoloropacity'] );
				if( !is_numeric( $atts['backgroundcoloropacity'] ) ) :
					echo sprintf( __( 'Background color opacity %s is not a number', 'prothemeswp-fade-in-shortcode' ), $atts['backgroundcoloropacity'] );
				endif;
				$background_color_opacity = 'opacity:' . ( floatval( $atts['backgroundcoloropacity'] ) / 100 ) . ';';
			endif;
			?><div class="prothemeswp-fade-in-shortcode-background-color prothemeswp-fade-in-shortcode-background-fit-<?php 
				echo $background_fit; ?> <?php echo $parallax_class ?>" 
				style="background-color:<?php echo $atts['backgroundcolor']?>;<?php echo $background_color_opacity; ?>">
				</div><!-- prothemeswp-fade-in-shortcode-background-color --><?php
		endif;
	}
}

//Used by [fadeinbackground] and [fadein]
if( !function_exists( 'prothemeswp_fade_in_shortcode_get_content_css' ) ) {

	function prothemeswp_fade_in_shortcode_get_content_css( $atts ) {
		$text_color_css = '';
		if( !empty( $atts['textcolor'] ) ) {
			$text_color_css = "color:{$atts['textcolor']};";
		}
		$padding_css = '';
		if( !empty( $atts['padding'] ) ) {
			if( is_numeric( $atts['padding'] ) ) {
				$atts['padding'] .= 'px';
			}
			$padding_css = "padding:{$atts['padding']};";
		}
		return $text_color_css . $padding_css;
	}
	
}

//[fadeinbackground] shortcode function
if( !function_exists( 'prothemeswp_fade_in_background_shortcode' ) ) {

	function prothemeswp_fade_in_background_shortcode( $atts, $content ) {
		ob_start();
		$atts = apply_filters( 'prothemeswp-fade-in-background-atts', $atts, $content );
		$content = apply_filters( 'prothemeswp-fade-in-background-content', $content, $atts );
		?>
		<div class="prothemeswp-fade-in-background-shortcode">
		<?php prothemeswp_fade_in_background_image_and_color( $atts ); ?>
		<div class="prothemeswp-fade-in-background-shortcode-content" style="<?php echo prothemeswp_fade_in_shortcode_get_content_css( $atts ); ?>">
		<?php echo do_shortcode( $content ); ?>
		</div><!-- prothemeswp-fade-in-background-shortcode-content -->
		</div><!-- prothemeswp-fade-in-background-shortcode -->
		<?php
		return apply_filters( 'prothemeswp-fade-in-background-shortcode', ob_get_clean(), $atts, $content );
	}
	
}

//[fadein] shortcode function
if( !function_exists( 'prothemeswp_fade_in_shortcode' ) ) {

	function prothemeswp_fade_in_shortcode( $atts, $content ) {
		ob_start();
		$atts = apply_filters( 'prothemeswp-fade-in-atts', $atts, $content );
		$content = apply_filters( 'prothemeswp-fade-in-content', $content, $atts );
		$duration = 2;
		$delay = 0;
		if( isset( $atts['duration'] ) ) :
			if( !is_numeric( $atts['duration'] ) ) :
				echo sprintf( __( 'Duration %s is not a number', 'prothemeswp-fade-in-shortcode' ), $atts['duration'] );
			endif;
			$duration = floatval( $atts['duration'] );
		endif;
		if( isset( $atts['delay'] ) ) :
			if( !is_numeric( $atts['delay'] ) ) :
				echo sprintf( __( 'Delay %s is not a number', 'prothemeswp-fade-in-shortcode' ), $atts['delay'] );
			endif;
			$delay = floatval( $atts['delay'] );
		endif;
		?>
		<div class="prothemeswp-fade-in-shortcode" data-duration="<?php echo $duration; ?>" data-delay="<?php echo $delay; ?>">
		<?php 
		prothemeswp_fade_in_background_image_and_color( $atts );
		?>
			<div class="prothemeswp-fade-in-shortcode-content" style="<?php echo prothemeswp_fade_in_shortcode_get_content_css( $atts ); ?>">
				<?php echo do_shortcode( $content ); ?>
			</div><!-- prothemeswp-fade-in-shortcode-content -->
		</div><!-- prothemeswp-fade-in-shortcode -->
		<?php
		wp_enqueue_style( 'prothemes-fade-in-shortcode', plugins_url( 'prothemeswp-fade-in-shortcode.css', __FILE__ ) );
		wp_enqueue_script( 'prothemes-fade-in-shortcode', plugins_url( 'prothemeswp-fade-in-shortcode.js', __FILE__ ) );
		return apply_filters( 'prothemeswp-fade-in-shortcode', ob_get_clean(), $atts, $content );
	}
	
}