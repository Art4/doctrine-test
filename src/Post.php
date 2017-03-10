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

		$metadata->mapField([
			'fieldName' => 'title',
			'columnName' => 'title',
			'type' => 'string',
			'length' => 255,
		]);

		$metadata->mapManyToOne([
			'fieldName' => 'author',
			'targetEntity' => User::class,
			'inversedBy' => 'posts',
			'cascade' => ['persist', 'refresh'],
		]);
	}

	/**
	 * Post-ID
	 *
	 * @var integer
	 */
	private $id;

	/**
	 * title
	 *
	 * @var string
	 */
	private $title;

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
	 * Set the post id
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
	 * Get the post title
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Set the post title
	 *
	 * @param string $title
	 *
	 * @return void
	 */
	public function setTitle($title)
	{
		$this->title = $title;
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
	 * @return void
	 */
	public function setAuthor(User $author)
	{
		$this->author = $author;
	}

	/**
	 * Add a comment
	 *
	 * @param Comment $comment
	 *
	 * @return void
	 */
	public function addComment(Comment $comment)
	{
		$comment->setParent($this);
	}
}
