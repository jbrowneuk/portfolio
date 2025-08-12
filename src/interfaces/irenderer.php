<?php

namespace jbrowneuk;

interface IRenderer {
    /**
     * Sets the style root directory
     *
     * @param string $directory root directory for all CSS URLs
     */
    public function setStyleRoot(string $directory);

    /**
     * Sets the root directory for the index.php script to control redirects, etc
     *
     * @param string $directory root directory for the index.php script
     */
    public function setScriptDirectory(string $directory);

    /**
     * Sets the page ID, used for pagination and navigation
     *
     * @param $string id the page ID
     */
    public function setPageId(string $id);

    /**
     * Renders the page to a specified template
     *
     * @param string $template template name
     */
    public function displayPage(string $template);

    /**
     * Redirects to a location under the set scriptDirectory
     *
     * @param string $location location to redirect to
     */
    public function redirectTo(string $location);

    /**
	 * Assigns a variable to the renderer
	 *
	 * @param string $key the template variable name(s)
	 * @param mixed $value the value to assign
     */
    public function assign(string $key, mixed $value);

    /**
	 * Registers plugin to be used in templates
	 *
	 * @param string $type plugin type
	 * @param string $name name of template tag
	 * @param callable $callback PHP callback to register
	 */
	public function registerPlugin($type, $name, $callback);
}