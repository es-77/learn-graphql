<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @forceDelete
 * 
 * This directive permanently deletes a record from the database,
 * bypassing soft deletes if they are enabled.
 * 
 * Usage:
 * ```graphql
 * type Mutation {
 *   forceDeletePost(id: ID!): Post! @forceDelete
 *   forceDeleteUser(id: ID!): User! @forceDelete
 * }
 * ```
 */
class ForceDeleteGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $model = $this->directiveArgValue('model');
        $id = $args['id'] ?? null;

        if ($model && $id) {
            $instance = app($model)->withTrashed()->findOrFail($id);
            $instance->forceDelete();
            return $instance;
        }

        return $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
    }
} 