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
        // Sample questions for each grade level
        $this->seedGrade1to3Questions();
        $this->seedGrade4to6Questions();
        $this->seedGrade7to9Questions();
        $this->seedGrade10to12Questions();
    }

    private function seedGrade1to3Questions()
    {
        $this->seedArithmeticQuestions();
        $this->seedBasicGeometryQuestions();
    }

    private function seedGrade4to6Questions()
    {
        $this->seedMeasurementQuestions();
        $this->seedDataStatisticsQuestions();
    }

    private function seedGrade7to9Questions()
    {
        $this->seedAlgebraBasicsQuestions();
        $this->seedEquationsQuestions();
        $this->seedProportionsPercentagesQuestions();
    }

    private function seedGrade10to12Questions()
    {
        $this->seedQuadraticFunctionsQuestions();
        $this->seedAnalyticGeometryQuestions();
        $this->seedMathematicalAnalysisQuestions();
    }

    private function seedArithmeticQuestions()
    {
        $topic = Topic::where('slug', 'aritmetika')->first();
        if (!$topic) return;

        // Grade 1-2: Basic addition
        $this->createQuestion($topic, 1, 'Saskaitīšana', 'single_choice', 'Kas ir 3 + 2?', 'easy', 1, [
            'time_limit' => 30
        ], [
            ['4', false],
            ['5', true],
            ['6', false],
            ['7', false]
        ]);

        // Grade 2-3: Multiplication
        $this->createQuestion($topic, 2, 'Reizināšana', 'single_choice', 'Kas ir 2 × 3?', 'easy', 1, [
            'time_limit' => 45
        ], [
            ['5', false],
            ['6', true],
            ['7', false],
            ['8', false]
        ]);
    }

    private function seedBasicGeometryQuestions()
    {
        $topic = Topic::where('slug', 'geometrija-pamati')->first();
        if (!$topic) return;

        $this->createQuestion($topic, 1, 'Ģeometriskās figūras', 'single_choice', 'Cik malas ir kvadrātam?', 'easy', 1, [
            'time_limit' => 30
        ], [
            ['3', false],
            ['4', true],
            ['5', false],
            ['6', false]
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
