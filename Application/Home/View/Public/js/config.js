//const SERVERURL = "http://moya2018/api/mobile/";	//设置域名
var curWwwPath = window.document.location.origin;
const SERVERURL = curWwwPath+"/api/mobile/";   //设置域名

let Config = { };

/*路由地址*/
Config.api = {

    //=========================
    // 1 首页
    //=========================
    //1.1 用户登录
    UserLogin                  : SERVERURL + "index/login",
    //1.2 用户注册
    UserRegister               : SERVERURL + "index/register",
    //1.3 幻灯片获取
    Toolsslides                : SERVERURL + "index/slide",
    //1.4 获取文章列表
    NewsList                   : SERVERURL + "index/newsList",
    //1.5 获取指定的文章
    NewsRead                   : SERVERURL + "index/newsRead",

    //=========================
    // 2 分类
    //=========================
    //2.1 获取子分类
    Category                   : SERVERURL + "category/getAlbumCategoryList",
    //2.2 子分类的内容
    CategoryList               : SERVERURL + "category/getListById",
    //2.3 子分类的详情-官方
    CategoryOfficeDetails            : SERVERURL + "category/getOfficeDetails",
    //2.4 子分类的详情-代理商
    CategoryAgentDetails            : SERVERURL + "category/getAgentDetails",

    //=========================
    // 3 我的
    //=========================
    //3.1 用户退出
    UserLoginOut               : SERVERURL + "mine/logout",
    //3.2 用户信息获取
    UserInfo                   : SERVERURL + "mine/userInfo",
    //3.3 用户信息修改
    UserInfoModify             : SERVERURL + "mine/userInfo",
    //3.4 以base64方式上传图片
    UserEditAvatar             : SERVERURL + "mine/uploadImgToBase64",
    //3.5 上传相册
    UploadAlbum                : SERVERURL + "mine/addAlbum",
    //3.6 我的相册列表
    MyAlbumList                : SERVERURL + "mine/myAlbumList",






    //=========================
    // 其他 1用户部分
    //=========================
    //1.1发送邮箱或手机验证码
    UserSend                  : SERVERURL + "user/verification_code/send",
    //1.4文件上传
    UserOne                  : SERVERURL + "user/upload/one",
    //1.5密码修改
    UserChangePassword                  : SERVERURL + "user/profile/changePassword",
    //1.6密码重置
    UserPasswordReset                  : SERVERURL + "user/public/passwordReset",
    //1.8用户手机号绑定
    UserBindingMobile                 : SERVERURL + "user/profile/bindingMobile",
    //1.9用户邮箱绑定
    UserBindingEmail                  : SERVERURL + "user/profile/bindingEmail",

    //=========================
    // 2小程序部分
    //=========================
    //2.1小程序用户登录
    WxLogin                  : SERVERURL + "wxapp/public/login",
    //=========================
    // 3评论部分
    //=========================
    //3.1评论列表
    CommentsGetComments                  : SERVERURL + "user/comments/getComments",
    //3.2我的评论列表
    CommentsGetUserComments                  : SERVERURL + "user/comments/getUserComments",
    //3.3添加评论
    CommentsSetComments                  : SERVERURL + "user/comments/setComments",
    //3.4删除评论
    CommentsDelComments                  : SERVERURL + "user/comments/delComments",

    //=========================
    // 6收藏
    //=========================


};
