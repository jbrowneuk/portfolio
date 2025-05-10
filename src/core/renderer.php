<?php

namespace jbrowneuk;

class PortfolioRenderer extends \Smarty\Smarty
{
    private static $parsedown = null;

    public static function modifier_parsedown(string $input)
    {
        if (self::$parsedown === null) {
            self::$parsedown = new \Parsedown();
        }

        return self::$parsedown->text($input);
    }

    public static function modifier_pagination(array $pagination)
    {
        $totalPages = ceil($pagination['total_items'] / $pagination['items_per_page']);
        $pages = range(1, $totalPages);
        return $pages;
    }

    public function __construct()
    {
        parent::__construct();

        $this->setCompileDir('smarty/compile');
        $this->setCacheDir('smarty/cache');

        $this->registerPlugin(\Smarty\Smarty::PLUGIN_MODIFIER, 'parsedown', '\jbrowneuk\PortfolioRenderer::modifier_parsedown');
        $this->registerPlugin(\Smarty\Smarty::PLUGIN_MODIFIER, 'pagination', '\jbrowneuk\PortfolioRenderer::modifier_pagination');
    }

    public function setStyleRoot(string $directory)
    {
        $this->assign('styleDirectory', $directory);
    }

    public function setPageId(string $id)
    {
        $this->assign('pageId', $id);
    }

    public function displayPage(string $template)
    {
        $this->display('pages/' . $template . '.tpl');
    }
}
