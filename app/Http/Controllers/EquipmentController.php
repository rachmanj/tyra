<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EquipmentController extends Controller
{
    public function index()
    {
        return view('equipments.index');
    }

    // data
    public function data()
    {
        $url = env('URL_EQUIPMENTS');

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);
        $data = json_decode($response->getBody()->getContents(), true)['data'];

        return datatables()->of($data)
            ->addIndexColumn()
            ->toJson();
    }
}
