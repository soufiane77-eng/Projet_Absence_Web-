<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Seance;
use App\Models\Absence;
use App\Models\Module;
use App\Models\Classe;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        $moduleIds = $user->modules()->pluck('modules.id');
        $classIds = $user->modules()->withPivot('class_id')->get()->pluck('pivot.class_id')->unique();

        $stats = [
            'total_modules' => $moduleIds->count(),
            'total_classes' => $classIds->count(),
            'upcoming_seances' => Seance::where('teacher_id', $user->id)
                ->where('date', '>=', now()->toDateString())
                ->where('status', 'scheduled')
                ->count(),
            'total_seances' => Seance::where('teacher_id', $user->id)->count(),
        ];

        $recentSeances = Seance::with('module', 'classe')
            ->where('teacher_id', $user->id)
            ->latest('date')
            ->take(5)
            ->get();

        $todayAbsences = Absence::whereHas('seance', function ($q) use ($user) {
            $q->where('teacher_id', $user->id)
              ->whereDate('date', today());
        })->count();

        return view('dashboard.teacher', compact('stats', 'recentSeances', 'todayAbsences'));
    }
}
