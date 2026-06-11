<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$patient = \App\Models\Patient::where('category', 'lansia')->first();
if (!$patient) {
    $patient = \App\Models\Patient::create([
        'category' => 'lansia',
        'full_name' => 'Test Lansia',
        'posyandu_id' => 1,
        'id_number' => '1234567890123456',
        'gender' => 'P'
    ]);
}
$request = new \Illuminate\Http\Request();
$request->merge([
    'patient_id' => $patient->id,
    'visit_date' => date('Y-m-d'),
    'weight' => 50.5,
    'height' => 150.5,
    'diagnosis' => 'Sehat',
    'category' => 'lansia'
]);

$request->setRouteResolver(function () {
    return new \Illuminate\Routing\Route('POST', 'dummy', []);
});

$rules = (new \App\Http\Requests\MedicalRecordRequest())->rules();
$req = new \App\Http\Requests\MedicalRecordRequest();
$req->merge($request->all());
$rules2 = $req->rules();
var_dump($rules2['measurement_method']);

$validator = validator($request->all(), $rules2);

if ($validator->fails()) {
    echo "FAILS: \n" . json_encode($validator->errors());
} else {
    echo "PASSED\n";
}
