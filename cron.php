<?php
/**
 * Cron Script
 * To schedule cron on Open Server (Windows) use the following command:
 * %progdir%\modules\php\%phpdriver%\php-win.exe -c %progdir%\userdata\config\%phpdriver%_php.ini -q -f %sitedir%\site-dir.loc\viber-bot\cron.php
 * Schedule period: * /1 * * * * (remove space between first asterisk and slash) - every minute.
 */
require_once __DIR__ . '/cron-config.php';
file_get_contents( $cron_config['bot_url_dev'] );
