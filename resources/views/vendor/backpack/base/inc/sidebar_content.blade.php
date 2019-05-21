@if(module_loaded('Company'))


    {{-- SUPER ADMIN --}}
    @if(!auth()->user()->company)
        <li class="header">@lang('company::module.nav.header.company')</li>

        <li>
            <a href="{{ route('company::crud.company.index') }}">
                <i class="fa fa-list"></i>
                <span>@lang('company::module.nav.companies')</span>
            </a>
        </li>

        <li>
            <a href="{{ route('company::crud.user.index') }}">
                <i class="fa fa-list"></i>
                <span>@lang('company::module.nav.company_users')</span>
            </a>
        </li>



    {{-- USER WITH COMPANY --}}
    @else

        @if(module_loaded('Company'))
            @include('company::admin.menu')
        @endif

        @if(module_loaded('Customer'))
            @include('customer::admin.menu')
        @endif

        @if(module_loaded('Vendor'))
            @include('vendor::admin.menu')
        @endif

        @if(module_loaded('Item'))
            @include('item::admin.menu')
        @endif

        @if(module_loaded('Quotation'))
            @include('quotation::admin.menu')
        @endif

        @if(module_loaded('Sale'))
            @include('sale::admin.menu')
        @endif

        @if(module_loaded('Purchase'))
            @include('purchase::admin.menu')
        @endif


    @endif
@endif



@if(module_loaded('Application'))
    @include('application::crud.menu')
@endif





