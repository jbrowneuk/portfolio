{* Smarty template: global page header *}

<header id="page-header">
    <div id="sticky-menu">
        <nav id="menu" class="container">
            <a class="home-link" href="{$scriptDirectory}/">
                <svg viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" class="link-icon">
                    <use href="#sitesheet-logo"></use>
                </svg>
                <h1 class="link-text hide-sm">Jason Browne</h1>
            </a>
            <menu id="nav-links">
                {strip}
                    {assign var=pid value=isset($pageId) ? $pageId : ''}

                    {include file="./nav-link.tpl" url="{$scriptDirectory}/" title="portfolio" isActive="{($pid == 'portfolio')}" extraClasses="hide-md hide-sm"}
                    {include file="./nav-link.tpl" url="{$scriptDirectory}/projects/code/" title="projects" isActive="{($pid == 'projects')}"}
                    {include file="./nav-link.tpl" url="{$scriptDirectory}/art/" title="art" isActive="{($pid == 'art')}"}
                    {include file="./nav-link.tpl" url="{$scriptDirectory}/journal/" title="posts" isActive="{($pid == 'journal')}"}
                    {block name="nav-links"}{* No extra nav links by default *}{/block}
                {/strip}
            </menu>
        </nav>
    </div>
</header>