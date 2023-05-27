<?php

class ffPluginFreshCustomCodeLite extends ffPluginAbstract {
 	const CUSTOMCODE_POST_TYPE_SLUG = 'ff-customcode-item';
	const CUSTOMCODE_CACHE_NAMESPACE = 'custom_code';
	/**
	 *
	 * @var ffPluginFreshCustomCodeLiteContainer
	 */
	protected $_container = null;


	private $_assetsHolder = array();

	
	private $_allPosts = null;
	
	protected function _registerAssets() {
		$metaBoxManager = $this->_getContainer()->getFrameworkContainer()->getMetaBoxes()->getMetaBoxManager();
		
		// ADDING META BOXES
		$metaBoxManager->addMetaBoxClassName('ffMetaBoxCustomCodeEditor');
		$metaBoxManager->addMetaBoxClassName('ffMetaBoxCustomCodeLogic');
		$metaBoxManager->addMetaBoxClassName('ffMetaBoxCustomCodeType');		
		$metaBoxManager->addMetaBoxClassName('ffMetaBoxCustomCodePlacement');
	}

	protected function _run() {
		$this->_registerCustomPostType();
		$this->_registerActions();
        $this->_registerAdminNotice();
//		die();

//		setcookie("ff-ark-hide", "", time() - 3600);
//		setcookie("ff-ark-hidex", "", time() - 3600);
//		setcookie('ff-ark-hide', null);
//		setcookie('ff-ark-hidex', null);
	}

    protected function _registerAdminNotice() {
        $WPLayer = $this->_getContainer()->getFrameworkContainer()->getWPLayer();
        $WPLayer->add_action('admin_notices', array($this, 'adminNoticeInformation'));
    }

    public function adminNoticeInformation() {
        $currentScreen = get_current_screen();

        if( property_exists($currentScreen, 'post_type') && $currentScreen->post_type == 'ff-customcode-item' ) {
            echo '<div class="updated">';
            echo '<p><strong>Hello, you are using "Custom CSS Editor" plugin - lite version</strong></p>';
            echo '<p>In current situation you can add only a 1 custom code. If you need more, <a class="button button-primary button-hero" href="http://freshface.net/extern-links/custom-css-lite-to-fresh-custom-code.php" target="_blank">Buy PRO version</a></p>';
            echo '</div>';
        }

		$releaseDate = '8th november 2016';
//	    $releaseDate = '8th october 2016';
	    $releaseTimestamp = strtotime( $releaseDate );


	    if( $releaseTimestamp < time() ) {


		    if( !isset( $_COOKIE['ff-ark-hide']) || $_COOKIE['ff-ark-hide'] != '1' ) {

			    echo '<div class="updated" style="position:relative;">';
			    echo '<button type="button" class="notice-dismiss ff-ark-notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>';
			    echo '<p><strong>Hello, <a href="http://freshface.net/extern-links/link-to-portfolio.php" target="_blank" style="color:black; text-decoration:none;">FRESHFACE</a> here</strong>, thank you for using "Custom CSS Editor" plugin</p>';
			    echo '<p>We just created <strong>ARK</strong>, currently the <strong>best WP theme on this planet</strong> (yes, you should hear the testimonials...)</p>';
			    echo '<p>In the next 24 hours, there is a way for our customers to <strong>get the ARK ($97) for FREE.</strong></p>';
			    echo '<p>Interested? Luckily, you are one of them, so <strong><a href="http://freshface.net/extern-links/get-ark.php">MORE INFO ABOUT HOW TO GET THE ARK ($97) FOR FREE</a></strong></p>';
			    echo '</div>';

			    ?>
			    <script>
					(function($){

						$(document).ready(function(){
							$('.ff-ark-notice-dismiss').click(function(){
								date = new Date();
								date.setTime(date.getTime()+(5*24*60*60*1000));
								document.cookie = "ff-ark-hide=1; expires="+date.toGMTString()+"; path=/";

								$(this).parents('.updated:first').hide(500);
							});

						});

					})(jQuery)
			    </script>
				<?php
//			    echo '</script>';
		    }

	    }

	    // thank you for using our fresh custom code plugin. We would like to inform you, that we have created the best theme on the planet called ARK. There is limited offer, when you get it for free. Click here for more info

    }
	
	protected function _registerActions() {
		$WPLayer = $this->_getContainer()->getFrameworkContainer()->getWPLayer();
		
		if( $WPLayer->is_admin() ) {
			return false;
		}
		
		$WPLayer->add_action('after_setup_theme', array($this, 'afterSetupTheme' ) );
		$WPLayer->add_action('wp_enqueue_scripts', array($this, 'prepareCustomCodes'), 999);
		$WPLayer->add_action('wp_head', array($this, 'printCustomCodesHeader'), 999);
		$WPLayer->add_action('wp_footer', array($this, 'printCustomCodesFooter'), 9999);
	}
	


	protected function _addAsset($placement, $type, $code, $priority, $externFile ) {
		$asset = array( 'code' => $code, 'type' => $type, 'extern' => $externFile );
		$this->_assetsHolder[ $placement ][ $priority ][] = $asset; 
	}
	
	public function printCustomCodesFooter() {
		$this->_printCodes('footer');
	}
	
	public function printCustomCodesHeader() {
		$this->_printCodes('header');
	}
	
	public function afterSetupTheme() {
		$fwc = $this->_getContainer()->getFrameworkContainer();
		$allPosts = $fwc->getPostLayer()->getPostGetter()->setNumberOfPosts(1)->getPostsByType( ffPluginFreshCustomCodeLite::CUSTOMCODE_POST_TYPE_SLUG );
		
		if( count( $allPosts ) == 0 ) {
			return false;
		} 
		
		$postmeta = $fwc->getDataStorageFactory()->createDataStorageWPPostMetas_NamespaceFacade();
		
		
		
		foreach( $allPosts as $key => $onePost ) {
			$id = $onePost->getID();
			$postmeta->setNamespace( $id );
			
			$type = $postmeta->getOption( 'customcode_type');
			
			if(  $type['radio']['type'] == 'php' ) {
				
				
				$code =  $postmeta->getOption( 'customcode_code');
				$type = $postmeta->getOption( 'customcode_type');
				if(is_array( $code ) && isset( $code['code'] ) && isset( $code['code']['code'] ) ) {
					$code = $code['code']['code'];
				} else { 
					$code = '';
				}
				
				eval( $code );
				
				
			}
		}
	
		
		$this->_allPosts = $allPosts;
	}
	
	public function prepareCustomCodes() {
		
		$fwc = $this->_getContainer()->getFrameworkContainer();
		if( $this->_getContainer()->getFrameworkContainer()->getWPLayer()->is_admin() ) {
			
			$cache = $fwc->getDataStorageCache();
			$cache->deleteOldFilesInNamespace(ffPluginFreshCustomCodeLite::CUSTOMCODE_CACHE_NAMESPACE , 60 * 60 * 24 * 7, 60*60*24*5);

			return false;
		}



		//
		$allPosts = $this->_allPosts;//$fwc->getPostLayer()->getPostGetter()->getPostsByType( ffPluginFreshCustomCode::CUSTOMCODE_POST_TYPE_SLUG );

		if( count( $allPosts ) == 0 ) {
			return false;
		}

		$postmeta = $fwc->getDataStorageFactory()->createDataStorageWPPostMetas_NamespaceFacade();
		$evaluator = $fwc->getLibManager()->createConditionalLogicEvaluator();
		$cache = $fwc->getDataStorageCache();

		foreach( $allPosts as $onePost ) {

			$id = $onePost->getID();
			$postmeta->setNamespace( $id );
			$customLogic = $postmeta->getOption('customcode_logic');

			$query = $fwc->getOptionsFactory()->createQuery($customLogic );

			if( $evaluator->evaluate($query) ) {

				$placement = $postmeta->getOption('customcode_placement');
				$code =  $postmeta->getOption( 'customcode_code');
				$type = $postmeta->getOption( 'customcode_type');
                if( empty( $code ) || !isset( $code['code'] ) || !isset( $code['code']['code'] ) ) {
                    continue;
                }
				$code = $code['code']['code'];
				if(  $type['radio']['type'] == 'less' ) {
					$code = $postmeta->getOption('customcode_less');
				}

				$placementQuery = $fwc->getOptionsFactory()->createQuery($placement, 'ffOptionsHolderCustomCodePlacement');

				$externalFile =  $placementQuery->get('radio extern_file');
				// IS EXTERNAL FILE
				if( $externalFile == 'external' ) {

					switch(  $type['radio']['type']  ) {
						case 'less':
						case 'css':
							$ext = 'css';
							break;
						case 'js':
						case 'tracking_code':
							$ext = 'js';
							break;
					}
					$codeHash = md5( $code );
					$namespace = ffPluginFreshCustomCodeLite::CUSTOMCODE_CACHE_NAMESPACE;
					
					if( $cache->optionExists( $namespace, $codeHash, $ext) ) {
						$cache->touch( $namespace, $codeHash, $ext);
					} else {
						$cache->setOption( $namespace, $codeHash, $code, $ext);
						
					}
					$url = $cache->getOptionUrl($namespace, $codeHash, $ext);
					$handle = $fwc->getWPLayer()->sanitize_title($onePost->getTitle());
					
					
				
					$placement['radio']['placement'] == 'header' ? $inFooter = false : $inFooter = true;
			
					
					if( $ext == 'js' ) {
						$fwc->getWPLayer()->wp_enqueue_script($handle,$url, null,null,$inFooter);
					} else if( $ext == 'css' ) {
						$fwc->getWPLayer()->wp_enqueue_style($handle,$url, null,null, $inFooter);
					}
					
					
				}  else {
					$this->_addAsset( $placement['radio']['placement'], $type['radio']['type'], $code, $placement['radio']['priority'], $externalFile);
				}
				
			}
		}
	}
	
	private function _getCodesForPlacement( $placement ) {
		if( isset( $this->_assetsHolder[ $placement] ) ) {
			return $this->_assetsHolder[ $placement ];
		} else {
			return null;
		}
	}
	
	protected function _printCodes( $placement ) {
		$codes = $this->_getCodesForPlacement($placement);
		
		if( $codes == null ) {
			return false;
		}
		ksort( $codes );
		
		foreach( $codes as $priority_key => $priority_codes ) {
			foreach( $priority_codes as $oneCode ) {
				switch( $oneCode['type'] ) {
					case 'js':
						echo '<script type="text/javascript">'."\n";
							echo $oneCode['code']."\n";
						echo '</script>'."\n";
						break;
						
					case 'css':
					case 'less':
						echo '<style type="text/css">'."\n";
						echo $oneCode['code']."\n";
						echo '</style>';
						break;
						
					case 'tracking_code':
						echo $oneCode['code']."\n";
						break;
				}
			}
		}
		
		unset ($this->_assetsHolder[ $placement ] );
	}

	/**
	 * Register custom post types directly for our custom code plugin.
	 * Main slug of this post type is under Appearance
	 */
	private function _registerCustomPostType() {
		$frameworkContainer = $this->_getContainer()
		->getFrameworkContainer();

		$registrator = $frameworkContainer->getPostTypeRegistratorManager()
			->addHiddenPostTypeRegistrator( ffPluginFreshCustomCodeLite::CUSTOMCODE_POST_TYPE_SLUG , 'Custom Code');

		$registrator->getArgs()
			->set('show_in_menu', 'themes.php');

		$registrator->getSupports()
			->set('revisions', false);

		$registrator->getLabels()
			->set('all_items', 'Custom Code');

		return;

	}



	protected function _setDependencies() {

	}


	/**
	 * @return ffPluginFreshCustomCodeLiteContainer
	 */
	protected function _getContainer() {
		return $this->_container;
	}
}