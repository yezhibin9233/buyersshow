<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>买家秀助手</title>
    <link rel="stylesheet" type="text/css" href="styles/show.css"/>
</head>
<!-- 引入jQuery库函数 -->
<script type="text/javascript" src="jquery-3.0.0.js"></script>
<script type="text/javascript" src="instoredata.js"></script>
<script type="text/javascript" src="photo_zoom.js"></script>
<script type="text/javascript" src="photo_position.js"></script>
<script>

	//函数体内部的函数无法被调用
	//如果jsonp中函数名被指定，则先执行指定函数的结果，否则执行默认的函数
	//数组是否可以相加？
	//清空页面元素后加载
	//$("document").ready与window.onload类似，但是速度更快，作用于页面代码加载完毕，而不是所有元素加载完毕
	
	//声明全局变量
	var ID=0;

	//存放当前页面
	var N=1;

	//获取最大页面数
	var maxPage=0;

	//n用于存储页面元素的个数，同时也作为数组的下标
	var n=0;

	//存放页面元素的数组
	var shows=[];


	//主调函数
	function main(){


		var url=window.location.href;

		//截取掉等号的后一位之前的全部内容
		ID=url.substring(url.indexOf('=')+1);

		//执行了这一步,并不代表获得了数据
		getData(ID,1);

		//定位表单
		var form=document.getElementsByTagName('form')[0];

		//获取页面元素标签
		var goods=document.getElementById('goods');
		var elements=goods.getElementsByTagName('dl');
		var search=document.getElementById('search');
		var a=search.getElementsByTagName('a')[0];

		//确定返回淘宝的地址
		a.href='//item.taobao.com/item.htm?id='+ID;
		
		//获取表单
		form[1].onclick=function(){
			var content=form[0].value;
			if(content==""){
				alert('请输入商品地址或ID');
			}else{
				//创建提交地址
				form.action='show.php?id='+content;
			}
		}
	}
    //获取数据
	var getData=function(id,n){
		n=n?n:1;
		var commentsAPI="https://rate.taobao.com/feedRateList.htm";
		var data={
			"rateType":3,
			"auctionNumId":id,
			//current 当前的
			"currentPageNum":n,
			"oderType":"feedbackdate"
		};
		var params="?";
		for(key in data){
			params+=key+"="+data[key]+"&";
		}
		var _URL=commentsAPI+params;
		//console.log(_URL);
		//a.jax是一个内置对象
		$.ajax({
			url:commentsAPI,
			//jsonpCallback:"callback",
			//jsonp:'methodname';
			dataType:"jsonp",
			data:data,
			success:function(response){
				callback(response);
			},
			error:function(){
				return;
			}
		});
	}
	
	//回调函数
	var callback=function(response){
		//评论为空
		if(response.total==0){
			//插入页面元素
			return false;
		}else{
			maxPage=response.maxPage;
			filter(response);
		}

		if(N==1){
			//存储数据
			//建立一个数组存放ID url discuss
			var goods={};
			goods.ID=ID;
			goods.url=shows[0].url;
			goods.dis=shows[0].content;
			phpinstore(goods,"server_instore.php");

			//第一页显示30条数据
		    for(var i=0;i<20;i++){

		        //插入到页面中去
		        //如果中间出错则不再往下运行
		        if(shows[i]!==undefined){
			        var insertHtml="<dl><dd class=\"img\"><img src=\""+shows[i].url+"\"/></dd><dd class=\"content\">"+shows[i].content+"</dd></dl>"
			        document.getElementById('goods').innerHTML+=insertHtml;  
			        photoPosition(i);
		    	}
		    }
		    
		    //监听页面元素
	    	photoZoom();
	    }

	}
	
	//过滤数据
	var filter=function(data){
			var comments=data.comments;
			//数组不能定义在内部，否则会被重写
			//var shows=new Array();

			//a,遍历photos目录中的图片			
			for(var i=0;i<comments.length;i++){
				if(comments[i].photos!=null){
					for(var m=0;m<comments[i].photos.length;m++){
						//show[n]=new Object();
						//此处不能加var 因为shows已经被定义，这里仅仅是赋值
						shows[n]={};
						if(comments[i].content!=null&&comments[i].photos[m].url!=null){
							shows[n].content=comments[i].content;
							shows[n].url=comments[i].photos[m].url;
							n++;
						}
					}
				}
			}
			// alert(n);||n=19

			//b,遍历append目录中的图片，一个目录中只有一组照片
			for(var i=0;i<comments.length;i++){
				if(comments[i].append!=null&&comments[i].append.photos!=null){
					for(var m=0;m<comments[i].append.photos.length;m++){
						//定义新的存储对象
						shows[n]={};
						if(comments[i].append.content!=null&&comments[i].append.content!=""){
							shows[n].content=comments[i].append.content;
						}else{
							shows[n].content=comments[i].content;
						}
						shows[n].url=comments[i].append.photos[m].url;
						n++;
					}
				}
			}

			// console.info(shows);
			// document.write(JSON.stringify(goods));			
		}


	//浏览量滚动条触底事件
    window.onscroll=function(){
        if (document.documentElement.scrollTop){

            //页面高度
            var X=document.documentElement.scrollHeight;

            //等于其中任意一个
            //var Ya=document.documentElement.scrollTop||docment.body.scrollTop;
            //a?b:c 如果a为真,则执行b,否则执行c
            //var Yb=document.documentElement.scrollTop?document.documentElement.scrollTop:document.body.scrollTop;
            
			//被滚动的高度
            var Y=document.documentElement.scrollTop+document.body.scrollTop;

			//窗口的高度
            var Z=document.documentElement.clientHeight;
            
            marginBot=X-Y-Z;
        } else {
            var J=document.body.scrollHeight;
            var I=document.body.scrollTop;
            //document.body.clientHeight;body对象高度
            //可见区域高度
            var K=window.screen.availHeight;
            marginBot=J-I-K;
        }
        //保证在顶端时不会触发事件
        if(marginBot<=0&&I!=0){
        	//do something
        	//获得页面元素的个数
        	//获取页面元素标签
        	var goods=document.getElementById('goods');
			var elements=goods.getElementsByTagName('dl');
        	
        	//不可以参与循环
        	var length=elements.length;
        	for(var i=length;i<length+20;i++){
		        //插入到页面中去
		        //如果中间出错则不再往下运行
		        if(shows[i]!==undefined){
			        var insertHtml="<dl><dd class=\"img\"><img src=\""+shows[i].url+"\"/></dd><dd class=\"content\">"+shows[i].content+"</dd></dl>"
			        goods.innerHTML+=insertHtml;
			        photoPosition(i);			       
		    	}else{
		    		N++;
		    		if(N<=maxPage){
			    		getData(ID,N);			    		
		    		}else{return;}
		    	}
		    }

		    //一旦加载新元素,就对页面内容进行监听
	    	photoZoom();
        }
    }
	

	//开关
	$("document").ready(main);
</script>

<?php 
    require dirname(__FILE__).'/includes/common.inc.php';
?>
<body>
	<div id="nav">
		<ul>
			<li><a href="index.php"><strong>首页</strong></a></li>
			<li>
				<a class="market" href="javascript:(function(){if(window.location.href.match(/id=\d{9,13}/)!=null){var id=window.location.href.match(/id=\d{9,13}/)[0];window.open('http://localhost:8080/Buyersshow1.0/show.php?goods_'+id);}else{alert('请在淘宝页面访问我哟!');return;}})();"><strong>查看买家秀</strong></a>
    		</li>
    		<li><span><--拖动我到书签栏,点我直接查看任意商品买家秀</span></li>
		</ul>
	</div>
	<div id="search">
		<img class="logo" src='images/logo.jpg'/>
		<form action="" method="post">
			<input type="text" class="information" name="information"/>
			<input type="submit" class="submit" value="查看买家秀" />
		</form>
		<ul>
			<!--target="_blank" 属性表示在新窗口中显示链接  -->
			<a class="return" href="###" target="_blank"><strong>返回淘宝</strong></a>
			<li><a href="###"><img src="images/qzone.png" /></a></li>
			<li><a href="###"><img src="images/blog.jpg" /></a></li>
			<li><a href="###"><img src="images/qq.jpg" /></a></li>
			<li><a href="###"><img src="images/wechat.png" /></a></li>
			<li><a href="###"><img src="images/copy.png" /></a></li>
		</ul>
	</div>
	<div id="goods">

	</div>
	<div id="footer">
		<p><strong>powered by tyck</strong></p>
	</div>
</body>