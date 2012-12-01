<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 	文件管理数据模型
 *
 */
class Pic_files_mdl extends CI_Model
{
	protected $primary_key = 'id';
	public static $tbl_pic_files = 'pic_files';

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 添加文件记录
	 *
	 * @param array $input
	 * @return int
	 */
	public function insert($input)
	{
		$this->db->insert(self::$tbl_pic_files, $input);
		return $this->db->insert_id();
	}

	/**
	 * 获取文件记录
	 *
	 * @param	int		$limit	数量
	 * @param	int		$offset	偏移量
	 * @param	int		$type	文件所属类型
	 * 
	 * @return	array
	 */
	public function get_many($limit = NULL, $offset = NULL, $type = NULL)
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
		return $this->db->order_by('id desc')
					->get(self::$tbl_pic_files)
					->result_array();
	}

	/**
	 * 统计数量
	 *
	 * @param	string $type	分类
	 * @return	int
	 */
	public function count($type = NULL)
	{
		if($type)
		{
			$this->db->where('type', $type);
		}
		return $this->db->count_all_results(self::$tbl_pic_files);
	}

	/**
	 * 根据流水id删除
	 *
	 * @param int $id	流水id
	 */
	public function del_by_id($id)
	{
		$this->db->where('id', $id)
				->delete(self::$tbl_pic_files);
	}

	/**
	 * 根据id获取文件
	 *
	 * @param int $id	流水id
	 * @return array
	 */
	public function get_by_id($id)
	{
		$rs = $this->db->where('id', $id)
						->get(self::$tbl_pic_files)
						->row_array();
		return $rs;
	}
	
	/**
	 * 根据流水id更新文件
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
	 * 根据内容id获取文件
	 *
	 * @param int $news_id	news_id
	 * @return array
	 */
	public function get_by_news($news_id)
	{
		return $this->db->where('news_id', $news_id)
						->get(self::$tbl_pic_files)
						->result_array();	
	}

	/**
	 * 删除文件记录
	 *
	 * @param int $id id
	 * @return void
	 */
	public function delete($id)
	{
		$item = $this->db->where('id', $id)
						->get(self::$tbl_pic_files)
						->row_array();
		if($item)
		{
			switch ($item['type'])
			{
				case 1:
					unlink('uploads/news/' . $item['file_name']);
					break;
				case 2:
					unlink('uploads/banner/' . $item['file_name']);
					break;
				case 3:
					unlink('uploads/news/' . $item['file_name']);
					break;
				case 4:
					unlink('uploads/logo/' . $item['file_name']);
					break;
				default:
					unlink('uploads/images/' . $item['file_name']);
					break;	
			}
			$this->db->where('id', $id)
				->delete(self::$tbl_pic_files);
			return TRUE;
		}
		return FALSE;	
	}
	
	/**
	 * 根据内容id删除文件记录
	 *
	 * @param int $news_id  news_id
	 * @return void
	 */
	public function del_by_news($news_id)
	{
		$items = $this->db->where('news_id', $news_id)
						->get(self::$tbl_pic_files)
						->result_array();
		if($items)
		{
			foreach ($items as $item)
			{
				$this->delete($item['id']);
			}
			return TRUE;
		}
		return ;
	}
}

/* End of file pic_files_mdl.php */
/* Location: ./models/pic_files_mdl.php */
