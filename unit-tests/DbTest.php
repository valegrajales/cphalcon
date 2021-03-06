<?php

/*
  +------------------------------------------------------------------------+
  | Phalcon Framework                                                      |
  +------------------------------------------------------------------------+
  | Copyright (c) 2011-2012 Phalcon Team (http://www.phalconphp.com)       |
  +------------------------------------------------------------------------+
  | This source file is subject to the New BSD License that is bundled     |
  | with this package in the file docs/LICENSE.txt.                        |
  |                                                                        |
  | If you did not receive a copy of the license and are unable to         |
  | obtain it through the world-wide-web, please send an email             |
  | to license@phalconphp.com so we can send you a copy immediately.       |
  +------------------------------------------------------------------------+
  | Authors: Andres Gutierrez <andres@phalconphp.com>                      |
  |          Eduar Carvajal <eduar@phalconphp.com>                         |
  +------------------------------------------------------------------------+
*/

class DbTest extends PHPUnit_Framework_TestCase {

	public function testDbMysql(){

		require 'unit-tests/config.db.php';

		$connection = Phalcon_Db::factory('Mysql', $configMysql);
		$this->assertTrue(is_object($connection));

		$this->assertEquals($connection->getDatabaseName(), $configMysql->name);
		$this->assertEquals($connection->getHostname(), $configMysql->host);
		$this->assertEquals($connection->getUsername(), $configMysql->username);
		$this->assertEquals($connection->getDefaultSchema(), $configMysql->name);

		$this->_executeTests($connection, 'Phalcon_Db_Result_Mysql');

	}

	public function testDbPostgresql(){

		require 'unit-tests/config.db.php';

		$connection = Phalcon_Db::factory('Postgresql', $configPostgresql);
		$this->assertTrue(is_object($connection));

		$this->assertEquals($connection->getDatabaseName(), $configPostgresql->name);
		$this->assertEquals($connection->getHostname(), $configPostgresql->host);
		$this->assertEquals($connection->getUsername(), $configPostgresql->username);
		$this->assertEquals($connection->getDefaultSchema(), $configPostgresql->name);

		$this->_executeTests($connection, 'Phalcon_Db_Result_Postgresql');

	}

	protected function _executeTests($connection, $resultType){

		$result = $connection->query("SELECT * FROM personas LIMIT 3");
		$this->assertTrue(is_object($result));
		$this->assertEquals($resultType, get_class($result));

		for($i=0;$i<3;$i++){
			$row = $result->fetchArray();
			$this->assertEquals(count($row), 22);
		}

		$row = $result->fetchArray();
		$this->assertEquals($row, false);
		$this->assertEquals($result->numRows(), 3);

		$number = 0;
		$result = $connection->query("SELECT * FROM personas LIMIT 5");
		$this->assertTrue(is_object($result));

		while($row = $result->fetchArray()){
			$number++;
		}
		$this->assertEquals($number, 5);

		$result = $connection->query("SELECT * FROM personas LIMIT 5");
		$result->setFetchMode(Phalcon_Db::DB_NUM);
		$row = $result->fetchArray();
		$this->assertEquals(count($row), 11);

		$result = $connection->query("SELECT * FROM personas LIMIT 5");
		$result->setFetchMode(Phalcon_Db::DB_ASSOC);
		$row = $result->fetchArray();
		$this->assertEquals(count($row), 11);

		$result = $connection->query("SELECT * FROM personas LIMIT 5");
		$result->setFetchMode(Phalcon_Db::DB_BOTH);
		$result->dataSeek(4);
		$row = $result->fetchArray();
		$row = $result->fetchArray();
		$this->assertEquals($row, false);

		$result = $connection->query("DELETE FROM prueba");
		$this->assertTrue($result);

		$success = $connection->insert('prueba', array('0', "'LOL 1'", "'A'"));
		$this->assertTrue($success);

		$success = $connection->insert('prueba', array("'LOL 2'", "'E'"), array('nombre', 'estado'));
		$this->assertTrue($success);

		$success = $connection->insert('prueba', array("LOL 3", "I"), array('nombre', 'estado'), true);
		$this->assertTrue($success);

		$success = $connection->insert('prueba', array(new Phalcon_Db_RawValue('current_date'), "'A'"), array('nombre', 'estado'));
		$this->assertTrue($success);

		for($i=0;$i<50;$i++){
			$success = $connection->insert('prueba', array("LOL ".$i, "F"), array('nombre', 'estado'), true);
			$this->assertTrue($success);
		}

		$success = $connection->update('prueba', array("nombre", "estado"), array("'LOL 1000'", "'X'"), "estado='E'");
		$this->assertTrue($success);

		$success = $connection->update('prueba', array("nombre"), array("'LOL 2500'"), "estado='X'");
		$this->assertTrue($success);

		$success = $connection->update('prueba', array("nombre"), array("LOL 3000"), "estado='X'", true);
		$this->assertTrue($success);

		$success = $connection->update('prueba', array("nombre"), array(new Phalcon_Db_RawValue('current_date')), "estado='X'", false);
		$this->assertTrue($success);

		$connection->delete("prueba", "estado='X'");
		$this->assertTrue($success);

		$connection->delete("prueba");
		$this->assertTrue($success);
		$this->assertEquals($connection->affectedRows(), 53);

		$row = $connection->fetchOne("SELECT * FROM personas");
		$this->assertEquals(count($row), 22);

		$row = $connection->fetchOne("SELECT * FROM personas", Phalcon_Db::DB_NUM);
		$this->assertEquals(count($row), 11);

		$rows = $connection->fetchAll("SELECT * FROM personas LIMIT 10");
		$this->assertEquals(count($rows), 10);

		$rows = $connection->fetchAll("SELECT * FROM personas LIMIT 10", Phalcon_Db::DB_NUM);
		$this->assertEquals(count($rows), 10);
		$this->assertEquals(count($rows[0]), 11);

	}

}
