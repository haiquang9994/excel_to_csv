<?php

require __DIR__ . '/../vendor/autoload.php';

$method = strtoupper($_SERVER['REQUEST_METHOD']);

if ($method === 'POST') {
    if ($file = $_FILES['file'] ?? null) {
        $filename = $file['name'];
        $csv_filename = substr($filename, 0, strrpos($filename, '.')) . '.csv';

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($file['tmp_name']);
        $rows = $spreadsheet->getActiveSheet()->toArray();
        $data = [];
        foreach ($rows as $row) {
            $data[] = implode(',', array_map('strval', $row));
        }
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $csv_filename . '"');
        echo implode("\n", $data);
    }
} else {
    header("HTTP/1.0 404 Not Found");
}
