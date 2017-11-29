<?php
namespace app\api\controller;
use think\Controller;
use think\Db;

/**
 * @author: My0511_div.
 * @return: json
 * @value: code: 0为成功 1为失败 (int)
		   msg: 错误提示 (str)
		   data: 返回数据 (array)
 * @description: app api查询接口数据
 */

class Index extends Controller
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
	* 99love 获取会员信息
	* @method: get
	*/
	public function MemberInfo()
	{
		if(session('?openid')){
			$openid = session('openid');
			//var_dump($_SESSION);
			$list = db('member')->where('openid',$openid)->find();
			return json(['code'=>'0','msg'=>'success','data'=>$list,'jumpUrl'=>'']);
		} else {
			var_dump($_SESSION);
			$jumpurl = "http://jiujiu99.yuanhang.org".url('wxauth/wxlogin','','');
			return json(['code'=>'1','msg'=>'error','data'=>'','jumpUrl'=>$jumpurl]);
		}
	}

  /**
	* 99love 获取首页轮播
	* @method: get
	* @limit: 4条记录
	*/
	public function Slide()
	{
		$list = db('slide')->field('id,title,image,link')->order('sort desc')->limit(4)->select();
		foreach($list as $k=>$v){
			$list[$k]['image'] = path2url($v['image']);
		}
		return json(['code'=>'0','msg'=>'success','data'=>$list]);
	}

  /**
	* 99love 获取文章列表
	* @method: get
	* @page: 页数
	* @limit: 每页条数
	* @order: 排序方式
	*/
	public function Article()
	{
		$page = input('get.page');
		$limit = input('get.limit');
		$order = input('get.order');
		$classify = input('get.classify');
		if($order == 0){
			// 最新时间
			$map = 'create_time desc';
		} else if($order == 1){
			// 排序值最大
			$map = 'sort desc';
		}
		if($classify == 'all'){
			$list = Db::table('99love_article')->field('id,title,depict,article_class,stick,create_time')->page($page,$limit)->order($map)->select();
		} else {
			$list = Db::table('99love_article')->field('id,title,depict,article_class,stick,create_time')->page($page,$limit)->where('article_class',$classify)->order($map)->select();
		}
		return json(['code'=>'0','msg'=>'success','data'=>$list]);
	}

  /**
	* 99love 获取文章详情
	* @method: get
	* @id: 主键id
	*/
	public function ArticleShow()
	{
		$id = input('get.id');
		db('article')->where('id',$id)->setInc('click');
		$list = db('article')->where('id',$id)->find();
		$list['content'] = htmlspecialchars_decode($list['content']);
		return json(['code'=>'0','msg'=>'success','data'=>$list]);
	}

  /**
	* 99love 获取一对一资助列表
	* @method: get
	* @page: 页数
	* @limit: 每页条数
	* @order: 排序方式
	*/
	public function YdyItem()
	{
		$page = input('get.page');
		$limit = input('get.limit');
		$order = input('get.order');
		$status = input('get.status');
		if($order == 0){
			// 最新时间
			$map = 'id desc';
		} else if($order == 1){
			// 排序值最大
			$map = 'sort desc';
		}
		if($status == 'all'){
			$where['op'] = '0';
			$list = Db::table('99love_zxitem')->field('id,tid,title,cover,begin_time,end_time,status,stick')->page($page,$limit)->where($where)->order($map)->select();
		} else {
			$where['op'] = '0';
			$where['status'] = $status;
			$list = Db::table('99love_zxitem')->field('id,tid,title,cover,begin_time,end_time,status,stick')->page($page,$limit)->where($where)->order($map)->select();
		}
		$now = time();
		foreach($list as $k=>$v){
			// 最新项目状态获取
			if($now < $v['begin_time']){
				db('zxitem')->where('id',$v['id'])->setField('status','0');
				$list[$k]['status'] = "0"; // 预告中
			} else if($now > $v['begin_time'] && $now < $v['end_time']){
				if(!empty($v['tid'])){
					$num = db('student')->where('tid',$v['tid'])->count();
					$listnum = Db::query("select count(*) AS count from 99love_student where uid>0 and tid='".$v['tid']."'");
					$listnum = $listnum[0]['count'];
					if($num == $listnum){
						db('zxitem')->where('id',$v['id'])->setField('status','2');
						$list[$k]['status'] = "2"; // 已结束
						$list[$k]['count_num'] = $num;
						$list[$k]['rs_num'] = $listnum;
					} else {
						db('zxitem')->where('id',$v['id'])->setField('status','1');
						$list[$k]['status'] = "1"; // 进行中
						$rsnum = $num-$listnum;
						$list[$k]['count_num'] = $num;
						$list[$k]['rs_num'] = $listnum;
					}
				}
			} else if($now > $v['end_time']){
				db('zxitem')->where('id',$v['id'])->setField('status','2');
				$list[$k]['status'] = "2"; // 已结束
			}
			$list[$k]['cover'] = path2url($v['cover']);
		}
		return json(['code'=>'0','msg'=>'success','data'=>$list]);
	}

  /**
	* 99love 获取众筹资助列表
	* @method: get
	* @page: 页数
	* @limit: 每页条数
	* @order: 排序方式
	* @status: 查看状态 all为所有 *必要
	*/
	public function ZcItem()
	{
		$page = input('get.page');
		$limit = input('get.limit');
		$order = input('get.order');
		$status = input('get.status');
		if($order == 0){
			// 最新时间
			$maporder = 'id desc';
		} else if($order == 1){
			// 排序值最大
			$maporder = 'sort desc';
		}
		if($status == 'all'){
			$where['op'] = '0';
			$list = Db::table('99love_wxyitem')->field('id,tid,title,cover,begin_time,end_time,need_money,now_money,status,stick')->page($page,$limit)->where($where)->order($maporder)->select();
		} else {
			$where['op'] = '0';
			$where['status'] = $status;
			$list = Db::table('99love_wxyitem')->field('id,tid,title,cover,begin_time,end_time,need_money,now_money,status,stick')->page($page,$limit)->where($where)->order($maporder)->select();
		}
		$now = time();
		foreach($list as $k=>$v){
			// 最新项目状态获取
			if($now < $v['begin_time']){
				db('wxyitem')->where('id',$v['id'])->setField('status','0');
				$list[$k]['status'] = "0"; // 预告中
			} else if($now > $v['begin_time'] && $now < $v['end_time']){
				if($v['now_money'] === $v['need_money']){
					db('wxyitem')->where('id',$v['id'])->setField('status','2');
					$list[$k]['status'] = "2"; // 已结束
				} else {
					db('wxyitem')->where('id',$v['id'])->setField('status','1');
					$list[$k]['status'] = "1"; // 进行中
				}
			} else if($now > $v['end_time']){
				db('wxyitem')->where('id',$v['id'])->setField('status','2');
				$list[$k]['status'] = "2"; // 已结束
			}
			$list[$k]['cover'] = path2url($v['cover']);
		}
		return json(['code'=>'0','msg'=>'success','data'=>$list]);
	}

  /**
	* 99love 获取受益人数列表
	* @method: get
	* @page: 页数
	* @limit: 每页条数
	* @order: 排序方式
	*/
	public function SyrItem()
	{
		$page = input('get.page');
		$limit = input('get.limit');
		$order = input('get.order');
		if(!isset($page) || !isset($limit)){
			return json(['code'=>'1','msg'=>'error','data'=>'']);
		}
		if($order == 0){
			// id最大
			$maporder = 'id desc';
		} else if($order == 1){
			// 最新时间
			$maporder = 'create_time desc';
		}
		$pagesize = ($page-1)*$limit;
		$dznum = db('dzapply')->where('status',0)->count();
		$zsnum = db('zsapply')->where('status',1)->count();
		$num = $dznum+$zsnum;
		$list = Db::query('(select name,mobile,begin_time,type,status from 99love_dzapply where status=0 order by '.$maporder.') union all (select name,mobile,begin_time,type,status from 99love_zsapply where status=1 order by '.$maporder.') limit '.$pagesize.','.$limit.'');
		$arr['num'] = $num;
		$arr['list'] = $list;
		return json(['code'=>'0','msg'=>'success','data'=>$arr]);
	}

  /**
	* 99love 获取助学项目-认捐学生列表
	* @method: get
	* @tid: 项目id
	*/
	public function Student()
	{
		$tid = input('get.tid');
		$list = db('student')->field('id,uid,cid,name,city,sex,grade,help_money,give_money,reason,op,anyone,status')->where('tid',$tid)->select();
		foreach($list as $k=>$v){
			$uname = db('member')->field('nikename')->where('uid',$v['uid'])->find();
			$list[$k]['uname'] = $uname['nikename'];
		}
		return json(['code'=>'0','msg'=>'success','data'=>$list]);
	}

  /**
	* 99love 获取助学项目-认捐学生详情
	* @method: get
	* @id: 主键id
	*/
	public function StudentShow()
	{
		$id = input('get.id');
		$list = db('student')->field('depict')->where('id',$id)->find();
		$list['depict'] = htmlspecialchars_decode($list['depict']);
		return json(['code'=>'0','msg'=>'success','data'=>$list]);
	}

  /**
	* 99love 获取一对一资助详情
	* @method: get
	* @id: 主键id
	*/
	public function YdyShow()
	{
		$id = input('id');
		$list = db('zxitem')->field('id,title,depict,begin_time,end_time,status')->where('id',$id)->find();
		return json(['code'=>'0','msg'=>'success','data'=>$list]);
	}

  /**
	* 99love 获取众筹资助详情
	* @method: get
	* @id: 主键id
	* @tid: 项目id
	*/
	public function ZcShow()
	{
		$id = input('get.id');
		$tid = input('get.tid');
		$list = db('wxyitem')->field('id,title,quick_payment,need_money,now_money,depict,details,cover,begin_time,end_time,status')->where('id',$id)->find();
		$count = db('donor')->where('tid',$tid)->count();
		$list['cover'] = path2url($list['cover']);
		$list['details'] = htmlspecialchars_decode($list['details']);
		$list['num'] = $count;
		return json(['code'=>'0','msg'=>'success','data'=>$list]);
	}

  /**
	* 99love 获取众筹资助详情-捐款用户头像
	* @method: get
	* @tid: 项目id
	*/
	public function ZcAvatar()
	{
		$tid = input('get.tid');
		$rslist = db('donor')->field('uid')->where('tid',$tid)->order('create_time desc')->limit(10)->select();
		foreach($rslist as $k=>$v){
			$list[$k] = db('member')->field('avatar')->where('uid',$v['uid'])->find();
		}
		return json(['code'=>'0','msg'=>'success','data'=>$list]);
	}

  /**
	* 99love 获取众筹资助详情-捐款用户留言
	* @method: get
	* @tid: 项目id
	*/
	public function ZcReply()
	{
		$page = input('get.page');
		$limit = input('get.limit');
		$tid = input('get.tid');
		$map['tid'] = $tid;
		$map['status'] = '1';
		$rslist = db('reply')->field('id,uid,tid,content,mark,create_time')->where($map)->page($page,$limit)->order('create_time desc')->select();
		foreach($rslist as $k=>$v){
			$list[$k] = db('member')->field('avatar')->where('uid',$v['uid'])->find();
			$where['uid'] = $v['uid'];
			$where['tid'] = $v['tid'];
			$list[$k] = db('donor')->field('nikename,money')->where($where)->find();
			$list[$k]['uid'] = $v['uid'];
			$list[$k]['content'] = $v['content'];
			$list[$k]['mark'] = $v['mark'];
			$list[$k]['create_time'] = $v['create_time'];
		}
		return json(['code'=>'0','msg'=>'success','data'=>$list]);
	}

  /**
	* 99love 获取申请爱心项目赠送用户详情
	* @method: get
	* @page: 页数
	* @limit: 每页条数
	* @order: 排序方式
	* @type: 项目类型
	*/
	public function ZsApply()
	{
		$page = input('get.page');
		$limit = input('get.limit');
		$type = input('get.type');
		if(empty($type)){
			return json(['code'=>'1','msg'=>'error','data'=>'']);
		}
		$list = db('zsapply')->field('id,name,depict,type,status,create_time,mark')->where('type',$type)->page($page,$limit)->order('create_time desc')->select();
		return json(['code'=>'0','msg'=>'success','data'=>$list]);
	}

  /**
	* 99love 获取申请爱心项目详情
	* @method: get
	* @id: 主键id
	* @type: 项目类型
	*/
	public function Sku()
	{
		$id = input('get.id');
		$type = input('get.type');
		if(empty($type)){
			return json(['code'=>'1','msg'=>'error','data'=>'']);
		}
		$list = db('sku')->where('id',$id)->find();
		$rs = Db::query('(select name,mobile,type,status from 99love_dzapply where status=0 and type='.$type.') union all (select name,mobile,type,status from 99love_zsapply where status=1 and type='.$type.')');
		$rsnum = count($rs);
		$arr['num'] = $list['number'];
		$arr['rs_num'] = $rsnum;
		return json(['code'=>'0','msg'=>'success','data'=>$arr]);
	}

  /**
	* 99love 获取求助列表
	* @method: get
	* @page: 页数
	* @limit: 每页条数
	* @order: 排序方式
	* @type: 求助类型
	*/
	public function HelpItem()
	{
		$page = input('get.page');
		$limit = input('get.limit');
		$order = input('get.order');
		$type = input('get.type');
		if($order == 0){
			// id最大
			$maporder = 'id desc';
		} else if($order == 1){
			// 最新时间
			$maporder = 'create_time desc';
		}
		if($type == 'all'){
			$list = Db::table('99love_help')->page($page,$limit)->order($maporder)->select();
		} else {
			$list = Db::table('99love_help')->page($page,$limit)->where('type',$type)->order($maporder)->select();
		}
		return json(['code'=>'0','msg'=>'success','data'=>$list]);
	}

  /**
	* 99love 获取个人中心项目列表
	* @method: get
	* @uid: 用户id
	* @item: 项目列表
	*/
	public function MemberItem()
	{
		$uid = input('get.uid');
		$item = input('get.item');
		if(empty($uid) || empty($item)){
			return json(['code'=>'1','msg'=>'error','data'=>'']);
		}
		if($item == 'ydy'){
			$list = db('student')->field('id,tid,cid,name,city,sex,grade,help_money,give_money,reason')->where('uid',$uid)->select();
		} else if($item == 'give'){
			$list = db('give')->where('uid',$uid)->select();
		} else if($item == 'zc'){
			$rslist = db('donor')->field('tid')->where('uid',$uid)->select();
			foreach($rslist as $k=>$v){
				$list[$k] = db('wxyitem')->field('id,tid,title,cover,begin_time,end_time,need_money,now_money,status')->where('tid',$v['tid'])->find();
			}
		} else if($item == 'lovegz'){
			$list = Db::query('(select * from 99love_dzapply where type=1) union all (select * from 99love_zsapply where type=1)');
		} else if($item == 'lovely'){
			$list = Db::query('(select * from 99love_dzapply where type=2) union all (select * from 99love_zsapply where type=2)');
		} else if($item == 'help'){
			$list = db('help')->where('uid',$uid)->select();
		}
		return json(['code'=>'0','msg'=>'success','data'=>$list]);
	}

}
