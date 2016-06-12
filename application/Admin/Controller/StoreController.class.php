<?php

/**
 * Store(商户管理)
 */
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class StoreController extends AdminbaseController {

    protected $menu_model;

    function _initialize() {
        parent::_initialize();
        $this->menu_model = D("Common/Store");
        //$this->menu_model = M("Store");
    }

  
    /**
     *  商户列表
     */
    public function index() {
    	$_SESSION['admin_store_index']="Store/index";
    	
    	//筛选条件
    	$type = isset($_POST['type']) && !empty($_POST['type']) ? trim($_POST['type']) : '';
    	$store_name = isset($_POST['store_name']) && !empty($_POST['store_name']) ? trim($_POST['store_name']) : '';
    	$starttime  = isset($_POST['starttime']) && !empty($_POST['starttime']) ? trim($_POST['starttime']) : '';
    	$endtime 	= isset($_POST['endtime']) && !empty($_POST['endtime']) ? trim($_POST['endtime']) : '';
    	
    	$where = array();
    	if(!empty($type)){
    		$where['type'] = $type;
    	}
    	if(!empty($store_name)){
    		$where['store_name'] = array('like',$store_name);
    	}
    	if(!empty($starttime)){
    		$start_time = strtotime($starttime);
    		$where['add_time'] = array('gt',$start_time);
    	}
    	if(!empty($endtime)){
    		$end_time = strtotime($endtime);
    		$where['add_time'] = array('elt',$end_time);
    	}
    	$this->assign('type',$type);
    	$this->assign('starttime',$starttime);
    	$this->assign('endtime',$endtime);

    	//分页
    	$count=$this->menu_model->where($where)->count();
    	$page = $this->page($count, 20);
    	//查询list
        $list = $this->menu_model
        	 ->where($where)
             ->order(array("listorders" => "ASC"))
             ->limit($page->firstRow . ',' . $page->listRows)
             ->select();
        //echo M()->getLastSql()."<br>";
        $status = array('0'=>'待审核','1'=>'审核通过','2'=>'系统关闭','3'=>'人为关闭','4'=>'拒绝通过');
        foreach ($list as $n=> $r) {
            $list[$n]['state'] 		= $status[$r['state']];
            $list[$n]['add_time'] 	= !empty($r['add_time']) ? date('Y-m-d H:i:s', $r['add_time']) : '';
            $list[$n]['end_time'] 	= !empty($r['end_time']) ? date('Y-m-d H:i:s', $r['end_time']) : '';
            $list[$n]['str_manage'] = '<a href="' . U("Store/edit", array("id" => $r['store_id'], "menuid" => I("get.menuid"))) . '">'.L('EDIT')
            							.'</a> | <a class="js-ajax-delete" href="' . U("Store/delete", array("id" => $r['store_id'], "menuid" => I("get.menuid")) ). '">'.L('DELETE').'</a> ';
        }
        //print_r($list);
        $this->assign("page", $page->show('Admin'));
        $this->assign("list", $list);
        $this->display();
    }
    
   

    /**
     *  添加
     */
    public function add() {
    	$this->display();
    }
    
    /**
     *  添加
     */
    public function add_post() {
    	$data = array();
    	$data['store_name'] 	 =  !empty($_POST['store_name']) ? trim($_POST['store_name']) : '';
    	$data['owner_name'] 	 =  !empty($_POST['owner_name']) ? trim($_POST['owner_name']) : '';
    	$data['owner_card'] 	 =  !empty($_POST['owner_card']) ? trim($_POST['owner_card']) : '';
    	$data['mobile'] 	 	 =  !empty($_POST['mobile']) ? trim($_POST['mobile']) : '';
    	$data['address'] 	 	 =  !empty($_POST['address']) ? trim($_POST['address']) : '';
    	$data['tel'] 	 	     =  !empty($_POST['tel']) ? trim($_POST['tel']) : '';
    	$data['zipcode'] 	 	 =  !empty($_POST['zipcode']) ? trim($_POST['zipcode']) : '';
    	$data['listorders'] 	 =  !empty($_POST['listorders']) ? intval($_POST['listorders']) : 0;
    	$data['state'] 	 	     =  $_POST['zipcode'];
    	$data['end_time'] 	 	 =  $_POST['end_time'];
    	$data['description'] 	 =  $_POST['description'];
    	$data['im_qq'] 	 		 =  $_POST['im_qq'];
    	$data['email'] 	 		 =  $_POST['email'];
    	$data['type'] 	 		 =  $_POST['type'];
    	$data['state'] 	 		 =  !empty($_POST['state']) ? intval($_POST['state']) : 0;
    	$data['add_time'] 	     =  time();
    	
    	if($data['store_name'] == ''){
    		$this->error("商户名称不能为空！");
    	}
    	if($data['owner_name'] == ''){
    		$this->error("店主姓名不能为空！");
    	}
    	if($data['owner_card'] == ''){
    		$this->error("店主身份证号不能为空！");
    	}
    	if($data['mobile'] == ''){
    		$this->error("店主手机号不能为空！");
    	}
    	if($data['address'] == ''){
    		$this->error("门店地址不能为空！");
    	}
    	
    	$where = array('store_name'=>$data['store_name']);
    	$info = $this->menu_model->where($where)->find();
    	if($info){
    		$this->error("商户已存在！");
    		return;
    	}else{
    		$this->menu_model->add($data);
    		$to	= empty($_SESSION['admin_store_index']) ? "Store/index" : $_SESSION['admin_store_index'];
    		$this->success("添加成功！", U($to));
    	}		
    }

    /**
     *  删除
     */
    public function delete() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id == 0)
        {
        	$this->error("删除失败！");
        	return;
        }
        
     	if($this->menu_model->delete($id)!==false) {
            $this->success("删除菜单成功！");
        } else {
            $this->error("删除失败！");
        }
    }

    /**
     *  编辑
     */
    public function edit() {
    	$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id == 0)
        {
        	$this->error("内容不存在！");
        	return;
        }
        
        $info = $this->menu_model->where(array("id" => $id))->find();
        $this->assign("info", $info);
        $this->display();
    }
    
    /**
     *  编辑
     */
    public function edit_post() {
    	$data = array();
    	$id 	 		 		 =  !empty($_POST['id']) ? intval($_POST['id']) : 0;
    	$data['store_name'] 	 =  !empty($_POST['store_name']) ? trim($_POST['store_name']) : '';
    	$data['owner_name'] 	 =  !empty($_POST['owner_name']) ? trim($_POST['owner_name']) : '';
    	$data['owner_card'] 	 =  !empty($_POST['owner_card']) ? trim($_POST['owner_card']) : '';
    	$data['mobile'] 	 	 =  !empty($_POST['mobile']) ? trim($_POST['mobile']) : '';
    	$data['address'] 	 	 =  !empty($_POST['address']) ? trim($_POST['address']) : '';
    	$data['tel'] 	 	     =  !empty($_POST['tel']) ? trim($_POST['tel']) : '';
    	$data['zipcode'] 	 	 =  !empty($_POST['zipcode']) ? trim($_POST['zipcode']) : '';
    	$data['listorders'] 	 =  !empty($_POST['listorders']) ? intval($_POST['listorders']) : 0;
    	$data['state'] 	 	     =  $_POST['zipcode'];
    	$data['end_time'] 	 	 =  $_POST['end_time'];
    	$data['description'] 	 =  $_POST['description'];
    	$data['im_qq'] 	 		 =  $_POST['im_qq'];
    	$data['email'] 	 		 =  $_POST['email'];
    	$data['type'] 	 		 =  $_POST['type'];
    	$data['state'] 	 		 =  !empty($_POST['state']) ? intval($_POST['state']) : 0;
    	 
    	if($id == 0){
    		$this->error("商户不存在！");
    	}
    	if($data['store_name'] == ''){
    		$this->error("商户名称不能为空！");
    	}
    	if($data['owner_name'] == ''){
    		$this->error("店主姓名不能为空！");
    	}
    	if($data['owner_card'] == ''){
    		$this->error("店主身份证号不能为空！");
    	}
    	if($data['mobile'] == ''){
    		$this->error("店主手机号不能为空！");
    	}
    	if($data['address'] == ''){
    		$this->error("门店地址不能为空！");
    	}

    	
    	$where = array('store_name'=>$data['store_name']);
    	$where['store_id'] = array('neq',$id);
    	$info = $this->menu_model->where($where)->find();
    	if($info){
    		$this->error("商户已存在！");
    		return;
    	}else{
    		$save_where = array('store_id'=>$id);
    		$this->menu_model->where($save_where)->save($data);
    		
    		$to	= empty($_SESSION['admin_store_index']) ? "Store/index" : $_SESSION['admin_store_index'];
    		$this->success("更新成功！", U($to));
    	}
    }

    //排序
    public function listorders() {
        $status = parent::_listorders($this->menu_model);
        if ($status) {
            $this->success("排序更新成功！");
        } else {
            $this->error("排序更新失败！");
        }
    }


  

}