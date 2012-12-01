<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 	新闻数据模型
 *
 */
class News_mdl extends CI_Model
{
	protected $primary_key = 'id';
	public static $tbl_news = 'news';

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 添加news
	 *
	 * @param array $input
	 * @return int
	 */
	public function insert($input)
	{
		$this->db->insert(self::$tbl_news, $input);
		return $this->db->insert_id();
	}

	/**
	 * 获取news
	 *
	 * @param	int		$limit	数量
	 * @param	int		$offset	偏移量
	 * @param	int		$type   新闻类型
	 * @param	string	$order   排序
	 * @param	int  	$hav_pic 是否包含图片
	 * 
	 * @return	array
	 */
	public function get_many($limit = NULL, $offset = NULL, $type = NULL, $order = 'created_on', $hav_pic = NULL)
	{
		if(is_numeric($limit))
		{
			$this->db->limit($limit);
		}
		if(is_numeric($offset))
		{
			$this->db->offset($offset);
		}

		if(isset($type) AND $type)
		{
			$this->db->where('news_type', $type);
		}
		
		return $this->db->select('news.*,nt.type_name AS type')
					->order_by($order, 'desc')
					->from(self::$tbl_news)
					->join('news_type AS nt', 'nt.id = news.news_type', 'left')
					->get()
					->result_array();
	}
	
	/**
	 * 获取最新一条带图片的解决方案
	 *
	 * @param	int		$type	新闻类型
	 *
	 * @return	array
	 */
	public function get_new_pic($type = 3)
	{	
		$rs = $this->db->where('news_type', $type)
			->where('hav_pic',1)
		    ->order_by('created_on', 'desc')
			->from(self::$tbl_news)
			->limit(1)
			->get()
			->row_array();
		if($rs)
		{
			return $this->db->where('news_id',$rs['id'])
				->order_by('created_on', 'desc')
				->from('pic_files')
				->limit(1)
				->get()
				->row_array();
		}	
		return ;
	}

	/**
	 * 统计数量
	 *
	 * @param	int		$type   新闻类型
	 * @return	int
	 */
	public function count($type = NULL)
	{
		if(isset($type) AND $type)
		{
			$this->db->where('news_type', $type);
		}
		return $this->db->count_all_results(self::$tbl_news);
	}

	/**
	 * 根据流水id删除new
	 *
	 * @param int $id	流水id
	 */
	public function del_by_id($id)
	{
		$this->db->where('id', $id)
				->delete(self::$tbl_news);
	}

	/**
	 * 根据流水id获取new
	 *
	 * @param int $id	流水id
	 * @return array
	 */
	public function get_by_id($id)
	{
		$rs = $this->db->where('id', $id)
						->get(self::$tbl_news)
						->row_array();
		return $rs;
	}

	/**
	 * 获取新闻分类
	 *
	 * @return array
	 */
	public function get_types()
	{
		return $this->db->get('news_type')
			->result_array();	
	}
	
	/**
	 * 获取新闻分类名称
	 * @param int $id
	 *
	 * @return array
	 */
	public function get_type_name($id)
	{
		return $this->db->where('id', $id)
						->get('news_type')
						->row_array();
	}
	
	/**
	 * 根据流水id更新new
	 *
	 * @param int	$id		流水id
	 * @param array $input	修改数组
	 */
	public function update_by_id($id, $input)
	{
		$this->db->where('id', $id)
				->update(self::$tbl_news, $input);
	}

	/**
	 * 删除new
	 *
	 * @param int $id new id
	 * @return void
	 */
	public function delete($id)
	{
		$this->db->where('id', $id)
				->delete(self::$tbl_news);
	}

}

/* End of file news_mdl.php */
/* Location: ./models/news_mdl.php */
