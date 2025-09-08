<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\Question;
use App\Models\Answer;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedAlgebraQuestions();
        $this->seedGeometryQuestions();
        $this->seedEquationQuestions();
    }

    private function seedAlgebraQuestions()
    {
        $topic = Topic::where('slug', 'algebra-ievads')->first();
        if (!$topic) return;

        // Question 1: Easy
        $q1 = Question::create([
            'topic_id' => $topic->id,
            'question_text' => 'Kas ir 5 + 3?',
            'explanation' => 'Pievienojot 5 un 3, iegūstam 8.',
            'difficulty' => 'easy',
            'points' => 1,
        ]);

        Answer::create(['question_id' => $q1->id, 'answer_text' => '7', 'is_correct' => false, 'order' => 1]);
        Answer::create(['question_id' => $q1->id, 'answer_text' => '8', 'is_correct' => true, 'order' => 2]);
        Answer::create(['question_id' => $q1->id, 'answer_text' => '9', 'is_correct' => false, 'order' => 3]);
        Answer::create(['question_id' => $q1->id, 'answer_text' => '6', 'is_correct' => false, 'order' => 4]);

        // Question 2: Easy
        $q2 = Question::create([
            'topic_id' => $topic->id,
            'question_text' => 'Kas ir 12 - 4?',
            'explanation' => 'Atņemot 4 no 12, iegūstam 8.',
            'difficulty' => 'easy',
            'points' => 1,
        ]);

        Answer::create(['question_id' => $q2->id, 'answer_text' => '8', 'is_correct' => true, 'order' => 1]);
        Answer::create(['question_id' => $q2->id, 'answer_text' => '7', 'is_correct' => false, 'order' => 2]);
        Answer::create(['question_id' => $q2->id, 'answer_text' => '9', 'is_correct' => false, 'order' => 3]);
        Answer::create(['question_id' => $q2->id, 'answer_text' => '6', 'is_correct' => false, 'order' => 4]);

        // Question 3: Medium
        $q3 = Question::create([
            'topic_id' => $topic->id,
            'question_text' => 'Kas ir 6 × 7?',
            'explanation' => 'Reizinot 6 ar 7, iegūstam 42.',
            'difficulty' => 'medium',
            'points' => 2,
        ]);

        Answer::create(['question_id' => $q3->id, 'answer_text' => '40', 'is_correct' => false, 'order' => 1]);
        Answer::create(['question_id' => $q3->id, 'answer_text' => '42', 'is_correct' => true, 'order' => 2]);
        Answer::create(['question_id' => $q3->id, 'answer_text' => '44', 'is_correct' => false, 'order' => 3]);
        Answer::create(['question_id' => $q3->id, 'answer_text' => '48', 'is_correct' => false, 'order' => 4]);

        // Question 4: Medium
        $q4 = Question::create([
            'topic_id' => $topic->id,
            'question_text' => 'Kas ir 48 ÷ 6?',
            'explanation' => 'Dalot 48 ar 6, iegūstam 8.',
            'difficulty' => 'medium',
            'points' => 2,
        ]);

        Answer::create(['question_id' => $q4->id, 'answer_text' => '7', 'is_correct' => false, 'order' => 1]);
        Answer::create(['question_id' => $q4->id, 'answer_text' => '8', 'is_correct' => true, 'order' => 2]);
        Answer::create(['question_id' => $q4->id, 'answer_text' => '9', 'is_correct' => false, 'order' => 3]);
        Answer::create(['question_id' => $q4->id, 'answer_text' => '6', 'is_correct' => false, 'order' => 4]);

        // Question 5: Hard
        $q5 = Question::create([
            'topic_id' => $topic->id,
            'question_text' => 'Aprēķiniet: (3 + 4) × 2 - 5',
            'explanation' => 'Vispirms iekavās: 3 + 4 = 7. Tad 7 × 2 = 14. Beidzot 14 - 5 = 9.',
            'difficulty' => 'hard',
            'points' => 3,
        ]);

        Answer::create(['question_id' => $q5->id, 'answer_text' => '7', 'is_correct' => false, 'order' => 1]);
        Answer::create(['question_id' => $q5->id, 'answer_text' => '9', 'is_correct' => true, 'order' => 2]);
        Answer::create(['question_id' => $q5->id, 'answer_text' => '11', 'is_correct' => false, 'order' => 3]);
        Answer::create(['question_id' => $q5->id, 'answer_text' => '13', 'is_correct' => false, 'order' => 4]);
    }

    private function seedGeometryQuestions()
    {
        $topic = Topic::where('slug', 'geometrija-pamati')->first();
        if (!$topic) return;

        // Question 1: Easy
        $q1 = Question::create([
            'topic_id' => $topic->id,
            'question_text' => 'Cik grādu ir taisnā leņķī?',
            'explanation' => 'Taisnā leņķī ir tieši 90 grādi.',
            'difficulty' => 'easy',
            'points' => 1,
        ]);

        Answer::create(['question_id' => $q1->id, 'answer_text' => '90°', 'is_correct' => true, 'order' => 1]);
        Answer::create(['question_id' => $q1->id, 'answer_text' => '180°', 'is_correct' => false, 'order' => 2]);
        Answer::create(['question_id' => $q1->id, 'answer_text' => '45°', 'is_correct' => false, 'order' => 3]);
        Answer::create(['question_id' => $q1->id, 'answer_text' => '360°', 'is_correct' => false, 'order' => 4]);

        // Question 2: Easy
        $q2 = Question::create([
            'topic_id' => $topic->id,
            'question_text' => 'Cik grādu ir pilnā leņķī?',
            'explanation' => 'Pilnā leņķī ir 360 grādi.',
            'difficulty' => 'easy',
            'points' => 1,
        ]);

        Answer::create(['question_id' => $q2->id, 'answer_text' => '180°', 'is_correct' => false, 'order' => 1]);
        Answer::create(['question_id' => $q2->id, 'answer_text' => '360°', 'is_correct' => true, 'order' => 2]);
        Answer::create(['question_id' => $q2->id, 'answer_text' => '90°', 'is_correct' => false, 'order' => 3]);
        Answer::create(['question_id' => $q2->id, 'answer_text' => '270°', 'is_correct' => false, 'order' => 4]);

        // Question 3: Medium
        $q3 = Question::create([
            'topic_id' => $topic->id,
            'question_text' => 'Kāda ir kvadrāta ar malu 5 cm laukums?',
            'explanation' => 'Kvadrāta laukums = mala² = 5² = 25 cm²',
            'difficulty' => 'medium',
            'points' => 2,
        ]);

        Answer::create(['question_id' => $q3->id, 'answer_text' => '20 cm²', 'is_correct' => false, 'order' => 1]);
        Answer::create(['question_id' => $q3->id, 'answer_text' => '25 cm²', 'is_correct' => true, 'order' => 2]);
        Answer::create(['question_id' => $q3->id, 'answer_text' => '30 cm²', 'is_correct' => false, 'order' => 3]);
        Answer::create(['question_id' => $q3->id, 'answer_text' => '10 cm²', 'is_correct' => false, 'order' => 4]);

        // Question 4: Medium
        $q4 = Question::create([
            'topic_id' => $topic->id,
            'question_text' => 'Kāda ir taisnstūra ar malām 6 cm un 4 cm laukums?',
            'explanation' => 'Taisnstūra laukums = garums × platums = 6 × 4 = 24 cm²',
            'difficulty' => 'medium',
            'points' => 2,
        ]);

        Answer::create(['question_id' => $q4->id, 'answer_text' => '20 cm²', 'is_correct' => false, 'order' => 1]);
        Answer::create(['question_id' => $q4->id, 'answer_text' => '24 cm²', 'is_correct' => true, 'order' => 2]);
        Answer::create(['question_id' => $q4->id, 'answer_text' => '28 cm²', 'is_correct' => false, 'order' => 3]);
        Answer::create(['question_id' => $q4->id, 'answer_text' => '10 cm²', 'is_correct' => false, 'order' => 4]);

        // Question 5: Hard
        $q5 = Question::create([
            'topic_id' => $topic->id,
            'question_text' => 'Kāda ir trijstūra ar pamatu 8 cm un augstumu 6 cm laukums?',
            'explanation' => 'Trijstūra laukums = (pamats × augstums) ÷ 2 = (8 × 6) ÷ 2 = 24 cm²',
            'difficulty' => 'hard',
            'points' => 3,
        ]);

        Answer::create(['question_id' => $q5->id, 'answer_text' => '20 cm²', 'is_correct' => false, 'order' => 1]);
        Answer::create(['question_id' => $q5->id, 'answer_text' => '24 cm²', 'is_correct' => true, 'order' => 2]);
        Answer::create(['question_id' => $q5->id, 'answer_text' => '28 cm²', 'is_correct' => false, 'order' => 3]);
        Answer::create(['question_id' => $q5->id, 'answer_text' => '48 cm²', 'is_correct' => false, 'order' => 4]);
    }

    private function seedEquationQuestions()
    {
        $topic = Topic::where('slug', 'vienadojumi')->first();
        if (!$topic) return;

        // Question 1: Easy
        $q1 = Question::create([
            'topic_id' => $topic->id,
            'question_text' => 'Atrisiniet vienādojumu: x + 3 = 7',
            'explanation' => 'x + 3 = 7, tātad x = 7 - 3 = 4',
            'difficulty' => 'easy',
            'points' => 1,
        ]);

        Answer::create(['question_id' => $q1->id, 'answer_text' => 'x = 3', 'is_correct' => false, 'order' => 1]);
        Answer::create(['question_id' => $q1->id, 'answer_text' => 'x = 4', 'is_correct' => true, 'order' => 2]);
        Answer::create(['question_id' => $q1->id, 'answer_text' => 'x = 5', 'is_correct' => false, 'order' => 3]);
        Answer::create(['question_id' => $q1->id, 'answer_text' => 'x = 10', 'is_correct' => false, 'order' => 4]);

        // Question 2: Easy
        $q2 = Question::create([
            'topic_id' => $topic->id,
            'question_text' => 'Atrisiniet vienādojumu: 2x = 10',
            'explanation' => '2x = 10, tātad x = 10 ÷ 2 = 5',
            'difficulty' => 'easy',
            'points' => 1,
        ]);

        Answer::create(['question_id' => $q2->id, 'answer_text' => 'x = 4', 'is_correct' => false, 'order' => 1]);
        Answer::create(['question_id' => $q2->id, 'answer_text' => 'x = 5', 'is_correct' => true, 'order' => 2]);
        Answer::create(['question_id' => $q2->id, 'answer_text' => 'x = 6', 'is_correct' => false, 'order' => 3]);
        Answer::create(['question_id' => $q2->id, 'answer_text' => 'x = 8', 'is_correct' => false, 'order' => 4]);

        // Question 3: Medium
        $q3 = Question::create([
            'topic_id' => $topic->id,
            'question_text' => 'Atrisiniet vienādojumu: 3x + 2 = 14',
            'explanation' => '3x + 2 = 14, tātad 3x = 12, un x = 4',
            'difficulty' => 'medium',
            'points' => 2,
        ]);

        Answer::create(['question_id' => $q3->id, 'answer_text' => 'x = 3', 'is_correct' => false, 'order' => 1]);
        Answer::create(['question_id' => $q3->id, 'answer_text' => 'x = 4', 'is_correct' => true, 'order' => 2]);
        Answer::create(['question_id' => $q3->id, 'answer_text' => 'x = 5', 'is_correct' => false, 'order' => 3]);
        Answer::create(['question_id' => $q3->id, 'answer_text' => 'x = 6', 'is_correct' => false, 'order' => 4]);

        // Question 4: Medium
        $q4 = Question::create([
            'topic_id' => $topic->id,
            'question_text' => 'Atrisiniet vienādojumu: 2x - 5 = 7',
            'explanation' => '2x - 5 = 7, tātad 2x = 12, un x = 6',
            'difficulty' => 'medium',
            'points' => 2,
        ]);

        Answer::create(['question_id' => $q4->id, 'answer_text' => 'x = 5', 'is_correct' => false, 'order' => 1]);
        Answer::create(['question_id' => $q4->id, 'answer_text' => 'x = 6', 'is_correct' => true, 'order' => 2]);
        Answer::create(['question_id' => $q4->id, 'answer_text' => 'x = 7', 'is_correct' => false, 'order' => 3]);
        Answer::create(['question_id' => $q4->id, 'answer_text' => 'x = 8', 'is_correct' => false, 'order' => 4]);

        // Question 5: Hard
        $q5 = Question::create([
            'topic_id' => $topic->id,
            'question_text' => 'Atrisiniet vienādojumu: 4(x + 2) = 20',
            'explanation' => '4(x + 2) = 20, tātad x + 2 = 5, un x = 3',
            'difficulty' => 'hard',
            'points' => 3,
        ]);

        Answer::create(['question_id' => $q5->id, 'answer_text' => 'x = 2', 'is_correct' => false, 'order' => 1]);
        Answer::create(['question_id' => $q5->id, 'answer_text' => 'x = 3', 'is_correct' => true, 'order' => 2]);
        Answer::create(['question_id' => $q5->id, 'answer_text' => 'x = 4', 'is_correct' => false, 'order' => 3]);
        Answer::create(['question_id' => $q5->id, 'answer_text' => 'x = 5', 'is_correct' => false, 'order' => 4]);
    }
}
