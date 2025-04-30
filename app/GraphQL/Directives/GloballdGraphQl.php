<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @globalId
 * 
 * This directive converts between global IDs and local IDs,
 * providing a consistent way to identify objects across the API.
 * 
 * Usage:
 * ```graphql
 * type Query {
 *   node(id: ID!): Node @globalId
 *   user(id: ID!): User @globalId
 * }
 * ```
 */
class GloballdGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $id = $args['id'] ?? null;
        $type = $this->directiveArgValue('type');

        if ($id && $type) {
            return [
                'id' => $id,
                'type' => $type,
            ];
        }

        return $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
    }
} 