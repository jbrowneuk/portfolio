{* Smarty template: Admin login layout *}

{extends file="layout/wrapper.tpl"}

{block name="page-title"}Jason Browne: Login{/block}

{block name="extra-head-elements"}
    <link href="{$styleRoot}/css/admin/login.css" rel="stylesheet">
{/block}

{block name="page-content"}
    <nav id="admin-breadcrumb" class="breadcrumbs">
        <div class="container">
            <ol role="navigation">
                <li><a href="/"><i class="las la-home"></i></a></li>
                <li><span>Login</span></li>
            </ol>
        </div>
    </nav>

    <section id="page-hero" class="page-section">
        <div id="login-container" class="base-card">
            <form action="{$scriptDirectory}/auth" method="post" id="login-form">
                <h1 class="top-area">Log in</h1>
                <div class="form-row">
                    <input type="text" name="username" placeholder="e.g. dawn" required data-username />
                    <i class="accessory las la-user-alt" aria-hidden="true"></i>
                </div>
                <div class="form-row">
                    <input type="password" name="password" required data-password />
                    <i class="accessory las la-fingerprint" aria-hidden="true"></i>
                </div>
                {if isset($loginError)}
                    <div class="error-row" data-error-message>
                        Sorry, your login details seem to be incorrect. Please check them and try again.
                    </div>
                {/if}
                <input type="hidden" name="action" value="login" />
                <div class="button-container">
                    <button type="submit" class="primary" data-submit>
                        <i class="las la-sign-in-alt"></i>
                        Log in
                    </button>
                </div>
                <div class="cookie-info">
                    <i class="las la-cookie-bite" aria-hidden="true"></i>
                    A cookie will be stored on your browser when logging in to authenticate you.
                </div>
            </form>

            <div id="side-image">
                <svg class="site-icon">
                    <use xlink:href="#sitesheet-logo" />
                </svg>
            </div>
        </div>
    </section>
{/block}