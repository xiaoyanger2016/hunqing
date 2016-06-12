<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<!-- Set render engine for 360 browser -->
	<meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- HTML5 shim for IE8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->

	<link href="/public/simpleboot/themes/<?php echo C('SP_ADMIN_STYLE');?>/theme.min.css" rel="stylesheet">
    <link href="/public/simpleboot/css/simplebootadmin.css" rel="stylesheet">
    <link href="/public/js/artDialog/skins/default.css" rel="stylesheet" />
    <link href="/public/simpleboot/font-awesome/4.4.0/css/font-awesome.min.css"  rel="stylesheet" type="text/css">
    <style>
		.length_3{width: 180px;}
		form .input-order{margin-bottom: 0px;padding:3px;width:40px;}
		.table-actions{margin-top: 5px; margin-bottom: 5px;padding:0px;}
		.table-list{margin-bottom: 0px;}
	</style>
	<!--[if IE 7]>
	<link rel="stylesheet" href="/public/simpleboot/font-awesome/4.4.0/css/font-awesome-ie7.min.css">
	<![endif]-->
<script type="text/javascript">
//全局变量
var GV = {
    DIMAUB: "/",
    JS_ROOT: "public/js/",
    TOKEN: ""
};
</script>
<!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/public/js/jquery.js"></script>
    <script src="/public/js/wind.js"></script>
    <script src="/public/simpleboot/bootstrap/js/bootstrap.min.js"></script>
<?php if(APP_DEBUG): ?><style>
		#think_page_trace_open{
			z-index:9999;
		}
	</style><?php endif; ?>
</head>
<body>
	<div class="wrap js-check-wrap">
		<ul class="nav nav-tabs">
			<li class="active"><a href="<?php echo U('store/index');?>"><?php echo L('ADMIN_STROE_LIST');?></a></li>
			<li><a href="<?php echo U('store/add');?>"><?php echo L('ADMIN_STROE_ADD');?></a></li>
		</ul>
		<div class="mrightTop">
		  <div class="fontl">
		    <form action="<?php echo U('Store/index');?>" method="post">
		       <div class="left">
		          <input type="hidden" name="mod" value="Store" />
		          <input type="hidden" name="act" value="index" />
		          商户类型：
		          <select name="type" style="width: 100px;" class="form-control">
						<option value="HQ" <?php if($type == 'HQ' ): ?>selected=selected<?php endif; ?> >婚庆</option>
						<option value="HOTEL" <?php if($type == 'HOTEL' ): ?>selected=selected<?php endif; ?> >酒店</option>
				  </select>
		          商户名称:
		          <input class="form-control" style="width: 200px;" type="text" name="store_name" value="<?php echo ($store_name); ?>" />
		          注册时间
		          <input class="js-datetime" type="text" id="input-datetime" placeholder="<?php echo ($starttime); ?>" name="starttime" value="<?php echo ($starttime); ?>"> —
		          <input class="js-datetime" type="text" id="input-datetime" placeholder="<?php echo ($endtime); ?>" name="endtime" value="<?php echo ($endtime); ?>">
		          <input type="submit" class="btn btn-inverse" value="搜索" />
		      </div>
		    </form>
		  </div>
		</div>
		<form class="js-ajax-form" action="<?php echo U('Store/listorders');?>" method="post">
			<table class="table table-hover table-bordered table-list" id="menus-table">

				<thead>
					<tr>
						<th width="80"><?php echo L('SORT');?></th>
						<th width="50">ID</th>
						<th><?php echo L('ADMIN_STROE_NAME');?></th>
						<th><?php echo L('ADMIN_STROE_INFO');?></th>
						<th width="80"><?php echo L('ADMIN_STROE_TYPE');?></th>
						<th width="100"><?php echo L('ADMIN_STROE_ADDRESS');?></th>
						<th><?php echo L('ADMIN_STROE_STATE');?></th>
						<th><?php echo L('ADMIN_STROE_ADDTIME');?></th>
						<th><?php echo L('ADMIN_STROE_ENDTIME');?></th>
						<th width="180"><?php echo L('ADMIN_OPL');?></th>
					</tr>
				</thead>
				<tbody>
					<?php if($list != NULL): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><tr>
						    <td style='padding-left:20px;'><input name='listorders[<?php echo ($item["store_id"]); ?>]' type='text' size='3' value='<?php echo ($item["listorders"]); ?>' class='input input-order'></td>
						    <td><?php echo ($item["store_id"]); ?></td>
							<td><?php echo ($item["store_name"]); ?></td>
							<td>
								<?php echo L('ADMIN_NAME');?>:<?php echo ($item["owner_name"]); ?><br/>
								<?php echo L('ADMIN_MOBILE');?>:<?php echo ($item["mobile"]); ?><br/>
								<?php echo L('ADMIN_CARD_NUM');?>:<?php echo ($item["owner_card"]); ?>
							</td>
							<td><?php echo ($item["type"]); ?></td>
							<td><?php echo ($item["address"]); ?></td>
							<td><?php echo ($item["state"]); ?></td>
							<td><?php echo ($item["add_time"]); ?></td>
							<td><?php echo ($item["end_time"]); ?></td>
							<td>
								<?php echo ($item["str_manage"]); ?>
							</td>
						</tr><?php endforeach; endif; else: echo "" ;endif; ?>
					<?php else: ?>
						<tr style="height: 120px;">
							<td align="center" colspan="10">暂无数据</td>
						</tr><?php endif; ?>
				</tbody>
				
			</table>
			<div class="table-actions">
				<button class="btn btn-primary btn-small js-ajax-submit" type="submit"><?php echo L('SORT');?></button>
			</div>
		</form>
		<div class="pagination"><?php echo ($page); ?></div>
	</div>
	<script src="/public/js/common.js"></script>
	<script>
		$(document).ready(function() {
			Wind.css('treeTable');
			Wind.use('treeTable', function() {
				$("#menus-table").treeTable({
					indent : 20
				});
			});
		});

		setInterval(function() {
			var refersh_time = getCookie('refersh_time_admin_menu_index');
			if (refersh_time == 1) {
				reloadPage(window);
			}
		}, 1000);
		setCookie('refersh_time_admin_menu_index', 0);
	</script>
</body>
</html>