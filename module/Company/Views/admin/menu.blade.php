<li class="header">@lang('company::module.nav.header.company')</li>

<li>
    <a href="{{ route('company::crud.company.index') }}">
        <i class="fa fa-building"></i>
        <span>@lang('company::module.nav.company')</span>
    </a>
</li>

<li>
    <a href="{{ route('company::crud.user.index') }}">
        <i class="fa fa-list"></i>
        <span>@lang('company::module.nav.company_users')</span>
    </a>
</li>

<li class="treeview">
    <a href="#">
        <i class="fa fa-gear"></i>
        <span>@lang('company::module.nav.company_settings')</span>
        <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
    </a>
    <ul class="treeview-menu">
        <li>
            <a href="{{ route('company::crud.role.index') }}">
                <i class="fa fa-list"></i>
                <span>@lang('company::module.nav.company_roles')</span>
            </a>
        </li>
        <li>
            <a href="{{ route('company::crud.currency.index') }}">
                <i class="fa fa-list"></i>
                <span>@lang('company::module.nav.currencies')</span>
            </a>
        </li>
        <li>
            <a href="{{ route('company::crud.payterm.index') }}">
                <i class="fa fa-list"></i>
                <span>@lang('company::module.nav.payterms')</span>
            </a>
        </li>
        <li>
            <a href="{{ route('company::crud.unit.index') }}">
                <i class="fa fa-list"></i>
                <span>@lang('company::module.nav.units')</span>
            </a>
        </li>
        <li>
            <a href="{{ route('company::crud.margin-rate.index') }}">
                <i class="fa fa-list"></i>
                <span>@lang('company::module.nav.margin_rates')</span>
            </a>
        </li>
    </ul>
</li>