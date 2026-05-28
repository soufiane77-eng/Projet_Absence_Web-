<?php

namespace Database\Seeders;

use App\Models\Filiere;
use App\Models\Classe;
use App\Models\Semester;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicStructureSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create 8 filières
        $filieres = [
            ['name' => 'Cycle Préparatoire',                            'code' => 'CP',   'description' => 'Filière Cycle Préparatoire'],
            ['name' => 'Génie Informatique',                            'code' => 'GI',   'description' => 'Filière Génie Informatique'],
            ['name' => 'Génie Civil',                                   'code' => 'GC',   'description' => 'Filière Génie Civil'],
            ['name' => 'Génie de l\'Eau et de l\'Environnement',       'code' => 'GEE',  'description' => 'Filière Génie de l\'Eau et de l\'Environnement'],
            ['name' => 'Génie Énergies Renouvelables',                  'code' => 'GEER', 'description' => 'Filière Génie Énergies Renouvelables'],
            ['name' => 'Génie Mécanique',                               'code' => 'GM',   'description' => 'Filière Génie Mécanique'],
            ['name' => 'Ingénierie des Données',                        'code' => 'ID',   'description' => 'Filière Ingénierie des Données'],
            ['name' => 'Transformation Digitale et Intelligence Artificielle', 'code' => 'TDIA', 'description' => 'Filière Transformation Digitale et IA'],
        ];

        $filiereNames = [];
        foreach ($filieres as $f) {
            $fil = Filiere::firstOrCreate(['code' => $f['code']], $f);
            $filiereNames[$fil->id] = $fil->code;
        }

        // 2. Levels per filière
        $niveauxParFiliere = [
            'CP'   => ['CP1', 'CP2'],
            'GI'   => ['GI1', 'GI2', 'GI3'],
            'GC'   => ['GC1', 'GC2', 'GC3'],
            'GEE'  => ['GEE1', 'GEE2', 'GEE3'],
            'GEER' => ['GEER1', 'GEER2', 'GEER3'],
            'GM'   => ['GM1', 'GM2', 'GM3'],
            'ID'   => ['ID1', 'ID2', 'ID3'],
            'TDIA' => ['TDIA1', 'TDIA2', 'TDIA3'],
        ];

        $classeIds = [];
        foreach ($filieres as $f) {
            $filiere = Filiere::where('code', $f['code'])->first();
            $niveaux = $niveauxParFiliere[$f['code']];
            foreach ($niveaux as $niveau) {
                $classe = Classe::firstOrCreate(
                    ['code' => $niveau],
                    [
                        'name' => $niveau,
                        'filiere_id' => $filiere->id,
                        'level' => $niveau,
                    ]
                );
                $classeIds[$niveau] = $classe->id;
            }
        }

        // 3. Create S1 through S10 semesters
        // Odd semesters (S1,S3,S5,S7,S9) share the same dates
        // Even semesters (S2,S4,S6,S8,S10) share the same dates
        $semesterDates = [
            1 => ['start' => '2025-09-15', 'end' => '2026-02-01'],
            2 => ['start' => '2026-02-20', 'end' => '2026-06-20'],
            3 => ['start' => '2025-09-15', 'end' => '2026-02-01'],
            4 => ['start' => '2026-02-20', 'end' => '2026-06-20'],
            5 => ['start' => '2025-09-15', 'end' => '2026-02-01'],
            6 => ['start' => '2026-02-20', 'end' => '2026-06-20'],
            7 => ['start' => '2025-09-15', 'end' => '2026-02-01'],
            8 => ['start' => '2026-02-20', 'end' => '2026-06-20'],
            9 => ['start' => '2025-09-15', 'end' => '2026-02-01'],
            10 => ['start' => '2026-02-20', 'end' => '2026-06-20'],
        ];

        $semesterNames = [];
        for ($i = 1; $i <= 10; $i++) {
            $dates = $semesterDates[$i];
            $sem = Semester::updateOrCreate(
                ['name' => "S{$i}"],
                [
                    'start_date' => $dates['start'],
                    'end_date' => $dates['end'],
                    'is_active' => $i <= 2, // S1 and S2 active by default
                ]
            );
            $semesterNames["S{$i}"] = $sem->id;
        }

        // 4. Map levels to semesters via level_semester pivot
        // Each level gets 2 semesters
        // CP1 → S1+S2, CP2 → S3+S4, and all 1st-year levels → S1+S2, etc.
        // For filières with 3 years: Year1 → S1+S2, Year2 → S3+S4, Year3 → S5+S6
        // CP1/CP2 are special (MIP only, 2 years)
        // All other filières: GI1/GC1/GEE1/GEER1/GM1/ID1/TDIA1 → S1+S2
        // GI2/GC2/... → S3+S4, GI3/GC3/... → S5+S6

        $levelSemesterMap = [
            // MIP (2 years)
            'CP1'   => ['S1', 'S2'],
            'CP2'   => ['S3', 'S4'],
            // Year 1 of 3-year filières
            'GI1'   => ['S1', 'S2'],
            'GC1'   => ['S1', 'S2'],
            'GEE1'  => ['S1', 'S2'],
            'GEER1' => ['S1', 'S2'],
            'GM1'   => ['S1', 'S2'],
            'ID1'   => ['S1', 'S2'],
            'TDIA1' => ['S1', 'S2'],
            // Year 2
            'GI2'   => ['S3', 'S4'],
            'GC2'   => ['S3', 'S4'],
            'GEE2'  => ['S3', 'S4'],
            'GEER2' => ['S3', 'S4'],
            'GM2'   => ['S3', 'S4'],
            'ID2'   => ['S3', 'S4'],
            'TDIA2' => ['S3', 'S4'],
            // Year 3
            'GI3'   => ['S5', 'S6'],
            'GC3'   => ['S5', 'S6'],
            'GEE3'  => ['S5', 'S6'],
            'GEER3' => ['S5', 'S6'],
            'GM3'   => ['S5', 'S6'],
            'ID3'   => ['S5', 'S6'],
            'TDIA3' => ['S5', 'S6'],
        ];

        foreach ($levelSemesterMap as $niveau => $semesters) {
            $classeId = $classeIds[$niveau] ?? null;
            if (!$classeId) continue;

            foreach ($semesters as $order => $semName) {
                $semesterId = $semesterNames[$semName] ?? null;
                if (!$semesterId) continue;

                DB::table('level_semester')->updateOrInsert(
                    ['classe_id' => $classeId, 'semester_id' => $semesterId],
                    ['order' => $order + 1, 'created_at' => now(), 'updated_at' => now()]
                );
            }
        }

        $this->command->info('Academic structure seeded: 8 filières, ' . count($classeIds) . ' levels, 10 semesters, ' . count($levelSemesterMap) . ' level-semester mappings.');
    }
}
