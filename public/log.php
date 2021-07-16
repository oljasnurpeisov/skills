<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('/var/www/html/Git.php/Git.php');

$repo = Git::open('/var/www/html/skills');
echo nl2br($repo->log());