    var photoPosition=function(i){

        //函数体内的函数不会进行预编译
        //一种形式的闭包,无参函数通过访问函数体外部的共享变量实现有参的效果
        var goods=document.getElementById('goods');
        var img=goods.getElementsByTagName('img')[i];
        var box=goods.getElementsByClassName('img')[0];

        //图片加载立即执行
        //事件立即产生,立即执行,随着下一次调用开始,上一次事件消亡
        //定义参数,便于理解
        //在赋值的时候,必须回到对象本身; 
        img.style.length=0;       
        var wid=img.width;
        var hei=img.height;
        var bwid=box.clientWidth;
        var bhei=box.clientHeight;
        var k=hei/wid;
        var bk=bhei/bwid;
        
        //斜率大于容器的纵向匹配
        if(k<bk){
            //按宽度进行缩放
            img.height=hei*(bwid/wid);
            img.width=bwid;

            //向下偏移
            img.style.top=(bhei-img.height)/2+'px';

        }else{
            //按高度进行缩放
            img.width=wid*(bhei/hei);
            img.height=bhei;

            //向右偏移
            img.style.left=(bwid-img.width)/2+'px';
        }
    }





                
    
    
