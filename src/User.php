<?php

namespace DoctrineTest;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 *
 */
class User
{
	/**
	 * Loads the metadata for the specified class into the provided container.
	 *
	 * @param ClassMetadata $metadata
	 *
	 * @return void
	 */
	public static function loadMetadata(ClassMetadata $metadata)
	{
		//$metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_AUTO);

		$metadata->setPrimaryTable([
			'name' => 'users',
		]);

		$metadata->mapField([
			'id' => true,
			'fieldName' => 'id',
			'columnName' => 'user_id',
			'type' => 'integer',
			'length' => 9,
		]);

		$metadata->mapField([
			'fieldName' => 'name',
			'columnName' => 'name',
			'type' => 'string',
			'length' => 255,
		]);

		$metadata->mapOneToMany([
			'fieldName' => 'posts',
			'targetEntity' => Post::class,
			'mappedBy' => 'author',
			'cascade' => ['persist', 'refresh'],
		]);
	}

	/**
	 * User-ID
	 *
	 * @var integer
	 */
	private $id;

	/**
	 * name
	 *
	 * @var string
	 */
	private $name;

	/**
	 * posts
	 *
	 * @var Post[]
	 */
	private $posts;

	/**
	 * Set Collection
	 */
	public function __construct()
	{
		$this->posts = new ArrayCollection();
	}

	/**
	 * Get the User-ID
	 *
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set the User-ID
	 *
	 * @param integer $id
	 *
	 * @return void
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * Get the user name
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Set the user name
	 *
	 * @param string $name
	 *
	 * @return void
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * Get the posts
	 *
	 * @return Post[]
	 */
	public function getPosts()
	{
		return $this->posts;
	}

	/**
	 * Add a Post
	 *
	 * @param Post $post
	 *
	 * @return void
	 */
	public function addPost(Post $post)
	{
		$this->posts[] = $post;
		$post->setAuthor($this);
	}
}
