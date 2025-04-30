<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @lazyLoad
 * 
 * This directive enables lazy loading of relationships,
 * loading related data only when it's requested.
 * 
 * Usage:
 * ```graphql
 * type User {
 *   posts: [Post!]! @lazyLoad
 *   comments: [Comment!]! @lazyLoad
 * }
 * 
 * type Post {
 *   author: User! @lazyLoad
 *   comments: [Comment!]! @lazyLoad
 * }
 * ```
 */
class LazyLoadGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $relation = $this->directiveArgValue('relation', $resolveInfo->fieldName);
        $scopes = $this->directiveArgValue('scopes', []);

        if (method_exists($root, $relation)) {
            $query = $root->{$relation}();

            foreach ($scopes as $scope) {
                $query->{$scope}();
            }

            return $query;
        }

        return $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
    }
} 