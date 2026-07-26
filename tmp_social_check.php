<?php
$pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=rakibul_portfolio;charset=utf8mb4', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec("UPDATE social_links SET url = 'rakibulhasansojuncse@gmail.com', label = 'rakibulhasansojuncse@gmail.com' WHERE platform = 'Email';");
$pdo->exec("UPDATE social_links SET url = 'https://www.linkedin.com/in/rakibul-hasan20', label = 'https://www.linkedin.com/in/rakibul-hasan20' WHERE platform = 'LinkedIn';");
$pdo->exec("UPDATE social_links SET url = 'https://github.com/sojunrakib', label = 'https://github.com/sojunrakib' WHERE platform = 'GitHub';");
$rows = $pdo->query('SELECT platform, url, label, is_visible FROM social_links ORDER BY display_order ASC')->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $row) {
    echo "social_links: " . $row['platform'] . " => [" . $row['url'] . "] label=[" . $row['label'] . "] visible=" . $row['is_visible'] . "\n";
}
