<?php

namespace Database\Seeders;

use App\Models\Statistic;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ChildrenAndStatisticsSeeder extends Seeder
{
    public function run(): void
    {
        $childrenData = [
            [
                'name' => 'Kis Feri',
                'email' => 'kis.feri@example.com',
            ],
            [
                'name' => 'Nagy Anna',
                'email' => 'nagy.anna@example.com',
            ],
            [
                'name' => 'Kovács Bence',
                'email' => 'kovacs.bence@example.com',
            ],
        ];

        foreach ($childrenData as $childData) {
            $child = User::firstOrCreate(
                ['email' => $childData['email']],
                [
                    'name' => $childData['name'],
                    'type' => 'token',
                    'password' => Hash::make(Str::random(32)),
                ]
            );

            $this->seedStatisticsForChild($child);
        }
    }

    protected function seedStatisticsForChild(User $child): void
    {
        $types = ['card', 'recognition', 'map'];

        foreach ($types as $type) {
            $sessionsCount = random_int(20, 40);

            for ($i = 0; $i < $sessionsCount; $i++) {
                $this->createStat($child, $type, $i);
            }
        }
    }

    protected function createStat(User $child, string $type, int $index): void
    {
        $daysBack = random_int(0, 60);
        $secondsBack = random_int(0, 86400);
        $createdAt = Carbon::now()->subDays($daysBack)->subSeconds($secondsBack);

        switch ($type) {
            case 'card':
                $points = random_int(60, 100);
                $time = random_int(40, 180);
                break;
            case 'recognition':
                $points = random_int(50, 95);
                $time = random_int(50, 210);
                break;
            case 'map':
                $points = random_int(40, 90);
                $time = random_int(60, 240);
                break;
            default:
                $points = random_int(40, 100);
                $time = random_int(40, 240);
        }

        Statistic::create([
            'user_id' => $child->id,
            'type' => $type,
            'points' => $points,
            'time' => $time,
            'created_at' => $createdAt,
        ]);
    }
}
