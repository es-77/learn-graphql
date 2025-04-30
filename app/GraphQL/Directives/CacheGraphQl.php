<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\Cache;

/**
 * @directive @cache
 * 
 * This directive caches the result of a field for a specified duration.
 * It helps improve performance by storing frequently accessed data in cache.
 * 
 * Usage:
 * ```graphql
 * type Query {
 *   expensiveComputation: Result! @cache(maxAge: 3600)
 *   userStats: Stats! @cache(key: "user_{id}")
 * }
 * ```
 */
class CacheGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $key = $this->directiveArgValue('key', $this->generateCacheKey($root, $args, $context));
        $maxAge = $this->directiveArgValue('maxAge', 3600);

        return Cache::remember($key, $maxAge, function () use ($root, $args, $context, $resolveInfo) {
            return $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
        });
    }

    protected function generateCacheKey($root, array $args, GraphQLContext $context): string
    {
        $user = $context->user();
        return md5(json_encode([
            'root' => $root,
            'args' => $args,
            'user' => $user ? $user->id : null,
        ]));
    }
} 