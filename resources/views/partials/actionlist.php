@php
$permission = routeResource('pluralUcFirst');
if(isset($priority) && !empty($priority)){
$permission_resource = $priority;
$target = "_blank";
}
if(!isset($priority) || empty($priority)){
$permission_resource = routeResource('singular');
$target = "";
}
$edit = $permission.'Edit';
$view = $permission.'View';
$delete = $permission.'Delete';
$DirectpermissionCreate = $permission.'DirectpermissionCreate';
@endphp
@canany([$edit , $view , $delete , $DirectpermissionCreate ])
<td>
    <div class="dropdown">
        <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
            <i data-feather="more-vertical"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end">
            @can($permission . 'Edit')
            <a class="dropdown-item" target="{{$target}}" href="{{ route('admin.'. $permission_resource .'.edit', $value->id) }}">
                <i data-feather="edit-2" class="me-50"></i>
                <span>Edit</span>
            </a>
            @endcan
            @can($permission . 'View')
            <a class="dropdown-item" target="{{$target}}" href="{{ route('admin.'. $permission_resource .'.show', $value->id) }}">
                <i data-feather="trash" class="me-50"></i>
                <span>Delete</span>
            </a>
            @endcan
        </div>
    </div>
</td>
@endcanany