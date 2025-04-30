<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\Cache;

/**
 * @directive @cacheKey
 * 
 * This directive allows specifying a custom cache key for a field,
 * providing more control over caching behavior.
 * 
 * Usage:
 * ```graphql
 * type Query {
 *   userProfile(userId: ID!): Profile! @cacheKey(key: "profile_{userId}")
 *   productDetails(sku: String!): Product! @cacheKey(key: "product_{sku}")
 * }
 * ```
 */
class CacheKeyGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $key = $this->directiveArgValue('key');
        $ttl = $this->directiveArgValue('ttl', 3600);

        if (!$key) {
            return $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
        }

        return Cache::remember($key, $ttl, function () use ($root, $args, $context, $resolveInfo) {
            return $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
        });
    }
} 