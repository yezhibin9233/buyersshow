<?php
    //引入公共文件
    require dirname(__FILE__).'/includes/common.inc.php';
	$_ID=$_GET['ID'];
	$_DIS=$_GET['dis'];
	$_URL=$_GET['url'];
	
	//点号表示字符串的连接
	//$_con=$_URL.$_ID.$_DIS;
	
	//show不能作为数据库表名
	//向数据库中写入文件
	//检查数据库中是否存在
	$_result=_query("select from_goods_id from shows where from_goods_id='{$_ID}'");

	//如果查询的结果不存在,则获得结果集为false
	$_rows=mysql_fetch_array($_result,MYSQL_ASSOC);

	//如果为空则写入数据库,否则什么都不做
	if(!$_rows){
		_query("insert into shows(from_goods_id,show_discuss,show_photo_url) values('{$_ID}','{$_DIS}','{$_URL}')");
	}
	
	
?>
