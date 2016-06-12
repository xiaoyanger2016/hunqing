<?php

/* * 
 * 商户
 */
namespace Common\Model;
use Common\Model\CommonModel;
class RegionModel extends CommonModel {

    //自动验证
    protected $_validate = array(
        //array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
    );
    //自动完成
    protected $_auto = array(
            //array(填充字段,填充内容,填充条件,附加规则)
    );
    
    /**
     * 取得地区列表
     *
     * @param int $parent_id 大于等于0表示取某个地区的下级地区，小于0表示取所有地区
     * @return array
     */
    function get_list($parent_id = -1) {
    	if ($parent_id >= 0) {
    		$list = $this->where(array('parent_id',$parent_id))->order('sort_order asc, region_id asc')->select();
    	} else {
    		$list = $this->order('sort_order asc, region_id asc')->select();
    	}	
    	return $list;
    }
    
    /**
     * 获取城市列表
     *
     * @param int $cacheTime
     *
     * @return array|bool|mixed
     */
    function get_city_list($cacheTime=0){
    	return $this->find(array(
    			'conditions' => "parent_id=2 OR parent_id IN(SELECT region_id FROM eihoo_region WHERE parent_id=2)",
    			'fields'     => 'region_id,region_name,bazaar_id',
    			'order'      => 'parent_id DESC,sort_order ASC, region_id',
    	),"city_list",$cacheTime);
    }
    
    
    //验证action是否重复添加
    public function checkAction($data) {
        //检查是否重复添加
        $find = $this->where($data)->find();
        if ($find) {
            return false;
        }
        return true;
    }
    //验证action是否重复添加
    public function checkActionUpdate($data) {
    	//检查是否重复添加
    	$id=$data['id'];
    	unset($data['id']);
    	$find = $this->field('id')->where($data)->find();
    	if (isset($find['id']) && $find['id']!=$id) {
    		return false;
    	}
    	return true;
    }
    

    /**
     * 后台有更新/编辑则删除缓存
     * @param type $data
     */
    public function _before_write(&$data) {
        parent::_before_write($data);
        F("Store", NULL);
    }

    //删除操作时删除缓存
    public function _after_delete($data, $options) {
        parent::_after_delete($data, $options);
        $this->_before_write($data);
    }


}