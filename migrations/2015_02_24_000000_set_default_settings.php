<?php

/*
 * This file is part of DuRoom.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

use DuRoom\Database\Migration;

return Migration::addSettings([
    'duroom-tags.max_primary_tags' => '1',
    'duroom-tags.min_primary_tags' => '1',
    'duroom-tags.max_secondary_tags' => '3',
    'duroom-tags.min_secondary_tags' => '0',
]);
