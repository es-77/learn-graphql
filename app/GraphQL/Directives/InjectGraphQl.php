<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\App;

/**
 * @directive @inject
 * 
 * This directive injects dependencies into field resolvers,
 * allowing you to use Laravel's dependency injection system.
 * 
 * Usage:
 * ```graphql
 * type Query {
 *   userStats: UserStats! @inject(resolver: "App\\GraphQL\\Resolvers\\UserStatsResolver")
 *   systemInfo: SystemInfo! @inject(resolver: "App\\GraphQL\\Resolvers\\SystemInfoResolver")
 * }
 * ```
 */
class InjectGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $resolver = $this->directiveArgValue('resolver');

        if (!$resolver) {
            return $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
        }

        if (str_contains($resolver, '@')) {
            [$class, $method] = explode('@', $resolver);
            $instance = App::make($class);
            return $instance->{$method}($root, $args, $context, $resolveInfo);
        }

        return App::make($resolver)($root, $args, $context, $resolveInfo);
    }
} 