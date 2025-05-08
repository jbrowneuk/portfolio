{* Smarty template: page header nav links *}

{if $isActive == 1}
    <li><a href="{$url}" class="active">{$title}</a></li>
{else}
    <li><a href="{$url}">{$title}</a></li>
{/if}