<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
use think\Config;
use think\Cookie;

/**
 * @author: My0511_div.
 * @return: json
 * @description: app 微信授权验证
 */

class Wxauth extends Controller
{
    public function _initialize()
    {
        //header("content-type:text/html;charset=utf-8");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Origin: https://wx.jiujiu99.net");
        // header("Access-Control-Allow-Origin: http://localhost:8080");
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
        //echo '111';
    }

    public function index()
    {
		//cookie(null);
        $return_url = input('get.return_url');
		$regex = "/\#/";
		$return_url = preg_replace($regex,"Goto",$return_url);
        $this->redirect(url('wxauth/wxlogin', '', '').'?return_url='.$return_url);
    }

    // public function getAsCookie()
    // {
    //     return json(['data' => ['openid' => cookie('openid'), 'access_token' => cookie('access_token')]]);
    // }

    public function wxlogin()
    {
        Config::load(APP_PATH . 'config/setting.php');
        $setwx = config('wx');
        $appid = $setwx['wx_appid'];
        $redirect_uri = "https://api.jiujiu99.net" . url('api/wxauth/getAsToken', '', '');
        $scope = 'snsapi_userinfo';
		$return_url = input('get.return_url');
        //弹出授权页面 snsapi_base静默授权
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$redirect_uri}&response_type=code&scope={$scope}&state={$return_url}#wechat_redirect";
        $this->redirect($url);
    }

    public function getAsToken()
    {
        Config::load(APP_PATH . 'config/setting.php');
        $setwx = config('wx');
        $appid = $setwx['wx_appid'];
        $secret = $setwx['wx_appecrt'];
        $code = input('get.code');
		if (empty($code)) {
			return json(['code' => '1', 'msg' => 'error', 'data' => '']);
		}
        $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appid . '&secret=' . $secret . '&code=' . $code . '&grant_type=authorization_code';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $get_token_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $res = curl_exec($ch);
        curl_close($ch);
        $json_obj = json_decode($res, true);
        //var_dump($json_obj);
        $return_url = input('get.state');
        if (empty($return_url)) {
			$return_url = "https://wx.jiujiu99.net/#/user";
        }
		$regex = "/Goto/";
		$return_url = preg_replace($regex,"#",$return_url);		
        Cookie::set('access_token', $json_obj['access_token'], ['expire' => 3600,'domain' => '.jiujiu99.net']);
        Cookie::set('openid', $json_obj['openid'], ['expire' => 3600,'domain' => '.jiujiu99.net']);
        $this->redirect($return_url);
    }

    public function getUserInfo()
    {
        // 根据openid和access_token查询用户信息
        $access_token = cookie('access_token');
        $openid = cookie('openid');
        if (empty($access_token) || empty($openid) || $access_token == null || $openid == null) {
            return json(['code' => '1', 'msg' => 'error', 'data' => '']);
        }
        $get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $access_token . '&openid=' . $openid . '&lang=zh_CN';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $get_user_info_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $res = curl_exec($ch);
        curl_close($ch);
        // 解析json
        $user_obj = json_decode($res, true);
        //print_r($user_obj);
        $rsuser = db('member')->where('openid', $user_obj['openid'])->find();
        // 库里无数据自动插库
        if (empty($rsuser)) {
            $data = ['openid' => $user_obj['openid'], 'nikename' => $user_obj['nickname'], 'avatar' => $user_obj['headimgurl'], 'create_time' => time()];
            $uid = Db::name('member')->insertGetId($data);
            db('member')->where('id', $uid)->setField('uid', $uid);
            $rsdata = db('member')->where('uid', $uid)->find();
        } else {
            $rsdata = $rsuser;
        }
        return json(['code' => '0', 'msg' => 'success', 'data' => $rsdata]);
    }
}
