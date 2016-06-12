<?php

/* * 
 * 商户
 */
namespace Common\Model;
use Common\Model\CommonModel;
class StoreModel extends CommonModel {

    //自动验证
    protected $_validate = array(
        //array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
        array('store_name', 'require', '商户名称不能为空！', 1, 'regex', CommonModel:: MODEL_BOTH ),
        array('owner_name', 'require', '店主姓名不能为空！', 1, 'regex', CommonModel:: MODEL_BOTH ),
        array('owner_card', 'require', '店主身份证号不能为空！', 1, 'regex', CommonModel:: MODEL_BOTH ),
        array('mobile', 'require', '店主手机号不能为空！', 1, 'regex', CommonModel:: MODEL_BOTH ),
    	array('address', 'require', '详细地址不能为空！', 1, 'regex', CommonModel:: MODEL_BOTH ),
        array('store_name', 'checkAction', '同样的记录已经存在！', 1, 'callback', CommonModel:: MODEL_INSERT   ),
    	array('store_name', 'checkActionUpdate', '同样的记录已经存在！', 1, 'callback', CommonModel:: MODEL_UPDATE   ),
    );
    //自动完成
    protected $_auto = array(
            //array(填充字段,填充内容,填充条件,附加规则)
    );

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