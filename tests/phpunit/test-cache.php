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
}
