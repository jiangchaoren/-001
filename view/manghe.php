<?php
include 'head.php';
?>
<style>
.mh-record-page {
    background: #010a1a;
    color: #e2e8f0;
}
</style>
<section class="aui-flexView mh-record-page">
    <header class="aui-navBar aui-navBar-fixed">
        <a href="/" class="aui-navBar-item"></a>
        <div class="aui-center">
            <span class="aui-center-title">盲盒记录</span>
        </div>
        <a onclick="tuiguang()" class="aui-navBar-item"></a>
    </header>
    <section class="aui-scrollView mh-record-scroll">
        <div class="mh-record-hero">
            <h1>记录你的心动轨迹</h1>
            <p>随时查看抽取与投递的盲盒，快速联系命中注定的 Ta。</p>
        </div>
        <div class="mh-tabs">
            <button class="mh-tab-btn active" data-target="draw">我抽取的</button>
            <button class="mh-tab-btn" data-target="deliver">我投递的</button>
        </div>
        <div class="mh-record-pane active" data-pane="draw">
            <?php
                $sex = array('女','男');
                $mh_info = $DB->query("SELECT * FROM `pre_manghe` WHERE `for_user`='{$userInfo['user']}' AND `ok`='1' ORDER BY `endtime` DESC");
                if(!$mh_info->rowCount()){
                    echo '<div class="mh-empty">还没有抽取记录，去星球探索一下吧～</div>';
                }
                while($row = $mh_info->fetch()){
                    if(!$row['name']){
                        $row['name'] = '不愿透露姓名的友人';
                    }
                    if(!$row['touxiang']){
                        $row['touxiang'] = './public/index/img/touxiang/moren.png';
                    }
                    echo '<div class="mh-record-card">
                        <div class="mh-record-avatar" onclick="getimg(\''.$row['touxiang'].'\')">
                            <img src="'.$row['touxiang'].'" alt="">
                        </div>
                        <div class="mh-record-body">
                            <h3>'.$row['name'].'</h3>
                            <p>年龄：'.birthday($row['birthday']).'岁 · 性别：'.$sex[$row['sex']].'</p>
                            <span>抽取时间：'.$row['endtime'].'</span>
                        </div>
                        <button type="button" class="mh-record-btn" onclick="xiangxi(\'toudi\','.$row['id'].')">详情</button>
                    </div>';
                }
            ?>
        </div>
        <div class="mh-record-pane" data-pane="deliver">
            <?php
                $sex = array('女','男');
                $mh_info = $DB->query("SELECT * FROM `pre_manghe` WHERE `from_user`='{$_COOKIE['userName']}' AND `ok`=1 ORDER BY `id` DESC");
                if(!$mh_info->rowCount()){
                    echo '<div class="mh-empty">你还没有投递的盲盒，快去展示自己吧！</div>';
                }
                while($row = $mh_info->fetch()){
                    if(!$row['name']){
                        $row['name'] = '不愿透露姓名的友人';
                    }
                    if(!$row['touxiang']){
                        $row['touxiang'] = './public/index/img/touxiang/moren.png';
                    }
                    echo '<div class="mh-record-card">
                        <div class="mh-record-avatar" onclick="getimg(\''.$row['touxiang'].'\')">
                            <img src="'.$row['touxiang'].'" alt="">
                        </div>
                        <div class="mh-record-body">
                            <h3>'.$row['name'].'</h3>
                            <p>年龄：'.birthday($row['birthday']).'岁 · 性别：'.$sex[$row['sex']].'</p>
                            <span>投递时间：'.$row['addtime'].'</span>
                        </div>
                        <button type="button" class="mh-record-btn" onclick="xiangxi(\'toudi\','.$row['id'].')">详情</button>
                    </div>';
                }
            ?>
        </div>
    </section>
    <?php include 'foot.php';?>
</section>
<script>
    function xiangxi(mod,id){
        var load = layer.load(3, {shade:[0.1,'#fff']});
    	$.ajax({
    		url: "./view/ajax.php?act=manghe_info",
    		data: {id},
    		type: "POST",
    		dataType: "json",
    		success: function (data) {
    			layer.close(load);
    			if(data.code==1){
    			    if(data.sex==0){
    			        data.sex = '女';
    			    }else{
    			        data.sex = '男';
    			    }
    			    if(!data.jieshao){
    			        data.jieshao = '不愿透露介绍的的友人';
    			    }
    			    if(!data.name){
    			        data.name = '不愿透露姓名的的友人';
    			    }
                    layer.alert(1,{
                        title:'盲盒信息',
                        skin: 'layui-layer-molv',
                        content:'姓名：'+data.name+'<br>性别：'+data.sex+'<br>生日：'+data.birthday+'<br>年龄：'+data.age+'<br>星座：'+data.xingzuo+'<br>地址：'+data.city+'<br>微信：'+data.weixin+'<br><br><textarea style="min-height:100px;height:auto;line-height:20px;padding:6px 10px;resize:vertical;display:block;width:100%;" disabled>'+data.jieshao+'</textarea>'
                    });
    			}else{
    			    layer.msg(data.msg);
    			}
    		},
    		error:function(data){
    		    layer.close(load);
    			layer.msg('服务器错误');
    			return false;
    		}
    	})
    }
    
$(function() {
    $('.mh-tab-btn').on('click', function(){
        var target = $(this).data('target');
        $(this).addClass('active').siblings().removeClass('active');
        $('.mh-record-pane').removeClass('active');
        $('.mh-record-pane[data-pane="'+target+'"]').addClass('active');
    });
})
</script>
    </body>
</html>