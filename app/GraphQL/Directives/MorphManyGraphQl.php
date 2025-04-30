<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @morphMany
 * 
 * This directive defines a polymorphic many relationship in the GraphQL schema,
 * allowing a model to have many related models of different types.
 * 
 * Usage:
 * ```graphql
 * type Post {
 *   comments: [Comment!]! @morphMany
 *   likes: [Like!]! @morphMany
 * }
 * 
 * type User {
 *   activities: [Activity!]! @morphMany
 * }
 * ```
 */
class MorphManyGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $relation = $this->directiveArgValue('relation', $resolveInfo->fieldName);
        $type = $this->directiveArgValue('type');
        $scopes = $this->directiveArgValue('scopes', []);

        if (method_exists($root, $relation)) {
            $query = $root->{$relation}();

            if ($type) {
                $query->where('morphable_type', $type);
            }

            foreach ($scopes as $scope) {
                $query->{$scope}();
            }

            return $query;
        }

        return $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
    }
} 