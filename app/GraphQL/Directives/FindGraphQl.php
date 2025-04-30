<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @find
 * 
 * This directive finds a single record by its ID or other unique identifier.
 * 
 * Usage:
 * ```graphql
 * type Query {
 *   user(id: ID!): User @find
 *   post(id: ID!): Post @find
 * }
 * ```
 */
class FindGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $model = $this->directiveArgValue('model');
        $id = $args['id'] ?? null;

        if ($model && $id) {
            return app($model)->find($id);
        }

        return $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
    }
} 