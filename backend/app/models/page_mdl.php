<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 	页面管理数据模型
 *
 */
class Page_mdl extends CI_Model
{
	protected $primary_key = 'id';
	public static $tbl_pages = 'page_save';

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 添加内容
	 *
	 * @param array $input
	 * @return int
	 */
	public function insert($input)
	{
		$this->db->insert(self::$tbl_pages, $input);
		return $this->db->insert_id();
	}

	/**
	 * 获取page内容
	 *
	 * @param	int		$limit	数量
	 * @param	int		$offset	偏移量
	 * @param	int		$type   新闻类型
	 * @param	string	$order   排序
	 * @return	array
	 */
	public function get_many($limit = NULL, $offset = NULL, $type = NULL, $order='created_on')
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
			$this->db->where('type', $type);
		}

		return $this->db->order_by('updated_on desc')
					->from(self::$tbl_pages)
					->get()
					->result_array();
	}

	/**
	 * 统计数量
	 *
	 * @param	string $type	页面标题
	 * @return	int
	 */
	public function count($type = NULL)
	{
		if($type)
		{
			$this->db->where('type', $type);
		}

		return $this->db->count_all_results(self::$tbl_pages);
	}

	/**
	 * 根据流水id获取页面内容
	 *
	 * @param int $id	流水id
	 * @return array
	 */
	public function get_by_id($id)
	{
		$rs = $this->db->where('id', $id)
						->get(self::$tbl_pages)
						->row_array();
		return $rs;
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
				->update(self::$tbl_pages, $input);
	}

	/**
	 * 删除页面内容
	 *
	 * @param int $id new id
	 * @return void
	 */
	public function delete($id)
	{
		$this->db->where('id', $id)
				->delete(self::$tbl_pages);
	}

}

/* End of file page_mdl.php */
/* Location: ./models/page_mdl.php */
