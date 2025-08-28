{* Smarty template: post list breadcrumbs controller *}

{block name="breadcrumbs"}
    {if isset($tag)}
        <li><a href="{$scriptDirectory}/journal">Journal</a></li>
        <li data-title><span>Posts tagged <b>{$tag}</b></span></li>
    {else}
        <li data-title>Journal</li>
    {/if}
{/block}
