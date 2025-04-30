<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @auth
 * 
 * This directive ensures that a field can only be accessed by authenticated users.
 * It can be used to protect specific fields or entire types in your GraphQL schema.
 * 
 * Usage:
 * ```graphql
 * type Query {
 *   protectedField: String! @auth
 *   userProfile: User! @auth
 * }
 * ```
 */
class AuthGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        if (!$context->user()) {
            throw new \Exception('Unauthenticated');
        }

        return $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
    }
} 