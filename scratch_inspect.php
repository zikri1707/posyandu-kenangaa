<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\User;
use App\Models\Posyandu;
use App\Models\Patient;
use App\Models\MedicalRecord;

$users = User::all();
echo "--- USERS ---\n";
foreach ($users as $u) {
    echo "ID: {$u->id}, Name: {$u->name}, Role: {$u->role}, Posyandu ID: {$u->posyandu_id}\n";
}

$posyandus = Posyandu::all();
echo "\n--- POSYANDUS ---\n";
foreach ($posyandus as $p) {
    echo "ID: {$p->id}, Name: {$p->name}\n";
}

echo "\n--- PATIENTS COUNT BY POSYANDU ---\n";
$patients = Patient::select('posyandu_id', \DB::raw('count(*) as total'))->groupBy('posyandu_id')->get();
foreach ($patients as $pt) {
    echo "Posyandu ID: {$pt->posyandu_id}, Total: {$pt->total}\n";
}

echo "\n--- MEDICAL RECORDS COUNT BY YEAR ---\n";
$records = MedicalRecord::select(\DB::raw('YEAR(visit_date) as year'), \DB::raw('count(*) as total'))->groupBy('year')->get();
foreach ($records as $r) {
    echo "Year: {$r->year}, Total: {$r->total}\n";
}
