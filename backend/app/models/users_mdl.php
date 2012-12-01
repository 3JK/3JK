<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 	用户管理数据模型
 *
 */
class Users_mdl extends CI_Model
{
	protected $primary_key = 'id';
	public static $tbl_users = 'users';

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 添加
	 *
	 * @param array $input
	 * @return int
	 */
	public function insert($input)
	{
		$this->db->insert(self::$tbl_users, $input);
		return $this->db->insert_id();
	}


	/**
	 * 验证用户登录
	 *
	 * @param string $username	用户名
	 * @param string $password	密码
	 * 
	 * @return array
	 */
	public function check_user($username, $password)
	{
		$rs = $this->db->where('username', $username)
						->where('password', $password)
						->get(self::$tbl_users)
						->row_array();
		return $rs;
	}

	/**
	 * 根据用户名获取内容
	 *
	 * @param string $username	用户名
	 * @return array
	 */
	public function get_by_name($username)
	{
		$rs = $this->db->where('username', $username)
						->get(self::$tbl_users)
						->row_array();
		return $rs;
	}

	/**
	 * 根据用户id获取内容
	 *
	 * @param int $id	用户id
	 * @return array
	 */
	public function get_by_id($id)
	{
		$rs = $this->db->where('id', $id)
				->get(self::$tbl_users)
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
				->update(self::$tbl_users, $input);
	}
	
	// ---------------------------------------------------------------------------------------------------
	
	/**
	 * 获取管理员
	 *
	 * @param	int		$limit	数量
	 * @param	int		$offset	偏移量
	 * @param	int		$group_id	群组id
	 * @return	array
	 */
	public function get_many($limit = NULL, $offset = NULL, $group_id = NULL)
	{
		if(is_numeric($limit))
		{
			$this->db->limit($limit);
		}
		if(is_numeric($offset))
		{
			$this->db->offset($offset);
		}
		if(is_numeric($group_id))
		{
			$this->db->where('group_id', $group_id);
		}
		return $this->db->select('users.*,gp.name AS group_name')
					->order_by('id')
					->from(self::$tbl_users)
					->join('groups AS gp', 'gp.id = users.group_id', 'left')
					->get()
					->result_array();
	}
	
	// ---------------------------------------------------------------------------------------------------
	
	/**
	 * 
	 * 统计数量
	 *
	 * @param	int		$group_id	群组id
	 * @return	int
	 */
	public function count($group_id = NULL)
	{
		if(is_numeric($group_id))
		{
			$this->db->where('group_id', $group_id);
		}
		return $this->db->count_all(self::$tbl_users);
	}
	
	// ---------------------------------------------------------------------------------------------------
	
	/**
	 * 根据流水id删除管理员
	 *
	 * @param int $id	流水id
	 */
	public function del_by_id($id)
	{
		$this->db->where('id', $id)
		->delete(self::$tbl_users);
	}

}

/* End of file users_mdl.php */
/* Location: ./models/users_mdl.php */
