<?php
namespace app\api\controller;
use think\Controller;
use think\Config;
use think\Db;

/**
 * @author: My0511_div.
 * @return: json
 * @value: code: 0为成功 1为失败 (int)
		   msg: 错误提示 (str)
		   data: 返回数据 (array)
 * @description: app api处理接口数据
 */
 
class Action extends Controller
{
	public function _initialize()
	{
		//header("content-type:text/html;charset=utf-8");
		header("Access-Control-Allow-Credentials: true");
		header("Access-Control-Allow-Origin: https://wx.jiujiu99.net");
		//echo '111';
	}
	
	public function Index()
	{
		return json(['code'=>'1','msg'=>'error','data'=>'']);
	}

  /**
	* 99love 获取手机验证码
	* @method: post
	*/		
	public function MobileCode()
	{
		Config::load(APP_PATH.'config/setting.php');
		$setsms = config('sms');		
        $tel = input('post.tel');
		if(empty($tel)){
			return json(['code'=>'1','msg'=>'请输入手机号码','data'=>'']);		
		}
		$rs = db('smscode')->where('mobile',$tel)->order('id desc')->find();
		if(empty($rs)){
			$yzcode = rand(111111, 999999);
		} else {
			$exptime = $rs['create_time'];
			$nowtime = time();
			$between = $nowtime - $exptime;
			if($between < 600){
				$sms['msg'] = "请勿重复提交，5分钟之后再试";
				return json($sms);
			}
			$yzcode = rand(111111, 999999);		
		}
		$data = [
			'mobile'=>$tel,
			'code'=>$yzcode,
			'create_time'=>time()
		];
		db('smscode')->insert($data);
        $sms = $this->send($tel,str_replace("#code#", $yzcode, $setsms['sms_tpl']));//使用模版替换
        $sms['msg'] = $sms['code'] == 9 ? "请勿重复提交，5分钟之后再试" : $sms['msg'];
		return json($sms);	
	}
	
    public function send($mobile,$text){
		Config::load(APP_PATH.'config/setting.php');
		$setsms = config('sms');
		$ch = curl_init();
        // 必要参数
        $apikey = $setsms['sms_apikey']; //修改为您的apikey(https://www.yunpian.com)登录官网后获取
        $mobile = $mobile; //请用手机号代替
        $text = $text;
        // 发送短信
        $data=array('text'=>$text,'apikey'=>$apikey,'mobile'=>$mobile);
        curl_setopt ($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/single_send.json');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        $json_data = curl_exec($ch);
        if(curl_error($ch) != ""){
            echo 'Curl error: ' .curl_error($ch);
        }
        //解析返回结果（json格式字符串）
        $array = json_decode($json_data,true);
        return $array;
    }	
		
  /**
	* 99love 验证手机
	* @method: post
	*/	
	public function Bind()
	{
		$dataarr = input('post.');
		if(empty($dataarr['tel']) || empty($dataarr['telcode'])){
			return json(['code'=>'1','msg'=>'参数不能为空','data'=>'']);
		}
		$telcode = (int)$dataarr['telcode'];
		$rs = db('smscode')->where('mobile',$dataarr['tel'])->order('id desc')->find();
		if($dataarr['tel'] !== $rs['mobile']){
			return json(['code'=>'1','msg'=>'参数错误','data'=>'']);
		} else if($telcode !== $rs['code']){
			return json(['code'=>'1','msg'=>'验证码错误或过期','data'=>'']);
		} else {
			$data = [
				'mobile'=>$dataarr['tel'],
				'verify'=>1
			];
			db('member')->where('uid',$dataarr['uid'])->update($data);
			return json(['code'=>'0','msg'=>'success','data'=>'']);
		}
	}
	
  /**
	* 99love 个人中心 轮椅/拐杖 退还
	* @method: post
	*/	
	public function GiveBack()
	{
		$dataarr = input('post.');
		if(empty($dataarr['id'])){
			return json(['code'=>'1','msg'=>'参数不能为空','data'=>'']);
		}
		db('dzapply')->where('id',$dataarr['id'])->setField('status',1);
		return json(['code'=>'0','msg'=>'success','data'=>'']);
	}
	
  /**
	* 99love 一对一资助 我要认捐
	* @method: post
	*/
	public function YdyDo()
	{	
		$dataarr = input('post.');
		if(empty($dataarr['uid']) || empty($dataarr['id'])){
			return json(['code'=>'1','msg'=>'参数不能为空','data'=>'']);
		}
		db('student')->where('id',$dataarr['id'])->update(['uid'=>$dataarr['uid'],'status'=>1]);			
		return json(['code'=>'0','msg'=>'success','data'=>'']);
	}
	
  /**
	* 99love 公众号支付获取微信签名
	* @method: post
	*/
    public function JsPay()
    {
		$openid = input('post.openid');
		$data = input('post.');
		if(empty($openid) || empty($data)){
			return json(['code'=>'1','msg'=>'参数不能为空','data'=>'']);
		}
        $params = [
            'body' => $data['body'],
            'out_trade_no' => mt_rand().time(),
            'total_fee' => $data['total_fee'],
        ];
        $result = \wxpay\JsapiPay::getParams($params,$openid);
		return json(['code'=>'0','msg'=>'success','data'=>$result]);
    }	
	

  /**
	* 99love 爱心轮椅/拐杖 捐赠
	* @method: post
	*/	
	public function GiveDo()
	{
		$dataarr = input('post.');
		if(empty($dataarr['uid']) || empty($dataarr['name']) || empty($dataarr['mobile']) || empty($dataarr['num'])){
			return json(['code'=>'1','msg'=>'参数不能为空','data'=>'']);
		}
		$data = [
			'uid'=>$dataarr['uid'],
			'name'=>$dataarr['name'],
			'mobile'=>$dataarr['mobile'],
			'give_num'=>$dataarr['num'],
			'create_time'=>time()
		];
		db('give')->insert($data);
		return json(['code'=>'0','msg'=>'success','data'=>'']);
	}
	
  /**
	* 99love 爱心轮椅/拐杖 申请短租
	* @method: post
	*/
	public function ApplyDz()
	{		
		$dataarr = input('post.');
		if(empty($dataarr['uid']) || empty($dataarr['name']) || empty($dataarr['mobile'])){
			return json(['code'=>'1','msg'=>'参数不能为空','data'=>'']);
		}
		$rs = db('sku')->where('tid',$type)->find();
		$data = [
			'uid'=>$dataarr['uid'],
			'name'=>$dataarr['name'],
			'mobile'=>$dataarr['mobile'],
			'type'=>$dataarr['type'],	
			'begin_time'=>$dataarr['begin_time'],
			'end_time'=>$dataarr['begin_time']+$rs['lease_time'],
			'create_time'=>time(),
		];
		db('dzapply')->insert($data);
		return json(['code'=>'0','msg'=>'success','data'=>'']);				
	}
	
  /**
	* 99love 爱心轮椅/拐杖 申请赠送
	* @method: post
	*/
	public function ApplyZs()
	{
		$dataarr = input('post.');
		if(empty($dataarr['uid']) || empty($dataarr['name']) || empty($dataarr['mobile']) || empty($dataarr['address']) || empty($dataarr['depict']) || empty($dataarr['begin_time']) || empty($dataarr['type'])){
			return json(['code'=>'1','msg'=>'参数不能为空','data'=>'']);
		}
		$data = [
			'uid'=>$dataarr['uid'],
			'name'=>$dataarr['name'],
			'mobile'=>$dataarr['mobile'],
			'address'=>$dataarr['address'],
			'depict'=>$dataarr['depict'],
			'begin_time'=>$dataarr['begin_time'],
			'type'=>$dataarr['type'],
			'create_time'=>time(),
		];
		db('zsapply')->insert($data);
		return json(['code'=>'0','msg'=>'success','data'=>'']);			
	}	
	
  /**
	* 99love 众筹捐款
	* @method: post
	*/
	public function ZcDo()
	{		
		$dataarr = input('post.');
		if(empty($dataarr['uid']) || empty($dataarr['tid']) || empty($dataarr['money']) || empty($dataarr['nikename'])){
			return json(['code'=>'1','msg'=>'参数不能为空','data'=>'']);
		}
		$zcdata = [
			'uid'=>$dataarr['uid'],
			'tid'=>$dataarr['tid'],
			'nikename'=>$dataarr['nikename'],
			'money'=>$datarr['money'],
			'create_time'=>time()
		];
		db('donor')->insert($zcdata);
		$redata = [
			'uid'=>$dataarr['uid'],
			'tid'=>$dataarr['tid'],
			'content'=>$dataarr['content'],
			'create_time'=>time()
		];
		$newid = Db::name('reply')->insertGetId($redata);
		db('zxitem')->where('id',$newid)->update(['rid'=>$newid]);
		return json(['code'=>'0','msg'=>'success','data'=>'']);
	}
	
  /**
	* 99love 我要求助
	* @method: post
	*/
	public function HelpDo()
	{	
		$dataarr = input('post.');
		if(empty($dataarr['uid']) || empty($dataarr['name']) || empty($dataarr['mobile'])){
			return json(['code'=>'1','msg'=>'参数不能为空','data'=>'']);
		}
		$data = [
			'uid'=>$dataarr['uid'],
			'name'=>$dataarr['name'],
			'mobile'=>$dataarr['mobile'],
			'content'=>$dataarr['content'],
			'type'=>$dataarr['type'],
			'create_time'=>time(),
		];
		db('help')->insert($data);
		return json(['code'=>'0','msg'=>'success','data'=>'']);
	}
}
