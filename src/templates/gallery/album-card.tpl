{* Smarty template: Gallery album list item card *}

<a class="album-card" href="/art/album/{$album['album_id']}/page/1]">
    <div class="image-area">
        <img src="{$imageRoot}{$iconDir}{$album['album_id']}.jpg" alt="icon" class="preview" />
    </div>
    <div class="text-area">
        <h2>{$album['name']}</h2>
        <div class="badge-area">
            {$album['image_count']}
            images
        </div>
    </div>
</a>