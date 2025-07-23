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
                <header>Portfolio</header>
                <ul>
                    <li><a href="{$scriptDirectory}/">Home</a></li>
                    <li><a href="{$scriptDirectory}/art">Art</a></li>
                    <li><a href="{$scriptDirectory}/projects/code">Projects</a></li>
                    <li><a href="{$scriptDirectory}/journal">Posts</a></li>
                </ul>
            </div>
            <div class="map-list">
                <header>Connect</header>
                <ul>
                    <li><a href="//github.com/jbrowneuk">GitHub</a></li>
                    <li><a href="//linkedin.com/in/jbrowneuk">LinkedIn</a></li>
                    <li><a href="//jbrowne.io/discord">Discord</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div id="legal-stuff">
        <div class="container">
            <div class="text-container">
                <p></p>
                <p>&copy;2020–{$smarty.now|date_format:'%Y'} Jason Browne</p>
            </div>
        </div>
    </div>
</footer>