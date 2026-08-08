<?php
    include 'head.php';
    if(daddslashes($_GET['orderid'])){
        $orderid = daddslashes($_GET['orderid']);
        $chouqu = daddslashes($_GET['chouqu']);
        echo '<script type="text/javascript" src="./public/index/js/main.js?var='.config('var').'"></script>';
        ticheng($orderid,$chouqu);
    }
    
    $manghe_nan = $DB->getColumn("SELECT count(*) FROM `pre_manghe` WHERE `sex`=1 AND `for_user` IS NULL AND `ok`='1'");
    $manghe_nv = $DB->getColumn("SELECT count(*) FROM `pre_manghe` WHERE `sex`=0 AND `for_user` IS NULL AND `ok`='1'");

?>
<style>
.auto-layer{
	width:300px;
}
</style>
<section class="aui-flexView mh-home">
    <section class="aui-scrollView">
        <!-- 顶部 3D 星球效果区域（静态 CSS 动画模拟） -->
        <div class="mh-hero">
            <div class="mh-hero-header">
                <div class="mh-hero-title">单身宝盒</div>
                <div class="mh-hero-subtitle">
                    已有 <span><?php echo ($manghe_nv + $manghe_nan);?></span> 位小伙伴加入心动星球
                </div>
            </div>
            <div class="mh-hero-sphere">
                <div class="mh-avatar-sphere">
                    <?php 
                        $totalAvatars = 38;
                        for($i=0;$i<$totalAvatars;$i++){ 
                            // 粗略随机一个球面角度，形成云状分布
                            $rx = rand(-60, 60);   // 上下俯仰角
                            $ry = rand(0, 360);    // 水平旋转角
                    ?>
                        <span class="mh-avatar-node" style="--rx:<?php echo $rx;?>deg;--ry:<?php echo $ry;?>deg;">
                            <img src="https://v2.xxapi.cn/api/head?return=302&t=<?php echo $i . '_' . time(); ?>" alt="avatar">
                        </span>
                    <?php } ?>
                </div>
            </div>
            <div class="mh-hero-actions">
                <button class="mh-btn mh-btn-primary" onclick="manghe('nan','<?php echo $order_id?>')">
                    抽对象
                </button>
                <button class="mh-btn mh-btn-secondary" onclick="manghe('nv','<?php echo $order_id?>')">
                    抽女神
                </button>
            </div>
            <div class="mh-hero-subactions">
                <button class="mh-link-btn" onclick="location.href='?mod=toudi'">存信息</button>
                <button class="mh-link-btn" onclick="location.href='?mod=manghe'">我的记录</button>
            </div>
        </div>

        <!-- 轮播广告与统计信息，保留原功能 -->
        <div class="mh-section-card">
            <div class="m-slider" data-ydui-slider="">
                <div class="slider-wrapper">
                    <div class="slider-item">
                        <a href="<?php echo config('img1_url');?>">
                            <img src="<?php echo config('img1');?>">
                        </a>
                    </div>
                    <div class="slider-item">
                        <a href="<?php echo config('img2_url');?>">
                            <img src="<?php echo config('img2');?>">
                        </a>
                    </div>
                    <div class="slider-item">
                        <a href="<?php echo config('img3_url');?>">
                            <img src="<?php echo config('img3');?>">
                        </a>
                    </div>
                </div>
                <div class="slider-pagination"></div>
            </div>
            <div class="mh-stat-card">
                <div class="mh-stat-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="M12 6v6l3 3"></path>
                        <path d="M5 9a7 7 0 0 1 0 6"></path>
                        <path d="M19 9a7 7 0 0 0 0 6"></path>
                    </svg>
                </div>
                <div class="mh-stat-copy">
                    <h2>今日缘分速报</h2>
                    <p>现有女生盲盒<?php echo $manghe_nv;?>个，男生盲盒<?php echo $manghe_nan;?>个~</p>
                </div>
            </div>
        </div>

        <!-- 客服与活动区域 -->
        <div class="mh-section-card">
            <?php
                if(config('first_toudi')){
                    echo '<div class="aui-local-box mh-promo-card">
                        <div class="aui-flex">
                            <div class="aui-flex-box">
                                <div class="aui-jiu-logo">
                                    <img src="/public/index/img/zan.png" alt="">
                                </div>
                                <div class="aui-head-info">新用户免费投放一次盲盒</div>
                            </div>
                            <div class="aui-head-button">
                                <a href="?mod=toudi">
                                    <div class="aui-head-button">
                                        <button>立即体验</button>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>';
                }
            ?>

            <div class="aui-local-box mh-service-card">
                <div class="aui-flex">
                    <div class="aui-flex-box">
                        <div class="aui-jiu-logo">
                            <img src="/public/index/img/kf.png" alt="">
                        </div>
                        <div class="aui-head-info">联系QQ客服</div>
                    </div>
                    <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $kfqq;?>&site=qq&menu=yes">
                        <div class="aui-head-button">
                            <button>QQ客服</button>
                        </div>
                    </a>
                </div>
            </div>

            <div class="aui-local-box mh-service-card">
                <div class="aui-flex">
                    <div class="aui-flex-box">
                        <div class="aui-jiu-logo">
                            <img src="/public/index/img/kf.png" alt="">
                        </div>
                        <div class="aui-head-info">联系微信客服</div>
                    </div>
                    <a onclick="layer.alert('客服微信号: <?php echo $kfwx;?>')">
                        <div class="aui-head-button">
                            <button>微信客服</button>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <br><br><br><br>
    </section>
    <?php include 'foot.php'?>
</section>
</body>
</html>