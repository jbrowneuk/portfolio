{* Smarty template: breadcrumb layout *}

{assign var=rootDir value=isset($rootDirectory) ? $rootDirectory : $scriptDirectory }

<nav class="breadcrumbs">
    <div class="container" role="navigation">
        <menu>
            <li><a href="{$rootDir}/"><i class="las la-home"></i></a></li>
            {block name="breadcrumbs"}{* Filled in by consumer *}{/block}
        </menu>
    </div>
</nav>