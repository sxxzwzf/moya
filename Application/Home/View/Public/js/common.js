/**
 * 页面初始化自动执行 -- 检查是否登录
 */
(function( window, undefined ) {
    var isLogin = function() {
        var token = sessionStorage.getItem('token');
        var pathname = window.location.pathname;
        //当前方法名
        var method_name = pathname.substring(pathname.lastIndexOf("/"));
        if(token == null || token == 'undefined') {
            if(method_name.indexOf("login") == -1){

                //$("[href='#login-modal']")[0].click();
                //alert($("[href='#login-modal']"));
               //window.location.href="/app/html/login/login.html";
            } 
            return false;
        } else {
            return true;
            if(method_name.indexOf("login") == 1){
                window.location.href="/app/html/home/index.html";
            }
            return false;
        }
    };
    window.onload = isLogin;
})(window);

/**
 *
 * @param params ajax 传递参数对象
 * @param callback 回调方法
 * @param isPost 是否为post 传参 默认或false为post true 为get
 * @param isAsync 是否为异步 默认或false为同步 true 为异步
 */
function ajax(params,callback,isPost,isAsync){
    showMask();
    //获取token
    var token       = sessionStorage .getItem('token');
    var devive_type = 'wap';

    //判断是否为post请求
    isPost  = (isPost == 0 || isPost == undefined) ? "post" : "get";
    isAsync = (isAsync == 0 || isAsync == undefined) ? false : true;

    //使用jq ajax方法
    setTimeout(function(){
        if(params.data == '' || params.data == 'undefined' || params.data == null) params.data = {};
        $.ajax({
            type:isPost,
            beforeSend: function(xhr){
                xhr.setRequestHeader('XX-Token', token);
                xhr.setRequestHeader('XX-Device-Type', devive_type);
            },
            url:params.url,
            data:params.data,
            async:isAsync,
            success:function(ret) {
                hideMask();
                if(ret.code === 10001){
                    localStorage.clear();
                    sessionStorage.clear();
                    window.location.href="/home/login/login.html";
                }
                typeof(callback) == 'function' && callback.apply(this, [ret]);
            },
            error:function(err) {
                hideMask();
                typeof(callback) == 'function' && callback.apply(this, [err]);
            }
        });
    }, 500);
}

//显示遮罩层
function showMask(){
    var html = "<div id='m_mask' style='position: fixed;width: 100%;height: 100%;top: 0;left: 0;background: rgba(0, 0, 0, 0.3);opacity: 0;z-index: 999;visibility: visible;-webkit-transition: opacity .3s,-webkit-transform .3s;transition: opacity .3s,transform .3s;'></div><div class=\"aui-toast loading\" style='display: block;' ><div class=\"aui-toast-loading\"></div><div class=\"aui-toast-content\">正在加载</div></div>";
    $('body')[0].insertAdjacentHTML('afterBegin',html);
    document.querySelector(".aui-toast").style.marginTop =  "-"+Math.round(document.querySelector(".aui-toast").offsetHeight/2)+"px";
}

//删除遮罩层
function hideMask(){
    $('#m_mask').remove();
    $('.loading').remove();
}



// aui-btn
/**
 * js 时间戳转时间格式
 * @param time
 * @returns {string}
 */
function getdate(time) {
    var now = new Date(time * 1000),
        y = now.getFullYear(),
        m = now.getMonth() + 1,
        d = now.getDate();
    // return y + "-" + (m < 10 ? "0" + m : m) + "-" + (d < 10 ? "0" + d : d) + " " + now.toTimeString().substr(0, 8);
    return now.toTimeString().substr(0, 5);
}

var xStart,xEnd,yStart,yEnd;
document.addEventListener('touchmove',function(evt){
    xEnd=evt.touches[0].pageX;
    yEnd=evt.touches[0].pageY;
    Math.abs(xStart-xEnd)> Math.abs(yStart-yEnd)&&
    evt.preventDefault();
},false);

document.addEventListener("touchstart",function(evt){
    xStart=evt.touches[0].pageX;
    yStart=evt.touches[0].pageY;
},false);