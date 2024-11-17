<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ToolController extends Controller
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getProjects()
    {
        $url = env('URL_ARKFLEET') . '/projects';
        $response = $this->client->get($url);
        $projects = json_decode($response->getBody()->getContents(), true)['data'];

        return $projects;
    }

    public function getEquipments($project = null)
    {
        $url = env('URL_EQUIPMENTS');
        $response = $this->client->get($url);
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
        return Transaction::where('tyre_id', $tyre_id)->latest()->first();
    }

    public function getFirstTransaction($tyre_id)
    {
        return Transaction::where('tyre_id', $tyre_id)->oldest()->first();
    }

    public function getHMTyre($tyre_id)
    {
        $first_transaction = $this->getFirstTransaction($tyre_id);
        $last_transaction = $this->getLastTransaction($tyre_id);

        if ($first_transaction && $last_transaction) {
            return $last_transaction->hm - $first_transaction->hm;
        }

        return null;
    }

    public function getUserRoles()
    {
        return User::find(auth()->user()->id)->getRoleNames()->toArray();
    }
}
