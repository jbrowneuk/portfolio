{* Smarty template: Single post page layout *}

{extends file="layout/wrapper.tpl"} 

{block name="page-title"}Jason Browne: {if isset($post)}{$post['title']}{else}Not found{/if}{/block}

{block name="page-content"}
    {if isset($post)}
        <nav class="breadcrumbs">
            <div class="container">
                <ol role="navigation">
                    <li><a href="/"><i class="las la-home"></i></a></li>
                    <li><a href="/journal" data-back-button>Journal</a></li>
                    <li data-title>{$post['title']}</li>
                </ol>
            </div>
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