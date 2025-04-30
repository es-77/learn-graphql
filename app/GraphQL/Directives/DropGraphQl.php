<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\Schema;

/**
 * @directive @drop
 * 
 * This directive drops a database table or collection.
 * Use with extreme caution as this is a destructive operation.
 * 
 * Usage:
 * ```graphql
 * type Mutation {
 *   dropTable(name: String!): Boolean! @drop
 *   dropCollection(name: String!): Boolean! @drop
 * }
 * ```
 */
class DropGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $name = $args['name'] ?? null;
        $type = $this->directiveArgValue('type', 'table');

        if (!$name) {
            throw new \Exception('Table/collection name is required');
        }

        if ($type === 'table') {
            Schema::dropIfExists($name);
        } else {
            // Handle collection dropping for MongoDB or other document stores
            // Implementation depends on your database driver
        }

        return true;
    }
} 