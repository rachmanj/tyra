<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService
    ) {}

    public function index()
    {
        $projects = $this->getProjectsForUser();
        $data = $this->dashboardService->getDashboardData($projects);

        return view('dashboard.index', array_merge($data, [
            'projects' => $projects,
        ]));
    }

    private function getProjectsForUser(): array
    {
        $roles = auth()->user()->getRoleNames()->toArray();

        if (array_intersect($roles, ['superadmin', 'admin'])) {
            return $this->dashboardService->getDashboardProjects();
        }

        $userProject = auth()->user()->project;
        if (!$userProject) {
            return [];
        }

        return [$userProject];
    }

    public function test()
    {
        return redirect()->route('dashboard.index');
    }
}
