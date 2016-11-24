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
			$db_file = __DIR__ . '/../tmp/db.sqlite3';

			if (file_exists($db_file))
			{
				unlink($db_file);
			}

			static::$entityManager = $this->createEntityManager($db_file);

			$pdo = static::$entityManager->getConnection()->getWrappedConnection();

			$pdo->exec("CREATE TABLE posts (id integer(9), title varchar(255), author_id integer(9))");
			$pdo->exec("CREATE TABLE users (id integer(9), name varchar(255))");
		}

		$this->em = static::$entityManager;
	}

	private function createEntityManager($db_file)
	{
		// Create a simple "default" Doctrine ORM configuration for static PHP
		$isDevMode = true;

		$config = Setup::createConfiguration($isDevMode, 'tmp');

		$config->setMetadataDriverImpl(new StaticPHPDriver(array(__DIR__."/../src")));

		// database configuration parameters
		$conn = array(
			'driver' => 'pdo_sqlite',
			'path' => ':memory:',
			//'path' => $db_file,
		);

		// obtaining the entity manager
		return EntityManager::create($conn, $config);
	}

	public function testCreateUsers()
	{
		$user1 = new User;
		$user1->setId(1);
		$user1->setName('Max');
		$this->em->persist($user1);

		$user2 = new User;
		$user2->setId(2);
		$user2->setName('Moritz');
		$this->em->persist($user2);

		$this->em->flush();

		$users = $this->em->getRepository(User::class)->findAll();

		$this->assertContainsOnlyInstancesOf(User::class, $users);

		$result = [];

		foreach ($users as $user)
		{
			$result[$user->getId()] = $user->getName();
		}

		$this->assertSame([
			1 => 'Max',
			2 => 'Moritz',
		], $result);
	}

	/**
	 * @depends testCreateUsers
	 */
	public function testCreatePosts()
	{
		$user = $this->em->getRepository(User::class)->findOneBy(['name' => 'Max']);

		$post = new Post;
		$post->setId(1);
		$post->setTitle('Hello World!');

		$user->addPost($post);

		$this->em->persist($post);
		$this->em->flush();

		$posts = $this->em->getRepository(Post::class)->findAll();

		$this->assertContainsOnlyInstancesOf(Post::class, $posts);
		$result = [];

		foreach ($posts as $post)
		{
			$result[$post->getId()] = $post->getTitle();
		}

		$this->assertSame([
			1 => 'Hello World!',
		], $result);


		$this->assertCount(1, $posts);
	}

	/**
	 * @depends testCreatePosts
	 */
	public function testGetPosts()
	{
		$user = $this->em->getRepository(User::class)->findOneBy(['name' => 'Max']);

		$posts = $user->getPosts();

		$this->assertCount(1, $posts);
		$this->assertContainsOnlyInstancesOf(Post::class, $posts);
	}
}
