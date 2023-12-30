<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Inertia\Inertia;


class CepController extends Controller
{
    public function show()
    {
        return Inertia::render(
            'Cep',
            [
                'title' => 'Integração  '
            ]
        );
    }

    public function home()
    {
        return Inertia::render(
            'Home',
            [
                'title' => 'Integração'
            ]
        );
    }

    public function findCep(Request $request)
    {
        $request->validate([
            'cep' => 'required|regex:/^[0-9]{5}-[0-9]{3}$/'
        ], [
            'cep.regex' => 'O CEP deve estar no formato XXXXX-XXX.',
        ]);

        $cep = $request->cep;

        // EndPoint
        $baseUrl = 'https://viacep.com.br/ws/';

        // Monta a URL completa para a consulta
        $url = $baseUrl . $cep . '/json/';

        $client = new Client();

        try {
            $response = $client->get($url);
            $data = json_decode($response->getBody(), true);

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro na consulta do CEP'], 500);
        }
    }

    public function getLatLng(Request $request)
    {
        try {
            $response = \GoogleMaps::load('geocoding')
                ->setParam(['address' => $request->logradouro])
                ->setParamByKey('components.administrative_area', $request->uf)
                ->get();


            $data = json_decode($response);
        } catch (\Throwable $th) {
            throw $th;
        };
        if ($response) {
            $geolocalization['lat'] = $data->results[0]->geometry->location->lat;
            $geolocalization['lng'] = $data->results[0]->geometry->location->lng;

            return response()->json($geolocalization);
        } else {
            return response()->json(['error' => 'CEP não encontrado'], 404);
        }
    }

    public function obterCoordenadasPorEndereco()
    {
        /*Fiz um teste com essa API, infelizmente ela é muito imprecisa,
        preferi usar a do google, embora ela peça o meu cartão de crédito*/

        $geocoder = new \OpenCage\Geocoder\Geocoder('eb1509886390456697693b559de31eb6');
        $query = '';
        $logradouro = 'Avenida Andrômeda';
        $bairro = 'Green Valley Alphaville';
        $localidade = 'Barueri';
        $uf = 'SP';
        $query .= $logradouro . ' ' . $bairro . ' ' . $localidade . ' ' . $uf;

        $result = $geocoder->geocode($query);

        return response()->json($result);
    }
}
