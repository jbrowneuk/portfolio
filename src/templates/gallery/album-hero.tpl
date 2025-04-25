<section *ngIf="data" id="page-hero" class="page-section small-hero">
    <div class="container">
        <div class="toolbar">
            <h1>{$albumName}</h1>
            <div class="spacer"></div>
            <div class="secondary-text right-side" data-image-count>
                {count($images)} {count($images) === 1 ? 'image' : 'images'}
            </div>
            <a href="/art/albums" class="button primary right-side" data-album-selector>Pick another album</a>
        </div>
    </div>
</section>