@extends('errors::minimal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Oops! Forbidden'))

<script src="{{asset('js/sleeping.js')}}"></script>