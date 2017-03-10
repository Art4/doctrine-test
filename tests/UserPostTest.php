<?php

namespace Tests\DoctrineTest;

use Doctrine\Common\Persistence\Mapping\Driver\StaticPHPDriver;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use DoctrineTest\Comment;
use DoctrineTest\User;
use DoctrineTest\Post;
use PHPUnit\Framework\TestCase;

class UserPostTest extends TestCase
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

			$pdo->exec("CREATE TABLE comments (id integer(9), content text, parent_type varchar(255), parent_id varchar(255), author_id integer(9))");
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
		$user1 = $this->em->getRepository(User::class)->find(1);
		$user2 = $this->em->getRepository(User::class)->find(2);

		$post1 = new Post;
		$post1->setId(1);
		$post1->setTitle('Hello World!');

		$post2 = new Post;
		$post2->setId(2);
		$post2->setTitle('Awesome post');

		$post3 = new Post;
		$post3->setId(3);
		$post3->setTitle('10 ways to code');

		$user1->addPost($post1);
		$user1->addPost($post2);
		$user2->addPost($post3);

		$this->em->flush();

		$posts = $this->em->getRepository(Post::class)->findAll();

		$this->assertCount(3, $posts);
		$this->assertContainsOnlyInstancesOf(Post::class, $posts);

		$result = [];

		foreach ($posts as $post)
		{
			$result[$post->getId()] = $post->getTitle();
		}

		$this->assertSame([
			1 => 'Hello World!',
			2 => 'Awesome post',
			3 => '10 ways to code',
		], $result);
	}

	/**
	 * @depends testCreatePosts
	 */
	public function testGetPosts()
	{
		$user = $this->em->getRepository(User::class)->findOneBy(['name' => 'Max']);

		$posts = $user->getPosts();

		$this->assertCount(2, $posts);
		$this->assertContainsOnlyInstancesOf(Post::class, $posts);
	}

	/**
	 * @depends testGetPosts
	 */
	public function testCreateComments()
	{
		$post = $this->em->getRepository(Post::class)->find(1);
		$user = $this->em->getRepository(User::class)->findOneBy(['name' => 'Max']);

		$comment = new Comment();
		$comment->setId(1);
		$comment->setContent('Toller Beitrag, vielen Dank!');
		$comment->setAuthor($user);

		$post->addComment($comment);

		$this->em->persist($comment);
		$this->em->flush();

		$this->assertInstanceOf(Comment::class, $comment);
	}
}
