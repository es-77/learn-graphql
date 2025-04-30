<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\Queue;

/**
 * @directive @async
 * 
 * This directive allows executing a field resolver asynchronously,
 * typically by dispatching it to a queue for background processing.
 * 
 * Usage:
 * ```graphql
 * type Mutation {
 *   processLargeData(input: ProcessInput!): ProcessResult! @async
 *   generateReport(input: ReportInput!): Report! @async
 * }
 * ```
 */
class AsyncGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $queue = $this->directiveArgValue('queue', 'default');
        $job = $this->directiveArgValue('job');

        if ($job) {
            Queue::pushOn($queue, new $job($args));
            return ['status' => 'queued'];
        }

        return $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
    }
} 