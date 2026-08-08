<?php
declare(strict_types=1);

/**
 * 简易在线安装向导
 */

if (file_exists(__DIR__ . '/../install.lock')) {
    exit('<h2 style="font-family:Arial,sans-serif;text-align:center;margin-top:60px;">系统已完成安装，如需重新安装请删除根目录中的 <code>install.lock</code> 文件。</h2>');
}

$rootPath   = dirname(__DIR__) . DIRECTORY_SEPARATOR;
$configPath = $rootPath . 'config.php';
$sqlPath    = $rootPath . 'install.sql';

$requirements = [
    'PHP 7.2+'      => version_compare(PHP_VERSION, '7.2.0', '>='),
    'PDO 扩展'      => extension_loaded('pdo'),
    'PDO_MySQL 扩展' => extension_loaded('pdo_mysql'),
    'config.php 可写' => is_writable($configPath),
    'install.sql 可读' => is_readable($sqlPath),
    '根目录可写'       => is_writable($rootPath),
];

$installable = !in_array(false, $requirements, true);
$messages    = [];
$success     = false;

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function importSql(PDO $pdo, string $sql, string $prefix): int
{
    $sql = str_replace("\r", "\n", $sql);
    $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
    $sql = preg_replace('/^\s*--.*$/m', '', $sql);
    $sql = str_replace('mh_', $prefix . '_', $sql);
    $statements = array_filter(array_map('trim', explode(";\n", $sql)));
    $count = 0;
    foreach ($statements as $statement) {
        if ($statement === '' || strtoupper($statement) === 'COMMIT') {
            continue;
        }
        $pdo->exec($statement);
        $count++;
    }
    return $count;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $installable) {
    $dbHost    = trim($_POST['db_host'] ?? '127.0.0.1');
    $dbPort    = trim($_POST['db_port'] ?? '3306');
    $dbName    = trim($_POST['db_name'] ?? '');
    $dbUser    = trim($_POST['db_user'] ?? '');
    $dbPwd     = trim($_POST['db_pwd'] ?? '');
    $dbPrefix  = trim($_POST['db_prefix'] ?? 'mh');
    $siteName  = trim($_POST['site_name'] ?? '交友盲盒系统');
    $siteTitle = trim($_POST['site_title'] ?? $siteName);
    $keywords  = trim($_POST['site_keywords'] ?? '交友盲盒系统');
    $desc      = trim($_POST['site_description'] ?? '交友盲盒系统');
    $kfwx      = trim($_POST['kfwx'] ?? '');
    $kfqq      = trim($_POST['kfqq'] ?? '');
    $adminName = trim($_POST['admin_name'] ?? 'admin');
    $adminPwd  = trim($_POST['admin_pwd'] ?? '');

    if ($dbHost === '' || $dbPort === '' || $dbName === '' || $dbUser === '' || $dbPrefix === '' || $adminName === '' || $adminPwd === '') {
        $messages[] = ['type' => 'error', 'text' => '请完整填写数据库与管理员信息。'];
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $dbPrefix)) {
        $messages[] = ['type' => 'error', 'text' => '数据表前缀只能由字母、数字或下划线组成。'];
    } else {
        try {
            $dsn = sprintf('mysql:host=%s;port=%s;charset=utf8mb4', $dbHost, $dbPort);
            $pdo = new PDO($dsn, $dbUser, $dbPwd, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
            $pdo->exec("USE `{$dbName}`");

            $tableCheckStmt = $pdo->prepare("SHOW TABLES LIKE :table");
            $tableCheckStmt->execute([':table' => $dbPrefix . '_config']);
            if ($tableCheckStmt->fetch()) {
                throw new RuntimeException('检测到数据库中已存在相同前缀的表，请更换前缀或清空数据库后再试。');
            }

            $sqlContent = file_get_contents($sqlPath);
            if ($sqlContent === false) {
                throw new RuntimeException('无法读取 install.sql 文件。');
            }

            $importCount = importSql($pdo, $sqlContent, $dbPrefix);
            $messages[]  = ['type' => 'info', 'text' => "成功执行 {$importCount} 条初始化 SQL 语句。"];

            $updateStmt = $pdo->prepare("UPDATE `{$dbPrefix}_config` SET `v` = :value WHERE `k` = :key");
            $configPairs = [
                'sitename'    => $siteName,
                'title'       => $siteTitle,
                'keywords'    => $keywords,
                'description' => $desc,
                'kfwx'        => $kfwx,
                'kfqq'        => $kfqq,
                'name'        => $adminName,
                'pwd'         => $adminPwd,
                'sitetime'    => date('Y-m-d'),
            ];
            foreach ($configPairs as $key => $value) {
                $updateStmt->execute([':value' => $value, ':key' => $key]);
            }

            $configArr = [
                'host'  => $dbHost,
                'port'  => (int)$dbPort,
                'user'  => $dbUser,
                'pwd'   => $dbPwd,
                'dbname'=> $dbName,
                'dbqz'  => $dbPrefix,
            ];
            $configContent = "<?php\n\n\$dbconfig = " . var_export($configArr, true) . ";\n";
            if (file_put_contents($configPath, $configContent) === false) {
                throw new RuntimeException('写入 config.php 失败，请检查文件权限。');
            }

            if (file_put_contents($rootPath . 'install.lock', 'installed at ' . date('c')) === false) {
                throw new RuntimeException('创建 install.lock 失败，请检查目录权限。');
            }

            $messages[] = ['type' => 'success', 'text' => '安装成功！请及时删除 install 目录或设置访问限制。'];
            $success    = true;
        } catch (Throwable $e) {
            $messages[] = ['type' => 'error', 'text' => '安装失败：' . $e->getMessage()];
        }
    }
}

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>交友盲盒系统 · 在线安装</title>
    <style>
        *{box-sizing:border-box;}
        body{margin:0;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Arial,sans-serif;background:#0f172a;color:#e2e8f0;}
        .wrapper{max-width:920px;margin:40px auto;padding:32px;background:rgba(15,23,42,.7);border:1px solid rgba(148,163,184,.3);border-radius:18px;box-shadow:0 30px 60px rgba(2,6,23,.6);backdrop-filter:blur(18px);}
        h1{margin-top:0;text-align:center;letter-spacing:.1em;}
        fieldset{border:1px solid rgba(148,163,184,.3);padding:20px;margin-bottom:22px;border-radius:12px;}
        legend{padding:0 8px;color:#fefce8;font-weight:600;}
        label{display:block;margin-bottom:12px;font-size:14px;}
        input,textarea{width:100%;padding:10px 14px;border-radius:10px;border:1px solid rgba(148,163,184,.35);background:rgba(15,23,42,.6);color:#f8fafc;}
        input:focus,textarea:focus{outline:none;border-color:#38bdf8;box-shadow:0 0 0 2px rgba(56,189,248,.35);}
        .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:18px;}
        .btn{display:inline-block;width:100%;padding:14px 18px;font-size:16px;font-weight:600;background:linear-gradient(120deg,#3b82f6,#8b5cf6);border:none;border-radius:999px;color:#fff;cursor:pointer;box-shadow:0 20px 40px rgba(59,130,246,.35);transition:transform .2s ease;}
        .btn:hover{transform:translateY(-2px);}
        ul.require{list-style:none;padding-left:0;margin:0;}
        ul.require li{padding:6px 0;border-bottom:1px dashed rgba(148,163,184,.2);}
        .status-ok{color:#34d399;}
        .status-bad{color:#f87171;}
        .alert{padding:12px 16px;border-radius:10px;margin-bottom:12px;}
        .alert-info{background:rgba(59,130,246,.15);border:1px solid rgba(59,130,246,.35);}
        .alert-error{background:rgba(248,113,113,.15);border:1px solid rgba(248,113,113,.35);}
        .alert-success{background:rgba(34,197,94,.15);border:1px solid rgba(34,197,94,.35);}
        .actions{margin-top:24px;}
        a{color:#93c5fd;}
    </style>
</head>
<body>
    <div class="wrapper">
        <h1>在线安装向导</h1>
        <fieldset>
            <legend>环境检测</legend>
            <ul class="require">
                <?php foreach ($requirements as $label => $status): ?>
                    <li><?php echo h($label); ?>：
                        <strong class="<?php echo $status ? 'status-ok' : 'status-bad'; ?>">
                            <?php echo $status ? '通过' : '未通过'; ?>
                        </strong>
                    </li>
                <?php endforeach; ?>
            </ul>
        </fieldset>

        <?php foreach ($messages as $msg): ?>
            <div class="alert alert-<?php echo h($msg['type']); ?>"><?php echo h($msg['text']); ?></div>
        <?php endforeach; ?>

        <?php if ($success): ?>
            <p>下一步：<a href="../">返回网站首页</a> | <a href="../admin/">进入后台</a></p>
        <?php else: ?>
            <form method="post">
                <fieldset>
                    <legend>数据库配置</legend>
                    <div class="grid">
                        <label>数据库地址
                            <input name="db_host" value="<?php echo h($_POST['db_host'] ?? '127.0.0.1'); ?>" required>
                        </label>
                        <label>数据库端口
                            <input name="db_port" value="<?php echo h($_POST['db_port'] ?? '3306'); ?>" required>
                        </label>
                        <label>数据库名称
                            <input name="db_name" value="<?php echo h($_POST['db_name'] ?? 'manghe'); ?>" required>
                        </label>
                        <label>数据库用户名
                            <input name="db_user" value="<?php echo h($_POST['db_user'] ?? 'root'); ?>" required>
                        </label>
                        <label>数据库密码
                            <input name="db_pwd" type="password" value="<?php echo h($_POST['db_pwd'] ?? ''); ?>">
                        </label>
                        <label>数据表前缀
                            <input name="db_prefix" value="<?php echo h($_POST['db_prefix'] ?? 'mh'); ?>" required>
                        </label>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>网站基础信息</legend>
                    <div class="grid">
                        <label>站点名称
                            <input name="site_name" value="<?php echo h($_POST['site_name'] ?? '交友盲盒系统'); ?>">
                        </label>
                        <label>站点标题
                            <input name="site_title" value="<?php echo h($_POST['site_title'] ?? '交友盲盒系统'); ?>">
                        </label>
                        <label>站点关键词
                            <input name="site_keywords" value="<?php echo h($_POST['site_keywords'] ?? '交友盲盒系统'); ?>">
                        </label>
                        <label>站点描述
                            <input name="site_description" value="<?php echo h($_POST['site_description'] ?? '交友盲盒系统'); ?>">
                        </label>
                        <label>客服微信
                            <input name="kfwx" value="<?php echo h($_POST['kfwx'] ?? ''); ?>">
                        </label>
                        <label>客服 QQ
                            <input name="kfqq" value="<?php echo h($_POST['kfqq'] ?? ''); ?>">
                        </label>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>管理员信息</legend>
                    <div class="grid">
                        <label>管理员账号
                            <input name="admin_name" value="<?php echo h($_POST['admin_name'] ?? 'admin'); ?>" required>
                        </label>
                        <label>管理员密码
                            <input name="admin_pwd" type="password" value="<?php echo h($_POST['admin_pwd'] ?? 'admin123'); ?>" required>
                        </label>
                    </div>
                </fieldset>
                <div class="actions">
                    <button class="btn" type="submit">开始安装</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>

