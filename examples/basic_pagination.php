<?php

declare(strict_types=1);

/**
 * Pagerfanta — basic pagination example using ArrayAdapter.
 *
 * Demonstrates: wrapping an array, setting page/per-page, iterating results.
 *
 * Run:
 *   php examples/basic_pagination.php
 */

require __DIR__ . '/vendor/autoload.php';

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

// Build a data set (in real usage this would come from a database query)
$allItems = range(1, 100);

$pagerfanta = new Pagerfanta(new ArrayAdapter($allItems));
$pagerfanta->setMaxPerPage(10);
$pagerfanta->setCurrentPage(3); // page 3

echo 'Total items:    ' . $pagerfanta->getNbResults()  . PHP_EOL;
echo 'Total pages:    ' . $pagerfanta->getNbPages()    . PHP_EOL;
echo 'Current page:   ' . $pagerfanta->getCurrentPage() . PHP_EOL;
echo 'Has previous:   ' . ($pagerfanta->hasPreviousPage() ? 'yes' : 'no') . PHP_EOL;
echo 'Has next:       ' . ($pagerfanta->hasNextPage()     ? 'yes' : 'no') . PHP_EOL;

echo PHP_EOL . 'Items on page 3:' . PHP_EOL;
foreach ($pagerfanta->getCurrentPageResults() as $item) {
    echo '  ' . $item . PHP_EOL;
}
