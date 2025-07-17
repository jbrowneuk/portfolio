<?php

namespace jbrowneuk;

class PortfolioRenderer extends \Smarty\Smarty
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

    /**
     * Sets the style root directory
     *
     * @param string $directory root directory for all CSS URLs
     */
    public function setStyleRoot(string $directory)
    {
        $this->assign('styleRoot', $directory);
    }

    /**
     * Sets the root directory for the index.php script to control redirects, etc
     *
     * @param string $directory root directory for the index.php script
     */
    public function setScriptDirectory(string $directory)
    {
        $this->scriptDirectory = $directory;
        $this->assign('scriptDirectory', $directory);
    }

    /**
     * Sets the page ID, used for pagination and navigation
     *
     * @param $string id the page ID
     */
    public function setPageId(string $id)
    {
        $this->assign('pageId', $id);
    }

    /**
     * Renders the page to a specified template
     *
     * @param string $template template name
     */
    public function displayPage(string $template)
    {
        $this->display('pages/' . $template . '.tpl');
    }

    /**
     * Redirects to a location under the set scriptDirectory
     *
     * @param string $location location to redirect to
     */
    public function redirectTo(string $location)
    {
        header("Location: {$this->scriptDirectory}/{$location}");
    }
}
