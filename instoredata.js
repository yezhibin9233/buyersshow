    //地址栏仅限向服务器端传输数据,客户端不需要通过此方式
    var phpinstore=function(data,url){

        var API=url?url:"ERROR:目标地址不能为空!";
        var data=data?data:{ERROR:"数据不能为空!"};
        var params="?";
        for(key in data){
            params+=key+"="+data[key]+"&";
        }
        var URL=API+params;
        
        // 创建script标签，设置其属性
        var script = document.createElement('script');
        
        // 为script标签添加属性
        script.setAttribute('src',URL);
        
        // 将script标签载入页面内
        document.getElementsByTagName('head')[0].appendChild(script);
    }
    
