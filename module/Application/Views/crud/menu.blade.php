<li class="header">@lang('application::module.nav.header.admin')</li>

<li class="treeview">
    <a href="#">
        <i class="fa fa-list"></i>
        <span>@lang('application::module.nav.user')</span>
        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
    </a>
    <ul class="treeview-menu">
        <li>
            <a href="{{ route('application::crud.user.index') }}">
                <i class="fa fa-list"></i>
                <span>@lang('application::module.nav.users')</span>
            </a>
        </li>
        <li>
            <a href="{{ route('application::crud.role.index') }}">
                <i class="fa fa-group"></i>
                <span>@lang('application::module.nav.roles')</span>
            </a>
        </li>
        <li>
            <a href="{{ route('application::crud.permission.index') }}">
                <i class="fa fa-key"></i>
                <span>@lang('application::module.nav.permissions')</span>
            </a>
        </li>
    </ul>
</li>


<li class="treeview">
    <a href="#">
        <i class="fa fa-list"></i>
        <span>@lang('application::module.nav.admin')</span>
        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
    </a>
    <ul class="treeview-menu">
        <li>
            <a href="{{ backpack_url('elfinder') }}">
                <i class="fa fa-files-o"></i>
                <span>@lang('backpack::crud.file_manager')</span>
            </a>
        </li>
        {{--<li><a href="{{ route('import::crud.import.index') }}"><i class="fa fa-files-o"></i> <span>Data import</span></a></li>--}}
    </ul>
</li>

<li class="treeview">
    <a href="#">
        <i class="fa fa-gear"></i>
        <span>@lang('application::module.nav.setting')</span>
        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
    </a>
    <ul class="treeview-menu">
        <li>
            <a href="{{ route('application::crud.locale.index') }}">
                <i class="fa fa-list"></i>
                <span>@lang('application::module.nav.locales')</span>
            </a>
        </li>
    </ul>
</li>

<li>
    <a href="{{ route('application::crud.log.index') }}">
        <i class="fa fa-list"></i>
        <span>@lang('application::module.nav.logs')</span>
    </a>
</li>
