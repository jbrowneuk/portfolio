{* Smarty template: Single post page layout *}

{extends file="layout/wrapper.tpl"} 

{block name="page-title"}Jason Browne: {$post['title']}{/block}

{block name="page-content"}
    {include file="components/journal/post.tpl"}
{/block}