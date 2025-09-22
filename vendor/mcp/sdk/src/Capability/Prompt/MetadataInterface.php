<?php

/*
 * This file is part of the official PHP MCP SDK.
 *
 * A collaboration between Symfony and the PHP Foundation.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mcp\Capability\Prompt;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
interface MetadataInterface extends IdentifierInterface
{
    public function getDescription(): ?string;

    /**
     * @return list<array{
     *   name: string,
     *   description?: string,
     *   required?: bool,
     * }>
     */
    public function getArguments(): array;
}
