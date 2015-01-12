<?php
defined('ABSPATH') or die();

class Basement_Map_Google extends Basement_Shortcode {

	private $maps = array();

	public function section_config( $config = array() ) {

		// TODO: to Shortcode_Config_Factory add ->addBlock() && ->setTitle() && ->setDesciption() && ->create() methods
		$config = array(
			'description' => __( 'Creates google map with custom parameters. You can get latitude and longitude here http://www.latlong.net/', BASEMENT_TEXTDOMAIN ),
			'blocks' => array()
		);

		// TODO: add ability to add links to description
		// $longlat_service_link = $container->appendChild( $container->ownerDocument->createElement( 'a', __( 'You can get latitude and longitude values here', BASEMENT_TEXTDOMAIN ) ) );
		// $longlat_service_link->setAttribute( 'href', 'http://www.latlong.net/' );
		// $longlat_service_link->setAttribute( 'target', '_blank' );

		$config[ 'blocks' ][] = array(
			'title' => __( 'Latitude', BASEMENT_TEXTDOMAIN ),
			'description' => __( 'Set map center point latitude.', BASEMENT_TEXTDOMAIN ),
			'param' => 'latitude'
		);

		$config[ 'blocks' ][] = array(
			'title' => __( 'Longitude', BASEMENT_TEXTDOMAIN ),
			'description' => __( 'Set map center point longitude.', BASEMENT_TEXTDOMAIN ),
			'param' => 'longitude'
		);

		$config[ 'blocks' ][] = array(
			'title' => __( 'Zoom', BASEMENT_TEXTDOMAIN ),
			'description' => __( 'Use zoom value from 0 to 19.', BASEMENT_TEXTDOMAIN ),
			'param' => 'zoom'
		);

		$config[ 'blocks' ][] = array(
			'title' => __( 'Height', BASEMENT_TEXTDOMAIN ),
			'description' => __( 'Sets map container height.', BASEMENT_TEXTDOMAIN ),
			'param' => 'height'
		);

		$config[ 'blocks' ][] = array(
			'title' => __( 'Full screen text', BASEMENT_TEXTDOMAIN ),
			'description' => __( 'Sets the text for full-screen button', BASEMENT_TEXTDOMAIN ),
			'param' => 'full_screen'
		);


		return $config;

	}

	protected function panel_markup_dom( $container ) {
		$param = Shortcode::create_param_wrapper( $container, 'text', 'latitude', false );
		$param->appendChild( $container->ownerDocument->importNode( $this->form->create_input( array(
						'label_text' => __( 'Latitude', BASEMENT_TEXTDOMAIN )
					) 
				), true
			)
		);

		$param = Shortcode::create_param_wrapper( $container, 'text', 'longitude', false );
		$param->appendChild( $container->ownerDocument->importNode( $this->form->create_input( array(
						'label_text' => __( 'Longitude', BASEMENT_TEXTDOMAIN )
					) 
				), true
			)
		);

		$longlat_service_link = $container->appendChild( $container->ownerDocument->createElement( 'a', __( 'You can get latitude and longitude values here', BASEMENT_TEXTDOMAIN ) ) );
		$longlat_service_link->setAttribute( 'href', 'http://www.latlong.net/' );
		$longlat_service_link->setAttribute( 'target', '_blank' );
		$container->appendChild( $container->ownerDocument->createElement( 'br' ) );
		$container->appendChild( $container->ownerDocument->createElement( 'br' ) );

		$param = Shortcode::create_param_wrapper( $container, 'text', 'zoom', true );
		$param->appendChild( $container->ownerDocument->importNode( $this->form->create_input( array(
						'label_text' => __( 'Zoom', BASEMENT_TEXTDOMAIN )
					) 
				), true
			)
		);

		$param = Shortcode::create_param_wrapper( $container, 'text', 'height', true );
		$param->appendChild( $container->ownerDocument->importNode( $this->form->create_input( array(
						'label_text' => __( 'Height', BASEMENT_TEXTDOMAIN )
					) 
				), true
			)
		);

		$param = Shortcode::create_param_wrapper( $container, 'text', 'full_screen', true );
		$param->appendChild( $container->ownerDocument->importNode( $this->form->create_input( array(
						'label_text' => __( 'Full screen text', BASEMENT_TEXTDOMAIN )
					) 
				), true
			)
		);
		
	}

	public function render( $atts = array(), $content = '' ) {
		extract( $atts = wp_parse_args( $atts, array(
			'latitude' => '',
			'longitude' => '',
			'zoom' => '11',
			'height' => '',
			'full_screen' => '',
		) ) );

		$atts[ 'id' ] = 'google_map_' . md5( serialize( $atts ) ); 
		$this->maps[] = $atts;

		$dom = new DOMDocument( '1.0', 'UTF-8' );
		$map = $dom->appendChild( $dom->createElement( 'div' ) );
		$map->setAttribute( 'class', 'map basement_google_map' );

		if ( $full_screen ) {
			$container = $map->appendChild( $dom->createElement( 'div' ) );
			$container->setAttribute( 'class', 'container' );
			
			$col = $container->appendChild( $dom->createElement( 'div' ) );
			$col->setAttribute( 'class', 'col-xs-12' );

			$a = $col->appendChild( $dom->createElement( 'a', $full_screen ) );
			$a->setAttribute( 'class', 'block-link right' );
			$a->setAttribute( 'href', '#' );
			$a->setAttribute( 'data-toggle', 'modal' );
			$a->setAttribute( 'data-target', '#' . $atts[ 'id' ] );
		}

		$map = $map->appendChild( $dom->createElement( 'div' ) );
		$map->setAttribute( 'class', 'basement_google_map_container' );
		$map->setAttribute( 'data-longitude', $longitude );
		$map->setAttribute( 'data-latitude', $latitude );
		$map->setAttribute( 'data-zoom', $zoom );

		$height = absint( $height );
		if ( $height ) {
			$map->setAttribute( 'style', 'height:' . $height . 'px' );
		}

		

		add_action( 'wp_footer', array( &$this, 'print_maps_modals_data' ) );

		return $dom->saveHTML();
	}

	public function print_maps_modals_data() {
		if ( !count( $this->maps ) ) {
			return;
		}
		

		foreach ( $this->maps  as $map ) {
			$dom = new DOMDocument( '1.0', 'UTF-8' );
			
			$modal = $dom->appendChild( $dom->createElement( 'div' ) );
			$modal->setAttribute( 'class', 'modal fade' );
			$modal->setAttribute( 'id', $map[ 'id' ] );
			
			$close = $modal->appendChild( $dom->createElement( 'a' ) );
			$close->setAttribute( 'href', '#' );
			$close->setAttribute( 'class', 'map-close' );
			$close->setAttribute( 'data-dismiss', 'modal' );
			$close->appendChild( $dom->createElement( 'i' ) );

			$divtable = $modal->appendChild( $dom->createElement( 'div' ) );
			$divtable->setAttribute( 'class', 'divtable basement_google_map_popup' );

			echo $dom->saveHTML();
			

		}

	}

}
