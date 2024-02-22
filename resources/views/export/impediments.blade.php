<html>
    <link rel="stylesheet" type="text/css" href="{{ Asset::get('vendor/legacy/Educacenso/Assets/Stylesheets/educacensoPdf.css?v=5') }}" />
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans">
    <title>Análise de exportação</title>
</html>
<body>
    <p class="date-info">Data da geração:
        {{ now()->format('d/m/Y, H:i:s') }}
    </p>
    <div id="content">
        <h1 class="title">
            Análise de exportação
        </h1>
    </div>
    <div id="editor"></div>
    <h2 class="subtitle">
        Análise exportação - Registros 89, 90 e 91<br/>
        <span style="font-size: 12px">
            <strong>{{ count($errors->all()) }}</strong> impeditivos encontrados
        </span>
    </h2>
    <ul>
        @foreach ($errors->all() as $error)
            @if(json_validate($error))
                @php
                    $error = json_decode($error);
                @endphp
                <li @if(property_exists($error, 'impediment') && $error->impediment == false) style="color: #449023; font-weight: bold"  @endif>
                    {{ $error->message ?? 'ERRO INDEFINIDO' }}
                </li>

                @if(property_exists($error, 'breadcrumb') && property_exists($error, 'url'))
                    <p>
                        <a class="educacenso-link-path" href="{{ $error->url }}" target="_new">
                            {{ $error->breadcrumb }}
                        </a>
                    </p>
                @endif
            @else
                <li>
                    {{ $error ?? 'ERRO INDEFINIDO' }}
                </li>
            @endif
        @endforeach
    </ul>
</body>

