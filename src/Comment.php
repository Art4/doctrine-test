<?php

namespace DoctrineTest;

use Doctrine\ORM\Mapping\ClassMetadata;

/**
 *
 */
class Comment
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
			'name' => 'comments',
		]);

		$metadata->mapField([
			'id' => true,
			'fieldName' => 'id',
			'columnName' => 'id',
			'type' => 'integer',
			'length' => 9,
		]);

		$metadata->mapField([
			'fieldName' => 'content',
			'columnName' => 'content',
			'type' => 'text',
		]);

		$metadata->mapField([
			'fieldName' => 'parent_type',
			'columnName' => 'parent_type',
			'type' => 'string',
			'length' => 255,
		]);

		$metadata->mapField([
			'fieldName' => 'parent_id',
			'columnName' => 'parent_id',
			'type' => 'string',
			'length' => 255,
		]);

		$metadata->mapManyToOne([
			'fieldName' => 'author',
			'targetEntity' => User::class,
			'cascade' => [],
		]);
	}

	/**
	 * Comment-ID
	 *
	 * @var integer
	 */
	private $id;

	/**
	 * content
	 *
	 * @var string
	 */
	private $content;

	/**
	 * parent_type
	 *
	 * @var string
	 */
	private $parent_type;

	/**
	 * parent_id
	 *
	 * @var string
	 */
	private $parent_id;

	/**
	 * Author
	 *
	 * @var Author
	 */
	private $author;

	/**
	 * Get the Comment-ID
	 *
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set the comment id
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
	 * Get the content
	 *
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * Set the content
	 *
	 * @param string $content
	 *
	 * @return void
	 */
	public function setContent($content)
	{
		$this->content = $content;
	}

	/**
	 * Get the parent type
	 *
	 * @return string
	 */
	public function getParentType()
	{
		return $this->parent_type;
	}

	/**
	 * Get the parent id
	 *
	 * @return string
	 */
	public function getParentId()
	{
		return $this->parent_id;
	}

	/**
	 * Set the parent
	 *
	 * @param string $content
	 *
	 * @return void
	 */
	public function setParent($parent)
	{
		if ($parent instanceOf Post)
		{
			$this->parent_type = 'post';
			$this->parent_id = $parent->getId();
		}
	}

	/**
	 * Get the Author
	 *
	 * @return User
	 */
	public function getAuthor()
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
}
