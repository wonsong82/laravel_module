<span>
  @if( !empty($entry->{$column['name']}) )
      @if(substr($entry->{$column['name']}, 0, 5) == 'data:')
            <a href="{{ route('item::crud.item-brand.viewLogo', ['item_brand' => $entry->id]) }}" onclick="openSinglePopup(this);return false;" class="popup-md">
                <img
                        src="{{ $entry->{$column['name']} }}"
                        style="
                                max-height: {{ isset($column['height']) ? $column['height'] : "22px" }};
                                width: {{ isset($column['width']) ? $column['width'] : "auto" }};
                                border-radius: 3px;"
                />
            </a>
      @else
            <a
                    href="{{ asset( (isset($column['prefix']) ? $column['prefix'] : '') . $entry->{$column['name']}) }}"
                    target="_blank"
            >
      <img
              src="{{ asset( (isset($column['prefix']) ? $column['prefix'] : '') . $entry->{$column['name']}) }}"
              style="
                      max-height: {{ isset($column['height']) ? $column['height'] : "22px" }};
                      width: {{ isset($column['width']) ? $column['width'] : "auto" }};
                      border-radius: 3px;"
      />
    </a>
      @endif
  @else
    -
  @endif
</span>
