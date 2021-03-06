<?php
/**
 * @package framework
 * @subpackage testing
 */

class MySQLDatabaseTest extends SapphireTest {
	protected $extraDataObjects = array(
		'MySQLDatabaseTest_DO',
	);
	
	function setUp() {
		if(DB::getConn() instanceof MySQLDatabase) {
			MySQLDatabaseTest_DO::$db = array(
				'MultiEnum1' => 'MultiEnum("A, B, C, D","")',
				'MultiEnum2' => 'MultiEnum("A, B, C, D","A")',
				'MultiEnum3' => 'MultiEnum("A, B, C, D","A, B")',
			);
		}
		$this->markTestSkipped('This test requires the Config API to be immutable');
		parent::setUp();
	}
		
	/**
	 * Check that once a schema has been generated, then it doesn't need any more updating
	 */
	function testFieldsDontRerequestChanges() {
		// These are MySQL specific :-S
		if(DB::getConn() instanceof MySQLDatabase) {
			$db = DB::getConn();
			DB::quiet();
		
			// Verify that it doesn't need to be recreated
			$db->beginSchemaUpdate();
			$obj = new MySQLDatabaseTest_DO();
			$obj->requireTable();
			$needsUpdating = $db->doesSchemaNeedUpdating();
			$db->cancelSchemaUpdate();

			$this->assertFalse($needsUpdating);
		}
	}
}

class MySQLDatabaseTest_DO extends DataObject implements TestOnly {
	static $db = array();

}
