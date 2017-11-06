<div class="item">
    <div class="itemInfo">
        <div class="itemTitle">
            {{ $item->name }}
        </div>
        <div class="itemDescription">
            @if ($item->identification_number)
                {{ $item->identification_number }} |
            @endif
            x {{ number_format($item->qty) }}
        </div>
    </div>
    <div class="itemCost">
        <div class="itemCostAmount">
            {{ $currencySymbol }} {{ $item->cost }}
        </div>
    </div>
</div>