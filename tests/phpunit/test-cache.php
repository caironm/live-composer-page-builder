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
	 * Test: Cache Enabled
	 */
	public function test_cache_enabled() {

		$expected = array(
			'lc_caching_engine' => 'enabled',
		);

		// Enable a cache.
		update_option( 'dslc_plugin_options', $expected );
		$dslc_plugin_options = get_option( 'dslc_plugin_options' );
		$dslc_cache = $dslc_plugin_options['lc_caching_engine'];

		// Add a custom transient cache in the DB.
		set_transient( 'lc_cache', 'lc_custom_cache' );
		$transient_lc_cache = get_transient( 'lc_cache' );

		// Check if cache is enabled.
		$this->assertEquals( 'enabled', $dslc_cache );

		// Check if have a transient cache in the DB.
		$this->assertEquals( 'lc_custom_cache', $transient_lc_cache );
	}

	/**
	 * Test: Cache Disabled
	 */
	public function test_cache_disabled() {

		$expected = array(
			'lc_caching_engine' => 'disabled',
		);

		// Disable a cache.
		update_option( 'dslc_plugin_options', $expected );
		$dslc_plugin_options = get_option( 'dslc_plugin_options' );
		$dslc_cache = $dslc_plugin_options['lc_caching_engine'];

		// Add a custom transient cache in the DB.
		set_transient( 'lc_cache', 'lc_custom_cache' );
		$transient_lc_cache = get_transient( 'lc_cache' );

		// Check if cache is disabled.
		$this->assertEquals( 'disabled', $dslc_cache );

		// Check if does not have a transient cache in the DB.
		$this->assertTrue( delete_transient( 'lc_cache' ) );
	}
}
