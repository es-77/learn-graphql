<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\Broadcast;

/**
 * @directive @broadcast
 * 
 * This directive enables real-time broadcasting of GraphQL operations
 * to connected clients using Laravel's broadcasting system.
 * 
 * Usage:
 * ```graphql
 * type Mutation {
 *   createPost(input: CreatePostInput!): Post! @broadcast(subscription: "postCreated")
 *   updateUser(input: UpdateUserInput!): User! @broadcast(subscription: "userUpdated")
 * }
 * ```
 */
class BroadcastGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $subscription = $this->directiveArgValue('subscription');
        $result = $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);

        if ($subscription) {
            Broadcast::event($subscription, $result);
        }

        return $result;
    }
} 