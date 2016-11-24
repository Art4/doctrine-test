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
		$metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_AUTO);

		$metadata->setPrimaryTable([
			'name' => 'users',
		]);

		$metadata->mapField([
			'id' => true,
			'fieldName' => 'id',
			'columnName' => 'id',
			'type' => 'integer',
			'length' => 9,
		]);

		$metadata->mapOneToMany([
			'fieldName' => 'posts',
			'targetEntity' => DoctrineTest\Post::class,
			'mappedBy' => 'author',
			'cascade' => ['persist'],
		]);
	}

	/**
	 * User-ID
	 *
	 * @var integer
	 */
	private $id;

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
		$this->permissions = new ArrayCollection();
	}

	/**
	 * Get the Post-ID
	 *
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
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
	 * @return self
	 */
	public function addPost(Post $post)
	{
		$this->posts[] = $post;
		$post->setAuthor($this);

		return $this;
	}
}
