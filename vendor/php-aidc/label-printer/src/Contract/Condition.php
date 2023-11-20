<?php

/**
 * This file is part of Appwilio LabelPrinter package.
 *
 *  © appwilio (https://appwilio.com)
 *  © JhaoDa (https://github.com/jhaoda)
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace PhpAidc\LabelPrinter\Contract;

interface Condition
{
    public function apply(Label $label): Label;
}
