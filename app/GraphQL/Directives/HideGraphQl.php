<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @hide
 * 
 * This directive hides a field from the GraphQL schema,
 * preventing it from being queried or mutated.
 * 
 * Usage:
 * ```graphql
 * type User {
 *   id: ID!
 *   name: String!
 *   password: String! @hide
 *   apiKey: String! @hide
 * }
 * ```
 */
class HideGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $user = $context->user();
        $roles = $this->directiveArgValue('roles', []);

        if ($user && !empty($roles) && !$user->hasRole($roles)) {
            return null;
        }

        return $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
    }
} 