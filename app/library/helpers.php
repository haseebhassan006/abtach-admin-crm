<?php

use App\Mail\DocumentLinkEmail;
use App\Models\BrandHasManager;
use App\Models\BrandHasProcessor;
use App\Models\BrandHasTeamLead;
use App\Models\Calling;
use App\Models\CallingFollowup;
use App\Models\Country;
use App\Models\DocumentType;
use App\Models\Followup;
use App\Models\Lead;
use App\Models\LeadFollowUp;
use App\Models\LeadHasDocument;
use App\Models\LeadHasLink;
use App\Models\LeadStatus;
use App\Models\Processor;
use App\Models\ProcessorHasDealType;
use App\Models\Role;
use App\Models\Team;
use App\Models\TerminalHasDealType;
use App\Models\User;
use App\Models\UserHasBrand;
use App\Models\UserHasSalesManager;
use App\Models\UserHasTeamLead;
use App\Models\UserHasUnit;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Process\Process;

function roles()
{
    $roles = Role::all();
    return $roles;
}
function getCurrentRoute()
{
    $route = Route::currentRouteName();
    return $route;
}

function getPagination()
{
    return 10;
}

function paginate($items, $perPage, $pagination)
{
    $page = null;
    $options = [];
    $perPage = $pagination;
    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
    $items = $items instanceof Collection ? $items : Collection::make($items);
    return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
}

function checkpermission()
{
    $permission = routeResource('pluralUcFirst');

    $permission_resource = routeResource('singular');
    $data['edit'] = $permission . 'Edit';
    $data['view'] = $permission . 'View';
    $data['delete'] = $permission . 'Delete';
    $data['DirectpermissionCreate'] = $permission . 'DirectpermissionCreate';
    return $data;
}

function routeResource($term = null, $name = "")
{
    if ($name == null) {
        $name = Route::currentRouteName();
    }
    $name = explode('.', $name);
    array_shift($name);
    array_pop($name);


    // get permission name
    if ($term == 'pluralUcFirst') {
        $name = array_map('ucwords', $name);
        $name[0] = Str::singular($name[0]);
        $name = implode('', $name);
        $name = Str::plural($name);
        $name = ucfirst($name);
        return $name;
    }
    // get resource name
    if ($term == 'singular' || $term == null) {
        $name = array_map('ucwords', $name);
        $name = implode('', $name);
        $name = Str::plural($name);
        $name = Str::lower($name);
        return $name;
    }
    return $name;
}


