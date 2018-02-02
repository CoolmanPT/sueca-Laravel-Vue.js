@include('template.header')
<div id="login_app">
    <router-view></router-view>
</div>
@section('vueScripts')
    <script src="{{ asset("js/loginVue.js") }}"></script>
@stop
@include('template/footer');
