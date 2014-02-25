<?php
$cnt = count($items);

$class = empty($css_class) ? '' : ' class="' . $css_class . '"';

if ($cnt > 1 or $hide_if_only_home == false) {
    echo "<div{$class}>";
    foreach ($items as $item) {
        echo --$cnt ? "<a href=\"{$item['uri']}\" title=\"{$item['descr']}\">" : '';
        echo $item['title'];
        echo $cnt ? "</a>&nbsp;{$delimiter}&nbsp;" : '';
    }
    echo "</div>";
}
