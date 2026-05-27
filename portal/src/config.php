<?php
// VULN: DB credentials hardcoded — lộ ra qua robots.txt hint
define('DB_HOST', getenv('DB_HOST') ?: 'db');
define('DB_PORT', '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'corpdb');
define('DB_USER', getenv('DB_USER') ?: 'appuser');
define('DB_PASS', getenv('DB_PASS') ?: 'SuperSecret123!');
