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
            [
                'name' => 'Aritmētika un skaitļu apstrāde', 
                'slug' => 'aritmetika', 
                'prerequisite_level' => 1,
                'theory' => '# Aritmētika un skaitļu apstrāde

## Kas ir aritmētika?
Aritmētika ir matemātikas pamatnozare, kas nodarbojas ar skaitļu saskaitīšanu, atņemšanu, reizināšanu un dalīšanu. Tā ir pamats visām citām matemātikas nozarēm un ikdienas dzīvē.

## Pamatoperācijas

### Saskaitīšana (+)
**Kāpēc mācāmies saskaitīt?** Saskaitīšana palīdz mums apvienot daudzumus un uzzināt kopējo summu.

**Piemērs:** Ja jums ir 3 āboli un jūs iegūstat vēl 2 ābolus, cik ābolu jums būs kopā?
- 3 + 2 = 5 āboli

**Kāpēc tas ir svarīgi?** Saskaitīšana ir nepieciešama ikdienas dzīvē - skaitot naudu, mērot laiku, plānojot budžetu.

### Atņemšana (-)
**Kāpēc mācāmies atņemt?** Atņemšana palīdz mums uzzināt, cik daudz paliek pēc tam, kad kaut kas tiek ņemts vai iztērēts.

**Piemērs:** Ja jums bija 8 eiro un jūs iztērējāt 3 eiro, cik eiro jums paliek?
- 8 - 3 = 5 eiro

### Reizināšana (×)
**Kāpēc mācāmies reizināt?** Reizināšana ir ātrāks veids, kā saskaitīt vienādus skaitļus.

**Piemērs:** Ja jums ir 4 rindas ar 3 āboliem katrā, cik ābolu jums ir kopā?
- 4 × 3 = 12 āboli (tas pats, kas 3 + 3 + 3 + 3)

**Kāpēc tas ir noderīgi?** Reizināšana palīdz ātri aprēķināt lielus daudzumus.

### Dalīšana (÷)
**Kāpēc mācāmies dalīt?** Dalīšana palīdz mums sadalīt lielu daudzumu vienādās daļās.

**Piemērs:** Ja jums ir 12 āboli un jūs vēlaties tos sadalīt 4 cilvēkiem vienādi, cik ābolu katrs saņems?
- 12 ÷ 4 = 3 āboli katram

## Skaitļu veidi

### Naturālie skaitļi (1, 2, 3, 4...)
- Sāk ar 1 un turpinās bezgalīgi
- Izmanto skaitīšanai un saskaitīšanai

### Daļskaitļi (1/2, 3/4, 2/5...)
- Parāda daļu no vesuma
- **Piemērs:** 1/2 no picas nozīmē pusi no picas

### Decimāldaļas (0.5, 3.14, 2.75...)
- Alternatīvs veids, kā rakstīt daļskaitļus
- **Piemērs:** 0.5 = 1/2, 3.14 ≈ π

### Negatīvi skaitļi (-1, -5, -10...)
- Parāda trūkumu vai parādu
- **Piemērs:** -5°C nozīmē 5 grādus zem nulles

## Praktiski piemēri

1. **Iepirkšanās:** Ja prece maksā 15€ un jums ir 20€, cik atlikums būs? (20 - 15 = 5€)
2. **Ēdiena gatavošana:** Ja receptē ir 4 porcijas, bet jūs vēlaties 8 porcijas, cik reizes jāpalielina sastāvdaļas? (8 ÷ 4 = 2 reizes)
3. **Laika plānošana:** Ja katra nodarbība ilgst 45 minūtes un jums ir 3 nodarbības, cik ilgi kopā? (3 × 45 = 135 minūtes = 2 stundas 15 minūtes)

Aritmētika ir pamats visām citām matemātikas nozarēm un palīdz mums izprast pasauli ap mums!'
            ],
            [
                'name' => 'Ģeometrija pamati', 
                'slug' => 'geometrija-pamati', 
                'prerequisite_level' => 1,
                'theory' => '# Ģeometrija pamati

## Kas ir ģeometrija?
Ģeometrija ir matemātikas nozare, kas pēta figūru īpašības, attālumus, leņķus un telpu. Tā palīdz mums izprast pasauli ap mums un attīsta telpisko domāšanu.

## Pamatfigūras

### Trijstūris
**Kas ir trijstūris?** Trijstūris ir figūra ar 3 malām un 3 leņķiem.

**Kāpēc trijstūri ir svarīgi?** Trijstūri ir visstabilākā figūra - tāpēc tilti un ēkas bieži izmanto trijstūrveida konstrukcijas.

**Piemērs:** Ja trijstūra malas ir 3 cm, 4 cm un 5 cm, tā perimetrs ir 3 + 4 + 5 = 12 cm.

### Kvadrāts
**Kas ir kvadrāts?** Kvadrāts ir figūra ar 4 vienādām malām un 4 taisniem leņķiem (90°).

**Kāpēc kvadrāti ir noderīgi?** Kvadrāti ir viegli aprēķināt un izmanto mērīšanai.

**Piemērs:** Ja kvadrāta mala ir 5 cm, tā laukums ir 5 × 5 = 25 cm².

### Aplis
**Kas ir aplis?** Aplis ir figūra, kur visi punkti atrodas vienādā attālumā no centra.

**Kāpēc apļi ir svarīgi?** Apļi ir visperfektākā forma - tāpēc riteņi ir apaļi, nevis kvadrātveida.

## Leņķi

### Taisnais leņķis (90°)
**Kāpēc 90° ir īpašs?** Taisnais leņķis ir pamats visām ēkām un konstrukcijām.

**Piemērs:** Grīdas stūris ir taisns leņķis.

### Akūts leņķis (< 90°)
**Kāpēc akūti leņķi ir noderīgi?** Tie palīdz izveidot asas malas un efektīvas konstrukcijas.

### Strups leņķis (> 90°)
**Kāpēc strupi leņķi ir svarīgi?** Tie palīdz izveidot plašākas un ērtākas telpas.

## Mērījumi

### Perimetrs
**Kas ir perimetrs?** Perimetrs ir attālums ap figūru.

**Kāpēc mācāmies perimetru?** Perimetrs palīdz uzzināt, cik daudz materiāla nepieciešams, lai aptvertu figūru.

**Piemērs:** Ja dārza garums ir 10 m un platums 6 m, perimetrs ir 2 × (10 + 6) = 32 m.

### Laukums
**Kas ir laukums?** Laukums ir figūras iekšējā daļa.

**Kāpēc mācāmies laukumu?** Laukums palīdz uzzināt, cik daudz materiāla nepieciešams, lai pārklātu virsmu.

**Piemērs:** Ja istabas garums ir 4 m un platums 3 m, laukums ir 4 × 3 = 12 m².

### Tilpums
**Kas ir tilpums?** Tilpums ir figūras iekšējā telpa.

**Kāpēc mācāmies tilpumu?** Tilpums palīdz uzzināt, cik daudz šķidruma vai gāzes var ietilpt konteinerā.

**Piemērs:** Ja kastes garums ir 2 m, platums 1 m un augstums 1 m, tilpums ir 2 × 1 × 1 = 2 m³.

## Praktiski piemēri

1. **Mājas celtniecība:** Cik daudz krāsas nepieciešams, lai nokrāsotu sienas? (jāzina sienu laukums)
2. **Dārza plānošana:** Cik daudz žoga nepieciešams, lai aptvertu dārzu? (jāzina perimetrs)
3. **Iepakojuma izvēle:** Kāda kastes izmērs ir vislabākais? (jāzina tilpums)

Ģeometrija palīdz mums izprast pasauli un veikt praktiskus aprēķinus!'
            ],
            [
                'name' => 'Mērīšana', 
                'slug' => 'merisana', 
                'prerequisite_level' => 2,
                'theory' => 'Mērīšana ir prasme noteikt dažādu lielumu vērtības, izmantojot atbilstošas mērvienības. Mācāmies par garuma, masas, laika, tilpuma un laukuma mērvienībām, kā arī to pārveidošanu. Mērīšana ir svarīga gan mācībās, gan ikdienas dzīvē, ļaujot precīzi aprakstīt pasauli ap mums.'
            ],
            [
                'name' => 'Dati un statistika', 
                'slug' => 'dati-statistika', 
                'prerequisite_level' => 3,
                'theory' => 'Statistika ir zinātne par datu vākšanu, analīzi un interpretāciju. Mācāmies organizēt datus tabulās un diagrammās, aprēķināt vidējās vērtības, mediānu un modu. Statistika palīdz izprast tendences un veikt informētus lēmumus, pamatojoties uz datiem.'
            ],
            
            // Pamatskola (7-9. klase)
            [
                'name' => 'Algebra pamati', 
                'slug' => 'algebra-pamati', 
                'prerequisite_level' => 4,
                'theory' => '# Algebra pamati

## Kas ir algebra?
Algebra ir matemātikas nozare, kas izmanto burtus un simbolus, lai apzīmētu skaitļus un matemātiskas attiecības. Tā palīdz mums risināt problēmas, kur mēs nezinām visus skaitļus.

## Mainīgie

### Kas ir mainīgais?
Mainīgais ir burts (piemēram, x, y, z), kas apzīmē nezināmu skaitli.

**Kāpēc izmantojam mainīgos?** Mainīgie palīdz mums rakstīt vispārīgas formulas, kas darbojas ar jebkuriem skaitļiem.

**Piemērs:** Ja jums ir 5 eiro un jūs vēlaties nopirkt x eiro vērtu preci, cik eiro jums paliks?
- Atbilde: 5 - x eiro

### Algebriskas izteiksmes
**Kas ir algebriska izteiksme?** Tā ir kombinācija no skaitļiem, mainīgajiem un operācijām.

**Piemērs:** 2x + 3 nozīmē "2 reizes x plus 3"

**Kāpēc tas ir noderīgi?** Algebriskas izteiksmes palīdz mums aprakstīt situācijas ar nezināmiem skaitļiem.

## Vienādojumi

### Kas ir vienādojums?
Vienādojums ir izteiksme ar vienādības zīmi (=), kur viena puse ir vienāda ar otru.

**Piemērs:** x + 3 = 7

**Kāpēc vienādojumi ir svarīgi?** Tie palīdz mums atrast nezināmos skaitļus.

### Kā atrisināt vienādojumus?

**1. solis:** Saprotiet, ko vēlaties atrast
**2. solis:** Izolējiet mainīgo vienā pusē
**3. solis:** Veiciet tās pašas darbības abās pusēs

**Piemērs:** x + 3 = 7
- Atņemam 3 no abām pusēm: x + 3 - 3 = 7 - 3
- Iegūstam: x = 4
- Pārbaudām: 4 + 3 = 7 ✓

## Praktiski piemēri

### 1. Naudas problēma
Ja jums ir 20 eiro un jūs iztērējāt x eiro, cik eiro paliek?
- Atbilde: 20 - x eiro

### 2. Vecuma problēma
Ja jūsu vecums ir x gadi, cik vecs jūs būsiet pēc 5 gadiem?
- Atbilde: x + 5 gadi

### 3. Cenas problēma
Ja prece maksā x eiro un jums ir 50% atlaide, cik maksās?
- Atbilde: x - 0.5x = 0.5x eiro

## Kāpēc algebra ir svarīga?

1. **Problēmu risināšana:** Palīdz atrast nezināmos skaitļus
2. **Formulu izveide:** Ļauj rakstīt vispārīgas formulas
3. **Loģiskā domāšana:** Attīsta abstraktu domāšanu
4. **Augstākā matemātika:** Ir pamats visām citām matemātikas nozarēm

## Tips mācībām

- **Sāciet vienkārši:** Sāciet ar vienkāršiem vienādojumiem
- **Praktizējieties:** Risiniet daudz piemēru
- **Pārbaudiet atbildes:** Vienmēr pārbaudiet, vai atbilde ir pareiza
- **Domājiet loģiski:** Katrs solis ir jāpamato

Algebra ir spēcīgs rīks, kas palīdz mums izprast un risināt daudzas problēmas!'
            ],
            [
                'name' => 'Vienādojumi un nevienādojumi', 
                'slug' => 'vienadojumi', 
                'prerequisite_level' => 5,
                'theory' => 'Vienādojumi ir matemātiskas izteiksmes, kas satur vienādības zīmi un nezināmus lielumus. Mācāmies atrisināt lineāros, kvadrātiskos un racionālos vienādojumus. Nevienādojumi nosaka attiecības starp lielumiem, kur viens ir lielāks vai mazāks par otru. Šīs prasmes ir nepieciešamas daudzās zinātnes un tehnoloģiju jomās.'
            ],
            [
                'name' => 'Funkcijas', 
                'slug' => 'funkcijas', 
                'prerequisite_level' => 6,
                'theory' => '# Funkcijas

## Kas ir funkcija?
Funkcija ir matemātiska attiecība, kas katram ievades vērtībam (x) piešķir tieši vienu izejas vērtību (y). To var uzrakstīt kā y = f(x).

**Kāpēc funkcijas ir svarīgas?** Funkcijas palīdz mums modelēt reālas situācijas un prognozēt rezultātus.

## Funkciju veidi

### Lineāras funkcijas
**Formula:** f(x) = mx + b
- m = slīpums (kā strauji funkcija aug)
- b = y-krustpunkts (kur funkcija šķērso y asi)

**Kāpēc lineāras funkcijas ir noderīgas?** Tās apraksta situācijas, kur izmaiņas ir vienmērīgas.

**Piemērs:** Ja telefona rēķins ir 20€ mēnesī plus 0.10€ par katru minūti, formula ir:
- f(x) = 0.10x + 20
- kur x = minūšu skaits

### Kvadrātiskās funkcijas
**Formula:** f(x) = ax² + bx + c
- Grafiks ir parabola (U vai ∩ forma)

**Kāpēc kvadrātiskās funkcijas ir svarīgas?** Tās apraksta situācijas ar maksimumu vai minimumu.

**Piemērs:** Ja mēs mestu bumbu augšup, tās augstums laika gaitā:
- h(t) = -5t² + 20t + 1
- kur t = laiks sekundēs, h = augstums metros

### Eksponenciālās funkcijas
**Formula:** f(x) = a · b^x
- Ātri aug vai samazinās

**Kāpēc eksponenciālās funkcijas ir svarīgas?** Tās apraksta augšanu vai sabrukšanu.

**Piemērs:** Naudas summa ar saliktajiem procentiem:
- A(t) = 1000 · 1.05^t
- kur t = gadi, A = naudas summa

## Funkciju īpašības

### Definīcijas apgabals
**Kas tas ir?** Visas iespējamās x vērtības.

**Piemērs:** f(x) = √x definīcijas apgabals ir x ≥ 0

### Vērtību kopa
**Kas tas ir?** Visas iespējamās y vērtības.

**Piemērs:** f(x) = x² vērtību kopa ir y ≥ 0

### Monotonitāte
**Augoša funkcija:** Ja x palielinās, arī y palielinās
**Dilstoša funkcija:** Ja x palielinās, y samazinās

## Praktiski piemēri

### 1. Temperatūras izmaiņas
Ja istabas temperatūra sākumā ir 20°C un katru stundu paaugstinās par 2°C:
- T(h) = 20 + 2h
- kur h = stundu skaits

### 2. Iedzīvotāju skaits
Ja pilsētā ir 100,000 iedzīvotāju un katru gadu pieaug par 3%:
- P(t) = 100,000 · 1.03^t
- kur t = gadi

### 3. Auto degvielas patēriņš
Ja auto patērē 8 litrus uz 100 km:
- F(d) = 0.08d
- kur d = nobrauktais attālums km

## Kāpēc funkcijas ir svarīgas?

1. **Prognozēšana:** Palīdz paredzēt nākotni
2. **Optimizācija:** Palīdz atrast labākos risinājumus
3. **Modelēšana:** Apraksta reālas situācijas
4. **Zinātne:** Pamats fizikai, ķīmijai, bioloģijai

## Tips mācībām

- **Sāciet ar vienkāršām funkcijām:** f(x) = x, f(x) = x + 1
- **Zīmējiet grafikus:** Tas palīdz izprast funkciju uzvedību
- **Praktizējieties ar reāliem piemēriem:** Meklējiet funkcijas ikdienas dzīvē
- **Pārbaudiet atbildes:** Aizstājiet x vērtības un pārbaudiet y

Funkcijas ir spēcīgs rīks, kas palīdz mums izprast un prognozēt pasauli ap mums!'
            ],
            [
                'name' => 'Proporcijas un procenti', 
                'slug' => 'proporcijas-procenti', 
                'prerequisite_level' => 4,
                'theory' => 'Proporcijas un procenti ir svarīgi rīki daudzu praktisku problēmu risināšanai. Proporcija ir vienādība starp divām attiecībām, bet procenti parāda daļu no vesuma. Mācāmies aprēķināt procentus, atlaides, pieaugumu un samazinājumu. Šīs prasmes ir nepieciešamas finanšu plānošanā un ikdienas dzīvē.'
            ],
            [
                'name' => 'Ģeometrija vidusskolas līmenī', 
                'slug' => 'geometrija-vidusskola', 
                'prerequisite_level' => 5,
                'theory' => 'Augstākā ģeometrija ietver sarežģītākas figūras un to īpašības. Mācāmies par līdzību, kongruenci, apļa teorēmām un koordinātu ģeometriju. Šī līmeņa ģeometrija attīsta telpisko domāšanu un loģisko spriešanu, gatavojot augstākajai matemātikai.'
            ],
            
            // Vidusskola (10-12. klase)
            [
                'name' => 'Kvadrātiskās funkcijas', 
                'slug' => 'kvadratiskas-funkcijas', 
                'prerequisite_level' => 7,
                'theory' => 'Kvadrātiskās funkcijas ir otrās pakāpes funkcijas, kuru grafiks ir parabola. Mācāmies par kvadrātfunkciju īpašībām - virsotni, simetrijas asi, nulles punktiem. Kvadrātfunkcijas tiek izmantotas fizikā, inženierzinātnēs un ekonomikā, lai modelētu daudzas reālas situācijas.'
            ],
            [
                'name' => 'Analītiskā ģeometrija', 
                'slug' => 'analitiska-geometrija', 
                'prerequisite_level' => 8,
                'theory' => 'Analītiskā ģeometrija apvieno ģeometriju ar algebru, izmantojot koordinātu sistēmu. Mācāmies par taisnes, apļa, elipses un hiperbolas vienādojumiem. Šī metode ļauj risināt ģeometriskas problēmas ar algebriskām metodēm un otrādi.'
            ],
            [
                'name' => 'Vektori', 
                'slug' => 'vektori', 
                'prerequisite_level' => 9,
                'theory' => 'Vektori ir matemātiskas struktūras, kas apraksta gan lielumu, gan virzienu. Mācāmies par vektoru saskaitīšanu, atņemšanu, skalāro un vektoriālo reizinājumu. Vektori ir svarīgi fizikā, inženierzinātnēs un datorgrafika, aprakstot spēkus, ātrumus un citus vektoriālus lielumus.'
            ],
            [
                'name' => 'Matemātiskā analīze', 
                'slug' => 'matematiska-analize', 
                'prerequisite_level' => 10,
                'theory' => 'Matemātiskā analīze ir augstākās matemātikas nozare, kas pēta funkciju īpašības, robežas, atvasinājumus un integrāļus. Mācāmies par funkciju uzvedību, ekstremāliem punktiem un laukumu aprēķināšanu. Analīze ir pamats daudzām zinātnes un tehnoloģiju jomām.'
            ],
            [
                'name' => 'Statistika un varbūtību teorija', 
                'slug' => 'statistika-varbutiba', 
                'prerequisite_level' => 8,
                'theory' => 'Varbūtību teorija pēta nejaušus notikumus un to iespējamību. Mācāmies par varbūtību aprēķināšanu, nejaušiem mainīgajiem un sadalījumiem. Statistika palīdz analizēt datus un izdarīt secinājumus par populācijām. Šīs zināšanas ir svarīgas zinātniskajā pētniecībā un lēmumu pieņemšanā.'
            ],
            [
                'name' => 'Finanšu matemātika', 
                'slug' => 'finansu-matematika', 
                'prerequisite_level' => 9,
                'theory' => 'Finanšu matemātika apvieno matemātikas metodes ar finanšu jautājumiem. Mācāmies par procentu aprēķiniem, saliktajiem procentiem, aizdevumiem un ieguldījumiem. Šīs zināšanas palīdz izprast personīgās finanses un pieņemt informētus finanšu lēmumus.'
            ],
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
        
        // Generate random questions for each topic
        $this->generateRandomQuestionsForAllTopics();
    }

    private function generateRandomQuestionsForAllTopics()
    {
        $topics = Topic::all();
        
        foreach ($topics as $topic) {
            // Determine grade range based on prerequisite level
            $gradeRange = $this->getGradeRangeForTopic($topic->prerequisite_level);
            
            foreach ($gradeRange as $grade) {
                // Generate random number of questions (3-8 per grade)
                $questionCount = rand(3, 8);
                
                for ($i = 0; $i < $questionCount; $i++) {
                    $this->generateRandomQuestion($topic, $grade);
                }
            }
        }
    }

    private function getGradeRangeForTopic($prerequisiteLevel)
    {
        // Map prerequisite levels to grade ranges
        $gradeRanges = [
            1 => [1, 2, 3],           // Elementary basics
            2 => [3, 4, 5],           // Intermediate basics
            3 => [4, 5, 6],           // Advanced basics
            4 => [6, 7, 8],           // Middle school start
            5 => [7, 8, 9],           // Middle school core
            6 => [8, 9, 10],          // Middle school advanced
            7 => [9, 10, 11],         // High school start
            8 => [10, 11, 12],        // High school core
            9 => [11, 12],            // High school advanced
            10 => [12],               // High school expert
        ];
        
        return $gradeRanges[$prerequisiteLevel] ?? [1, 2, 3];
    }

    private function generateRandomQuestion($topic, $grade)
    {
        $questionData = $this->getQuestionTemplate($topic->slug, $grade);
        
        if (!$questionData) {
            return;
        }
        
        // Randomly select one question template
        $template = $questionData[array_rand($questionData)];
        
        // Randomly modify some values to create variation
        $template = $this->randomizeQuestionValues($template, $grade);
        
        $this->createQuestion(
            $topic,
            $grade,
            $template['subtopic'],
            $template['question_type'],
            $template['question_text'],
            $template['difficulty'],
            $template['points'],
            $template['metadata'],
            $template['answers']
        );
    }

    private function getQuestionTemplate($topicSlug, $grade)
    {
        $templates = [
            'aritmetika' => $this->getArithmeticTemplates($grade),
            'geometrija-pamati' => $this->getBasicGeometryTemplates($grade),
            'merisana' => $this->getMeasurementTemplates($grade),
            'dati-statistika' => $this->getDataStatisticsTemplates($grade),
            'algebra-pamati' => $this->getAlgebraBasicsTemplates($grade),
            'vienadojumi' => $this->getEquationsTemplates($grade),
            'funkcijas' => $this->getFunctionsTemplates($grade),
            'proporcijas-procenti' => $this->getProportionsTemplates($grade),
            'geometrija-vidusskola' => $this->getAdvancedGeometryTemplates($grade),
            'kvadratiskas-funkcijas' => $this->getQuadraticFunctionsTemplates($grade),
            'analitiska-geometrija' => $this->getAnalyticGeometryTemplates($grade),
            'vektori' => $this->getVectorsTemplates($grade),
            'matematiska-analize' => $this->getMathematicalAnalysisTemplates($grade),
            'statistika-varbutiba' => $this->getStatisticsProbabilityTemplates($grade),
            'finansu-matematika' => $this->getFinancialMathematicsTemplates($grade),
        ];
        
        return $templates[$topicSlug] ?? [];
    }

    private function randomizeQuestionValues($template, $grade)
    {
        // Add some randomization to create variety
        $variations = [
            'time_limit' => rand(30, 180),
            'points' => rand(1, 3),
        ];
        
        $template['metadata'] = array_merge($template['metadata'], $variations);
        $template['points'] = $variations['points'];
        
        return $template;
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

    // Template methods for each topic
    private function getArithmeticTemplates($grade)
    {
        $templates = [];
        
        if ($grade <= 3) {
            $templates[] = [
                'subtopic' => 'Saskaitīšana',
                'question_type' => 'single_choice',
                'question_text' => 'Kas ir ' . rand(1, 10) . ' + ' . rand(1, 10) . '?',
                'difficulty' => 'easy',
                'points' => 1,
                'metadata' => ['time_limit' => 30],
                'answers' => $this->generateArithmeticAnswers('addition', $grade)
            ];
        }
        
        if ($grade >= 2) {
            $templates[] = [
                'subtopic' => 'Reizināšana',
                'question_type' => 'single_choice',
                'question_text' => 'Kas ir ' . rand(2, 12) . ' × ' . rand(2, 12) . '?',
                'difficulty' => 'medium',
                'points' => 2,
                'metadata' => ['time_limit' => 60],
                'answers' => $this->generateArithmeticAnswers('multiplication', $grade)
            ];
        }
        
        if ($grade >= 4) {
            $templates[] = [
                'subtopic' => 'Daļas',
                'question_type' => 'single_choice',
                'question_text' => 'Kas ir ' . rand(1, 5) . '/' . rand(2, 8) . ' + ' . rand(1, 5) . '/' . rand(2, 8) . '?',
                'difficulty' => 'medium',
                'points' => 2,
                'metadata' => ['time_limit' => 90],
                'answers' => $this->generateArithmeticAnswers('fractions', $grade)
            ];
        }
        
        return $templates;
    }

    private function getBasicGeometryTemplates($grade)
    {
        $templates = [];
        
        if ($grade <= 3) {
            $templates[] = [
                'subtopic' => 'Ģeometriskās figūras',
                'question_type' => 'single_choice',
                'question_text' => 'Cik malas ir ' . ['trijstūrim', 'kvadrātam', 'piecstūrim'][rand(0, 2)] . '?',
                'difficulty' => 'easy',
                'points' => 1,
                'metadata' => ['time_limit' => 30],
                'answers' => $this->generateGeometryAnswers('sides', $grade)
            ];
        }
        
        if ($grade >= 4) {
            $templates[] = [
                'subtopic' => 'Laukums',
                'question_type' => 'single_choice',
                'question_text' => 'Kāds ir kvadrāta laukums, ja mala ir ' . rand(2, 10) . ' cm?',
                'difficulty' => 'medium',
                'points' => 2,
                'metadata' => ['time_limit' => 60],
                'answers' => $this->generateGeometryAnswers('area', $grade)
            ];
        }
        
        return $templates;
    }

    private function getMeasurementTemplates($grade)
    {
        return [
            [
                'subtopic' => 'Mērvienības',
                'question_type' => 'single_choice',
                'question_text' => 'Cik centimetru ir ' . rand(1, 5) . ' metrā?',
                'difficulty' => 'easy',
                'points' => 1,
                'metadata' => ['time_limit' => 45],
                'answers' => $this->generateMeasurementAnswers($grade)
            ]
        ];
    }

    private function getDataStatisticsTemplates($grade)
    {
        return [
            [
                'subtopic' => 'Statistika',
                'question_type' => 'single_choice',
                'question_text' => 'Kāda ir skaitļu ' . implode(', ', array_map(fn() => rand(1, 20), range(1, 5))) . ' vidējā vērtība?',
                'difficulty' => 'medium',
                'points' => 2,
                'metadata' => ['time_limit' => 90],
                'answers' => $this->generateStatisticsAnswers($grade)
            ]
        ];
    }

    private function getAlgebraBasicsTemplates($grade)
    {
        return [
            [
                'subtopic' => 'Mainīgie',
                'question_type' => 'single_choice',
                'question_text' => 'Ja x = ' . rand(1, 10) . ', kas ir x + ' . rand(1, 10) . '?',
                'difficulty' => 'easy',
                'points' => 1,
                'metadata' => ['time_limit' => 60],
                'answers' => $this->generateAlgebraAnswers($grade)
            ]
        ];
    }

    private function getEquationsTemplates($grade)
    {
        return [
            [
                'subtopic' => 'Vienādojumi',
                'question_type' => 'single_choice',
                'question_text' => 'Atrisiniet: x + ' . rand(1, 10) . ' = ' . rand(5, 20),
                'difficulty' => 'medium',
                'points' => 2,
                'metadata' => ['time_limit' => 90],
                'answers' => $this->generateEquationAnswers($grade)
            ]
        ];
    }

    private function getFunctionsTemplates($grade)
    {
        return [
            [
                'subtopic' => 'Funkcijas',
                'question_type' => 'single_choice',
                'question_text' => 'Kas ir f(x) = ' . rand(2, 5) . 'x + ' . rand(1, 10) . ', ja x = ' . rand(1, 5) . '?',
                'difficulty' => 'medium',
                'points' => 2,
                'metadata' => ['time_limit' => 90],
                'answers' => $this->generateFunctionAnswers($grade)
            ]
        ];
    }

    private function getProportionsTemplates($grade)
    {
        return [
            [
                'subtopic' => 'Procenti',
                'question_type' => 'single_choice',
                'question_text' => 'Kas ir ' . rand(10, 50) . '% no ' . rand(100, 1000) . '?',
                'difficulty' => 'medium',
                'points' => 2,
                'metadata' => ['time_limit' => 90],
                'answers' => $this->generatePercentageAnswers($grade)
            ]
        ];
    }

    private function getAdvancedGeometryTemplates($grade)
    {
        return [
            [
                'subtopic' => 'Ģeometrija',
                'question_type' => 'single_choice',
                'question_text' => 'Cik diagonāles ir ' . ['sešstūrim', 'astotstūrim', 'desmitstūrim'][rand(0, 2)] . '?',
                'difficulty' => 'hard',
                'points' => 3,
                'metadata' => ['time_limit' => 120],
                'answers' => $this->generateAdvancedGeometryAnswers($grade)
            ]
        ];
    }

    private function getQuadraticFunctionsTemplates($grade)
    {
        return [
            [
                'subtopic' => 'Kvadrātfunkcijas',
                'question_type' => 'single_choice',
                'question_text' => 'Kāda ir funkcijas f(x) = x² - ' . rand(2, 8) . 'x + ' . rand(1, 6) . ' virsotne?',
                'difficulty' => 'hard',
                'points' => 3,
                'metadata' => ['time_limit' => 180],
                'answers' => $this->generateQuadraticAnswers($grade)
            ]
        ];
    }

    private function getAnalyticGeometryTemplates($grade)
    {
        return [
            [
                'subtopic' => 'Analītiskā ģeometrija',
                'question_type' => 'single_choice',
                'question_text' => 'Kāds ir attālums starp punktiem (' . rand(0, 5) . ',' . rand(0, 5) . ') un (' . rand(6, 10) . ',' . rand(6, 10) . ')?',
                'difficulty' => 'hard',
                'points' => 3,
                'metadata' => ['time_limit' => 120],
                'answers' => $this->generateAnalyticGeometryAnswers($grade)
            ]
        ];
    }

    private function getVectorsTemplates($grade)
    {
        return [
            [
                'subtopic' => 'Vektori',
                'question_type' => 'single_choice',
                'question_text' => 'Kāds ir vektora (' . rand(1, 5) . ', ' . rand(1, 5) . ') garums?',
                'difficulty' => 'hard',
                'points' => 3,
                'metadata' => ['time_limit' => 120],
                'answers' => $this->generateVectorAnswers($grade)
            ]
        ];
    }

    private function getMathematicalAnalysisTemplates($grade)
    {
        return [
            [
                'subtopic' => 'Atvasinājumi',
                'question_type' => 'single_choice',
                'question_text' => 'Kāds ir funkcijas f(x) = x^' . rand(2, 4) . ' atvasinājums?',
                'difficulty' => 'hard',
                'points' => 3,
                'metadata' => ['time_limit' => 180],
                'answers' => $this->generateDerivativeAnswers($grade)
            ]
        ];
    }

    private function getStatisticsProbabilityTemplates($grade)
    {
        return [
            [
                'subtopic' => 'Varbūtība',
                'question_type' => 'single_choice',
                'question_text' => 'Kāda ir varbūtība iegūt ' . rand(1, 6) . ', metot kauliņu?',
                'difficulty' => 'medium',
                'points' => 2,
                'metadata' => ['time_limit' => 60],
                'answers' => $this->generateProbabilityAnswers($grade)
            ]
        ];
    }

    private function getFinancialMathematicsTemplates($grade)
    {
        return [
            [
                'subtopic' => 'Procenti',
                'question_type' => 'single_choice',
                'question_text' => 'Cik būs ' . rand(100, 1000) . '€ pēc gada ar ' . rand(3, 10) . '% gada procentu likmi?',
                'difficulty' => 'medium',
                'points' => 2,
                'metadata' => ['time_limit' => 90],
                'answers' => $this->generateFinancialAnswers($grade)
            ]
        ];
    }

    // Helper methods to generate answer options
    private function generateArithmeticAnswers($type, $grade)
    {
        $correct = rand(1, 100);
        $answers = [
            [$correct, true],
            [$correct + rand(1, 10), false],
            [$correct - rand(1, 10), false],
            [$correct + rand(11, 20), false]
        ];
        shuffle($answers);
        return $answers;
    }

    private function generateGeometryAnswers($type, $grade)
    {
        if ($type === 'sides') {
            $correct = rand(3, 8);
            return [
                [$correct, true],
                [$correct + 1, false],
                [$correct - 1, false],
                [$correct + 2, false]
            ];
        }
        
        $correct = rand(4, 100);
        return [
            [$correct, true],
            [$correct + rand(1, 10), false],
            [$correct - rand(1, 10), false],
            [$correct * 2, false]
        ];
    }

    private function generateMeasurementAnswers($grade)
    {
        $meters = rand(1, 5);
        $correct = $meters * 100;
        return [
            [$correct, true],
            [$meters * 10, false],
            [$meters * 1000, false],
            [$meters * 50, false]
        ];
    }

    private function generateStatisticsAnswers($grade)
    {
        $numbers = array_map(fn() => rand(1, 20), range(1, 5));
        $correct = round(array_sum($numbers) / count($numbers), 1);
        return [
            [$correct, true],
            [$correct + rand(1, 5), false],
            [$correct - rand(1, 5), false],
            [array_sum($numbers), false]
        ];
    }

    private function generateAlgebraAnswers($grade)
    {
        $x = rand(1, 10);
        $add = rand(1, 10);
        $correct = $x + $add;
        return [
            [$correct, true],
            [$x, false],
            [$add, false],
            [$x * $add, false]
        ];
    }

    private function generateEquationAnswers($grade)
    {
        $add = rand(1, 10);
        $total = rand(5, 20);
        $correct = $total - $add;
        return [
            ['x = ' . $correct, true],
            ['x = ' . ($correct + 1), false],
            ['x = ' . ($correct - 1), false],
            ['x = ' . $total, false]
        ];
    }

    private function generateFunctionAnswers($grade)
    {
        $a = rand(2, 5);
        $b = rand(1, 10);
        $x = rand(1, 5);
        $correct = $a * $x + $b;
        return [
            [$correct, true],
            [$a + $b, false],
            [$x + $b, false],
            [$a * $x, false]
        ];
    }

    private function generatePercentageAnswers($grade)
    {
        $percent = rand(10, 50);
        $number = rand(100, 1000);
        $correct = round($number * $percent / 100);
        return [
            [$correct, true],
            [$number + $correct, false],
            [$correct / 2, false],
            [$percent, false]
        ];
    }

    private function generateAdvancedGeometryAnswers($grade)
    {
        $sides = rand(6, 10);
        $correct = $sides * ($sides - 3) / 2;
        return [
            [$correct, true],
            [$sides, false],
            [$sides * 2, false],
            [$sides - 3, false]
        ];
    }

    private function generateQuadraticAnswers($grade)
    {
        $a = 1;
        $b = rand(2, 8);
        $c = rand(1, 6);
        $vertex_x = -$b / (2 * $a);
        $vertex_y = $a * $vertex_x * $vertex_x + $b * $vertex_x + $c;
        $correct = "($vertex_x, $vertex_y)";
        return [
            [$correct, true],
            [($vertex_x + 1) . ', ' . ($vertex_y + 1), false],
            [($vertex_x - 1) . ', ' . ($vertex_y - 1), false],
            [($vertex_x + 2) . ', ' . ($vertex_y + 2), false]
        ];
    }

    private function generateAnalyticGeometryAnswers($grade)
    {
        $x1 = rand(0, 5);
        $y1 = rand(0, 5);
        $x2 = rand(6, 10);
        $y2 = rand(6, 10);
        $correct = round(sqrt(($x2 - $x1) ** 2 + ($y2 - $y1) ** 2), 1);
        return [
            [$correct, true],
            [$correct + rand(1, 3), false],
            [$correct - rand(1, 3), false],
            [abs($x2 - $x1) + abs($y2 - $y1), false]
        ];
    }

    private function generateVectorAnswers($grade)
    {
        $x = rand(1, 5);
        $y = rand(1, 5);
        $correct = round(sqrt($x * $x + $y * $y), 1);
        return [
            [$correct, true],
            [$x + $y, false],
            [$x * $y, false],
            [abs($x - $y), false]
        ];
    }

    private function generateDerivativeAnswers($grade)
    {
        $power = rand(2, 4);
        $correct = $power . 'x^' . ($power - 1);
        return [
            [$correct, true],
            ['x^' . $power, false],
            [($power - 1) . 'x^' . $power, false],
            [$power . 'x^' . ($power + 1), false]
        ];
    }

    private function generateProbabilityAnswers($grade)
    {
        return [
            ['1/6', true],
            ['1/3', false],
            ['1/2', false],
            ['1', false]
        ];
    }

    private function generateFinancialAnswers($grade)
    {
        $principal = rand(100, 1000);
        $rate = rand(3, 10);
        $correct = round($principal * (1 + $rate / 100));
        return [
            [$correct . '€', true],
            [($principal + $rate) . '€', false],
            [$principal . '€', false],
            [($principal * $rate) . '€', false]
        ];
    }
}
