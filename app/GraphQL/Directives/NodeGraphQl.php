<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @node
 * 
 * This directive enables node resolution in the GraphQL schema,
 * allowing you to fetch any type of object by its global ID.
 * 
 * Usage:
 * ```graphql
 * type Query {
 *   node(id: ID!): Node @node
 * }
 * 
 * interface Node {
 *   id: ID!
 * }
 * ```
 */
class NodeGraphQl extends BaseDirective implements FieldResolver
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