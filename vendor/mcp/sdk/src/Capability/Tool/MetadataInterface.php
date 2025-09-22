<?php

/*
 * This file is part of the official PHP MCP SDK.
 *
 * A collaboration between Symfony and the PHP Foundation.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mcp\Capability\Tool;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
interface MetadataInterface extends IdentifierInterface
{
    public function getDescription(): string;

    /**
     * @return array{
     *   type?: string,
     *   required?: list<string>,
     *   properties?: array<string, array{
     *       type: string,
     *       description?: string,
     *   }>,
     * }
     */
    public function getInputSchema(): array;
}
