<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @delete
 * 
 * This directive deletes a record from the database.
 * 
 * Usage:
 * ```graphql
 * type Mutation {
 *   deletePost(id: ID!): Post! @delete
 *   deleteUser(id: ID!): User! @delete
 * }
 * ```
 */
class DeleteGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $model = $this->directiveArgValue('model');
        $id = $args['id'] ?? null;

        if ($model && $id) {
            $instance = app($model)->findOrFail($id);
            $instance->delete();
            return $instance;
        }

        return $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
    }
} 