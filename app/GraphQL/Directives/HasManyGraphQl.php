<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @hasMany
 * 
 * This directive defines a has-many relationship in the GraphQL schema,
 * allowing you to query related records.
 * 
 * Usage:
 * ```graphql
 * type User {
 *   posts: [Post!]! @hasMany
 *   comments: [Comment!]! @hasMany
 * }
 * 
 * type Post {
 *   comments: [Comment!]! @hasMany
 * }
 * ```
 */
class HasManyGraphQl extends BaseDirective implements FieldResolver
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