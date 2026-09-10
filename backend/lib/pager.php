<?php

function getPager($pages = 0, $current_page = 1, $visible_page_links = 6)
{
    if (!$pages) {
        return [
            'prev'  => '0',
            'next'  => '0',
            'first' => '1',
            'last'  => '1',
            'pages' => ["1"]
        ];
    }

    $current_page = max($current_page, 0);
    $max = $next = $current_page + 1;
    $min = $prev = $current_page - 1;
    $first = 1;

    if ($next > $pages) {
        $next = (string)$pages;
        $min = $current_page - 2;
        $max = $pages;
    }

    if ($current_page <= 1) {
        $min = 2;
        $prev = $first = '1';
        $max = $current_page + 3;
        $max = ($max > $pages) ? $pages : $max;
    }

    $pager = [
        'prev'  => $prev,
        'next'  => $next,
        'first' => $first,
        'last'  => ($current_page == $pages) ? (string)$pages : $pages,
        'pages' => []
    ];

    if ($pages <= $visible_page_links) {
        for ($i = 0; $i < $pages; $i++) {
            $pager['pages'][] = ($i+1 == $current_page) ? (string)($i+1) : $i+1;
        }

    } elseif ($pages > 2) {
        $pager['pages'][] = $first;
        (($first + 1) < $min) ? $pager['pages'][] = '&#8230;' : null;

        if ($min == $first) {
            ++ $min;
            ++ $max;
        }

        if ($max == $pager['last']) {
            -- $max;
        }

        for ($i = $min; $i <= $max; ++ $i) {
            $pager['pages'][] = ($i == $current_page) ? (string)$i : $i;
        }

        (($pages - 1) > $max) ? $pager['pages'][] = '&#8230;' : null;
        $pager['pages'][] = $current_page == $pages ? (string) $pages : $pages;

    }

    return $pager;
}

