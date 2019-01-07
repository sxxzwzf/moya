/**
 * 显示密码
 * @author hongwei 2018-04-15
 */
function showPassword() {
    $api.attr($api.byId('password'), 'type', 'text');
    $api.removeCls($api.byId('showpass-btn'), 'aui-icon-display');
    $api.addCls($api.byId('showpass-btn'), 'aui-icon-hide');
    $api.attr($api.byId('showpass-btn'), 'onclick', 'hidePassword();');
}

/**
 * 隐藏密码
 * @author hongwei 2018-04-15
 */
function hidePassword() {
    $api.attr($api.byId('password'), 'type', 'password');
    $api.removeCls($api.byId('showpass-btn'), 'aui-icon-hide');
    $api.addCls($api.byId('showpass-btn'), 'aui-icon-display');
    $api.attr($api.byId('showpass-btn'), 'onclick', 'showPassword();');
}

/**
 * 验证码倒计时
 * @author hongwei 2018-04-15
 * @type {number} 倒计时时间
 */
var countdown = 5;
function sms(val){
    if(countdown == 0){
        val.removeAttribute('disabled');
        val.innerHTML = '验证码';
        countdown = 5;
    }else{
        val.setAttribute('disabled',true);
        val.innerHTML = "（" + countdown + "）";
        countdown--;
        setTimeout(function(){
            sms(val);
        },1000)
    }
}
