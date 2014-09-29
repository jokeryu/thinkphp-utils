<?php
namespace Org\Util;
use \Think\Model;
/**
 * Simple jqgrid DataGrid util for ThinkPHP
 */
class Grid extends Model {

    protected $adaptar = 'jqgrid'; // Grid 类型
    protected $grid = array(); // Grid 数据
    protected $params = array(); // Grid 参数
    protected $formatters = array(); // 格式化方法列表

    public function __construct($model) {
        parent::__construct($model);
        $constructor = 'constructBy' . ucfirst($this->adaptar);
        $this->$constructor();
    }

    /**
     * jqgrid 构造函数
     */
    public function constructByJqgrid() {
        $this->params['page'] = (int)$_REQUEST['page'];
        $this->params['rows'] = (int)$_REQUEST['rows'];
        $this->params['sidx'] = $_REQUEST['sidx'];
        $this->params['sord'] = $_REQUEST['sord'];
        // 合并 order 参数
        if($this->params['sidx'] && $this->params['sord']){
            $this->options['order'] .= ($this->options['order'] ? ', ' : '') . (strtolower($this->params['sord']) == 'asc' ? $this->params['sidx'].' asc' : $this->params['sidx'].' desc');
        }
        $this->options['limit'] = ($this->params['page'] - 1) * $this->params['rows'] . ','.$this->params['rows'];
    }

    /**
     * 从数据库查询 Grid 数据
     */
    public function fetchGridData() {
        // 获取计数
        $countInstance = clone $this;
        $this->grid['count'] = $countInstance->count();
        unset($countInstance);
        // 获取列表
        $queryInstance = clone $this;
        $this->grid['items'] = $queryInstance->select();
        unset($queryInstance);
    }

    /**
     * 格式化 Grid 数据
     */
    public function formatGridData() {
        $formatter = 'formatGridDataBy' . ucfirst($this->adaptar);
        return $this->$formatter();
    }

    /**
     * 用 jqgrid 格式化 Grid 数据
     * @return array 格式化后的数据
     */
    public function formatGridDataByJqgrid() {
        if ($this->formatters['data']) {
           $this->grid['items'] = call_user_func($this->formatters['data'], $this->grid['items']);
        }
        $output = array(
            'data' => array(
                'totalsize' => (int)($this->grid['count'] / (int)($this->params['rows'])) + 1, // 总页数
                'items' => $this->grid['items'] ? $this->grid['items'] : array(), // 当前页数据列表
                'page' => $this->params['page'], // 当前页数
                'records' => $this->grid['count'] // 总记录数
            ),
            'msg' => '操作成功',
            'status' => 200
        );
        return $output;
    }

    /**
     * 创建 Grid 数据
     * @return array Grid 数据
     */
    public function makeGridData() {
        $this->fetchGridData();
        return $this->formatGridData();
    }

    /**
     * 创建 Grid 数据并发送响应
     */
    public function makeGridRes() {
        $data = $this->makeGridData();
        exit(json_encode($data));
    }
}