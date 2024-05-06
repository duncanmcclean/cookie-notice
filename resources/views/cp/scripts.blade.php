@extends('statamic::layout')
@section('title', 'Manage Scripts')

@section('content')
    <publish-form
        title="Manage Scripts"
        action="{{ cp_route('cookie-notice.scripts.update') }}"
        :blueprint='@json($blueprint)'
        :meta='@json($meta)'
        :values='@json($values)'
    ></publish-form>
@stop
