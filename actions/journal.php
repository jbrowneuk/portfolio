<?php
namespace jbrowneuk;

function renderAction($pdo, $smarty) {
    try {
        $posts = get_posts($pdo);
    } catch (\PDOException $ex) {
        die($ex->getMessage());
    }

    $smarty->assign('pageId', 'journal');
    $smarty->assign('posts', $posts);
    $smarty->display('post-list.tpl');
}