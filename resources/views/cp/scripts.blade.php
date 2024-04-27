@extends('statamic::layout')

@section('content')
    <publish-form
        title="Scripts"
        action="{{ cp_route('cookie-notice.scripts.update') }}"
        :blueprint='@json($blueprint)'
        :meta='@json($meta)'
        :values='@json($values)'
    ></publish-form>
@stop
