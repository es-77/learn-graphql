<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @belongsTo
 * 
 * This directive defines a belongs-to relationship between models.
 * It's used to load related models in a one-to-many relationship.
 * 
 * Usage:
 * ```graphql
 * type Post {
 *   author: User! @belongsTo
 * }
 * 
 * type Comment {
 *   post: Post! @belongsTo
 * }
 * ```
 */
class BelongsToGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $relation = $this->directiveArgValue('relation', $resolveInfo->fieldName);
        $foreignKey = $this->directiveArgValue('foreignKey');

        if ($foreignKey) {
            return $root->belongsTo($relation, $foreignKey);
        }

        return $root->belongsTo($relation);
    }
} 