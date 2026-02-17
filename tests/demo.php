<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\AdminUser;
use App\CustomerUser;
use App\UserBase;

/**
 * Regular function requirement: format output lines.
 */
function printLine(string $label, mixed $value): void
{
    echo $label . ': ' . (is_string($value) ? $value : var_export($value, true)) . PHP_EOL;
}

echo "=== DEMO START ===" . PHP_EOL;

try {
    // Optional superglobal simulation:
    // Example: php tests/Demo.php "name=Vidya&email=vidya@example.com"
    parse_str($argv[1] ?? '', $_GET);

    $nameFromGet = $_GET['name'] ?? 'Default User';
    $emailFromGet = $_GET['email'] ?? 'default@example.com';

    $admin = new AdminUser('Alice Admin', 'alice.admin@example.com');
    $customer = new CustomerUser($nameFromGet, $emailFromGet, 10);

    // Magic __set / __get demo
    $customer->favoriteColor = 'blue';
    printLine('Customer favoriteColor (magic __get)', $customer->favoriteColor);

    // Numeric array: ordered list (best for loops + map/filter)
    $usersNumeric = [$admin, $customer];

    // Associative array: key-value lookup (best for fast access by key)
    $usersByEmail = [
        $admin->getEmail() => $admin,
        $customer->getEmail() => $customer,
    ];

    printLine('Instance count (static)', UserBase::getInstanceCount());

    // foreach requirement
    foreach ($usersNumeric as $user) {
        printLine('User', (string) $user);
    }

    // array_map requirement (closure)
    $emails = array_map(
        fn (UserBase $u): string => $u->getEmail(),
        $usersNumeric
    );
    printLine('Emails (array_map)', $emails);

    // array_filter requirement (closure)
    $adminsOnly = array_filter(
        $usersNumeric,
        fn (UserBase $u): bool => $u->role === UserBase::ROLE_ADMIN
    );
    printLine('Admins only count (array_filter)', count($adminsOnly));

    // if + logical operators
    if ($customer->login($customer->getEmail()) && $customer->isLoggedIn()) {
        printLine('Customer logged in?', 'yes');
    } else {
        printLine('Customer logged in?', 'no');
    }

    // switch requirement
    switch ($admin->role) {
        case UserBase::ROLE_ADMIN:
            printLine('Admin role recognized', true);
            break;
        case UserBase::ROLE_CUSTOMER:
            printLine('Admin role recognized', false);
            break;
        default:
            printLine('Unknown role', $admin->role);
    }

    // while requirement
    $i = 0;
    while ($i < 2) {
        printLine('While loop i', $i);
        $i++;
    }

    // Associative array lookup
    $lookupEmail = $customer->getEmail();
    $found = $usersByEmail[$lookupEmail] ?? null;
    printLine('Lookup by email (associative)', $found ? (string) $found : 'not found');

    // Exception handling demo: invalid email
    try {
        $badUser = new CustomerUser('Bad Email', 'not-an-email');
        printLine('Bad user', (string) $badUser);
    } catch (\InvalidArgumentException $e) {

    // Interface method demo
    $admin->resetPassword('new-secure-password');
    printLine('Admin resetPassword called', true);

    // Minimal unit test via assert()
    assert($admin->role === UserBase::ROLE_ADMIN);
    assert($customer->role === UserBase::ROLE_CUSTOMER);
    assert(in_array('alice.admin@example.com', $emails, true));
    printLine('Asserts passed', true);

} catch (Throwable $e) {
    // Catch-all demo
    printLine('Unexpected error', $e->getMessage());
}

echo "=== DEMO END ===" . PHP_EOL;
