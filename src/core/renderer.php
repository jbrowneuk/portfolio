<?php

namespace jbrowneuk;

class PortfolioRenderer extends \Smarty\Smarty implements IRenderer
{
    private static ?\Parsedown $parsedown = null;
    private string $scriptDirectory = '';

    /**
     * Parses markdown to HTML text
     *
     * @param string $input markdown text content
     * 
     * @return string HTML representation of the markdown input
     */
    public static function modifier_parsedown(string $input)
    {
        if (self::$parsedown === null) {
            self::$parsedown = new \Parsedown();
        }

        return self::$parsedown->text($input);
    }

    /**
     * Parses pagination data for rendering
     *
     * @param array $pagination pagination data
     *
     * @return array pagination data
     */
    public static function modifier_pagination(array $pagination)
    {
        $totalPages = ceil($pagination['total_items'] / $pagination['items_per_page']);
        $pages = range(1, $totalPages);
        return $pages;
    }

    /**
     * Constructs an instance of the PortfolioRenderer
     */
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
        $this->assign('styleRoot', $directory);
    }

    public function setScriptDirectory(string $directory)
    {
        $this->scriptDirectory = $directory;
        $this->assign('scriptDirectory', $directory);
    }

    public function setPageId(string $id)
    {
        $this->assign('pageId', $id);
    }

    public function displayPage(string $template)
    {
        $this->display('pages/' . $template . '.tpl');
    }

    public function redirectTo(string $location)
    {
        header("Location: {$this->scriptDirectory}/{$location}");
    }
}
