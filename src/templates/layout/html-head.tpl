{* Smarty template: HTML <head> area *}

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{block name="page-title"}Jason Browne{/block}</title>

    <!-- Third-party dependencies -->
    <link href="{$styleRoot}/assets/thirdparty/normalize/normalize.css" rel="stylesheet">

    <!-- Base font -->
    <link href="{$styleRoot}/assets/thirdparty/nunito/nunito.css" rel="stylesheet">

    <!-- Iconography -->
    <link href="{$styleRoot}/assets/thirdparty/la/css/line-awesome.min.css?1.3.0" rel="stylesheet">

    <!-- Theme -->
    <link href="{$styleRoot}/theme/palette.css?v3.5.3" rel="stylesheet">

    <!-- Component library -->
    <link href="{$styleRoot}/theme/styles.css?v3.6.0" rel="stylesheet">

    <!-- Site icons and manifest -->
    <link rel="icon" href="/favicon.ico" sizes="any" />
    <link rel="icon" href="/icons/favicon.svg" type="image/svg+xml" />
    <link
      rel="icon"
      href="/icons/icon-32.png"
      type="image/png"
      sizes="32x32"
    />
    <link
      rel="icon"
      href="/icons/icon-16.png"
      type="image/png"
      sizes="16x16"
    />
    <link
      rel="icon"
      href="/icons/icon-512.png"
      type="image/png"
      sizes="512x512"
    />
    <link
      rel="apple-touch-icon"
      href="/apple-touch-icon.png"
      sizes="180x180"
    />
    <link rel="manifest" href="/site.webmanifest" />
    <meta name="theme-color" content="#4d698e" />

    {block name="extra-head-elements"}{/block}
</head>