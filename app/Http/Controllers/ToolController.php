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
        // $url = env('URL_ARKFLEET') . '/projects';
        // $response = $this->client->get($url);
        // $projects = json_decode($response->getBody()->getContents(), true)['data'];
        $projects = $this->defaultprojects();

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

    private function defaultprojects()
    {
        return [
            [
                'project_code' => "000H",
                'bowheer' => "Head Office",
                'location' => "Balikpapan"
            ],
            [
                'project_code' => "001H",
                'bowheer' => "Branch Office",
                'location' => "Jakarta"
            ],
            [
                'project_code' => "005P",
                'bowheer' => "Pratasaba Resort",
                'location' => "Maratua, Berau"
            ],
            [
                'project_code' => "017C",
                'bowheer' => "Kayan Putra Utama Coal, PT",
                'location' => "Malinau"
            ],
            [
                'project_code' => "021C",
                'bowheer' => "Solusi Bangun Indonesia, PT",
                'location' => "Narogong"
            ],
            [
                'project_code' => "022C",
                'bowheer' => "Graha Panca Karsa, PT",
                'location' => "Melak"
            ],
            [
                'project_code' => "023C",
                'bowheer' => "PT Tambang Raya Usaha Tama (TRUST) - TCM BEK",
                'location' => "Melak"
            ],
            [
                'project_code' => "APS",
                'bowheer' => "ARKA Project Support",
                'location' => "Kariangau"
            ]
        ];
    }
}
