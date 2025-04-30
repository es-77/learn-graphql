<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @hasManyThrough
 * 
 * This directive defines a has-many-through relationship in the GraphQL schema,
 * allowing you to query related records through an intermediate model.
 * 
 * Usage:
 * ```graphql
 * type Country {
 *   posts: [Post!]! @hasManyThrough(relation: "posts", through: "users")
 * }
 * 
 * type User {
 *   comments: [Comment!]! @hasManyThrough(relation: "comments", through: "posts")
 * }
 * ```
 */
class HasManyThroughGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $relation = $this->directiveArgValue('relation', $resolveInfo->fieldName);
        $through = $this->directiveArgValue('through');
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