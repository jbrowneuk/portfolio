{* Smarty template: Post list page layout *}

{extends file="layout/wrapper.tpl"} 

{block name="page-title"}Jason Browne: Journal{/block}

{block name="page-content"}
    {include file="components/journal/tag-header.tpl"}

    {foreach $posts as $post}
        {include file="components/journal/post.tpl"}
    {/foreach}

    {include file="components/pagination.tpl"}
{/block}
