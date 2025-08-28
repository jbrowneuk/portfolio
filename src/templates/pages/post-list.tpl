{* Smarty template: Post list page layout *}

{extends file="layout/wrapper.tpl"} 

{block name="page-title"}Jason Browne: Journal{/block}

{block name="extra-head-elements"}
    <link href="https://jbrowne.io/rss/journal" rel="alternate" type="application/rss+xml" title="Jason Browne’s Journal">
{/block}

{block name="page-content"}
    {include file="components/journal/tag-header.tpl"}

    {foreach $posts as $post}
        {include file="components/journal/post.tpl"}
    {/foreach}

    {include file="components/pagination.tpl"}
{/block}
