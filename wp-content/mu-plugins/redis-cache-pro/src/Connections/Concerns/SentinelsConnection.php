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

namespace RedisCachePro\Connections\Concerns;

use Throwable;

use RedisCachePro\Exceptions\ConnectionException;

trait SentinelsConnection
{
    /**
     * Returns the current Sentinel's URL.
     *
     * @return string
     */
    public function sentinelUrl()
    {
        return $this->sentinel;
    }

    /**
     * Set the pool for read commands.
     *
     * @return void
     */
    protected function setPool()
    {
        $this->pool = $this->replicas;
    }

    /**
     * Connect to the first available Sentinel.
     *
     * @return void
     */
    protected function connectToSentinels()
    {
        if ($this->sentinel) {
            $this->sentinels[$this->sentinel] = false;
        }

        foreach ($this->sentinels as $url => $state) {
            unset($this->sentinel, $this->primary, $this->replicas, $this->pool);

            if (! is_null($state)) {
                continue;
            }

            try {
                $this->sentinel = $url;
                $this->establishConnections($url);
                $this->setPool();

                return;
            } catch (Throwable $error) {
                $this->sentinels[$url] = false;

                if ($this->config->debug) {
                    error_log('objectcache.notice: ' . $error->getMessage());
                }
            }
        }

        $message = isset($error)
            ? sprintf('Unable to connect to any valid sentinels [%s]', $error->getMessage())
            : 'Unable to connect to any valid sentinels';

        throw new ConnectionException($message, 0, $error ?? null);
    }

    /**
     * Run a command against Redis Sentinel.
     *
     * @param  string  $name
     * @param  array<mixed>  $parameters
     * @return mixed
     */
    public function command(string $name, array $parameters = [])
    {
        $this->lastCommand = null;

        $isReading = \in_array(\strtoupper($name), $this->readonly);

        // send `alloptions` read requests to the primary node
        if ($isReading && \is_string($parameters[0] ?? null)) {
            $isReading = \strpos($parameters[0], 'options:alloptions') === false;
        }

        $node = $isReading
            ? $this->pool[\array_rand($this->pool)]
            : $this->primary;

        try {
            $result = $node->command($name, $parameters);

            $this->lastCommand = $node->lastCommand;

            return $result;
        } catch (Throwable $th) {
            try {
                $this->connectToSentinels();
            } catch (ConnectionException $ex) {
                throw new ConnectionException($ex->getMessage(), $ex->getCode(), $th);
            }
        }

        $result = $node->command($name, $parameters);

        $this->lastCommand = $node->lastCommand;

        return $result;
    }
}
