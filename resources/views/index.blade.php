@extends('layouts.app')

@section('title', 'Page Title')

@section('sidebar')
    @parent

    <p>Это дополнение к основной боковой панели.</p>
@endsection

@section('content')
    <p>Это содержимое тела страницы.</p>
    <example-component>
    	<div>GHghjkjkksadjkas</div>
    </example-component>
@endsection