{* Smarty template: global page footer *}

<footer id="page-footer">
    <div class="container">
        <svg id="backdrop-icon" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
            <use href="#sitesheet-logo"></use>
        </svg>
        <div id="site-map">
            <svg id="site-icon" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                <use href="#sitesheet-logo"></use>
            </svg>
            <div class="map-list">
                <h2>Portfolio</h2>
                <menu>
                    <li><a href="{$scriptDirectory}/">Home</a></li>
                    <li><a href="{$scriptDirectory}/art">Art</a></li>
                    <li><a href="{$scriptDirectory}/projects/code">Projects</a></li>
                    <li><a href="{$scriptDirectory}/journal">Posts</a></li>
                </menu>
            </div>
            <div class="map-list">
                <h2>Connect</h2>
                <menu>
                    <li><a href="//github.com/jbrowneuk">GitHub</a></li>
                    <li><a href="//linkedin.com/in/jbrowneuk">LinkedIn</a></li>
                    <li><a href="//jbrowne.io/discord">Discord</a></li>
                </menu>
            </div>
        </div>
    </div>
    <div id="legal-stuff">
        <div class="container">
            <div class="text-container">
                <div class="color-theme-switcher">
                    <button type="button" aria-pressed="false" data-theme="light" id="light-mode-button">
                        Light
                    </button>
                    <button type="button" aria-pressed="true" data-theme="auto" id="system-mode-button">
                        Auto
                    </button>
                    <button type="button" aria-pressed="false" data-theme="dark" id="dark-mode-button">
                        Dark
                    </button>
                </div>
                <p>&copy;2020–{$smarty.now|date_format:'%Y'} Jason Browne</p>
            </div>
        </div>
    </div>
</footer>