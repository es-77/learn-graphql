<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @interface
 * 
 * This directive defines a GraphQL interface type,
 * allowing you to create abstract types that can be implemented by other types.
 * 
 * Usage:
 * ```graphql
 * interface Node @interface {
 *   id: ID!
 *   createdAt: DateTime!
 *   updatedAt: DateTime!
 * }
 * 
 * interface Searchable @interface {
 *   search(query: String!): [SearchResult!]!
 * }
 * ```
 */
class InterfaceGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $type = $this->directiveArgValue('type');
        $resolver = $this->directiveArgValue('resolver');

        if ($type && $resolver) {
            return app($resolver)->resolveType($root, $context, $resolveInfo);
        }

        return $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
    }
} 