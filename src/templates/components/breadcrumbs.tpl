{* Smarty template: breadcrumb layout *}

<nav class="breadcrumbs">
    <div class="container" role="navigation">
        <menu>
            <li><a href="{$scriptDirectory}/"><i class="las la-home"></i></a></li>
            {block name="breadcrumbs"}{* Filled in by consumer *}{/block}
        </menu>
    </div>
</nav>