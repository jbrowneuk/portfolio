{* Smarty template: global page header *}

<header id="page-header">
  <div id="sticky-menu">
    <nav id="menu" class="container">
      <a class="home-link" href="/">
        <svg viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" class="link-icon">
          <use href="#sitesheet-logo"></use>
        </svg>
        <span class="link-text hide-sm">Jason Browne</span>
      </a>
      <ul id="nav-links">
        {include file="./nav-link.tpl" url="/" title="portfolio" isActive="{(isset($pageId) && $pageId == 'portfolio')}"}
        {include file="./nav-link.tpl" url="/projects/code/" title="projects" isActive="{(isset($pageId) && $pageId == 'projects')}"}
        {include file="./nav-link.tpl" url="/art/" title="art" isActive="{(isset($pageId) && $pageId == 'art')}"}
        {include file="./nav-link.tpl" url="/journal/" title="posts" isActive="{(isset($pageId) && $pageId == 'journal')}"}
        {block name="nav-links"}
          {* No extra nav links by default *}
        {/block}
      </ul>
    </nav>
  </div>
</header>