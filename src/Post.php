<?php

namespace DoctrineTest;

use Doctrine\ORM\Mapping\ClassMetadata;

/**
 *
 */
class Post
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
			'name' => 'posts',
		]);

		$metadata->mapField([
			'id' => true,
			'fieldName' => 'id',
			'columnName' => 'id',
			'type' => 'integer',
			'length' => 9,
		]);

		$metadata->mapManyToOne([
			'fieldName' => 'author',
			'targetEntity' => DoctrineTest\User::class,
			'inversedBy' => 'posts',
			'cascade' => ['persist'],
		]);
	}

	/**
	 * Post-ID
	 *
	 * @var integer
	 */
	private $id;

	/**
	 * Author
	 *
	 * @var Author
	 */
	private $author;

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
	 * Get the Author
	 *
	 * @return User
	 */
	public function getUser()
	{
		return $this->author;
	}

	/**
	 * Set the Author
	 *
	 * @param User $author
	 *
	 * @return self
	 */
	public function setAuthor(User $author)
	{
		$this->author = $author;

		return $this;
	}
}
