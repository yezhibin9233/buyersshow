var photoZoom=function(){
    //找到图片的容器
    //页面中一旦生成新的代码,document属性被改变,原先绑定的js事件都会失效
    //页面每改变一次重新生成一次事件监听
    //闭包中数据共享
    //函数体变量之所以可以共享,是因为他们在同一线程中
    //函数是一个动态执行的过程
    //存在不足:
    //1,对函数体中变量的传递理解还不够透彻
    //2,图片的过渡和兼容性还有待加强
    //根据页面加载情况动态监听点击事件
    //通过求余,将页面内元素进行分类
    var goods=document.getElementById('goods');
    var box=goods.getElementsByClassName('img');
    var img=goods.getElementsByTagName('img');
    
    var clickNumber=0;
    var self=function(x){
        return x;
    }
    
    //i的值不断的随宿主函数发生变化.
    for(var i=0;i<img.length;i++){
        
        img[i].index=i;
        img[i].onclick=function(){           
            if(clickNumber%2==0){

                //改变图片地址
                this.src=this.src.substring(this.src.length-11,0)+'800x800.jpg';
                //清除格式
                this.style.cssText='';

                //改变图片大小
                var w=500;
                var h=parseInt((500/this.width)*this.height);
                var left=box[0].clientWidth-w;
                this.width=w;
                this.height=h;

                //添加浮动
                //this.style.cssFloat='left';
                //最前端显示
                //下标作为静态存储的变量,需要一个静态的值
                this.style.zIndex=999;
                //改变容器属性
                box[this.index].style.overflow='visible';

                if((this.index+1)%4==0||(this.index+1)%4==3){
                    //定位图片位置
                    this.style.left=left+'px'; 
                } 
            }else{
                //清除格式
                this.style.cssText='';
                //重写格式
                photoPosition(this.index);
            }

            clickNumber++;
        }
    }
}





            


