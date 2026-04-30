<?php
header('Content-Type: text/plain');
foreach (['REQUEST_URI','SCRIPT_NAME','PHP_SELF','PATH_INFO','ORIG_PATH_INFO','QUERY_STRING','HTTP_HOST'] as $k) {
    echo $k . '=' . (isset($_SERVER[$k]) ? $_SERVER[$k] : '') . PHP_EOL;
}
