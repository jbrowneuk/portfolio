{* Smarty template: Gallery album list *}

{extends file="layout/wrapper.tpl"}

{block name="page-title"}Jason Browne: Gallery - All albums{/block}

{block name="extra-stylesheets"}
    <link href="{$styleRoot}/css/art/album-list.css" rel="stylesheet" />
{/block}

{block name="breadcrumbs"}
    <li><a href="{$scriptDirectory}/art" data-back-button>Art</a></li>
    <li data-title>All albums</li>
{/block}

{block name="page-content"}
    {include file="components/breadcrumbs.tpl"}

    <section class="page-section">
        <div class="container">
            <div id="albums-container" class="grid">
                {foreach $albums as $idx=>$album}
                    {include file="components/gallery/album-card.tpl"}
                {/foreach}
            </div>
        </div>
    </section>
{/block}