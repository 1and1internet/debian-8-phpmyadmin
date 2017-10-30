<?php
/* Modified copy of file found at:
 *  < https://github.com/phpmyadmin/docker/blob/master/etc/phpmyadmin/config.inc.php >
 * This file licensed under: GNU General Public License v3.0
 *  < https://github.com/phpmyadmin/docker/blob/master/LICENSE >
 */

require('config.secret.inc.php');

/* Ensure we got the environment */
$vars = array(
    'PMA_ARBITRARY',
    'PMA_HOST',
    'PMA_HOSTS',
    'PMA_VERBOSE',
    'PMA_VERBOSES',
    'PMA_PORT',
    'PMA_PORTS',
    'PMA_USER',
    'PMA_PASSWORD',
    'PMA_CONTROL_HOST',
    'PMA_CONTROL_PORT',
    'PMA_CONTROL_USER',
    'PMA_CONTROL_PASSWORD',
    'PMA_ABSOLUTE_URI'
);
foreach ($vars as $var) {
    $env = getenv($var);
    if (!isset($_ENV[$var]) && $env !== false) {
        $_ENV[$var] = $env;
    }
}

/* Arbitrary server connection */
if (isset($_ENV['PMA_ARBITRARY']) && $_ENV['PMA_ARBITRARY'] === '1') {
    $cfg['AllowArbitraryServer'] = true;
}

/* Play nice behind reverse proxys */
if (isset($_ENV['PMA_ABSOLUTE_URI'])) {
    $cfg['PmaAbsoluteUri'] = trim($_ENV['PMA_ABSOLUTE_URI']);
}

/* Figure out hosts */

/* Fallback to default linked */
$hosts = array('db');

/* Set by environment */
if (!empty($_ENV['PMA_HOST'])) {
    $hosts = array($_ENV['PMA_HOST']);
    $verbose = array($_ENV['PMA_VERBOSE']);
    $ports = array($_ENV['PMA_PORT']);
} elseif (!empty($_ENV['PMA_HOSTS'])) {
    $hosts = explode(',', $_ENV['PMA_HOSTS']);
    $verbose = explode(',', $_ENV['PMA_VERBOSES']);
    $ports = explode(',', $_ENV['PMA_PORTS']);
}

/* Server settings */
for ($i = 1; isset($hosts[$i - 1]); $i++) {
    $cfg['Servers'][$i]['host'] = $hosts[$i - 1];
    if (isset($verbose[$i - 1])) {
        $cfg['Servers'][$i]['verbose'] = $verbose[$i - 1];
    }
    if (isset($ports[$i - 1])) {
        $cfg['Servers'][$i]['port'] = $ports[$i - 1];
    }
    if (isset($_ENV['PMA_USER'])) {
        $cfg['Servers'][$i]['auth_type'] = 'config';
        $cfg['Servers'][$i]['user'] = $_ENV['PMA_USER'];
        $cfg['Servers'][$i]['password'] = isset($_ENV['PMA_PASSWORD']) ? $_ENV['PMA_PASSWORD'] : '';
    } else {
        $cfg['Servers'][$i]['auth_type'] = 'cookie';
    }
    if (isset($_ENV['PMA_CONTROL_USER'])) {
        $cfg['Servers'][$i]['pmadb'] = 'phpmyadmin';
        $cfg['Servers'][$i]['controlhost'] = $_ENV['PMA_CONTROL_HOST'];
        $cfg['Servers'][$i]['controlport'] = $_ENV['PMA_CONTROL_PORT'];
        $cfg['Servers'][$i]['controluser'] = $_ENV['PMA_CONTROL_USER'];
        $cfg['Servers'][$i]['controlpass'] = isset($_ENV['PMA_CONTROL_PASSWORD']) ? $_ENV['PMA_CONTROL_PASSWORD'] : null;
        $cfg['Servers'][$i]['bookmarktable'] = 'pma__bookmark';
        $cfg['Servers'][$i]['relation'] = 'pma__relation';
        $cfg['Servers'][$i]['favorite'] = 'pma__favorite';
        $cfg['Servers'][$i]['table_info'] = 'pma__table_info';
        $cfg['Servers'][$i]['table_coords'] = 'pma__table_coords';
        $cfg['Servers'][$i]['pdf_pages'] = 'pma__pdf_pages';
        $cfg['Servers'][$i]['column_info'] = 'pma__column_info';
        $cfg['Servers'][$i]['history'] = 'pma__history';
        $cfg['Servers'][$i]['table_uiprefs'] = 'pma__table_uiprefs';
        $cfg['Servers'][$i]['tracking'] = 'pma__tracking';
        $cfg['Servers'][$i]['userconfig'] = 'pma__userconfig';
        $cfg['Servers'][$i]['recent'] = 'pma__recent';
        $cfg['Servers'][$i]['users'] = 'pma__users';
        $cfg['Servers'][$i]['usergroups'] = 'pma__usergroups';
        $cfg['Servers'][$i]['navigationhiding'] = 'pma__navigationhiding';
        $cfg['Servers'][$i]['savedsearches'] = 'pma__savedsearches';
        $cfg['Servers'][$i]['central_columns'] = 'pma__central_columns';
        $cfg['Servers'][$i]['designer_settings'] = 'pma__designer_settings';
        $cfg['Servers'][$i]['export_templates'] = 'pma__export_templates';
    }
    $cfg['Servers'][$i]['connect_type'] = 'tcp';
    $cfg['Servers'][$i]['compress'] = false;
    $cfg['Servers'][$i]['AllowNoPassword'] = true;
}

/* Uploads setup */
$cfg['UploadDir'] = '';
$cfg['SaveDir'] = '';

/* Avoid permissions warning */
$cfg['CheckConfigurationPermissions'] = false;

/* Include User Defined Settings Hook */
if (file_exists('config.user.inc.php')) {
    include('config.user.inc.php');
}
