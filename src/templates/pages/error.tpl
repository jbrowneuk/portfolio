{* Smarty template: Error page *}

{extends file="layout/wrapper.tpl"}

{block name="page-title"}Jason Browne{/block}

{block name="extra-stylesheets"}
    <!-- [TODO] fix urls -->
    <link href="./css/error.css" rel="stylesheet">
{/block}

{block name="page-content"}
    <div id="page-hero" class="error-image">
        <div class="container">
            <img src="/assets/images/404-large.svg" alt="404 image" />
        </div>
    </div>
    <section class="page-section background-alternate">
        <article class="container">
            <header>
                <h1 data-title>Feeling lost? Don’t worry.</h1>
            </header>
            <p>A “404” error appears if there is a broken link or a page was moved.</p>
            <p>
                We all get lost, once in a while. Here’s some directions back to known
                locations, like you'd find on street signs in the real world.
            </p>
            <ul id="site-map" class="button-container" data-navigation>
                {include file="components/error/link-button.tpl" title="Home" image="about" link="/"}
                {include file="components/error/link-button.tpl" title="Art" image="art" link="/art"}
                {include file="components/error/link-button.tpl" title="Software" image="software" link="/projects/code"}
                {include file="components/error/link-button.tpl" title="Journal" image="about" link="/journal"}
            </ul>
        </article>
    </section>
{/block}