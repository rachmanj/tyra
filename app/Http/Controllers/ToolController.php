<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function getProjects()
    {
        $url = env('URL_PROJECTS');
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);
        $projects = json_decode($response->getBody()->getContents(), true)['data'];

        return $projects;
    }

    public function getEquipments($project = null)
    {
        $url = env('URL_EQUIPMENTS');

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);
        $equipments = json_decode($response->getBody()->getContents(), true)['data'];

        if ($project) {
            $equipments = array_filter($equipments, function ($item) use ($project) {
                return $item['project'] == $project;
            });
        }

        return $equipments;
    }

    public function getLastTransaction($tyre_id)
    {
        $last_transaction = Transaction::where('tyre_id', $tyre_id)->orderBy('id', 'desc')->first();

        if ($last_transaction) {
            return $last_transaction;
        } else {
            return null;
        }
    }
}
