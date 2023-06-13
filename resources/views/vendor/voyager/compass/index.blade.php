@extends('voyager::master')

@section('css')
    @include('voyager::compass.includes.styles')
@stop

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-compass"></i>
        <p> {{ __('voyager::generic.compass') }}</p>
        <span class="page-description">{{ __('voyager::compass.welcome') }}</span>
    </h1>
@stop

@section('content')
    <div id="gradient_bg"></div>
    <div class="container-fluid">
        @include('voyager::alerts')
    </div>
    <div class="page-content compass container-fluid">
        <ul class="nav nav-tabs">
          <li @if(empty($active_tab) || (isset($active_tab) && $active_tab == 'resources')){!! 'class="active"' !!}@endif><a data-toggle="tab" href="#resources"><i class="voyager-book"></i> {{ __('voyager::compass.resources.title') }}</a></li>
          <li @if($active_tab == 'commands'){!! 'class="active"' !!}@endif><a data-toggle="tab" href="#commands"><i class="voyager-terminal"></i> {{ __('voyager::compass.commands.title') }}</a></li>
          <li @if($active_tab == 'logs'){!! 'class="active"' !!}@endif><a data-toggle="tab" href="#logs"><i class="voyager-logbook"></i> {{ __('voyager::compass.logs.title') }}</a></li>
        </ul>

        <div class="tab-content">
            <div id="resources" class="tab-pane fade in @if(empty($active_tab) || (isset($active_tab) && $active_tab == 'resources')){!! 'active' !!}@endif">
                <h3><i class="voyager-book"></i> {{ __('voyager::compass.resources.title') }} <small>{{ __('voyager::compass.resources.text') }}</small></h3>
                <div class="">
                    <div class="" aria-expanded="true" aria-controls="links">
                        <h4>{{ __('voyager::compass.links.title') }}</h4>
                    </div>
                    <div class="" id="links">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="https://voyager-docs.devdojo.com/" target="_blank" class="voyager-link" style="background-image:url('{{ voyager_asset('images/compass/documentation.jpg') }}')">
                                    <span class="resource_label"><i class="voyager-documentation"></i> <span class="copy">{{ __('voyager::compass.links.documentation') }}</span></span>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="https://voyager.devdojo.com/" target="_blank" class="voyager-link" style="background-image:url('{{ voyager_asset('images/compass/voyager-home.jpg') }}')">
                                    <span class="resource_label"><i class="voyager-browser"></i> <span class="copy">{{ __('voyager::compass.links.voyager_homepage') }}</span></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="" aria-expanded="true" aria-controls="fonts">
                        <h4>{{ __('voyager::compass.fonts.title') }}</h4>
                    </div>
                    <div class="" id="fonts">
                        @include('voyager::compass.includes.fonts')
                    </div>
                </div>
            </div>
            <div id="commands" class="tab-pane fade in @if($active_tab == 'commands'){!! 'active' !!}@endif">
                <h3><i class="voyager-terminal"></i> {{ __('voyager::compass.commands.title') }} <small>{{ __('voyager::compass.commands.text') }}</small></h3>
                <div id="command_lists">
                    @include('voyager::compass.includes.commands')
                </div>
            </div>
            <div id="logs" class="tab-pane fade in @if($active_tab == 'logs'){!! 'active' !!}@endif">
                <div class="row">

                    @include('voyager::compass.includes.logs')

                </div>
            </div>
        </div>

    </div>
@stop
@section('javascript')
   
@stop
