<?php
    include 'head.php';
    
    if(daddslashes($_GET['orderid'])){
        $orderid = daddslashes($_GET['orderid']);
        $pay_info = $DB->getRow("SELECT * FROM `pre_pay` WHERE `orderno`='{$orderid}'");
        $point_info = $DB->getRow("SELECT * FROM `pre_point` WHERE `orderid`='{$orderid}'");
        if($pay_info['status']=='1' && !$point_info){
            $res = rmb('+',$pay_info['money'],$userInfo['user'],'充值余额',$orderid);
            if($res){
                exit('<script>layer.alert(1,{title:"盲盒信息",btn:false,title:"",closeBtn:"",content:\'<center><img src="./public/index/img/user-003.png"><br><br>恭喜您充值余额成功，请核实！<br><a href="./?mod=user" style="display:inline-block;padding:6px 12px;margin-bottom:0;font-size:14px;font-weight:400;line-height:1.42857143;text-align:center;white-space:nowrap;vertical-align:middle;-ms-touch-action:manipulation;touch-action:manipulation;cursor:pointer;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;background-image:none;border:1px solid transparent;border-radius:4px;color:#fff;background-color:#5cb85c;border-color:#4cae4c;display:block;width:100%;">立即查看</a>\'});</script>');
            }
        }
    }
    
    if($userInfo['sex']==1){
        $userInfo['sex'] = '♂';
        $color = '#2196f3';
    }else{
        $userInfo['sex'] = '♀';
        $color = '#e91e63';
    }
    
    if(!$userInfo['touxiang']){
        $userInfo['touxiang'] = '/public/index/img/touxiang/moren.png';
    }
    
    $time = date("Y-m-d H:i:s");
    if($userInfo['viptime']>$time){
        $type = '<button> 本 站 尊 贵 V I P </button>';
    }elseif($userInfo['dltime']>$time){
        $type = '<button color=""> 本 站 尊 贵 月 老 </button>';
    }else{
        $type = '<big> 普 通 用 户 </big>';
    }
    
    $thtime=date("Y-m-d").' 00:00:00';
    // $chouqu_all = $DB->getColumn("SELECT count(*) FROM `pre_manghe` WHERE `for_user`='{$userInfo['user']}'");
    // $chouqu_day = $DB->getColumn("SELECT count(*) FROM `pre_manghe` WHERE `for_user`='{$userInfo['user']}' AND `endtime`>='$thtime'");
    
    // $beichou_all = $DB->getColumn("SELECT count(*) FROM `pre_manghe` WHERE `from_user`='{$userInfo['user']}'");
    // $beichou_day = $DB->getColumn("SELECT count(*) FROM `pre_manghe` WHERE `from_user`='{$userInfo['user']}' AND `endtime`>='$thtime'");
?>
<style>
.test div.layui-layer-btn{
	padding: 0 20px 12px;
}
</style>
<body>
<section class="aui-flexView mh-user-page">
    <section class="aui-scrollView">
        <header class="aui-navBar aui-navBar-fixed">
            <a href="/" class="aui-navBar-item">
                <i class="icon icon-return"></i>
            </a>
            <div class="aui-center">
                <span class="aui-center-title">个人中心</span>
            </div>
            <a onclick="logout('成功退出登录！')" class="aui-navBar-item">
                <i class="icon icon-user"></i>
            </a>
        </header>
        <section class="aui-scrollView mh-user-scroll">
            <div class="mh-profile-card">
                <div class="mh-profile-header">
                    <div class="mh-profile-avatar">
                        <img src="<?php echo $userInfo['touxiang'];?>" alt="avatar">
                    </div>
                    <div class="mh-profile-meta">
                        <div class="mh-profile-name">
                            <h1><?php echo $users['name']?></h1>
                            <button type="button" onclick="user_set()">编辑资料</button>
                        </div>
                        <div class="mh-profile-tags">
                            <span class="mh-chip"><?php echo strip_tags($type); ?></span>
                            <span class="mh-chip"><?php echo $userInfo['sex'].' '.$userInfo['user']?></span>
                            <span class="mh-chip">余额 ￥<?php echo $userInfo['rmb']?></span>
                        </div>
                    </div>
                </div>
                <div class="mh-profile-foot">
                    <div>
                        <p>账户ID</p>
                        <strong><?php echo $userInfo['user']?></strong>
                    </div>
                    <div>
                        <p>会员有效期</p>
                        <strong><?php echo $userInfo['viptime']?></strong>
                    </div>
                    <div>
                        <p>星球身份</p>
                        <strong><?php echo strip_tags($type);?></strong>
                    </div>
                </div>
            </div>

            <div class="mh-action-grid">
                <a onclick="qiandao('<?php echo $userInfo['user']?>')" class="mh-action-tile">
                    <div class="mh-action-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M5 12l5 5L20 7"></path>
                            <rect x="3" y="3" width="18" height="18" rx="4"></rect>
                        </svg>
                    </div>
                    <div>
                        <p>每日签到</p>
                        <span>打卡领取心动值</span>
                    </div>
                </a>
                <a href="?mod=manghe" class="mh-action-tile">
                    <div class="mh-action-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M4 7l8-4 8 4-8 4z"></path>
                            <path d="M4 7v10l8 4 8-4V7"></path>
                        </svg>
                    </div>
                    <div>
                        <p>盲盒记录</p>
                        <span>抽取 / 投递详情</span>
                    </div>
                </a>
                <a id="chongzhi" class="mh-action-tile">
                    <div class="mh-action-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 5v14"></path>
                            <path d="M5 12h14"></path>
                        </svg>
                    </div>
                    <div>
                        <p>充值余额</p>
                        <span>解锁更多机会</span>
                    </div>
                </a>
                <a href="?mod=jiameng" class="mh-action-tile">
                    <div class="mh-action-icon">
                        <svg viewBox="0 0 24 24">
                            <circle cx="7" cy="8" r="3"></circle>
                            <circle cx="17" cy="8" r="3"></circle>
                            <path d="M2 20c0-3 2-6 5-6s5 3 5 6"></path>
                            <path d="M12 20c0-3 2-6 5-6s5 3 5 6"></path>
                        </svg>
                    </div>
                    <div>
                        <p><?php echo ($userInfo['dltime']>=$thtime)?'月老业绩':'加入月老';?></p>
                        <span>扩展人脉，赚取收益</span>
                    </div>
                </a>
            </div>

            <div class="mh-info-card">
                <div class="mh-info-icon">i</div>
                <div>
                    <h3>小贴士</h3>
                    <p><?php echo config('user_gonggao')?></p>
                </div>
            </div>
        </section>
    </section>
</section>
	<script src="//cdn.staticfile.org/modernizr/2.8.3/modernizr.min.js"></script>
	<script src="//cdn.staticfile.org/layer/2.3/layer.js"></script>
	<script>
		$('#chongzhi').click(function(){
		   layer.prompt({title: '输入充值金额'}, function(value, index){
              layer.close(index);
              if(isNaN(value)) {
                layer.alert('请输入正确的金额');
                return false;
              }else{
                pay_cz('chongzhi','<?php echo $order_id?>',value);
              }
            }); 
		});
	</script>
</body>
</html>
<?php include 'foot.php'?>
</body>
</html>