{* Smarty template: Single post page layout *}

{extends file="layout/wrapper.tpl"} 

{block name="page-title"}Jason Browne: {if isset($post)}{$post->title}{else}Not found{/if}{/block}

{block name="extra-stylesheets"}
    <link href="https://jbrowne.io/rss/journal" rel="alternate" type="application/rss+xml" title="Jason Browne’s Journal">
    {if isset($post)}
        <meta property="og:title" content="{$post->title}" />
        {if isset($post->summary)}<meta property="og:description" content="{$post->summary}" />{/if}
        <meta property="og:type" content="article" />
        <meta property="og:url" content="https://jbrowne.io/rss/journal/post/{$post->id}" />
        <meta property="og:image" content="https://jbrowne.io/android-chrome-256x256.png" />
    {/if}
{/block}

{block name="breadcrumbs"}
    <li><a href="{$scriptDirectory}/journal" data-back-button>Journal</a></li>

    {if isset($post)}
        <li data-title>{$post->title}</li>
    {/if}
{/block}

{block name="page-content"}
    {include file="components/breadcrumbs.tpl"}

    {if isset($post)}
        {include file="components/journal/post.tpl"}
    {else}
<section class="page-section text-center">
    <h1>
        <i class="las la-frown" aria-hidden="true"></i>
        This post does not exist
    </h1>
    <p>Try going <a href="{$scriptDirectory}/journal">back to the journal</a>.</p>
</section>
    {/if}
{/block}