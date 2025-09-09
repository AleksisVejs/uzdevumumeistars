<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\Question;
use App\Models\Answer;

class ComprehensiveCurriculumSeeder extends Seeder
{
    public function run(): void
    {
        $this->createTopics();
        $this->seedSampleQuestions();
    }

    private function createTopics()
    {
        $topics = [
            // Pamatskolas sākumposms (1-6. klase)
            ['name' => 'Aritmētika un skaitļu apstrāde', 'slug' => 'aritmetika', 'prerequisite_level' => 1],
            ['name' => 'Ģeometrija pamati', 'slug' => 'geometrija-pamati', 'prerequisite_level' => 1],
            ['name' => 'Mērīšana', 'slug' => 'merisana', 'prerequisite_level' => 2],
            ['name' => 'Dati un statistika', 'slug' => 'dati-statistika', 'prerequisite_level' => 3],
            
            // Pamatskola (7-9. klase)
            ['name' => 'Algebra pamati', 'slug' => 'algebra-pamati', 'prerequisite_level' => 4],
            ['name' => 'Vienādojumi un nevienādojumi', 'slug' => 'vienadojumi', 'prerequisite_level' => 5],
            ['name' => 'Funkcijas', 'slug' => 'funkcijas', 'prerequisite_level' => 6],
            ['name' => 'Proporcijas un procenti', 'slug' => 'proporcijas-procenti', 'prerequisite_level' => 4],
            ['name' => 'Ģeometrija vidusskolas līmenī', 'slug' => 'geometrija-vidusskola', 'prerequisite_level' => 5],
            
            // Vidusskola (10-12. klase)
            ['name' => 'Kvadrātiskās funkcijas', 'slug' => 'kvadratiskas-funkcijas', 'prerequisite_level' => 7],
            ['name' => 'Analītiskā ģeometrija', 'slug' => 'analitiska-geometrija', 'prerequisite_level' => 8],
            ['name' => 'Vektori', 'slug' => 'vektori', 'prerequisite_level' => 9],
            ['name' => 'Matemātiskā analīze', 'slug' => 'matematiska-analize', 'prerequisite_level' => 10],
            ['name' => 'Statistika un varbūtību teorija', 'slug' => 'statistika-varbutiba', 'prerequisite_level' => 8],
            ['name' => 'Finanšu matemātika', 'slug' => 'finansu-matematika', 'prerequisite_level' => 9],
        ];

        foreach ($topics as $topicData) {
            Topic::updateOrCreate(
                ['slug' => $topicData['slug']],
                $topicData
            );
        }
    }

    private function seedSampleQuestions()
    {
        // Clear existing questions to avoid duplicates
        Answer::query()->delete();
        Question::query()->delete();
        
        // Comprehensive questions for each topic and grade
        $this->seedArithmeticQuestions();
        $this->seedBasicGeometryQuestions();
        $this->seedMeasurementQuestions();
        $this->seedDataStatisticsQuestions();
        $this->seedAlgebraBasicsQuestions();
        $this->seedEquationsQuestions();
        $this->seedFunctionsQuestions();
        $this->seedProportionsPercentagesQuestions();
        $this->seedAdvancedGeometryQuestions();
        $this->seedQuadraticFunctionsQuestions();
        $this->seedAnalyticGeometryQuestions();
        $this->seedVectorsQuestions();
        $this->seedMathematicalAnalysisQuestions();
        $this->seedStatisticsProbabilityQuestions();
        $this->seedFinancialMathematicsQuestions();
    }

    private function seedFunctionsQuestions()
    {
        $topic = Topic::where('slug', 'funkcijas')->first();
        if (!$topic) return;

        // Grade 7: Basic functions
        $this->createQuestion($topic, 7, 'Funkciju pamati', 'single_choice', 'Kas ir f(x) = 2x + 3, ja x = 1?', 'easy', 1, [
            'time_limit' => 60
        ], [
            ['4', false],
            ['5', true],
            ['6', false],
            ['7', false]
        ]);

        // Grade 8: Linear functions
        $this->createQuestion($topic, 8, 'Lineāras funkcijas', 'single_choice', 'Kāds ir funkcijas f(x) = 3x - 2 slīpums?', 'medium', 2, [
            'time_limit' => 90
        ], [
            ['-2', false],
            ['3', true],
            ['-3', false],
            ['2', false]
        ]);

        // Grade 9: Function properties
        $this->createQuestion($topic, 9, 'Funkciju īpašības', 'single_choice', 'Kāda ir funkcijas f(x) = x² definīcijas apgabals?', 'medium', 2, [
            'time_limit' => 90
        ], [
            ['x > 0', false],
            ['x ≥ 0', false],
            ['Visi reālie skaitļi', true],
            ['x ≠ 0', false]
        ]);

        // Grade 10: Advanced functions
        $this->createQuestion($topic, 10, 'Funkcijas', 'single_choice', 'Kas ir funkcijas f(x) = 1/x definīcijas apgabals?', 'hard', 3, [
            'time_limit' => 120
        ], [
            ['x > 0', false],
            ['x ≠ 0', true],
            ['x ≥ 0', false],
            ['Visi reālie skaitļi', false]
        ]);

        // Grade 11: Trigonometric functions
        $this->createQuestion($topic, 11, 'Trigonometriskas funkcijas', 'single_choice', 'Kāda ir funkcijas f(x) = sin(x) vērtību kopa?', 'hard', 3, [
            'time_limit' => 120
        ], [
            ['[-1, 1]', true],
            ['[0, 1]', false],
            ['[0, ∞)', false],
            ['(-∞, ∞)', false]
        ]);

        // Grade 12: Complex functions
        $this->createQuestion($topic, 12, 'Funkcijas', 'single_choice', 'Kas ir funkcijas f(x) = e^x atvasinājums?', 'hard', 3, [
            'time_limit' => 120
        ], [
            ['e^x', true],
            ['x·e^x', false],
            ['e^(x-1)', false],
            ['ln(x)', false]
        ]);
    }

    private function seedAdvancedGeometryQuestions()
    {
        $topic = Topic::where('slug', 'geometrija-vidusskola')->first();
        if (!$topic) return;

        // Grade 7: Advanced shapes
        $this->createQuestion($topic, 7, 'Daudzstūri', 'single_choice', 'Cik diagonāles ir sešstūrim?', 'medium', 2, [
            'time_limit' => 90
        ], [
            ['6', false],
            ['9', true],
            ['12', false],
            ['15', false]
        ]);

        // Grade 8: Circle theorems
        $this->createQuestion($topic, 8, 'Apļa teorēmas', 'single_choice', 'Kāds ir ierakstītā leņķa īpašums?', 'medium', 2, [
            'time_limit' => 90
        ], [
            ['Vienāds ar centrālo leņķi', false],
            ['Puse no centrālā leņķa', true],
            ['Divreiz lielāks par centrālo leņķi', false],
            ['Nav saistīts ar centrālo leņķi', false]
        ]);

        // Grade 9: Similarity
        $this->createQuestion($topic, 9, 'Līdzība', 'single_choice', 'Kādi ir līdzīgu trijstūru kritēriji?', 'hard', 3, [
            'time_limit' => 120
        ], [
            ['Tikai leņķi', false],
            ['Tikai malas', false],
            ['Leņķi un malas', true],
            ['Tikai perimetrs', false]
        ]);

        // Grade 10: Coordinate geometry
        $this->createQuestion($topic, 10, 'Koordinātu ģeometrija', 'single_choice', 'Kāds ir taisnes y = 2x + 3 slīpums?', 'medium', 2, [
            'time_limit' => 90
        ], [
            ['-3', false],
            ['2', true],
            ['3', false],
            ['-2', false]
        ]);

        // Grade 11: 3D geometry
        $this->createQuestion($topic, 11, '3D ģeometrija', 'single_choice', 'Kāds ir sfēras tilpums, ja rādiuss ir 3 cm?', 'hard', 3, [
            'time_limit' => 120
        ], [
            ['27π cm³', false],
            ['36π cm³', true],
            ['54π cm³', false],
            ['81π cm³', false]
        ]);

        // Grade 12: Advanced geometry
        $this->createQuestion($topic, 12, 'Ģeometrija', 'single_choice', 'Kāda ir elipses ekscentricitāte, ja a = 5, b = 3?', 'hard', 3, [
            'time_limit' => 120
        ], [
            ['0.6', false],
            ['0.8', true],
            ['1.2', false],
            ['1.6', false]
        ]);
    }

    private function seedVectorsQuestions()
    {
        $topic = Topic::where('slug', 'vektori')->first();
        if (!$topic) return;

        // Grade 10: Vector basics
        $this->createQuestion($topic, 10, 'Vektoru pamati', 'single_choice', 'Kāds ir vektora (3, 4) garums?', 'medium', 2, [
            'time_limit' => 90
        ], [
            ['5', true],
            ['7', false],
            ['12', false],
            ['25', false]
        ]);

        // Grade 11: Vector operations
        $this->createQuestion($topic, 11, 'Vektoru operācijas', 'single_choice', 'Kas ir vektoru (1,2) un (3,4) skalārais reizinājums?', 'hard', 3, [
            'time_limit' => 120
        ], [
            ['11', true],
            ['7', false],
            ['10', false],
            ['14', false]
        ]);

        // Grade 12: Advanced vectors
        $this->createQuestion($topic, 12, 'Vektori', 'single_choice', 'Kas ir vektoru (1,1,1) un (2,0,0) vektoriālais reizinājums?', 'hard', 3, [
            'time_limit' => 120
        ], [
            ['(0,2,-2)', true],
            ['(2,0,0)', false],
            ['(1,1,1)', false],
            ['(0,0,0)', false]
        ]);
    }

    private function seedStatisticsProbabilityQuestions()
    {
        $topic = Topic::where('slug', 'statistika-varbutiba')->first();
        if (!$topic) return;

        // Grade 8: Basic probability
        $this->createQuestion($topic, 8, 'Varbūtība', 'single_choice', 'Kāda ir varbūtība iegūt 6, metot kauliņu?', 'easy', 1, [
            'time_limit' => 60
        ], [
            ['1/6', true],
            ['1/3', false],
            ['1/2', false],
            ['1', false]
        ]);

        // Grade 9: Statistics basics
        $this->createQuestion($topic, 9, 'Statistika', 'single_choice', 'Kāda ir skaitļu 2, 4, 6, 8, 10 vidējā vērtība?', 'easy', 1, [
            'time_limit' => 60
        ], [
            ['5', false],
            ['6', true],
            ['7', false],
            ['8', false]
        ]);

        // Grade 10: Advanced probability
        $this->createQuestion($topic, 10, 'Varbūtība', 'single_choice', 'Kāda ir varbūtība iegūt vismaz vienu galvu, metot monētu divreiz?', 'medium', 2, [
            'time_limit' => 90
        ], [
            ['1/4', false],
            ['1/2', false],
            ['3/4', true],
            ['1', false]
        ]);

        // Grade 11: Normal distribution
        $this->createQuestion($topic, 11, 'Normālais sadalījums', 'single_choice', 'Kāda ir standartnormālā sadalījuma vidējā vērtība?', 'medium', 2, [
            'time_limit' => 90
        ], [
            ['0', true],
            ['1', false],
            ['-1', false],
            ['0.5', false]
        ]);

        // Grade 12: Advanced statistics
        $this->createQuestion($topic, 12, 'Statistika', 'single_choice', 'Kas ir t-izlases standartkļūda?', 'hard', 3, [
            'time_limit' => 120
        ], [
            ['s/√n', true],
            ['s/n', false],
            ['√s/n', false],
            ['s·√n', false]
        ]);
    }

    private function seedFinancialMathematicsQuestions()
    {
        $topic = Topic::where('slug', 'finansu-matematika')->first();
        if (!$topic) return;

        // Grade 9: Basic percentages
        $this->createQuestion($topic, 9, 'Procenti', 'single_choice', 'Cik maksā prece, kas maksā 100€ ar 20% atlaidi?', 'easy', 1, [
            'time_limit' => 60
        ], [
            ['80€', true],
            ['120€', false],
            ['20€', false],
            ['100€', false]
        ]);

        // Grade 10: Interest
        $this->createQuestion($topic, 10, 'Procenti', 'single_choice', 'Cik būs 1000€ pēc gada ar 5% gada procentu likmi?', 'medium', 2, [
            'time_limit' => 90
        ], [
            ['1050€', true],
            ['1100€', false],
            ['1500€', false],
            ['2000€', false]
        ]);

        // Grade 11: Compound interest
        $this->createQuestion($topic, 11, 'Saliktie procenti', 'single_choice', 'Cik būs 1000€ pēc 2 gadiem ar 10% gada procentu likmi?', 'hard', 3, [
            'time_limit' => 120
        ], [
            ['1200€', false],
            ['1210€', true],
            ['1100€', false],
            ['1300€', false]
        ]);

        // Grade 12: Advanced finance
        $this->createQuestion($topic, 12, 'Finanses', 'single_choice', 'Kāda ir pašreizējā vērtība 1000€, kas saņemama pēc 3 gadiem ar 8% diskonta likmi?', 'hard', 3, [
            'time_limit' => 120
        ], [
            ['793.83€', true],
            ['800€', false],
            ['900€', false],
            ['1000€', false]
        ]);
    }

    private function seedArithmeticQuestions()
    {
        $topic = Topic::where('slug', 'aritmetika')->first();
        if (!$topic) return;

        // Grade 1: Basic addition and subtraction
        $this->createQuestion($topic, 1, 'Saskaitīšana', 'single_choice', 'Kas ir 3 + 2?', 'easy', 1, [
            'time_limit' => 30
        ], [
            ['4', false],
            ['5', true],
            ['6', false],
            ['7', false]
        ]);

        $this->createQuestion($topic, 1, 'Atņemšana', 'single_choice', 'Kas ir 7 - 3?', 'easy', 1, [
            'time_limit' => 30
        ], [
            ['3', false],
            ['4', true],
            ['5', false],
            ['6', false]
        ]);

        $this->createQuestion($topic, 1, 'Saskaitīšana', 'single_choice', 'Kas ir 4 + 4?', 'easy', 1, [
            'time_limit' => 30
        ], [
            ['6', false],
            ['7', false],
            ['8', true],
            ['9', false]
        ]);

        // Grade 2: Multiplication and division basics
        $this->createQuestion($topic, 2, 'Reizināšana', 'single_choice', 'Kas ir 2 × 3?', 'easy', 1, [
            'time_limit' => 45
        ], [
            ['5', false],
            ['6', true],
            ['7', false],
            ['8', false]
        ]);

        $this->createQuestion($topic, 2, 'Dalīšana', 'single_choice', 'Kas ir 8 ÷ 2?', 'easy', 1, [
            'time_limit' => 45
        ], [
            ['3', false],
            ['4', true],
            ['5', false],
            ['6', false]
        ]);

        $this->createQuestion($topic, 2, 'Reizināšana', 'single_choice', 'Kas ir 5 × 4?', 'medium', 2, [
            'time_limit' => 60
        ], [
            ['18', false],
            ['20', true],
            ['22', false],
            ['24', false]
        ]);

        // Grade 3: Larger numbers and mixed operations
        $this->createQuestion($topic, 3, 'Saskaitīšana', 'single_choice', 'Kas ir 25 + 37?', 'easy', 1, [
            'time_limit' => 60
        ], [
            ['61', false],
            ['62', true],
            ['63', false],
            ['64', false]
        ]);

        $this->createQuestion($topic, 3, 'Reizināšana', 'single_choice', 'Kas ir 6 × 7?', 'medium', 2, [
            'time_limit' => 60
        ], [
            ['40', false],
            ['42', true],
            ['44', false],
            ['46', false]
        ]);

        $this->createQuestion($topic, 3, 'Dalīšana', 'single_choice', 'Kas ir 48 ÷ 6?', 'medium', 2, [
            'time_limit' => 60
        ], [
            ['7', false],
            ['8', true],
            ['9', false],
            ['10', false]
        ]);

        // Grade 4: Fractions and decimals introduction
        $this->createQuestion($topic, 4, 'Daļas', 'single_choice', 'Kura daļa ir lielāka: 1/2 vai 1/3?', 'easy', 1, [
            'time_limit' => 60
        ], [
            ['1/3', false],
            ['1/2', true],
            ['Vienādas', false],
            ['Nevar noteikt', false]
        ]);

        $this->createQuestion($topic, 4, 'Decimāldaļas', 'single_choice', 'Kas ir 0.5 + 0.3?', 'easy', 1, [
            'time_limit' => 60
        ], [
            ['0.7', false],
            ['0.8', true],
            ['0.9', false],
            ['1.0', false]
        ]);

        // Grade 5: Advanced operations
        $this->createQuestion($topic, 5, 'Reizināšana', 'single_choice', 'Kas ir 12 × 15?', 'medium', 2, [
            'time_limit' => 90
        ], [
            ['170', false],
            ['180', true],
            ['190', false],
            ['200', false]
        ]);

        $this->createQuestion($topic, 5, 'Dalīšana', 'single_choice', 'Kas ir 144 ÷ 12?', 'medium', 2, [
            'time_limit' => 90
        ], [
            ['11', false],
            ['12', true],
            ['13', false],
            ['14', false]
        ]);

        // Grade 6: Complex arithmetic
        $this->createQuestion($topic, 6, 'Jauktais', 'single_choice', 'Kas ir (15 + 25) × 2?', 'medium', 2, [
            'time_limit' => 90
        ], [
            ['70', false],
            ['80', true],
            ['90', false],
            ['100', false]
        ]);

        $this->createQuestion($topic, 6, 'Daļas', 'single_choice', 'Kas ir 3/4 + 1/4?', 'easy', 1, [
            'time_limit' => 60
        ], [
            ['1/2', false],
            ['1', true],
            ['4/8', false],
            ['2/4', false]
        ]);

        // Grade 7: Negative numbers and advanced fractions
        $this->createQuestion($topic, 7, 'Negatīvi skaitļi', 'single_choice', 'Kas ir -5 + 3?', 'easy', 1, [
            'time_limit' => 60
        ], [
            ['-8', false],
            ['-2', true],
            ['2', false],
            ['8', false]
        ]);

        $this->createQuestion($topic, 7, 'Daļas', 'single_choice', 'Kas ir 2/3 × 3/4?', 'medium', 2, [
            'time_limit' => 90
        ], [
            ['5/7', false],
            ['6/12', false],
            ['1/2', true],
            ['3/7', false]
        ]);

        // Grade 8: Powers and roots
        $this->createQuestion($topic, 8, 'Potenču', 'single_choice', 'Kas ir 2³?', 'easy', 1, [
            'time_limit' => 60
        ], [
            ['6', false],
            ['8', true],
            ['9', false],
            ['12', false]
        ]);

        $this->createQuestion($topic, 8, 'Saknes', 'single_choice', 'Kas ir √16?', 'easy', 1, [
            'time_limit' => 60
        ], [
            ['2', false],
            ['4', true],
            ['8', false],
            ['16', false]
        ]);

        // Grade 9: Advanced arithmetic
        $this->createQuestion($topic, 9, 'Potenču', 'single_choice', 'Kas ir 5² + 3²?', 'medium', 2, [
            'time_limit' => 90
        ], [
            ['28', false],
            ['34', true],
            ['36', false],
            ['40', false]
        ]);

        $this->createQuestion($topic, 9, 'Saknes', 'single_choice', 'Kas ir √(25 + 11)?', 'medium', 2, [
            'time_limit' => 90
        ], [
            ['5', false],
            ['6', true],
            ['7', false],
            ['8', false]
        ]);

        // Grade 10: Complex operations
        $this->createQuestion($topic, 10, 'Jauktais', 'single_choice', 'Kas ir (2³ + 3²) ÷ 5?', 'hard', 3, [
            'time_limit' => 120
        ], [
            ['3.2', false],
            ['3.4', true],
            ['3.6', false],
            ['3.8', false]
        ]);

        // Grade 11: Advanced topics
        $this->createQuestion($topic, 11, 'Potenču', 'single_choice', 'Kas ir 2⁴ × 2²?', 'medium', 2, [
            'time_limit' => 90
        ], [
            ['2⁶', true],
            ['2⁸', false],
            ['4⁶', false],
            ['8²', false]
        ]);

        // Grade 12: Complex arithmetic
        $this->createQuestion($topic, 12, 'Jauktais', 'single_choice', 'Kas ir (√64 + 4²) ÷ 2?', 'hard', 3, [
            'time_limit' => 120
        ], [
            ['10', false],
            ['12', true],
            ['14', false],
            ['16', false]
        ]);
    }

    private function seedBasicGeometryQuestions()
    {
        $topic = Topic::where('slug', 'geometrija-pamati')->first();
        if (!$topic) return;

        // Grade 1: Basic shapes
        $this->createQuestion($topic, 1, 'Ģeometriskās figūras', 'single_choice', 'Cik malas ir kvadrātam?', 'easy', 1, [
            'time_limit' => 30
        ], [
            ['3', false],
            ['4', true],
            ['5', false],
            ['6', false]
        ]);

        $this->createQuestion($topic, 1, 'Ģeometriskās figūras', 'single_choice', 'Cik malas ir trijstūrim?', 'easy', 1, [
            'time_limit' => 30
        ], [
            ['2', false],
            ['3', true],
            ['4', false],
            ['5', false]
        ]);

        // Grade 2: Shape properties
        $this->createQuestion($topic, 2, 'Figūru īpašības', 'single_choice', 'Kura figūra ir apaļa?', 'easy', 1, [
            'time_limit' => 30
        ], [
            ['Kvadrāts', false],
            ['Trijstūris', false],
            ['Aplis', true],
            ['Taisnstūris', false]
        ]);

        // Grade 3: Angles and lines
        $this->createQuestion($topic, 3, 'Leņķi', 'single_choice', 'Cik grādi ir taisnajam leņķim?', 'easy', 1, [
            'time_limit' => 45
        ], [
            ['45°', false],
            ['90°', true],
            ['180°', false],
            ['360°', false]
        ]);

        // Grade 4: Perimeter and area basics
        $this->createQuestion($topic, 4, 'Perimetrs', 'single_choice', 'Kāds ir kvadrāta perimetrs, ja viena mala ir 5 cm?', 'medium', 2, [
            'time_limit' => 60
        ], [
            ['15 cm', false],
            ['20 cm', true],
            ['25 cm', false],
            ['30 cm', false]
        ]);

        // Grade 5: Area calculations
        $this->createQuestion($topic, 5, 'Laukums', 'single_choice', 'Kāds ir taisnstūra laukums, ja garums ir 6 cm un platums 4 cm?', 'medium', 2, [
            'time_limit' => 60
        ], [
            ['20 cm²', false],
            ['24 cm²', true],
            ['28 cm²', false],
            ['32 cm²', false]
        ]);

        // Grade 6: Volume basics
        $this->createQuestion($topic, 6, 'Tilpums', 'single_choice', 'Kāds ir kuba tilpums, ja mala ir 3 cm?', 'medium', 2, [
            'time_limit' => 90
        ], [
            ['9 cm³', false],
            ['27 cm³', true],
            ['36 cm³', false],
            ['54 cm³', false]
        ]);

        // Grade 7: Coordinate geometry basics
        $this->createQuestion($topic, 7, 'Koordinātu plakne', 'single_choice', 'Kur atrodas punkts (3, 4)?', 'easy', 1, [
            'time_limit' => 60
        ], [
            ['Pirmajā kvadrantā', true],
            ['Otrajā kvadrantā', false],
            ['Trešajā kvadrantā', false],
            ['Ceturtais kvadrantā', false]
        ]);

        // Grade 8: Advanced angles
        $this->createQuestion($topic, 8, 'Leņķi', 'single_choice', 'Cik grādi ir papildleņķim, ja viens leņķis ir 45°?', 'medium', 2, [
            'time_limit' => 60
        ], [
            ['45°', false],
            ['135°', true],
            ['225°', false],
            ['315°', false]
        ]);

        // Grade 9: Circle properties
        $this->createQuestion($topic, 9, 'Aplis', 'single_choice', 'Kāds ir apļa rādiuss, ja diametrs ir 10 cm?', 'easy', 1, [
            'time_limit' => 60
        ], [
            ['5 cm', true],
            ['10 cm', false],
            ['20 cm', false],
            ['100 cm', false]
        ]);

        // Grade 10: Advanced geometry
        $this->createQuestion($topic, 10, 'Ģeometrija', 'single_choice', 'Kāds ir vienādmalu trijstūra leņķis?', 'medium', 2, [
            'time_limit' => 60
        ], [
            ['45°', false],
            ['60°', true],
            ['90°', false],
            ['120°', false]
        ]);

        // Grade 11: Trigonometry basics
        $this->createQuestion($topic, 11, 'Trigonometrija', 'single_choice', 'Kas ir sin(30°)?', 'medium', 2, [
            'time_limit' => 90
        ], [
            ['0.5', true],
            ['0.707', false],
            ['0.866', false],
            ['1', false]
        ]);

        // Grade 12: Complex geometry
        $this->createQuestion($topic, 12, 'Analītiskā ģeometrija', 'single_choice', 'Kāds ir attālums starp punktiem (0,0) un (3,4)?', 'hard', 3, [
            'time_limit' => 120
        ], [
            ['5', true],
            ['7', false],
            ['12', false],
            ['25', false]
        ]);
    }

    private function seedMeasurementQuestions()
    {
        $topic = Topic::where('slug', 'merisana')->first();
        if (!$topic) return;

        $this->createQuestion($topic, 4, 'Garuma mērvienības', 'single_choice', 'Cik centimetru ir 1 metrā?', 'easy', 1, [
            'time_limit' => 45
        ], [
            ['10 cm', false],
            ['100 cm', true],
            ['1000 cm', false],
            ['10000 cm', false]
        ]);
    }

    private function seedDataStatisticsQuestions()
    {
        $topic = Topic::where('slug', 'dati-statistika')->first();
        if (!$topic) return;

        $this->createQuestion($topic, 4, 'Datu interpretācija', 'single_choice', 'Ja klasē ir 20 skolēni un 12 no viņiem ir meitenes, cik zēnu ir klasē?', 'easy', 1, [
            'time_limit' => 60
        ], [
            ['6 zēni', false],
            ['8 zēni', true],
            ['10 zēni', false],
            ['12 zēni', false]
        ]);
    }

    private function seedAlgebraBasicsQuestions()
    {
        $topic = Topic::where('slug', 'algebra-pamati')->first();
        if (!$topic) return;

        $this->createQuestion($topic, 7, 'Mainīgie', 'single_choice', 'Ja x = 5, kas ir x + 3?', 'easy', 1, [
            'time_limit' => 60
        ], [
            ['7', false],
            ['8', true],
            ['9', false],
            ['10', false]
        ]);
    }

    private function seedEquationsQuestions()
    {
        $topic = Topic::where('slug', 'vienadojumi')->first();
        if (!$topic) return;

        $this->createQuestion($topic, 7, 'Lineārie vienādojumi', 'single_choice', 'Atrisiniet: x + 4 = 9', 'easy', 1, [
            'time_limit' => 90
        ], [
            ['x = 3', false],
            ['x = 4', false],
            ['x = 5', true],
            ['x = 6', false]
        ]);
    }

    private function seedProportionsPercentagesQuestions()
    {
        $topic = Topic::where('slug', 'proporcijas-procenti')->first();
        if (!$topic) return;

        $this->createQuestion($topic, 7, 'Procenti', 'single_choice', 'Kas ir 20% no 150?', 'medium', 2, [
            'time_limit' => 90
        ], [
            ['25', false],
            ['30', true],
            ['35', false],
            ['40', false]
        ]);
    }

    private function seedQuadraticFunctionsQuestions()
    {
        $topic = Topic::where('slug', 'kvadratiskas-funkcijas')->first();
        if (!$topic) return;

        $this->createQuestion($topic, 10, 'Kvadrātfunkciju īpašības', 'single_choice', 'Kāda ir funkcijas f(x) = x² - 4x + 3 virsotne?', 'hard', 3, [
            'time_limit' => 180
        ], [
            ['(2, -1)', true],
            ['(-2, 1)', false],
            ['(1, 2)', false],
            ['(-1, 2)', false]
        ]);
    }

    private function seedAnalyticGeometryQuestions()
    {
        $topic = Topic::where('slug', 'analitiska-geometrija')->first();
        if (!$topic) return;

        $this->createQuestion($topic, 10, 'Taisnes vienādojumi', 'single_choice', 'Kāds ir taisnes, kas iet caur punktiem (0,0) un (2,4), vienādojums?', 'hard', 3, [
            'time_limit' => 180
        ], [
            ['y = 2x', true],
            ['y = 4x', false],
            ['y = x + 2', false],
            ['y = 2x + 1', false]
        ]);
    }

    private function seedMathematicalAnalysisQuestions()
    {
        $topic = Topic::where('slug', 'matematiska-analize')->first();
        if (!$topic) return;

        $this->createQuestion($topic, 12, 'Atvasinājumi', 'single_choice', 'Kāds ir funkcijas f(x) = x³ atvasinājums?', 'hard', 3, [
            'time_limit' => 180
        ], [
            ['3x²', true],
            ['x²', false],
            ['3x', false],
            ['x³', false]
        ]);
    }

    private function createQuestion($topic, $grade, $subtopic, $questionType, $questionText, $difficulty, $points, $metadata, $answers)
    {
        $question = Question::create([
            'topic_id' => $topic->id,
            'grade' => $grade,
            'subtopic' => $subtopic,
            'question_type' => $questionType,
            'question_text' => $questionText,
            'explanation' => $this->generateExplanation($difficulty),
            'difficulty' => $difficulty,
            'points' => $points,
            'metadata' => $metadata,
            'is_active' => true,
        ]);

        foreach ($answers as $index => $answerData) {
            Answer::create([
                'question_id' => $question->id,
                'answer_text' => $answerData[0],
                'is_correct' => $answerData[1],
                'order' => $index + 1,
            ]);
        }
    }

    private function generateExplanation($difficulty)
    {
        $explanations = [
            'easy' => 'Šis ir pamata jautājums, kas prasa zināšanas par matemātikas pamatiem.',
            'medium' => 'Šis jautājums prasa vidēja līmeņa zināšanas un prasmi pielietot matemātiskās metodes.',
            'hard' => 'Šis ir sarežģīts jautājums, kas prasa dziļas zināšanas un prasmi risināt sarežģītas problēmas.'
        ];

        return $explanations[$difficulty] ?? 'Skatiet risinājumu un mācieties no kļūdām.';
    }
}
