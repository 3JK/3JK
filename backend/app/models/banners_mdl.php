<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 	banners数据模型
 *
 */
class Banners_mdl extends CI_Model
{
	protected $primary_key = 'id';
	public static $tbl_banners = 'banners';

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 添加Banner
	 *
	 * @param array $input
	 * @return int
	 */
	public function insert($input)
	{
		$this->db->insert(self::$tbl_banners, $input);
		return $this->db->insert_id();
	}

	/**
	 * 获取banner
	 *
	 * @param	int		$limit	数量
	 * @param	int		$offset	偏移量
	 * @return	array
	 */
	public function get_many($limit = NULL, $offset = NULL)
	{
		if(is_numeric($limit))
		{
			$this->db->limit($limit);
		}
		if(is_numeric($offset))
		{
			$this->db->offset($offset);
		}

		return $this->db->order_by('created_on desc')
					->get(self::$tbl_banners)
					->result_array();
	}

	/**
	 * 统计数量
	 *
	 * @param	string $status	状态
	 * @return	int
	 */
	public function count($status = NULL)
	{
		if($status)
		{
			$this->db->where('status', $status);
		}

		return $this->db->count_all(self::$tbl_banners);
	}

	/**
	 * 根据流水id删除Banner
	 *
	 * @param int $id	流水id
	 */
	public function del_by_id($id)
	{
		$this->db->where('id', $id)
				->delete(self::$tbl_banners);
	}

	/**
	 * 根据流水id获取Banner
	 *
	 * @param int $id	流水id
	 * @return array
	 */
	public function get_by_id($id)
	{
		$rs = $this->db->where('id', $id)
						->get(self::$tbl_banners)
						->row_array();
		return $rs;
	}

	/**
	 * 根据流水id更新Banner
	 *
	 * @param int	$id		流水id
	 * @param array $input	修改数组
	 */
	public function update_by_id($id, $input)
	{
		$this->db->where('id', $id)
				->update(self::$tbl_banners, $input);
	}

	/**
	 * 删除banner
	 *
	 * @param int $id banner id
	 * @return void
	 */
	public function delete($id)
	{
		$this->db->where('id', $id)
				->delete(self::$tbl_banners);
	}

}

/* End of file banner_mdl.php */
/* Location: ./ldm/models/banner_mdl.php */
