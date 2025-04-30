<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @hasOne
 * 
 * This directive defines a has-one relationship in the GraphQL schema,
 * allowing you to query a single related record.
 * 
 * Usage:
 * ```graphql
 * type User {
 *   profile: Profile @hasOne
 *   address: Address @hasOne
 * }
 * 
 * type Post {
 *   featuredImage: Image @hasOne
 * }
 * ```
 */
class HasOneGraphQl extends BaseDirective implements FieldResolver
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