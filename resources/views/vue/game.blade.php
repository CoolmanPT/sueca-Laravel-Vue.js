@include('template.header')

<div id="game_app">
    <router-view></router-view>
    </div>
        @section('vueScripts')
    <script src="{{ asset("js/gameVue.js") }}"></script>
@stop
@include('template/footer')