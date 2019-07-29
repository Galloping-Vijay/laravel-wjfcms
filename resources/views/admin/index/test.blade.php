@extends('layouts.admin')
@section('control_name', $control_name)
@section('content')
    {{--填充内容--}}
@endsection

@section('footer')
@endsection

@section('script')
    <script>
        layui.config({
            base: "/static/layuiadmin/"
        }).extend({
            index: 'lib/index'
        }).use(['index', 'table'], function () {
            var $ = layui.$
                , form = layui.form;
        });
    </script>
@endsection