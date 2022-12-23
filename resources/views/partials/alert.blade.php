@if(Session::get('success'))

<input type="hidden" value="{{Session::get('success')}}" id="success_msg">

@endif

@if(Session::get('error'))

<input type="hidden" value="{{Session::get('error')}}" id="error_msg">

@endif
@if(Session::get('delete'))

<input type="hidden" value="{{Session::get('error')}}" id="delete_msg">
@endif