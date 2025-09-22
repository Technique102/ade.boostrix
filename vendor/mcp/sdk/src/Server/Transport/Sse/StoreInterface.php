<?php

/*
 * This file is part of the official PHP MCP SDK.
 *
 * A collaboration between Symfony and the PHP Foundation.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mcp\Server\Transport\Sse;

use Symfony\Component\Uid\Uuid;

/**
 * @author Christopher Hertel <mail@christopher-hertel.de>
 */
interface StoreInterface
{
    public function push(Uuid $id, string $message): void;

    public function pop(Uuid $id): ?string;

    public function remove(Uuid $id): void;
}
