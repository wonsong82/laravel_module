<aside class="control-sidebar control-sidebar-light">

    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
        <li class="active"><a href="#control-sidebar-log-tab" data-toggle="tab"><i class="fa fa-edit"></i> Logs</a></li>
    </ul>


    <div class="tab-content">
        <div class="tab-pane active" id="control-sidebar-log-tab">

            @if(isset($logs) && $logs->count())

            <h3 class="control-sidebar-heading">Recent activities for <b>{{$logs->name}}</b>.</h3>

            @php
            $routeOption = ['type' => $logs->name];
            if($logs->id){
                $routeOption['id'] = $logs->id;
            }
            @endphp
            <a href="{{route('application::crud.log.index', $routeOption)}}">View All</a>


            <ul class="control-sidebar-menu">
                @foreach($logs as $log)
                <li>
                    <a href="javascript:void(0)">
                        <b class="menu-icon label-{{$log->type->type}}">{{$log->type->display_name[0]}}</b>

                        <div class="menu-info">
                            <h4 class="control-sidebar-subheading">
                                {{$log->title}}
                            </h4>
                            <p class="text">{{$log->text}}</p>
                            <p>{{$log->created_at->format('g:ia m/d/y ')}} by {{$log->user->name ?? 'Unknown'}}</p>
                            @if($log->detail)
                                <button class="detail-btn btn btn-default btn-xs popup-md" onclick="openSinglePopup(this)" href="{{route('application::crud.log.show', ['log' => $log->id])}}">Changes</button>
                            @endif
                        </div>
                    </a>
                </li>
                @endforeach

            </ul>

            @else
                <h3 class="control-sidebar-heading">No logs found.</h3>
            @endif

        </div>
    </div>
</aside>

<div class="control-sidebar-bg"></div>