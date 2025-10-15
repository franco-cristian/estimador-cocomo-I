<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Detalle de Estimacion COCOMO I</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; }
        .container { width: 100%; }
        h1 { text-align: center; border-bottom: 1px solid #ccc; padding-bottom: 10px; }
        h2 { border-bottom: 1px solid #eee; padding-bottom: 5px; font-size: 14px; }
        .header { margin-bottom: 20px; }
        .project-name { font-size: 18px; font-weight: bold; }
        .grid { display: block; width: 100%; }
        .col-half { width: 48%; display: inline-block; vertical-align: top; }
        .space { width: 4%; display: inline-block; }
        p { margin: 5px 0; line-height: 1.4; }
        strong { font-weight: bold; }
        .mono { font-family: 'Courier New', Courier, monospace; }
        .params { font-size: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Estimacion de Proyecto COCOMO I</h1>
        <div class="header">
            <p><strong class="project-name">{{ $estimation->project_name }}</strong></p>
            <p><strong>Fecha de Generacion:</strong> {{ date('d/m/Y H:i') }}</p>
        </div>
        <div class="grid">
            <div class="col-half">
                <h2>Resultados de la Estimacion</h2>
                <p><strong>Esfuerzo (PM):</strong> {{ number_format($estimation->pm, 2) }} persona-meses</p>
                <p><strong>Duracion:</strong> {{ number_format($estimation->duracion, 2) }} meses</p>
                <p><strong>Personal Promedio:</strong> {{ number_format($estimation->personal, 2) }} personas</p>
                <p><strong>Costo Total Estimado:</strong> ${{ number_format($estimation->costo_total, 2) }}</p>
                <p><strong>EAF Utilizado:</strong> {{ number_format($estimation->eaf, 4) }}</p>
                <div style="margin-top: 20px;">
                    <h2>Parametros del Modelo</h2>
                    @php
                        $constants = config('cocomo.constants')[$estimation->modo];
                        $modeTranslations = ['organic' => 'Organico', 'semi-detached' => 'Semi-acoplado', 'embedded' => 'Empotrado'];
                    @endphp
                    <p><strong>Modo:</strong> {{ $modeTranslations[$estimation->modo] ?? 'Desconocido' }}</p>
                    <p class="mono params">(a={{ $constants['a'] }}, b={{ $constants['b'] }}, c={{ $constants['c'] }}, d={{ $constants['d'] }})</p>
                </div>
            </div>
            <div class="space"></div>
            <div class="col-half">
                <h2>Factores de Costo Aplicados</h2>
                @foreach ($estimation->factores_costo as $key => $value)
                    <p><strong class="mono">{{ $key }}:</strong> {{ $value }}</p>
                @endforeach
            </div>
        </div>
    </div>
</body>
</html>