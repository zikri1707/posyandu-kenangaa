<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(600);

$chunkFiles = glob('deploy.zip.*');
if (!empty($chunkFiles)) {
    echo "<h3>Recombining deploy.zip from chunks...</h3>";
    sort($chunkFiles); // Ensure 001, 002, 003 are in order
    $out = fopen('deploy.zip', 'wb');
    foreach ($chunkFiles as $file) {
        echo "Processing chunk: $file<br>";
        $in = fopen($file, 'rb');
        stream_copy_to_stream($in, $out);
        fclose($in);
        unlink($file); // Delete chunk after combining to save space
    }
    fclose($out);
    echo "<p style='color: green;'>Successfully recombined deploy.zip!</p>";
}

echo "<h3>Extracting deploy.zip...</h3>";
if (!file_exists('deploy.zip')) {
    die("<p style='color: red;'>Error: deploy.zip not found on server.</p>");
}

$zip = new ZipArchive;
if ($zip->open('deploy.zip') === TRUE) {
    $zip->extractTo(__DIR__);
    $zip->close();
    echo "<p style='color: green;'>Success! Extraction complete.</p>";
    unlink('deploy.zip');
    echo "<p>Temporary zip file deleted.</p>";
} else {
    echo "<p style='color: red;'>Failed to open deploy.zip</p>";
}
?>
