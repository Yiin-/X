<?php

namespace App\Interfaces\Http\Controllers\Features;

use SoapClient;
use Exception;
use Illuminate\Http\Request;
use App\Interfaces\Http\Controllers\AbstractController;

class VatCheckController extends AbstractController
{
    public function index(Request $request)
    {
        return auth()->user()
                ->vatChecks()
                ->orderBy('id', 'desc')
                ->limit($request->get('limit', 10))
                ->get();
    }

    public function check(Request $request)
    {
        $vat_CC = $request->get('cc');
        $vat_VN = $request->get('vn');

        try {
            $opts = [
                'http' => [
                    'user_agent' => 'PHPSoapClient'
                ]
            ];
            $context = stream_context_create($opts);

            $client = new SoapClient('http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl',
                [
                    'stream_context' => $context,
                    'cache_wsdl' => WSDL_CACHE_NONE
                ]
            );

            $result = $client->checkVat([
                'countryCode' => $vat_CC,
                'vatNumber' => $vat_VN
            ]);
        } catch (Exception $e) {
            $data = auth()->user()->vatChecks()->create([
                'status' => \App\Domain\Constants\VatInfo\Statuses::INVALID,
                'country_code' => $vat_CC,
                'number' => $vat_VN,
                'message' => $e->getMessage()
            ]);

            return response()->json($data, 500);
        }
        if ($result->name !== '' && $result->name !== '---' && $result->address !== '') {
            $data = auth()->user()->vatChecks()->create([
                'status' => \App\Domain\Constants\VatInfo\Statuses::VALID,
                'name' => $result->name,
                'address' => trim(str_replace(' ,', ', ', str_replace(' .', '. ', $result->address))),
                'country_code' => $vat_CC,
                'number' => $vat_VN
            ]);
        } else {
            $data = auth()->user()->vatChecks()->create([
                'status' => \App\Domain\Constants\VatInfo\Statuses::INVALID,
                'country_code' => $vat_CC,
                'number' => $vat_VN
            ]);
        }

        return response()->json($data);
    }
}