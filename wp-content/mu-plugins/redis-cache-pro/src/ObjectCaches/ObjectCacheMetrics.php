<?php
/**
 * Copyright © Rhubarb Tech Inc. All Rights Reserved.
 *
 * All information contained herein is, and remains the property of Rhubarb Tech Incorporated.
 * The intellectual and technical concepts contained herein are proprietary to Rhubarb Tech Incorporated and
 * are protected by trade secret or copyright law. Dissemination and modification of this information or
 * reproduction of this material is strictly forbidden unless prior written permission is obtained from
 * Rhubarb Tech Incorporated.
 *
 * You should have received a copy of the `LICENSE` with this file. If not, please visit:
 * https://objectcache.pro/license.txt
 */

declare(strict_types=1);

namespace RedisCachePro\ObjectCaches;

use RedisCachePro\Configuration\Configuration;
use RedisCachePro\Connections\ConnectionInterface;

/**
 * Memory efficient and approximate object cache metrics.
 *
 * Several operations (PING, FLUSHDB, INFO, DBSIZE, etc.) are not captured by
 * this class, but can be logged using the `save_commands` configuration option.
 *
 * @see https://objectcache.pro/docs/debugging
 */
class ObjectCacheMetrics
{
    /**
     * Number of times the cache data was already cached in memory.
     *
     * @var int
     */
    public $hits = 0;

    /**
     * Number of times the cache did not have the object in memory.
     *
     * @var int
     */
    public $misses = 0;

    /**
     * The in-memory hits-to-misses ratio.
     *
     * @var int|float
     */
    public $hitRatio = 0;

    /**
     * Number of times Redis had the object already cached.
     *
     * @var int
     */
    public $storeHits = 0;

    /**
     * Number of times the Redis did not have the object.
     *
     * @var int
     */
    public $storeMisses = 0;

    /**
     * Number of times the cache read from the datastore.
     *
     * @var int
     */
    public $storeReads = 0;

    /**
     * Number of times the cache wrote to the datastore.
     *
     * @var int
     */
    public $storeWrites = 0;

    /**
     * Number of milliseconds (ms) waited for the datastore to respond.
     *
     * @var float
     */
    public $storeWait = 0.0;

    /**
     * Average number of milliseconds (ms) waited for the datastore to respond.
     *
     * @var float
     */
    public $storeWaitAverage = 0.0;

    /**
     * Number of `$storeWait` samples.
     *
     * @var int
     */
    protected $storeWaitSamples = 0;

    /**
     * The number of prefetched keys.
     *
     * @var ?int
     */
    public $prefetches = 0;

    /**
     * Number of bytes allocated in PHP memory for datastore result.
     *
     * @var float
     */
    public $memory = 0.0;

    /**
     * The cache group metrics.
     *
     * @var \RedisCachePro\Support\ObjectCacheMetricsGroup[]
     */
    public $groups = [];

    /**
     * Empty group instance used internally.
     *
     * @var \RedisCachePro\Support\ObjectCacheMetricsGroup
     */
    protected $emptyGroup;

    /**
     * The configuration instance.
     *
     * @var \RedisCachePro\Configuration\Configuration
     */
    protected $config;

    /**
     * The connection instance.
     *
     * @var ?\RedisCachePro\Connections\ConnectionInterface
     */
    protected $connection;

    public function __construct(Configuration $config, ?ConnectionInterface $connection = null)
    {
        $this->config = $config;
        $this->connection = $connection;

        $this->emptyGroup = (object) [ // @phpstan-ignore-line
            'keys' => 0,
            'memory' => 0,
            'wait' => 0.0,
        ];
    }

    /**
     * Clones the instance and computes all metrics.
     *
     * @param  array<string, array<int|string, mixed>>  &$cache
     * @return self
     */
    public function compute(array &$cache)
    {
        $metrics = clone $this;
        $metrics->computeHitRatio();
        $metrics->computeGroups($cache);

        $this->storeWaitAverage = $this->storeWaitSamples
            ? ($this->storeWait / $this->storeWaitSamples)
            : 0;

        if (! $this->config->prefetch) {
            $this->prefetches = null;
        }

        return $metrics;
    }

    /**
     * Computes metrics for cache groups.
     *
     * @param  array<string, array<int|string, mixed>>  &$cache
     * @return void
     */
    protected function computeGroups(array &$cache)
    {
        $cacheGroups = array_keys($cache);
        $metricGroups = array_keys($this->groups);

        foreach (array_diff($cacheGroups, $metricGroups) as $group) {
            $this->groups[$group] = clone $this->emptyGroup;
        }

        array_walk($this->groups, static function (&$data, $group) use ($cache) {
            $data->keys = count($cache[$group] ?? []);
        });

        ksort($this->groups);
    }

    /**
     * Computes and sets the cache hit-ratio.
     *
     * @return void
     */
    protected function computeHitRatio()
    {
        $total = $this->hits + $this->misses;

        $this->hitRatio = $total > 0
            ? round($this->hits / ($total / 100), 2)
            : 0;
    }

    /**
     * Records a datastore flush.
     *
     * @return void
     */
    public function flush()
    {
        if (! $lastCommand = $this->connection->lastCommand) { // @phpstan-ignore-line
            return;
        }

        $this->storeWrites++;
        $this->storeWaitSamples++;
        $this->storeWait += $lastCommand['wait'];
        $this->memory += $lastCommand['memory'];
    }

    /**
     * Records a datastore read.
     *
     * @param  ?string  $group
     * @return void
     */
    public function read(?string $group = null)
    {
        if (! $lastCommand = $this->connection->lastCommand) { // @phpstan-ignore-line
            return;
        }

        $this->storeReads++;
        $this->storeWaitSamples++;
        $this->storeWait += $lastCommand['wait'];
        $this->memory += $lastCommand['memory'];

        if (! $group) {
            return;
        }

        if (! isset($this->groups[$group])) {
            $this->groups[$group] = clone $this->emptyGroup;
        }

        $this->groups[$group]->wait += $lastCommand['wait'];
        $this->groups[$group]->memory += $lastCommand['memory'];
    }

    /**
     * Records a datastore write.
     *
     * @param  ?string  $group
     * @return void
     */
    public function write(?string $group = null)
    {
        if (! $lastCommand = $this->connection->lastCommand) { // @phpstan-ignore-line
            return;
        }

        $this->storeWrites++;
        $this->storeWaitSamples++;
        $this->storeWait += $lastCommand['wait'];
        $this->memory += $lastCommand['memory'];

        if (! $group) {
            return;
        }

        if (! isset($this->groups[$group])) {
            $this->groups[$group] = clone $this->emptyGroup;
        }

        $this->groups[$group]->wait += $lastCommand['wait'];
        $this->groups[$group]->memory += $lastCommand['memory'];
    }
}
