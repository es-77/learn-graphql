<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @morphTo
 * 
 * This directive defines a polymorphic local relationship in the GraphQL schema,
 * allowing a model to belong to another model of a different type.
 * 
 * Usage:
 * ```graphql
 * type Comment {
 *   commentable: Commentable! @morphTo
 * }
 * 
 * type Like {
 *   likeable: Likeable! @morphTo
 * }
 * ```
 */
class MorphloGraphQl extends BaseDirective implements FieldResolver
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