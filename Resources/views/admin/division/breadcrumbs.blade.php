<div class="breadcrumbs">
    <ul>
        <li>
            <a href="{{ route('admin.index') }}"><i class="fa fa-home"></i></a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{ route('admin.team.division.index') }}" class="text-black">@lang('team::admin.team_division.index')</a>
        </li>
        @if(url()->current() === route('admin.team.division.create'))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.team.division.create') }}" class="text-purple">@lang('team::admin.team_division.create')</a>
            </li>
        @elseif(Request::segment(3) !== null && url()->current() === route('admin.team.division.edit', ['id' => Request::segment(3)]))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.team.division.edit', ['id' => Request::segment(3)]) }}" class="text-purple">@lang('team::admin.team_division.edit')</a>
            </li>
        @endif
    </ul>
</div>
