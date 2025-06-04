{* Smarty template: album view breadcrumb *}

<nav class="breadcrumbs">
    <div class="container">
        <ol role="navigation">
            <li><a href="/"><i class="las la-home"></i></a></li>
            <li><a href="/art">Art</a></li>
            <li>{$album['name']}</li>
        </ol>
    </div>
</nav>