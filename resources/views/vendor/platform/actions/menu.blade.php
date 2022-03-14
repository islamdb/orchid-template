@isset($title)
    <li class="nav-item mt-3 mb-1">
        <small class="text-muted ms-4 w-100 user-select-none">{{ __($title) }}</small>
    </li>
@endisset

@if (!empty($name))
<li class="nav-item {{ active($active) }}" style="@if(active($active) == 'active' and config('platform.sidebar.color', 'white') == 'white') padding-top: 1px; padding-bottom: 1px @endif">
    <div class="@if(active($active) == 'active' and config('platform.sidebar.color', 'white') == 'white') bg-secondary {{ config('platform.sidebar.active', 'rounded') }} @endif" style="width: 100%">
        <a data-turbo="{{ var_export($turbo) }}"
            {{ $attributes }}
        >
        <span class="@if(active($active) == 'active' and config('platform.sidebar.color', 'white') == 'white') text-white @endif">
            @isset($icon)
                <x-orchid-icon :path="$icon" class="{{ empty($name) ?: 'me-2'}}"/>
            @endisset

            {{ $name ?? '' }}
        </span>

            @isset($badge)
                <b class="badge bg-{{$badge['class']}} col-auto ms-auto">{{$badge['data']()}}</b>
            @endisset
        </a>
    </div>
</li>
@endif

@if(!empty($list))
    <div class="nav collapse sub-menu ps-2 {{active($active, 'show')}}"
         id="menu-{{$slug}}"
         data-bs-parent="#headerMenuCollapse">
        @foreach($list as $item)
            {!!  $item->build($source) !!}
        @endforeach
    </div>
@endif

@if($divider)
    <li class="divider my-2"></li>
@endif

