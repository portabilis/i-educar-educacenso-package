@extends('layout.default')

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ Asset::get('css/ieducar.css') }}" />
@endpush

@section('content')
    <form id="formcadastro" target="_blank" action="{{ route('educacenso-export-situation') }}" method="post">
        <table class="tablecadastro" width="100%" border="0" cellpadding="2" cellspacing="0">
            <tbody>
            <tr>
                <td class="formdktd" colspan="2" height="24"><b>Exportações</b></td>
            </tr>
            <tr>
                <td class="formlttd" valign="top">
                    <span class="form">
                        Ano
                    </span>
                    <span class="campo_obrigatorio">*</span>
                </td>
                <td class="formlttd" valign="top">
                    <span class="form">
                        <select name="ano" id="ano" required class="formcampo">
                            @foreach($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </span>
                </td>
            </tr>
            <tr>
                <td class="formlttd" valign="top">
                    <span class="form">
                        Instituição
                    </span>
                    <span class="campo_obrigatorio">*</span>
                </td>
                <td class="formlttd" valign="top">
                    <span class="form">
                        @include('form.select-institution')
                    </span>
                </td>
            </tr>
            <tr>
                <td class="formmdtd" valign="top">
                    <span class="form">
                        Escola
                    </span>
                    <span class="campo_obrigatorio">*</span>
                </td>
                <td class="formmdtd" valign="top">
                    <span class="form">
                        @include('form.select-school')
                    </span>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="separator"></div>

        <div style="text-align: center; margin-bottom: 10px">
            <button id="export-button" class="btn-green" type="submit">Exportar</button>
        </div>

        <style>
            #export-button[disabled] {
                opacity: 0.7;
            }

            .flex {
                display: flex;
            }

            .gap-4 {
                gap: 1rem;
            }

            .justify-between {
                justify-content: space-between;
            }

            .items-center {
                align-items: center;
            }

            .border-l-8 {
                border-left-width: 8px !important;
            }

            .border-solid {
                border-style: solid !important;
            }

            .py-2 {
                padding-top: 0.5rem;
                padding-bottom: 0.5rem;
            }

            .px-4 {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .border-warning {
                border: 0;
                border-left-color: #dfc32e;
            }

            .bg-warning {
                background-color: #fff8d6;
            }

            .flex-grow {
                flex-grow: 1;
            }

            .x-alert-icon {
                color: #dfc32e;
            }

            .x-alert-icon::before {
                font-size: 1.5rem;
            }

            .text-warning {
                color: #958f73;
            }
        </style>
        <link type='text/css' rel='stylesheet' href='{{ Asset::get("/vendor/legacy/Portabilis/Assets/Plugins/Chosen/chosen.css") }}'>
        <script type='text/javascript' src='{{ Asset::get('/vendor/legacy/Portabilis/Assets/Plugins/Chosen/chosen.jquery.min.js') }}'></script>
        <script type="text/javascript" src="{{ Asset::get("/vendor/legacy/Portabilis/Assets/Javascripts/ClientApi.js") }}"></script>
        <script type="text/javascript" src="{{ Asset::get("/vendor/legacy/DynamicInput/Assets/Javascripts/DynamicInput.js") }}"></script>
        <script type="text/javascript" src="{{ Asset::get("/vendor/legacy/DynamicInput/Assets/Javascripts/Escola.js") }}"></script>
    </form>
@endsection
