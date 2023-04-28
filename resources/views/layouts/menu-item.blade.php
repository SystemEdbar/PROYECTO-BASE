@php($permission = auth()->user()->getAllPermissions())
@if ($item['children']==[])

    <li class="nav-item {{($permission->pluck('name')->contains('show '.$item['link'])||$item['link']=="#")?'':'disabled'}}">
        <a href="{{url($item['link'])}}"
           class="nav-link {{(new general())->setActive($item['link'])}}
           {{((auth()->user()->getAllPermissions()->pluck('name')->contains('show '.$item['link'])||$item['link']=="#"))?'':'disabled'}}">
            <i class="nav-icon {{$item["icon"]? :"fas fa-circle"}}"></i>
            <p>{{$item["name"]}}</p>
        </a>
    </li>

@else
    @if((new general())->checkPermission($permission,$item['children']))
        <li
            class="nav-item has_treeview {{(($permission->pluck('name')->contains('show '.$item['link'])||$item['link']=="#"))?'':'disabled'}}">
            <a href="#"
               class="nav-link {{(($permission->pluck('name')->contains('show '.$item['link'])||$item['link']=="#"))?'font-weight-bold':'disabled'}}">
                <i class="nav-icon {{$item["icon"]? :"fas fa-circle"}}"></i>
                <p>
                    {{$item["name"]}}
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>

            <ul class="nav nav-treeview">
                @foreach ($item["children"] as $submenu)
                    @include("layouts.menu-item",["item"=>$submenu])
                @endforeach
            </ul>
        </li>
    @endif
@endif

