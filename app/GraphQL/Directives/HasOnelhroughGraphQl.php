<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @hasOneThrough
 * 
 * This directive defines a has-one-through relationship in the GraphQL schema,
 * allowing you to query a single related record through an intermediate model.
 * 
 * Usage:
 * ```graphql
 * type Country {
 *   latestPost: Post @hasOneThrough(relation: "latestPost", through: "users")
 * }
 * 
 * type User {
 *   latestComment: Comment @hasOneThrough(relation: "latestComment", through: "posts")
 * }
 * ```
 */
class HasOnelhroughGraphQl extends BaseDirective implements FieldResolver
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