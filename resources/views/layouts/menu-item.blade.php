@php($permission = auth()->user()->getAllPermissions())
@if ($item['children']==[])
    @if ($permission->pluck('name')->contains('show '.$item['link']) || $item['link'] == "#")
        <li class="nav-item">
            <a href="{{url($item['link'])}}"
               class="nav-link {{(new general())->setActive($item['link'])}}">
                <i class="nav-icon {{$item["icon"] ?: "fas fa-circle"}}"></i>
                <p>{{$item["name"]}}</p>
            </a>
        </li>
    @endif
@else
    @if ((new general())->checkPermission($permission, $item['children']))
        @php($isDisabled = (!$permission->pluck('name')->contains('show ' . $item['link'])) && ($item['link'] != "#"))
        @if (!$isDisabled)
            <li class="nav-item has_treeview">
                <a href="#" class="nav-link font-weight-bold">
                    <i class="nav-icon {{$item["icon"] ?: "fas fa-circle"}}"></i>
                    <p>
                        {{$item["name"]}}
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>

                <ul class="nav nav-treeview">
                    @foreach ($item["children"] as $submenu)
                        @include("layouts.menu-item", ["item" => $submenu])
                    @endforeach
                </ul>
            </li>
        @endif
    @endif
@endif
