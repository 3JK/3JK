<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *      GA数据模型

 *
 *      @package	Anying
 *      @subpackage Admin
 *      @category	Models
 *      @author		Lamtin <lamtin.li@yoozi.cn>
 *      @link
 *
 */
class Ga_mdl extends CI_Model {

        public $ga = null;

        public function __construct() {

                parent::__construct();

                require APPPATH . "libraries/gapi.class.php";

                $this->config->load('ga');
        }

        /**
         * month: 获取月统计
         *
         * @access public
         * @return void
         */
        public function month() {

                $this->ga = new gapi($this->config->item('gmail'), $this->config->item('pwd'));
                $array = $json = array();
                $start_date = date("Y-m-d", mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")));
                $end_date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")));

                $this->ga->requestReportData($this->config->item('pid'), array('date'), array('pageviews', 'visits'), array("date"), null, $start_date, $end_date);

                foreach ($this->ga->getResults() as $result) {
                        $metrics = $result->getMetrics();
                        $dimesions = $result->getDimesions();
                        $date = $dimesions["date"];
                        $array['date'] = substr($date, 6, 2);
                        $array['visits'] = $metrics['visits'];
                        $array['pageviews'] = $metrics['pageviews'];
                        $json[] = $array;
                }

                return $json;
        }

        /**
         * visitor_type: 获取浏览者类型
         *
         * @access public
         * @return void
         */
        public function visitor_type() {

                $this->ga = new gapi($this->config->item('gmail'), $this->config->item('pwd'));
                $json = array();
                $start_date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
                $end_date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")));

                $this->ga->requestReportData($this->config->item('pid'), array('visitorType'), array('visits'), array("visits"), null, $start_date, $end_date);

                foreach ($this->ga->getResults() as $result) {
                        $metrics = $result->getMetrics();
                        $dimesions = $result->getDimesions();
                        $json[$dimesions['visitorType']] = $metrics['visits'];
                }

                return $json;
        }

        /**
         * browser: 获取浏览器版本
         *
         * @access public
         * @return void
         */
        public function browser() {

                $this->ga = new gapi($this->config->item('gmail'), $this->config->item('pwd'));
                $json = array();
                $start_date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
                $end_date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")));

                $this->ga->requestReportData($this->config->item('pid'), array('browser', 'browserVersion'), array('visits'), array("visits"), null, $start_date, $end_date);

                foreach ($this->ga->getResults() as $result) {
                        $metrics = $result->getMetrics();
                        $dimesions = $result->getDimesions();
                        $json[$dimesions['browser'] . " " . $dimesions['browserVersion']] = $metrics['visits'];
                }

                return $json;
        }

        /**
         * country: 获取地区
         *
         * @access public
         * @return void
         */
        public function country() {

                $this->ga = new gapi($this->config->item('gmail'), $this->config->item('pwd'));
                $json = array();
                $start_date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
                $end_date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")));

                $this->ga->requestReportData($this->config->item('pid'), array('country'), array('visits'), array("visits"), null, $start_date, $end_date);

                foreach ($this->ga->getResults() as $result) {
                        $metrics = $result->getMetrics();
                        $dimesions = $result->getDimesions();
                        $json[$dimesions['country']] = $metrics['visits'];
                }

                return $json;
        }

}

/* End of file ga_mdl.php */
/* Location: ./models/ga_mdl.php */