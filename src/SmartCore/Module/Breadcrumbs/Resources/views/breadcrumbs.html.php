<?php
$cnt = count($items);

if ($cnt > 1 or $hide_if_only_home == false) {
    echo "<div>";
    foreach ($items as $item) {
        echo --$cnt ? "<a href=\"{$item['uri']}\" title=\"{$item['descr']}\">" : '';
        echo $item['title'];
        echo $cnt ? "</a>&nbsp;{$delimiter}&nbsp;" : '';
    }
    echo "</div>";
}
