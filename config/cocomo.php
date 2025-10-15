<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Constantes del Modelo COCOMO I
    |--------------------------------------------------------------------------
    |
    | Estas son las constantes (a, b, c, d) para los tres modos del
    | modelo COCOMO I, como las definió Barry Boehm.
    |
    */
    'constants' => [
        'organic' => ['a' => 2.4, 'b' => 1.05, 'c' => 2.5, 'd' => 0.38],
        'semi-detached' => ['a' => 3.0, 'b' => 1.12, 'c' => 2.5, 'd' => 0.35],
        'embedded' => ['a' => 3.6, 'b' => 1.20, 'c' => 2.5, 'd' => 0.32],
    ],

    /*
    |--------------------------------------------------------------------------
    | Factores de Costo (Cost Drivers) de COCOMO I
    |--------------------------------------------------------------------------
    |
    | Estos son los 15 factores de costo (multiplicadores de esfuerzo) para el
    | modelo Intermedio de COCOMO. Los valores provienen del libro original
    | de Boehm, "Software Engineering Economics". Un valor `null` indica
    | que una valoración no es aplicable para ese factor específico.
    |
    | Acrónimos de las Valoraciones:
    | VL = Very Low (Muy Bajo), L = Low (Bajo), N = Nominal, H = High (Alto),
    | VH = Very High (Muy Alto), XH = Extra High (Extra Alto)
    |
    */
    'drivers' => [
        // Atributos del Producto
        'RELY' => [
            'name' => 'Fiabilidad requerida del software',
            'ratings' => [ 'VL' => 0.75, 'L' => 0.88, 'N' => 1.00, 'H' => 1.15, 'VH' => 1.40, 'XH' => null ],
        ],
        'DATA' => [
            'name' => 'Tamaño de la base de datos',
            'ratings' => [ 'VL' => null, 'L' => 0.94, 'N' => 1.00, 'H' => 1.08, 'VH' => 1.16, 'XH' => null ],
        ],
        'CPLX' => [
            'name' => 'Complejidad del producto',
            'ratings' => [ 'VL' => 0.70, 'L' => 0.85, 'N' => 1.00, 'H' => 1.15, 'VH' => 1.30, 'XH' => 1.65 ],
        ],

        // Atributos del Hardware
        'TIME' => [
            'name' => 'Restricción del tiempo de ejecución',
            'ratings' => [ 'VL' => null, 'L' => null, 'N' => 1.00, 'H' => 1.11, 'VH' => 1.30, 'XH' => 1.66 ],
        ],
        'STOR' => [
            'name' => 'Restricción del almacenamiento principal',
            'ratings' => [ 'VL' => null, 'L' => null, 'N' => 1.00, 'H' => 1.06, 'VH' => 1.21, 'XH' => 1.56 ],
        ],
        'VIRT' => [
            'name' => 'Volatilidad de la máquina virtual',
            'ratings' => [ 'VL' => 0.87, 'L' => 0.94, 'N' => 1.00, 'H' => 1.10, 'VH' => 1.15, 'XH' => null ],
        ],
        'TURN' => [
            'name' => 'Tiempo de respuesta del ordenador',
            'ratings' => [ 'VL' => null, 'L' => 0.87, 'N' => 1.00, 'H' => 1.07, 'VH' => 1.15, 'XH' => null ],
        ],

        // Atributos del Personal
        'ACAP' => [
            'name' => 'Capacidad del analista',
            'ratings' => [ 'VL' => 1.46, 'L' => 1.19, 'N' => 1.00, 'H' => 0.86, 'VH' => 0.71, 'XH' => null ],
        ],
        'AEXP' => [
            'name' => 'Experiencia en la aplicación',
            'ratings' => [ 'VL' => 1.29, 'L' => 1.13, 'N' => 1.00, 'H' => 0.91, 'VH' => 0.82, 'XH' => null ],
        ],
        'PCAP' => [
            'name' => 'Capacidad del programador',
            'ratings' => [ 'VL' => 1.42, 'L' => 1.17, 'N' => 1.00, 'H' => 0.86, 'VH' => 0.70, 'XH' => null ],
        ],
        'VEXP' => [
            'name' => 'Experiencia plataforma / entorno', 
            'ratings' => [ 'VL' => 1.19, 'L' => 1.10, 'N' => 1.00, 'H' => 0.90, 'VH' => 0.85, 'XH' => null ]
        ],
        'LTEX' => [
            'name' => 'Experiencia en lenguaje / herramientas', 
            'ratings' => [ 'VL' => 1.14, 'L' => 1.07, 'N' => 1.00, 'H' => 0.95, 'VH' => 0.84, 'XH' => null ]
        ],

        // Atributos del Proyecto
        'MODP' => [
            'name' => 'Uso de prácticas de programación modernas',
            'ratings' => [ 'VL' => 1.24, 'L' => 1.10, 'N' => 1.00, 'H' => 0.91, 'VH' => 0.82, 'XH' => null ],
        ],
        'TOOL' => [
            'name' => 'Uso de herramientas de software',
            'ratings' => [ 'VL' => 1.24, 'L' => 1.10, 'N' => 1.00, 'H' => 0.91, 'VH' => 0.83, 'XH' => null ],
        ],
        'SCED' => [
            'name' => 'Cumplimiento del cronograma de desarrollo',
            'ratings' => [ 'VL' => 1.23, 'L' => 1.08, 'N' => 1.00, 'H' => 1.04, 'VH' => 1.10, 'XH' => null ],
        ],
    ],
];