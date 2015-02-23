#!/bin/bash

cp web/app.php var/app.php
echo "$(date +'%s')" > var/rebuild_cache.lock
# Здесь надо web/app.php с проверкой лока
bin/clear_cache
bin/warmup_cache prod
rm var/rebuild_cache.lock
#mv var/app.php web/app.php
bin/warmup_cache dev

: << COMMENTBLOCK
<?php
$count = 10;
$isLocked = false;

while ($count--) {
    if (file_exists(__DIR__.'/../var/rebuild_cache.lock')) {
        echo time().'<br/>';
        sleep(1);
        $isLocked = true;
    } else {
        $isLocked = false;
        break;
    }
}

if ($isLocked) {
    die('Try again later');
}

?>
COMMENTBLOCK
