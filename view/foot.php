<?php
	function check($name,$mod){
	    if(!$mod){
	        $mod = 'index';
	    }
		if($mod==$name){
			return 'aui-tabBar-item-active';
		}
	}
?>
<footer class="aui-footer">
    <a href="/" style="text-decoration: none" class="aui-tabBar-item <?php echo check('index',$mod);?>">
        <span class="aui-tabBar-item-icon">
            <svg viewBox="0 0 24 24">
                <path d="M3 11.5 12 4l9 7.5"></path>
                <path d="M5 10v10h14V10"></path>
            </svg>
        </span>
        <span class="aui-tabBar-item-text">盲盒</span>
    </a>
    <a href="?mod=toudi" style="text-decoration: none" class="aui-tabBar-item <?php echo check('toudi',$mod);?>">
        <span class="aui-tabBar-item-icon">
            <svg viewBox="0 0 24 24">
                <path d="M4 5h16v14H4z"></path>
                <path d="M8 9h8"></path>
                <path d="M12 9v6"></path>
            </svg>
        </span>
        <span class="aui-tabBar-item-text">投递</span>
    </a>
    <a href="?mod=manghe" style="text-decoration: none" class="aui-tabBar-item <?php echo check('manghe',$mod);?>">
        <span class="aui-tabBar-item-icon">
            <svg viewBox="0 0 24 24">
                <path d="M5 5h14v14H5z"></path>
                <path d="M9 5v6l3 2 3-2V5"></path>
            </svg>
        </span>
        <span class="aui-tabBar-item-text">记录</span>
    </a>
    <a href="?mod=user" style="text-decoration: none" class="aui-tabBar-item <?php echo check('user',$mod);?>">
        <span class="aui-tabBar-item-icon">
            <svg viewBox="0 0 24 24">
                <path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z"></path>
                <path d="M5 20a7 7 0 0 1 14 0"></path>
            </svg>
        </span>
        <span class="aui-tabBar-item-text">我的</span>
    </a>
</footer>

<script type="text/javascript" src="./public/index/js/main.js?a=11"></script>