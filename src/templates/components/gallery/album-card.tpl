{* Smarty template: Gallery album list item card *}

<a class="album-card" href="{$scriptDirectory}/art/album/{$album->id}/page/1">
    <div class="image-area">
        <img src="{$imageRoot}{$iconDir}{$album->id}.jpg" alt="icon" class="preview" />
    </div>
    <div class="text-area">
        <h2>{$album->name}</h2>
        <div class="badge-area">
            {$album->imageCount}
            images
        </div>
    </div>
</a>