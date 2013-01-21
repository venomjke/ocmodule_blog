<?php

class ModelCatalogBlog extends Model{

	/*
	* Фильтр
	*/
	private $filter;

	public function setFilter($filter)
	{
		$this->filter = $filter;
	}

	/*
	* Загрузка списка категории вместе с постами
	*/
	public function loadCategories($args = array())
	{
		$cats_limit = !empty($args['cats_limit'])? $args['cats_limit']: '';
		$posts_limit= !empty($args['posts_limit'])? $args['posts_limit']: '';

		$cats = $this->getCategories(array( 'limit' => $cats_limit ));
		foreach($cats as & $cat){
			$cat['posts'] = $this->getCategoryPosts($cat['id'], array( 'limit' => $posts_limit ));
		}
		return $cats;
	}

	/*
	* Выбор списка категорий
	*/
	public function getCategories($args = array())
	{
		$query = "SELECT * FROM `" . DB_PREFIX . "blog_categories` ";

		$this->setLimit($query,$args);

		$result = $this->db->query($query);
		return $result->rows;
	}

	/*
	* Подсчет общего числа категорий
	*/
	public function getTotalCategories()
	{
		$query = "SELECT COUNT(*) as cnt FROM `" . DB_PREFIX . "blog_categories`";

		$result = $this->db->query($query);

		if( ! empty($result->row['cnt']) ){
			return $result->row['cnt'];
		}
		return 0;
	}

	/*
	* Выбор категории
	*/
	public function getCategory($id)
	{
		$result = $this->db->query("SELECT * FROM `".DB_PREFIX."blog_categories` WHERE id='".(int)$id."' LIMIT 1");
		return $result->row;
	}

	/*
	* Выбор всех постов
	*/
	public function getPosts($args = array())
	{
		$query = "SELECT * FROM `".DB_PREFIX."blog_posts` WHERE status='1' ";

		$this->setLimit($query, $args);

		$result = $this->db->query($query);
		return $result->rows;
	}

	public function getTotalPosts()
	{
		$query = "SELECT COUNT(*) as cnt FROM `" . DB_PREFIX . "blog_posts`";

		$result = $this->db->query($query);

		if( ! empty($result->row['cnt'])){
			return $result->row['cnt'];
		}
		return 0;
	}

	/*
	* Выбор постов определенной категории
	*/
	public function getCategoryPosts($catId, $args = array())
	{
		$query = "SELECT * FROM `".DB_PREFIX."blog_posts` INNER JOIN `".DB_PREFIX."blog_posts_cats` ON `".DB_PREFIX."blog_posts`.id=`".DB_PREFIX."blog_posts_cats`.postId WHERE catId='".(int)$catId."' AND status='1' ";

		$this->setLimit($query,$args);

		$result = $this->db->query($query);
		return $result->rows;
	}

	/*
	* Подсчет кол-ва постов конкретной категории
	*/
	public function getTotalCategoryPosts($catId)
	{
		$query = "SELECT COUNT(*) as cnt FROM `" . DB_PREFIX . "blog_posts` INNER JOIN `" . DB_PREFIX . "blog_posts_cats` ON `" . DB_PREFIX . "blog_posts`.id=`" . DB_PREFIX . "blog_posts_cats`.postId WHERE catId='" . (int)$catId . "' AND status='1'";

		$result = $this->db->query($query);

		if( ! empty($result->row['cnt']) ){
			return $result->row['cnt'];
		}
		return 0;
	}

	/*
	* Выбор категории по Id Поста
	*/
	public function getCategoryByPostId($postId)
	{
		$result = $this->db->query("SELECT * FROM `".DB_PREFIX."blog_posts_cats` INNER JOIN `".DB_PREFIX."blog_categories` ON `".DB_PREFIX."blog_posts_cats`.catId = `".DB_PREFIX."blog_categories`.id WHERE postId='".(int)$postId."'");
		return $result->rows;
	}

	/*
	* Выбор поста
	*/
	public function getPost($id)
	{
		$result = $this->db->query("SELECT * FROM `".DB_PREFIX."blog_posts` WHERE id='".(int)$id."' LIMIT 1");
		return $result->row;
	}

	private function setLimit(& $query, $args = array())
	{
		if( ! empty($args['limit']) ){
			$query .= "LIMIT " . $args['limit'];

			if( ! empty($args['offset']) ){
				$query .= " OFFSET " . ($args['limit'] * ($args['offset']-1)); 
			}
		}
	}
}