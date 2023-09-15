<?php

require_once 'Header.php';

$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
$reader->setReadDataOnly(true);
$spreadsheet = $reader->load(__DIR__ . "\Reader\sampleData\\example2.xls");

$worksheet = $spreadsheet->getActiveSheet();
// Get the highest row and column numbers referenced in the worksheet
$highestRow = $worksheet->getHighestDataRow(); // e.g. 10
$highestColumn = $worksheet->getHighestDataColumn(); // e.g 'F'
$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5

echo '<table>' . "\n";
for ($row = 1; $row <= $highestRow; ++$row) {
  echo '<tr>' . PHP_EOL;
  for ($col = 1; $col <= $highestColumnIndex; ++$col) {
    $value = $worksheet->getCell($col, $row)->getValue();
    echo '<td>' . $value . '</td>' . PHP_EOL;
  }
  echo '</tr>' . PHP_EOL;
}
echo '</table>' . PHP_EOL;
