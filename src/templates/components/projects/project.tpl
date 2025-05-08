{* Smarty template: Project container *}

<section class="page-section post project-list-section" data-project-name="{$project['name']}">
    <article class="container">
        <header>
            <h1>
                <a href="{$project['url']}" data-test="name">{$project['name']}</a>
            </h1>
            {if $project['archived']}
                <span class="archived">
                    <i class="las la-box" aria-hidden="true"></i> Archived
                </span>
            {/if}
        </header>

        <div class="grid">
            <div class="text-area">
                <ul class="project-info">
                    <li data-project-language>
                        <i class="las la-code" aria-hidden="true"></i>
                        {$project['language']}
                    </li>
                    <li data-project-license>
                        <i class="las la-balance-scale" aria-hidden="true"></i>
                        {$project['license']}
                    </li>
                    <li data-project-last-updated>
                        <i class="las la-calendar" aria-hidden="true"></i>
                        Last updated {$project['updated']|date_format}
                    </li>
                </ul>

                <p data-test="description">{$project['description']}</p>

                <div class="button-container">
                    <a href="{$project['url']}" class="button neutral" data-project-link>
                        <i class="las la-rocket"></i> Go to project page
                    </a>
                </div>
            </div>

            <div class="image-area">
                {if $project['archived']}
                    <i class="las la-box project-icon-archived" aria-hidden="true"></i>
                {else}
                    <img src="/public/images/code/{$project['name']}.jpg" alt="project image" class="rounded" />
                {/if}
            </div>
        </div>
    </article>
</section>