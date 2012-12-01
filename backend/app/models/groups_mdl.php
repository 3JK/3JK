<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  anying: 安盈
 *
 *  
 *
 *  @package    Anying
 *  @author     Yoozi Dev Team
 *  @copyright  Copyright (c) 2012, Yoozi.cn
 *  @since      Version 1.0.0
 *  @filesource
 */

 /**
  * groups数据模型
  * 
  * 
  * @package    Anying
  * @subpackage Classad
  * @category   Model
  * @author     pob986 <qiang.pan@yoozi.cn>
  */
class Groups_mdl extends CI_Model 
{

	protected $primary_key = 'id';
	public static $tbl_groups = 'groups';

	/**
	 * 构造方法
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
	}

	// ---------------------------------------------------------------------------------------------------

	/**
	 * 添加群组
	 *
	 * @param array $input
	 * @return int
	 */
	public function insert($input)
	{
		$this->db->insert(self::$tbl_groups, $input);
		return $this->db->insert_id();
	}

	// ---------------------------------------------------------------------------------------------------

	/**
	 * 获取群组分类
	 *
	 * @return	array
	 */
	public function get_types()
	{
		return $this->db->select('id,name')
					->order_by('id')
					->get(self::$tbl_groups)
					->result_array();
	}
	
	// ---------------------------------------------------------------------------------------------------

	/**
	 * 获取群组
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
		return $this->db->order_by('id')
					->get(self::$tbl_groups)
					->result_array();
	}

	// ---------------------------------------------------------------------------------------------------

	/**
	 * 统计数量
	 *
	 * @return	int
	 */
	public function count()
	{
		return $this->db->count_all(self::$tbl_groups);
	}

	// ---------------------------------------------------------------------------------------------------

	/**
	 * 根据流水id删除群组
	 *
	 * @param int $id	流水id
	 */
	public function del_by_id($id)
	{
		$this->db->where('id', $id)
				->delete(self::$tbl_groups);
	}

	// ---------------------------------------------------------------------------------------------------

	/**
	 * 根据流水id获取群组
	 *
	 * @param int $id	流水id
	 * @return array
	 */
	public function get_by_id($id)
	{
		$rs = $this->db->where('id', $id)
						->get(self::$tbl_groups)
						->row_array();
		return $rs;
	}

	// ---------------------------------------------------------------------------------------------------

	/**
	 * 根据流水id更新群组
	 *
	 * @param int	$id		流水id
	 * @param array $input	修改数组
	 */
	public function update_by_id($id, $input)
	{
		$this->db->where('id', $id)
				->update(self::$tbl_groups, $input);
	}

	// ---------------------------------------------------------------------------------------------------	
}

/* End of file groups_mdl.php */
/* Location: ./app/models/groups_mdl.php */