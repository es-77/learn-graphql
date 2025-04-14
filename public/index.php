<?php

$content = "
scalar DateTime @scalar(class: 'Nuwave\\Lighthouse\\Schema\\Types\\Scalars\\DateTime')

type Query {
    user(
      id: ID @eq @rules(apply: ['prohibits:email', 'required_without:email'])
      email: String @eq @rules(apply: ['prohibits:id', 'required_without:id', 'email'])
    ): User @find

    users(
      name: String @where(operator: 'like')
    ): [User!]! @paginate(defaultCount: 10)
}

type User {
    id: ID!
    name: String!
    email: String!
    email_verified_at: DateTime
    created_at: DateTime!
    updated_at: DateTime!
}

";
$directives = [
    'aggregateGraphQl',
    'allGraphQl',
    'asyncGraphQl',
    'authGraphQl',
    'belongstoGraphQl',
    'belongsToManyGraphQl',
    'bindGraphQl',
    'broadcastGraphQl',
    'builderGraphQl',
    'cacheGraphQl',
    'cacheControlGraphQl',
    'cacheKeyGraphQl',
    'canGraphQl',
    'complexityGraphQl',
    'convertEmptyStringsToNullGraphQl',
    'countGraphQl',
    'createGraphQl',
    'createManyGraphQl',
    'deleteGraphQl',
    'deprecatedGraphQl',
    'dropGraphQl',
    'featureGraphQl',
    'fieldGraphQl',
    'findGraphQl',
    'firstGraphQl',
    'forceDeleteGraphQl',
    'enumGraphQl',
    'eqGraphQl',
    'eventGraphQl',
    'globalldGraphQl',
    'guardGraphQl',
    'hashGraphQl',
    'hasManyGraphQl',
    'hasManyThroughGraphQl',
    'hasOneGraphQl',
    'hasOnelhroughGraphQl',
    'inGraphQl',
    'injectGraphQl',
    'interfaceGraphQl',
    'hideGraphQl',
    'lazyLoadGraphQl',
    'likeGraphQl',
    'limitGraphQl',
    'methodGraphQl',
    'modelGraphQl',
    'morphManyGraphQl',
    'morphOneGraphQl',
    'morphloGraphQl',
    'morphloManyGraphQl',
    'namespaceGraphQl',
    'namespacedGraphQl',
    'neqGraphQl',
    'nestGraphQl',
    'nodeGraphQl',
    'notinGraphQl',
    'orderByGraphQl',
    'paginateGraphQl',
    'renameGraphQl',
    'restoreGraphQl',
    'rulesGraphQl',
    'rulesForArrayGraphQl',
    'scalarGraphQl',
    'scopeGraphQl',
    'searchGraphQl',
    'showGraphQl',
    'softDeletesGraphQl',
    'spreadGraphQl',
    'subscriptionGraphQl',
    'throttleGraphQl',
    'trashedGraphQl',
    'trimGraphQl',
    'unionGraphQl',
    'updateGraphQl',
    'updateManyGraphQl',
    'uploadGraphQl',
    'upsertGraphQl',
    'upsertManyGraphQl',
    'validatorGraphQl',
    'whereGraphQl',
    'whereAuthGraphQl',
    'whereBetweenGraphQl',
    'whereConditionsGraphQl',
    'whereHasConditionsGraphQl',
    'whereKeyGraphQl',
    'whereNotBetweenGraphQl',
    'whereNotNullGraphQl',
    'whereNullGraphQl',
    'withGraphQl',
    'withCountGraphQl',
    'withoutGlobalScopesGraphQl',
];
foreach ($directives as $key => $directive) {
    $fileSavePath = 'C:\laragon\www\learn-graphql\graphql\directives' . '\\' . $directive . '.graphql';
    print($fileSavePath);
    file_put_contents($fileSavePath, $content, FILE_APPEND);
}
echo "hello ";
die;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->handleRequest(Request::capture());