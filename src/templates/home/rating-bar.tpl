{* Smarty template: generic rating bar used on about page *}

<div class="rating-bar-container">
    <span class="sr-only">{$value} out of {$amount}</span>
    <span class="rating-bar">
        {for $index=0 to $amount - 1}
            {if $index <= $value - 1}
                <span class="dot filled" role="presentation" data-dot data-filled></span>
            {else}
                <span class="dot" role="presentation" data-dot></span>
            {/if}
        {/for}
    </div>
</span>