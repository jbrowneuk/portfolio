{* Smarty template: pagination template *}

<nav class="pagination">
    <div class="container">
        <ol role="navigation">
            {foreach $pagination|pagination as $segment}
                {assign var=activeClass value=($segment == $pagination['page']) ? 'active' : 'normal'}
                <li class="{$activeClass}"><a
                    href="{$pageUrl}{$pagination['prefix']|default:''}/page/{$segment}">{$segment}</a></li>
            {/foreach}
        </ol>
    </div>
</nav>