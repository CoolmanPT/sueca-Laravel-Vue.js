@section('styles')
<link rel="stylesheet" href="css/default.css">
        @stop
@include('template.header')

<div id="admin_app">
    <router-view></router-view>
    </div>
        @section('vueScripts')
    <script src="{{ asset("js/adminVue.js") }}"></script>
@stop
@include('template/footer');