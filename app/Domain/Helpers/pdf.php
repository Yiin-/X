<?php

use mikehaertl\wkhtmlto\Pdf;
use GuzzleHttp\Client;

if (!function_exists('generate_pdf')) {
    function generate_pdf($html, $path, $filename) {
        return generate_pdf_using_chrome($html, $path, $filename);
    }

    function generate_pdf_using_chrome($html, $path, $filename)
    {
        $httpClient = new Client;

        try {
            $response = $httpClient->request('POST', config('node-server.url.html_to_pdf'), [
                'json' => [
                    'save_to_path' => $path . DIRECTORY_SEPARATOR . $filename,
                    'html' => (string)$html
                ]
            ]);
            return [
                true,
                $response
            ];
        }
        catch (\Exception $e) {
            return [
                false,
                $e
            ];
        }
    }

    function generate_pdf_using_wkhtmltopdf($html, $path, $filename)
    {
        $pdf = new Pdf([
            'commandOptions' => [
                'useExec' => true
            ],
            'no-outline',         // Make Chrome not complain
            'margin-top'    => 0,
            'margin-right'  => 0,
            'margin-bottom' => 0,
            'margin-left'   => 0,

            // Default page options
            'disable-smart-shrinking'
        ]);

        $pdf->addPage((string)$html);

        if (!file_exists($path)) {
            mkdir($path, 0644, true);
        }

        return [
            $pdf->saveAs($path . DIRECTORY_SEPARATOR . $filename),
            $pdf
        ];
    }
}