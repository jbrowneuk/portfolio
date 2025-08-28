{* Smarty template: main page wrapper *}

<!DOCTYPE html>
<html lang="en" prefix="og: https://ogp.me/ns# article: https://ogp.me/ns/article#">

{include file="./html-head.tpl"}

<body>
    {include file="../components/spritesheet.svg"}
    {include file="../components/global-header.tpl"}

    <main>
        {block name="page-content"}
        <h1>Blank page!</h1>
        <p>No content block provided for template</p>
        {/block}
    </main>

    {include file="../components/global-footer.tpl"}
</body>

</html>