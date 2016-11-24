<?php

namespace DotrineTest\Tests;

use Doctrine\Common\Persistence\Mapping\Driver\StaticPHPDriver;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class DoctrineTest extends \PHPUnit_Framework_TestCase
{
	private $em;

	public function setUp()
	{
		// Create a simple "default" Doctrine ORM configuration for static PHP
		$isDevMode = true;

		$config = Setup::createConfiguration($isDevMode, 'tmp');

		$config->setMetadataDriverImpl(new StaticPHPDriver(array(__DIR__."/../src")));

		// database configuration parameters
		$conn = array(
			'driver' => 'pdo_sqlite',
			'path' => ':memory:',
		);

		// obtaining the entity manager
		$this->em = EntityManager::create($conn, $config);

		$pdo = $this->em->getConnection()->getWrappedConnection();

		$pdo->exec("CREATE TABLE posts (id integer(9), author_id integer(9))");
		$pdo->exec("CREATE TABLE users (id integer(9))");
	}

	public function testCreatePostAndUser()
	{
		$this->assertInstanceOf(EntityManager::class, $this->em);
	}
}
