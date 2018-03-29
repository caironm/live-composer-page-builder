<?php
/**
 * Class Cache
 *
 * @package Live_Composer_Page_Builder
 */

/**
 * Test Cache.
 */
class Cache extends WP_UnitTestCase {

	/**
	 * Test: Cache Enabled/Disabled
	 */
	public function test_cache_enabled_or_disabled() {

		/**
		 * Cache is enabled
		 */

		$lc_cache     = new DSLC_Cache();
		$ref_object   = new ReflectionObject( $lc_cache );
		$ref_property = $ref_object->getProperty( 'enabled' ); // private static $enabled.
		$ref_property->setAccessible( true );
		$ref_property->setValue( null, true ); // cache is enabled

		$cache_enabled = $lc_cache->enabled();
		$lc_cache->set_cache( 'lc_custom_cache', '5', 'html' );
		$transient_lc_cache = $lc_cache->get_cache( '5', 'html' );

		// Check if cache is enabled.
		$this->assertTrue( $cache_enabled );

		// Check if have a transient cache in the DB.
		$this->assertEquals( 'lc_custom_cache', $transient_lc_cache );

		/**
		 * Cache is disabled
		 */

		$ref_property->setValue( null, false ); // cache is disabled
		$cache_disabled = $lc_cache->enabled();
		$lc_cache->delete_cache();
		$transient_lc_cache = $lc_cache->get_cache( '5', 'html' );

		// Check if cache is disabled.
		$this->assertFalse( $cache_disabled );

		// Check if does not have a transient cache in the DB.
		$this->assertEmpty( $transient_lc_cache );

		// fwrite( STDERR, print_r( $transient_lc_cache, true ) );
	}

	/**
	 * Test: Cache Disabled
	 */
	public function test_cache_update_page() {

		// Cache is enabled.
		$lc_cache     = new DSLC_Cache();
		$ref_object   = new ReflectionObject( $lc_cache );
		$ref_property = $ref_object->getProperty( 'enabled' ); // private static $enabled.
		$ref_property->setAccessible( true );
		$ref_property->setValue( null, true ); // cache is enabled

		$cache_enabled = $lc_cache->enabled();

		// Check if cache is enabled.
		$this->assertTrue( $cache_enabled );

		$page_id = $this->factory->post->create(
			array(
				'post_type'    => 'page',
				'post_title'   => 'Page Test Cache',
				'post_content' => 'Test Cache',
			)
		);

		$lc_cache->set_cache( 'lc_html', $page_id, 'html' );
		$lc_cache->set_cache( 'lc_css', $page_id, 'css' );
		$lc_cache->set_cache( 'lc_fonts', $page_id, 'fonts' );

		// Check if a page is a cached.
		$this->assertTrue( $lc_cache->cached( $page_id ) );

		// Update Page.
		$update_page                 = array();
		$update_page['ID']           = $page_id;
		$update_page['post_content'] = 'New Content';

		wp_update_post( wp_slash( $update_page ) );

		// Check if a page is not cached.
		$this->assertFalse( $lc_cache->cached( $page_id ) );

		//fwrite( STDERR, print_r( $lc_cache->cached( $page_id ), true ) );
	}
}
