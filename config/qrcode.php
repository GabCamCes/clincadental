<?php


use SimpleSoftwareIO\QrCode\Generator;

return [
    /*
    |--------------------------------------------------------------------------
    | QRCode Renderer
    |--------------------------------------------------------------------------
    |
    | Aquí se define el "backend" que se usará para renderizar los QR.
    | Puede ser:
    |   - Generator::RENDERER_GD
    |   - Generator::RENDERER_IMAGICK
    | 
    | Te recomiendo dejarlo en GD para máxima compatibilidad.
    */
    'renderer' => 'gd',

    /*
    |--------------------------------------------------------------------------
    | Error Correction Level
    |--------------------------------------------------------------------------
    |
    | Los niveles posibles son: 'L', 'M', 'Q', 'H'
    | Mientras más alto, más robusto el QR ante daños, pero menos datos.
    */
    'error_correction' => 'M',

    /*
    |--------------------------------------------------------------------------
    | Default Size
    |--------------------------------------------------------------------------
    |
    | El tamaño predeterminado del QR en pixeles.
    */
    'size' => 250,

    /*
    |--------------------------------------------------------------------------
    | Default Margin
    |--------------------------------------------------------------------------
    |
    | Margen alrededor del QR.
    */
    'margin' => 1,
];
