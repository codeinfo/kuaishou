<?php

/**
 * This file is part of the Codeinfo\LaravelKuaishou.
 *
 * (c) codeinfo <nanye@codeinfo.cn>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Codeinfo\LaravelKuaishou\Kernel\Contracts;

use Illuminate\Container\Container;

interface ServiceProviderInterface
{
    public function register(Container $app);
}
