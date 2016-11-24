<?php

namespace Tests\DoctrineTest;

use Doctrine\Common\Persistence\Mapping\Driver\StaticPHPDriver;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use DoctrineTest\User;
use DoctrineTest\Post;

class DoctrineTest extends \PHPUnit_Framework_TestCase
{
	private static $entityManager;

	private $em;

	public function setUp()
	{
		if (static::$entityManager === null)
		{
			static::$entityManager = $this->createEntityManager();

			$pdo = static::$entityManager->getConnection()->getWrappedConnection();

			$pdo->exec("CREATE TABLE posts (id integer(9), author_id integer(9))");
			$pdo->exec("CREATE TABLE users (id integer(9))");
		}

		$this->em = static::$entityManager;
	}

	private function createEntityManager()
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
		return EntityManager::create($conn, $config);
	}

	public function testCreateUser()
	{
		$user = new User;

		$this->em->persist($user);

		$this->em->flush();

		$this->em->clear();

		$users = $this->em->getRepository(User::class)->findAll();

		$this->assertContainsOnlyInstancesOf(User::class, $users);
		$this->assertCount(1, $users);
	}

	/**
	 * @depends testCreateUser
	 */
	public function testCreatePost()
	{
		$post = new Post;

		$users = $this->em->getRepository(User::class)->findAll();

		$this->assertCount(1, $users);

		$user = $users[0];

		$user->addPost($post);

		$this->em->persist($post);
		$this->em->flush();

		$posts = $this->em->getRepository(Post::class)->findAll();

		$this->assertCount(1, $posts);
		$this->assertContainsOnlyInstancesOf(Post::class, $posts);
	}
}
