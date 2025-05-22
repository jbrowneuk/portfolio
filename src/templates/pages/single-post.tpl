{* Smarty template: Single post page layout *}

{extends file="layout/wrapper.tpl"} 

{block name="page-title"}Jason Browne: {if isset($post)}{$post['title']}{else}Not found{/if}{/block}

{block name="page-content"}
    {if isset($post)}
        <nav class="breadcrumbs page-section">
            <a href="/journal" data-back-button>All posts</a>
            <span class="spacer">
                <i class="las la-angle-double-right" aria-hidden="true"></i>
            </span>
            <span data-title>{$post['title']}</span>
        </nav>

        {include file="components/journal/post.tpl"}
    {else}
<section class="page-section text-center">
    <h1>
        <i class="las la-frown" aria-hidden="true"></i>
        This post does not exist
    </h1>
    <p>Try going <a href="/journal">back to the journal</a>.</p>
</section>
    {/if}
{/block}