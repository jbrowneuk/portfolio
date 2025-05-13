{* Smarty template: pagination template *}

<nav class="page-section breadcrumbs pagination-container">
    <ul role="navigation">
        {foreach $pagination|pagination as $segment}
            {assign var=activeClass value=($segment == $pagination['page']) ? 'active' : 'normal'}
            <li class="{$activeClass}"><a href="{$pageUrl}{$pagination['prefix']|default:''}/page/{$segment}">{$segment}</a></li>
        {/foreach}
    </ul>
</nav>