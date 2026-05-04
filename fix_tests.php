<?php
$file = 'tests/Feature/Admin/MedicalRecordTest.php';
$content = file_get_contents($file);

// Replace any occurrence of 'height' => xxx, that isn't followed by measurement_method
$content = preg_replace('/(\'height\'\s*=>\s*[\d\.]+,\n)(?!.*\'measurement_method\')/m', "$1            'measurement_method' => 'standing',\n            'diagnosis' => 'Sehat',\n", $content);

// Fix the specific test that tests missing diagnosis
$content = str_replace(
    "it('dapat menyimpan rekam medis tanpa diagnosis', function () {",
    "it('menolak menyimpan rekam medis tanpa diagnosis', function () {",
    $content
);
$content = str_replace(
    "        $response->assertSessionDoesntHaveErrors();\n    });\n});",
    "        \$response->assertSessionHasErrors('diagnosis');\n    });\n});",
    $content
);

file_put_contents($file, $content);
echo "Fixed MedicalRecordTest.php\n";
