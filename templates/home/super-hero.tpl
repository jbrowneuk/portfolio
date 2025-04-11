{* Smarty template: Portfolio top/home page: super hero *}

<div class="top-super-hero beam-container" data-test="signature-area">
    <div class="text text-center">
        <svg id="signature" data-test="signature">
            <use xlink:href="#sitesheet-signature" />
        </svg>
        <p id="tagline" data-test="tagline">
            <span class="fancy-heading-container light">
                <span>Artworks</span> • <span>Visual Design</span> •
                <span>Code</span>
            </span>
        </p>
    </div>

    <div class="text-center scroll-bouncer">
        <i class="las la-arrow-down icon"></i>
    </div>

    {for $index=1 to 9}
        <div class="beam el{$index}"></div>
    {/for}
</div>