<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$base = __DIR__ . '/resources/views/website/web/center';
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base));
$errors = [];
$total = 0;

foreach ($it as $file) {
    if (!$file->isFile()) continue;
    if (strtolower($file->getExtension()) !== 'php' && !str_ends_with($file->getFilename(), '.blade.php')) continue;
    if (!str_ends_with($file->getFilename(), '.blade.php')) continue;

    $path = $file->getPathname();
    $rel = str_replace(__DIR__ . DIRECTORY_SEPARATOR, '', $path);
    $total++;

    try {
        $compiled = app('blade.compiler')->compileString(file_get_contents($path));
        $tmp = __DIR__ . '/storage/framework/views/_tmp_center_' . md5($path) . '.php';
        file_put_contents($tmp, $compiled);
        $output = [];
        $code = 0;
        exec('php -l ' . escapeshellarg($tmp) . ' 2>&1', $output, $code);
        @unlink($tmp);
        if ($code !== 0) {
            $errors[] = [
                'file' => $rel,
                'msg' => implode("\n", $output),
            ];
        }
    } catch (Throwable $e) {
        $errors[] = [
            'file' => $rel,
            'msg' => $e->getMessage(),
        ];
    }
}

echo "TOTAL:$total\n";
if (empty($errors)) {
    echo "OK\n";
    exit(0);
}

echo "ERRORS:" . count($errors) . "\n";
foreach ($errors as $err) {
    echo "FILE:" . $err['file'] . "\n";
    echo "MSG:" . $err['msg'] . "\n";
    echo "---\n";
}
exit(1);
