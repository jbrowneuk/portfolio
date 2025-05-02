{* Smarty template: Gallery image grid container *}
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
        {/if}

        {if true}
        <!-- Images -->
        <div id="gallery-container" class="grid-list">
            {foreach $images as $idx=>$image}
                {include file="gallery/thumbnail.tpl" isPromoted="{$promotedImageIndex === $idx}"}
            {/foreach}
        </div>
        {/if}
    </div>
</section>