<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\ExerciseAssignment;
use App\Models\MuscleGroup;
use Illuminate\Database\Seeder;

class ExerciseSetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeds realistic, real-world gym muscle groups and exercises without pre-assigned targets.
     */
    public function run(): void
    {
        // 1. Muscle Groups (Standard Anatomical Breakdown)
        $muscleGroupsData = [
            [
                'name' => 'Chest',
                'description' => 'Pectoralis major (sternal & clavicular heads) and pectoralis minor.',
                'is_active' => true,
            ],
            [
                'name' => 'Back',
                'description' => 'Latissimus dorsi, rhomboids, trapezius, and spinal erectors.',
                'is_active' => true,
            ],
            [
                'name' => 'Shoulders',
                'description' => 'Anterior, lateral, and posterior deltoid muscle heads.',
                'is_active' => true,
            ],
            [
                'name' => 'Biceps',
                'description' => 'Biceps brachii (short and long head) and brachialis.',
                'is_active' => true,
            ],
            [
                'name' => 'Triceps',
                'description' => 'Triceps brachii (lateral, long, and medial heads).',
                'is_active' => true,
            ],
            [
                'name' => 'Legs',
                'description' => 'Quadriceps, hamstrings, gluteus complex, and abductors/adductors.',
                'is_active' => true,
            ],
            [
                'name' => 'Abs & Core',
                'description' => 'Rectus abdominis, internal and external obliques, and transverse abdominis.',
                'is_active' => true,
            ],
            [
                'name' => 'Forearms',
                'description' => 'Wrist flexors, wrist extensors, and brachioradialis.',
                'is_active' => true,
            ],
            [
                'name' => 'Calves',
                'description' => 'Gastrocnemius and soleus lower leg muscles.',
                'is_active' => true,
            ],
        ];

        foreach ($muscleGroupsData as $mgData) {
            MuscleGroup::firstOrCreate(
                ['name' => $mgData['name']],
                $mgData
            );
        }

        // 2. Real-World Gym Exercises Pool (No pre-mapped targets)
        // Valid equipment: 'barbell', 'dumbbell', 'cable', 'machine', 'bodyweight'
        $exercisesData = [
            // --- CHEST MOVEMENTS ---
            [
                'name' => 'Barbell Flat Bench Press',
                'description' => 'Compound horizontal pressing for overall pectoralis development.',
                'equipment' => 'barbell',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Incline Dumbbell Press',
                'description' => 'Incline dumbbell press targeting the clavicular head of the chest.',
                'equipment' => 'dumbbell',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Decline Barbell Press',
                'description' => 'Decline pressing movement emphasizing the lower pectoral fibers.',
                'equipment' => 'barbell',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Dumbbell Chest Fly',
                'description' => 'Isolation fly movement providing deep stretch across the chest.',
                'equipment' => 'dumbbell',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Standing Cable Crossover',
                'description' => 'Cable fly for continuous horizontal adduction chest tension.',
                'equipment' => 'cable',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Pec Deck Machine',
                'description' => 'Machine chest fly offering strict stabilization and peak contraction.',
                'equipment' => 'machine',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Push-ups',
                'description' => 'Fundamental bodyweight push movement for chest and core strength.',
                'equipment' => 'bodyweight',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Chest Dips',
                'description' => 'Forward-leaning parallel bar dips targeting lower chest and triceps.',
                'equipment' => 'bodyweight',
                'video_url' => null,
                'is_active' => true,
            ],

            // --- BACK MOVEMENTS ---
            [
                'name' => 'Conventional Deadlift',
                'description' => 'Foundational posterior chain movement engaging back, glutes, and traps.',
                'equipment' => 'barbell',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Barbell Bent-Over Row',
                'description' => 'Heavy horizontal pulling movement for mid-back thickness and lats.',
                'equipment' => 'barbell',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Lat Pulldown',
                'description' => 'Vertical cable pull targeting latissimus dorsi width.',
                'equipment' => 'cable',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Seated Cable Row',
                'description' => 'Horizontal cable row for lower lats, rhomboids, and mid-traps.',
                'equipment' => 'cable',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Single-Arm Dumbbell Row',
                'description' => 'Unilateral free weight row for balanced lat and scapular control.',
                'equipment' => 'dumbbell',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Pull-ups',
                'description' => 'Overhand grip bodyweight vertical pulling movement.',
                'equipment' => 'bodyweight',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'T-Bar Machine Row',
                'description' => 'Chest-supported T-Bar machine row for upper and mid-back development.',
                'equipment' => 'machine',
                'video_url' => null,
                'is_active' => true,
            ],

            // --- SHOULDERS MOVEMENTS ---
            [
                'name' => 'Overhead Barbell Press',
                'description' => 'Standing overhead press for shoulder strength and core stability.',
                'equipment' => 'barbell',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Seated Dumbbell Shoulder Press',
                'description' => 'Seated overhead press focusing on anterior and lateral deltoids.',
                'equipment' => 'dumbbell',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Dumbbell Lateral Raise',
                'description' => 'Side lateral raise to build capped lateral deltoid width.',
                'equipment' => 'dumbbell',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Cable Lateral Raise',
                'description' => 'Unilateral low-pulley cable raise for continuous lateral delt tension.',
                'equipment' => 'cable',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Cable Face Pull',
                'description' => 'High pulley rope pull for rear delts, external rotators, and posture.',
                'equipment' => 'cable',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Reverse Pec Deck Machine',
                'description' => 'Rear deltoid fly machine providing isolated posterior delt work.',
                'equipment' => 'machine',
                'video_url' => null,
                'is_active' => true,
            ],

            // --- BICEPS MOVEMENTS ---
            [
                'name' => 'Standing Barbell Bicep Curl',
                'description' => 'Classic straight-bar curl for maximum bicep overload.',
                'equipment' => 'barbell',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Incline Dumbbell Curl',
                'description' => 'Incline bench curl placing the long head of the bicep on stretch.',
                'equipment' => 'dumbbell',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Dumbbell Hammer Curl',
                'description' => 'Neutral-grip curl targeting brachialis and brachioradialis.',
                'equipment' => 'dumbbell',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Preacher Curl Machine',
                'description' => 'Arm-supported curl machine preventing momentum and cheating.',
                'equipment' => 'machine',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Cable Bicep Curl',
                'description' => 'Standing cable curl with a straight or EZ bar attachment.',
                'equipment' => 'cable',
                'video_url' => null,
                'is_active' => true,
            ],

            // --- TRICEPS MOVEMENTS ---
            [
                'name' => 'Tricep Rope Pushdown',
                'description' => 'Cable pushdown with rope attachment for lateral and long head.',
                'equipment' => 'cable',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Barbell Skull Crusher',
                'description' => 'Lying tricep extension for maximum long head recruitment.',
                'equipment' => 'barbell',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Overhead Dumbbell Tricep Ext',
                'description' => 'Seated or standing single dumbbell overhead extension.',
                'equipment' => 'dumbbell',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Bench Dips',
                'description' => 'Bodyweight or weighted tricep dips between benches.',
                'equipment' => 'bodyweight',
                'video_url' => null,
                'is_active' => true,
            ],

            // --- LEGS MOVEMENTS ---
            [
                'name' => 'Barbell Back Squat',
                'description' => 'King of compound lower-body exercises for quads and glutes.',
                'equipment' => 'barbell',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Leg Press Machine',
                'description' => '45-degree sled leg press for high-volume quad and leg loading.',
                'equipment' => 'machine',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Romanian Deadlift',
                'description' => 'Barbell hip hinge emphasizing hamstring stretch and glute recruitment.',
                'equipment' => 'barbell',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Bulgarian Split Squat',
                'description' => 'Rear foot elevated split squat with dumbbells for unilateral power.',
                'equipment' => 'dumbbell',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Leg Extension Machine',
                'description' => 'Seated quad isolation machine for rectus femoris development.',
                'equipment' => 'machine',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Lying Hamstring Leg Curl',
                'description' => 'Prone machine leg curl isolating the hamstring muscle group.',
                'equipment' => 'machine',
                'video_url' => null,
                'is_active' => true,
            ],

            // --- ABS & CORE MOVEMENTS ---
            [
                'name' => 'Hanging Leg Raise',
                'description' => 'Hanging from pull-up bar, raising legs to engage lower abs.',
                'equipment' => 'bodyweight',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Cable Woodchopper',
                'description' => 'Rotational cable movement strengthening obliques and core rotation.',
                'equipment' => 'cable',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Kneeling Cable Crunch',
                'description' => 'Kneeling rope cable crunch overloading rectus abdominis.',
                'equipment' => 'cable',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Plank',
                'description' => 'Isometric core hold for abdominal endurance and spine support.',
                'equipment' => 'bodyweight',
                'video_url' => null,
                'is_active' => true,
            ],

            // --- CALVES & FOREARMS ---
            [
                'name' => 'Standing Calf Raise Machine',
                'description' => 'Standing machine calf raise focusing on gastrocnemius contraction.',
                'equipment' => 'machine',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Seated Calf Raise',
                'description' => 'Seated calf raise targeting the deeper soleus muscle.',
                'equipment' => 'machine',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Barbell Wrist Curl',
                'description' => 'Seated forearm flexor curl with forearms resting on bench.',
                'equipment' => 'barbell',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Reverse Barbell Curl',
                'description' => 'Overhand grip barbell curl targeting brachioradialis and forearms.',
                'equipment' => 'barbell',
                'video_url' => null,
                'is_active' => true,
            ],
        ];

        // 3. Insert Exercises & Cache by Name
        $exercises = [];
        foreach ($exercisesData as $exData) {
            $exercises[$exData['name']] = Exercise::firstOrCreate(
                ['name' => $exData['name']],
                $exData
            );
        }

        // Cache Muscle Groups by Name
        $muscleGroups = [];
        foreach ($muscleGroupsData as $mgData) {
            $muscleGroups[$mgData['name']] = MuscleGroup::where('name', $mgData['name'])->first();
        }

        // 4. Realistic Real-World Exercise Assignments Mapping
        $realisticAssignments = [
            // --- CHEST ASSIGNMENTS ---
            [
                'exercise' => 'Barbell Flat Bench Press',
                'muscle' => 'Chest',
                'notes' => 'Primary horizontal pushing compound for overall sternal pectoralis mass.',
            ],
            [
                'exercise' => 'Incline Dumbbell Press',
                'muscle' => 'Chest',
                'notes' => 'Targets the clavicular head (upper chest) with an extended range of motion.',
            ],
            [
                'exercise' => 'Decline Barbell Press',
                'muscle' => 'Chest',
                'notes' => 'Emphasizes lower pectoral fibers while reducing anterior deltoid strain.',
            ],
            [
                'exercise' => 'Dumbbell Chest Fly',
                'muscle' => 'Chest',
                'notes' => 'Isolation fly creating deep horizontal stretch under controlled tempo.',
            ],
            [
                'exercise' => 'Standing Cable Crossover',
                'muscle' => 'Chest',
                'notes' => 'Maintains continuous resistance across the midline for peak contraction.',
            ],
            [
                'exercise' => 'Pec Deck Machine',
                'muscle' => 'Chest',
                'notes' => 'Fixed-path fly machine providing strict horizontal adduction.',
            ],
            [
                'exercise' => 'Push-ups',
                'muscle' => 'Chest',
                'notes' => 'Bodyweight push movement for muscular endurance and scapular rhythm.',
            ],
            [
                'exercise' => 'Chest Dips',
                'muscle' => 'Chest',
                'notes' => 'Forward leaning angle targeting lower chest and pushing power.',
            ],

            // --- BACK ASSIGNMENTS ---
            [
                'exercise' => 'Conventional Deadlift',
                'muscle' => 'Back',
                'notes' => 'Foundational posterior chain compound building mid/upper back thickness.',
            ],
            [
                'exercise' => 'Barbell Bent-Over Row',
                'muscle' => 'Back',
                'notes' => 'Heavy horizontal pulling movement for lats, rhomboids, and mid-traps.',
            ],
            [
                'exercise' => 'Lat Pulldown',
                'muscle' => 'Back',
                'notes' => 'Vertical pulling movement developing latissimus dorsi width and flare.',
            ],
            [
                'exercise' => 'Seated Cable Row',
                'muscle' => 'Back',
                'notes' => 'Neutral grip cable pull for lower lat stretch and rhomboid retraction.',
            ],
            [
                'exercise' => 'Single-Arm Dumbbell Row',
                'muscle' => 'Back',
                'notes' => 'Unilateral row addressing muscle imbalances with full range of motion.',
            ],
            [
                'exercise' => 'Pull-ups',
                'muscle' => 'Back',
                'notes' => 'Overhand bodyweight vertical pull engaging lats, biceps, and core.',
            ],
            [
                'exercise' => 'T-Bar Machine Row',
                'muscle' => 'Back',
                'notes' => 'Chest-supported rowing eliminating lower back fatigue for max upper back overload.',
            ],

            // --- SHOULDERS ASSIGNMENTS ---
            [
                'exercise' => 'Overhead Barbell Press',
                'muscle' => 'Shoulders',
                'notes' => 'Standing compound press targeting front and lateral deltoid heads.',
            ],  
            [
                'exercise' => 'Seated Dumbbell Shoulder Press',
                'muscle' => 'Shoulders',
                'notes' => 'Seated vertical press providing stability and full deltoid contraction.',
            ],
            [
                'exercise' => 'Dumbbell Lateral Raise',
                'muscle' => 'Shoulders',
                'notes' => 'Abduction movement isolating the lateral deltoid for shoulder width.',
            ],
            [
                'exercise' => 'Cable Lateral Raise',
                'muscle' => 'Shoulders',
                'notes' => 'Low-pulley cable raise ensuring constant lateral delt tension.',
            ],
            [
                'exercise' => 'Cable Face Pull',
                'muscle' => 'Shoulders',
                'notes' => 'Rope attachment pull targeting rear deltoids and external rotators.',
            ],
            [
                'exercise' => 'Reverse Pec Deck Machine',
                'muscle' => 'Shoulders',
                'notes' => 'Direct rear delt fly machine isolating posterior deltoids.',
            ],

            // --- BICEPS ASSIGNMENTS ---
            [
                'exercise' => 'Standing Barbell Bicep Curl',
                'muscle' => 'Biceps',
                'notes' => 'Heavy straight-bar curl for maximum bicep overload and peak mass.',
            ],
            [
                'exercise' => 'Incline Dumbbell Curl',
                'muscle' => 'Biceps',
                'notes' => 'Places long head of the bicep under full stretch behind the torso.',
            ],
            [
                'exercise' => 'Dumbbell Hammer Curl',
                'muscle' => 'Biceps',
                'notes' => 'Neutral grip developing the brachialis and outer arm thickness.',
            ],
            [
                'exercise' => 'Preacher Curl Machine',
                'muscle' => 'Biceps',
                'notes' => 'Elbow-supported curl isolating the short head with zero momentum.',
            ],
            [
                'exercise' => 'Cable Bicep Curl',
                'muscle' => 'Biceps',
                'notes' => 'Cable pulley curl providing steady tension from stretch to contraction.',
            ],

            // --- TRICEPS ASSIGNMENTS ---
            [
                'exercise' => 'Tricep Rope Pushdown',
                'muscle' => 'Triceps',
                'notes' => 'Cable pushdown spreading the rope at lockout for lateral head contraction.',
            ],
            [
                'exercise' => 'Barbell Skull Crusher',
                'muscle' => 'Triceps',
                'notes' => 'Lying tricep extension maximizing the long head stretch at the bottom.',
            ],
            [
                'exercise' => 'Overhead Dumbbell Tricep Ext',
                'muscle' => 'Triceps',
                'notes' => 'Overhead elbow extension isolating the long head of the triceps.',
            ],
            [
                'exercise' => 'Bench Dips',
                'muscle' => 'Triceps',
                'notes' => 'Bodyweight or plate-loaded dips emphasizing tricep lockout strength.',
            ],

            // --- LEGS ASSIGNMENTS ---
            [
                'exercise' => 'Barbell Back Squat',
                'muscle' => 'Legs',
                'notes' => 'Foundational lower body compound for quadriceps, glutes, and adductors.',
            ],
            [
                'exercise' => 'Leg Press Machine',
                'muscle' => 'Legs',
                'notes' => 'Heavy 45-degree leg press for quad hypertrophy with lumbar support.',
            ],
            [
                'exercise' => 'Romanian Deadlift',
                'muscle' => 'Legs',
                'notes' => 'Hip hinge emphasizing hamstring eccentric stretch and glute engagement.',
            ],
            [
                'exercise' => 'Bulgarian Split Squat',
                'muscle' => 'Legs',
                'notes' => 'Unilateral split squat targeting quad drive, glute depth, and hip stability.',
            ],
            [
                'exercise' => 'Leg Extension Machine',
                'muscle' => 'Legs',
                'notes' => 'Direct quadriceps isolation targeting the rectus femoris in extension.',
            ],
            [
                'exercise' => 'Lying Hamstring Leg Curl',
                'muscle' => 'Legs',
                'notes' => 'Knee flexion isolation directly recruiting all three hamstring heads.',
            ],

            // --- ABS & CORE ASSIGNMENTS ---
            [
                'exercise' => 'Hanging Leg Raise',
                'muscle' => 'Abs & Core',
                'notes' => 'Dynamic hip flexion engaging lower rectus abdominis and hip flexors.',
            ],
            [
                'exercise' => 'Cable Woodchopper',
                'muscle' => 'Abs & Core',
                'notes' => 'Rotational cable exercise building oblique strength and rotational stability.',
            ],
            [
                'exercise' => 'Kneeling Cable Crunch',
                'muscle' => 'Abs & Core',
                'notes' => 'Weighted spinal flexion targeting upper rectus abdominis contraction.',
            ],
            [
                'exercise' => 'Plank',
                'muscle' => 'Abs & Core',
                'notes' => 'Isometric anti-extension hold promoting transverse abdominis endurance.',
            ],

            // --- CALVES ASSIGNMENTS ---
            [
                'exercise' => 'Standing Calf Raise Machine',
                'muscle' => 'Calves',
                'notes' => 'Straight-leg calf raise heavily loading the gastrocnemius muscle.',
            ],
            [
                'exercise' => 'Seated Calf Raise',
                'muscle' => 'Calves',
                'notes' => 'Bent-knee calf raise isolating the deeper soleus calf muscle.',
            ],

            // --- FOREARMS ASSIGNMENTS ---
            [
                'exercise' => 'Barbell Wrist Curl',
                'muscle' => 'Forearms',
                'notes' => 'Wrist flexion targeting the anterior forearm flexor compartment.',
            ],
            [
                'exercise' => 'Reverse Barbell Curl',
                'muscle' => 'Forearms',
                'notes' => 'Overhand grip curl building brachioradialis and forearm extensor endurance.',
            ],
        ];

        foreach ($realisticAssignments as $assignData) {
            if (isset($exercises[$assignData['exercise']]) && isset($muscleGroups[$assignData['muscle']])) {
                ExerciseAssignment::firstOrCreate(
                    [
                        'exercise_id' => $exercises[$assignData['exercise']]->id,
                        'muscle_group_id' => $muscleGroups[$assignData['muscle']]->id,
                    ],
                    [
                        'notes' => $assignData['notes'],
                        'is_active' => true,
                        'record_deleted' => false,
                    ]
                );
            }
        }
    }
}
