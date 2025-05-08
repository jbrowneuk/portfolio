{* Smarty template: Gallery album list *}

{extends file="layout/wrapper.tpl"}

{block name="page-title"}Jason Browne: Gallery - All albums{/block}

{block name="extra-stylesheets"}
    <!-- [TODO] fix urls -->
    <link href="./css/art/album-list.css" rel="stylesheet" />
{/block}

{block name="page-content"}
    <!-- Hero -->
    <section id="page-hero" class="page-section small-hero">
        <div class="container">
            <h1>All albums</h1>
        </div>
    </section>

    <!-- Album list -->
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