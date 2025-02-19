<?php

namespace EO\Model;

use Pecee\Exceptions\InvalidArgumentException;
use EO\Interfaces\IModel as IModel;
use EO\Database\DataModel;
use EO\Model\Traits\ArticleTrait;

class ArticleModel extends \EO\Model implements IModel
{
	protected $table = 'articles';
	protected $primaryKey = 'article_id';

	protected $properties = [
		"article_id",
		"category",
		"title",
		"name",
		"content",
		"is_published",
		"created_by",
		"created_at",
		"modified_by",
		"modified_at"
	];
}
