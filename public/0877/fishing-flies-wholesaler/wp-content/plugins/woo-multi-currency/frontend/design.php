<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WOOMULTI_CURRENCY_F_Frontend_Design
 */
class WOOMULTI_CURRENCY_F_Frontend_Design {
	protected $settings;

	public function __construct() {

		$this->settings = WOOMULTI_CURRENCY_F_Data::get_ins();

		/*Add order information*/


		add_action( 'wp_footer', array( $this, 'show_action' ) );


		if ( $this->settings->get_enable() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'front_end_script' ) );
		}

	}

	/**
	 * Public
	 */
	public function front_end_script() {
		if ( WP_DEBUG ) {
			wp_enqueue_style( 'woo-multi-currency', WOOMULTI_CURRENCY_F_CSS . 'woo-multi-currency.css', array(), WOOMULTI_CURRENCY_F_VERSION );
			wp_enqueue_style( 'wmc-flags', WOOMULTI_CURRENCY_F_CSS . 'flags-64.min.css' );
		} else {
			wp_enqueue_style( 'woo-multi-currency', WOOMULTI_CURRENCY_F_CSS . 'woo-multi-currency.min.css', array(), WOOMULTI_CURRENCY_F_VERSION );
			wp_enqueue_style( 'wmc-flags', WOOMULTI_CURRENCY_F_CSS . 'flags-64.min.css' );
		}

		if ( is_rtl() ) {
			wp_enqueue_style( 'woo-multi-currency-rtl', WOOMULTI_CURRENCY_F_CSS . 'woo-multi-currency-rtl.css', array(), WOOMULTI_CURRENCY_F_VERSION );
		}

		/*Custom CSS*/
		$text_color                = $this->settings->get_text_color();
		$background_color          = $this->settings->get_background_color();
		$main_color                = $this->settings->get_main_color();
		$shortcode_bg_color        = $this->settings->get_param( 'shortcode_bg_color' );
		$shortcode_color           = $this->settings->get_param( 'shortcode_color' );
		$shortcode_active_bg_color = $this->settings->get_param( 'shortcode_active_bg_color' );
		$shortcode_active_color    = $this->settings->get_param( 'shortcode_active_color' );
		$links                     = $this->settings->get_links();
		$currency_qty              = count( $links ) - 1;

		$custom = '.woo-multi-currency .wmc-list-currencies .wmc-currency.wmc-active,.woo-multi-currency .wmc-list-currencies .wmc-currency:hover,.woo-multi-currency.wmc-price-switcher a:hover {background: ' . $main_color . ' !important;}
		.woo-multi-currency .wmc-list-currencies .wmc-currency,.woo-multi-currency .wmc-title, .woo-multi-currency.wmc-price-switcher a {background: ' . $background_color . ' !important;}
		.woo-multi-currency .wmc-title, .woo-multi-currency .wmc-list-currencies .wmc-currency span,.woo-multi-currency .wmc-list-currencies .wmc-currency a,.woo-multi-currency.wmc-price-switcher a {color: ' . $text_color . ' !important;}';

		$custom .= ".woocommerce-multi-currency.wmc-shortcode .wmc-currency{background-color:{$shortcode_bg_color};color:{$shortcode_color} !important}";
		$custom .= ".woocommerce-multi-currency.wmc-shortcode .wmc-current-currency{background-color:{$shortcode_active_bg_color};color:{$shortcode_active_color}}";
		$custom .= ".woocommerce-multi-currency.wmc-shortcode.vertical-currency-symbols-circle .wmc-currency-wrapper:hover .wmc-sub-currency {animation: height_slide {$currency_qty}00ms;}";
		$custom .= "@keyframes height_slide {0% {height: 0;} 100% {height: {$currency_qty}00%;} }";

		$custom .= $this->settings->get_custom_css();
		wp_add_inline_style( 'woo-multi-currency', $custom );

		/*Multi currency JS*/
		if ( WP_DEBUG ) {
			wp_enqueue_script( 'woo-multi-currency', WOOMULTI_CURRENCY_F_JS . 'woo-multi-currency.js', array( 'jquery' ), WOOMULTI_CURRENCY_F_VERSION );
		} else {
			wp_enqueue_script( 'woo-multi-currency', WOOMULTI_CURRENCY_F_JS . 'woo-multi-currency.min.js', array( 'jquery' ), WOOMULTI_CURRENCY_F_VERSION );
		}


	}

	/**
	 * Show Currency converter
	 */
	public function show_action() {
		if ( ! $this->enable() ) {
			return;
		}
		$logic_value = $this->settings->get_conditional_tags();
		if ( $logic_value ) {
			if ( stristr( $logic_value, "return" ) === false ) {
				$logic_value = "return (" . $logic_value . ");";
			}
			if ( ! eval( $logic_value ) ) {
				return;
			}
		}
		$enable_checkout = $this->settings->get_enable_multi_payment();
		if ( ! $enable_checkout && is_checkout() ) {
			return;
		}
		$currency_selected = $this->settings->get_current_currency();
		$title             = $this->settings->get_design_title();
		$enable_collapse   = $this->settings->enable_collapse();
		$class             = array();
		/*Position left or right*/
		if ( ! $this->settings->get_design_position() ) {
			$class[] = 'wmc-left';
		} else {
			$class[] = 'wmc-right';
		}
		$class[] = 'style-1';
		switch ( $this->settings->get_sidebar_style() ) {
			case 1:
				$class[] = 'wmc-currency-symbol';
				break;
			case 2:
				$class[] = 'wmc-currency-flag';
				break;
			case 3:
				$class[] = 'wmc-currency-flag wmc-currency-code';
				break;
			case 4:
				$class[] = 'wmc-currency-flag wmc-currency-symbol';
				break;
		}
		if ( $enable_collapse ) {
			$class[] = 'wmc-collapse';
		}
		?>
        <div class="woo-multi-currency <?php echo esc_attr( implode( ' ', $class ) ); ?> wmc-bottom wmc-sidebar">
            <div class="wmc-list-currencies">
				<?php if ( $title ) { ?>
                    <div class="wmc-title">
						<?php echo esc_html( $title ) ?>
                    </div>
				<?php }
				$links         = $this->settings->get_links();
				$currency_name = get_woocommerce_currencies();
				foreach ( $links as $k => $link ) {
					$selected = '';
					if ( $currency_selected == $k ) {
						$selected = 'wmc-active';
					}
					?>
                    <div class="wmc-currency <?php echo esc_attr( $selected ) ?>">
						<?php switch ( $this->settings->get_sidebar_style() ) {
							case 1: ?>
                                <span><?php echo get_woocommerce_currency_symbol( $k ) ?></span>
								<?php break;
							case 2:
							case 3:
							case 4:
								$country = $this->settings->get_country_data( $k );
								?>
                                <span>
                                  <i class="vi-flag-64 flag-<?php echo strtolower( $country['code'] ) ?> "></i></span>
								<?php
								break;
							default:
								?>
                                <span><?php echo esc_html( $k ) ?></span>
							<?php }
						if ( $selected ){
							echo '<span class="wmc-active-title">';
						}else{ ?>
                        <a href="<?php echo esc_url( $link ) ?>">
							<?php
							}
							switch ( $this->settings->get_sidebar_style() ) {
								case 3:
									echo $k;
									break;
								case 4:
									echo get_woocommerce_currency_symbol( $k );
									break;
								default:
									echo esc_html( $currency_name[ $k ] );

							}
							if ( $selected ){
								echo '</span>';
							}else{
							?>
                        </a>
					<?php } ?>
                    </div>
				<?php } ?>
                <div class="wmc-sidebar-open"></div>
            </div>
        </div>
	<?php }

	/**
	 * Check design enable
	 * @return bool
	 *
	 */
	protected function enable() {
		$enable = $this->settings->get_enable_design();
		if ( ! $enable ) {
			return false;
		}
		if ( $this->settings->is_checkout() ) {
			if ( is_checkout() ) {
				return false;
			}
		}
		if ( $this->settings->is_cart() ) {
			if ( is_cart() ) {
				return false;
			}
		}

		return true;
	}

}