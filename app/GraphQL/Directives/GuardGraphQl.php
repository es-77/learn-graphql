<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\Auth;

/**
 * @directive @guard
 * 
 * This directive specifies which authentication guard to use
 * for a particular field or type.
 * 
 * Usage:
 * ```graphql
 * type Query {
 *   adminData: AdminData! @guard(with: "admin")
 *   apiData: ApiData! @guard(with: "api")
 * }
 * ```
 */
class GuardGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $guard = $this->directiveArgValue('with', 'web');

        if (!Auth::guard($guard)->check()) {
            throw new \Exception('Unauthenticated');
        }

        return $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
    }
} 