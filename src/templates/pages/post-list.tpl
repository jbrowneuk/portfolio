{* Smarty template: Post list page layout *}

{extends file="layout/wrapper.tpl"} 

{block name="page-title"}Jason Browne: Journal{/block}

{block name="extra-stylesheets"}
    <link href="https://jbrowne.io/rss/journal" rel="alternate" type="application/rss+xml" title="Jason Browne’s Journal">
{/block}

{include file="components/journal/breadcrumb-generator.tpl"}

{block name="page-content"}
    {include file="components/breadcrumbs.tpl"}

    {foreach $posts as $post}
        {include file="components/journal/post.tpl"}
    {/foreach}

    {include file="components/pagination.tpl"}
{/block}
