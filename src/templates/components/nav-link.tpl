{* Smarty template: page header nav links *}

{* Calculate classList *}
{assign var=activeClass value=$isActive == 1 ? 'active' : ''}
{assign var=classList value=[]}
{if !$activeClass|empty}
    {append var=classList value=$activeClass}
{/if}

{if !$extraClasses|empty}
    {append var=classList value=$extraClasses}
{/if}

<li><a href="{$url}" class="{$classList|join:' '}">{$title}</a></li>