<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\AdminUser;
use App\CustomerUser;
use App\UserBase;
use InvalidArgumentException;

function printLine(string $label, mixed $value): void
{
    echo $label . ': ' . (is_string($value) ? $value : var_export($value, true)) . PHP_EOL;
}

echo "=== DEMO START ===" . PHP_EOL;

try {
    parse_str($argv[1] ?? '', $_GET);

    $nameFromGet = $_GET['name'] ?? 'Default User';
    $emailFromGet = $_GET['email'] ?? 'default@example.com';

    $admin = new AdminUser('Alice Admin', 'alice.admin@example.com');
    $customer = new CustomerUser($nameFromGet, $emailFromGet, 10);

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

    foreach ($usersNumeric as $user) {
        printLine('User', (string)$user);
    }

    $emails = array_map(
        fn (UserBase $u): string => $u->getEmail(),
        $usersNumeric
    );
    printLine('Emails (array_map)', $emails);

    $adminsOnly = array_filter(
        $usersNumeric,
        fn (UserBase $u): bool => $u->role === UserBase::ROLE_ADMIN
    );
    printLine('Admins only count (array_filter)', count($adminsOnly));

    if ($customer->login($customer->getEmail()) && $customer->isLoggedIn()) {
        printLine('Customer logged in?', 'yes');
    } else {
        printLine('Customer logged in?', 'no');
    }

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

    $i = 0;
    while ($i < 2) {
        printLine('While loop i', $i);
        $i++;
    }

    $lookupEmail = $customer->getEmail();
    $found = $usersByEmail[$lookupEmail] ?? null;
    printLine('Lookup by email (associative)', $found ? (string)$found : 'not found');

    try {
        $badUser = new CustomerUser('Bad Email', 'not-an-email');
        printLine('Bad user', (string)$badUser);
    } catch (InvalidArgumentException $e) {
        printLine('Caught exception (invalid email)', $e->getMessage());
    }

    $admin->resetPassword('new-secure-password');
    printLine('Admin resetPassword called', true);

    assert($admin->role === UserBase::ROLE_ADMIN);
    assert($customer->role === UserBase::ROLE_CUSTOMER);
    assert(in_array('alice.admin@example.com', $emails, true));
    printLine('Asserts passed', true);

} catch (Throwable $e) {
    printLine('Unexpected error', $e->getMessage());
}

echo "=== DEMO END ===" . PHP_EOL;
