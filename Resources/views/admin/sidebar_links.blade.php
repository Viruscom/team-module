@php use App\Helpers\WebsiteHelper;use Illuminate\Http\Request; @endphp
<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingSeven" data-toggle="collapse" data-parent="#accordionMenu" href="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
        <h4 class="panel-title">
            <a>
                <i class="fas fa-users"></i> <span>@lang('team::admin.team.index')</span>
            </a>
        </h4>
    </div>
    <div id="collapseSeven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSeven">
        <div class="panel-body">
            <ul class="nav">
                <li>
                    <a href="{{ route('admin.team.division.index') }}" class="{{ WebsiteHelper::isActiveRoute('admin.team.division.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i> <span>@lang('team::admin.team_division.index')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.team.index') }}" class="{{ WebsiteHelper::isActiveRoute('admin.team.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i> <span>@lang('team::admin.team.index')</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
