{* Smarty template: Gallery image grid container *}

{include file="components/pagination.tpl"}

<section class="page-section">
    <div class="container">
        {if count($images) === 0}
            <!-- Loading failed -->
            <div>
                <h1>
                    <i class="las la-frown" aria-hidden="true"></i>
                    Nothing found
                </h1>
            </div>
        {else}
            <!-- Images -->
            <div id="gallery-container" class="grid-list">
                {foreach $images as $idx=>$image}
                    {include file="components/gallery/thumbnail.tpl" isPromoted="{$promotedImageIndex === $idx}"}
                {/foreach}
            </div>
        {/if}
    </div>
</section>

{include file="components/pagination.tpl"}