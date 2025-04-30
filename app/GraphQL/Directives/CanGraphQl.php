<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @can
 * 
 * This directive ensures that a field can only be accessed by users with specific abilities.
 * It works in conjunction with Laravel's authorization system.
 * 
 * Usage:
 * ```graphql
 * type Query {
 *   adminDashboard: Dashboard! @can(ability: "view-admin-dashboard")
 *   deleteUser(id: ID!): User! @can(ability: "delete-users")
 * }
 * ```
 */
class CanGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $ability = $this->directiveArgValue('ability');
        $user = $context->user();

        if (!$user || !$user->can($ability)) {
            throw new \Exception('Unauthorized');
        }

        return $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
    }
} 