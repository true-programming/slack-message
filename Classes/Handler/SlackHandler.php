<?php

declare(strict_types=1);

namespace Trueprogramming\SlackMessage\Handler;

/*
 * This file is part of TYPO3 CMS-based extension "slack_message" by true-programming.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use Monolog\Handler\SlackWebhookHandler;
use Monolog\Level;
use Monolog\Logger;
use TYPO3\CMS\Core\Core\Environment;

class SlackHandler
{
    public function __invoke(string $customSlackChannel = null, Level $handlerLevel = Level::Critical): ?Logger
    {
        $url = getenv('SLACK_WEBHOOK_URL');

        if ($customSlackChannel) {
            $channel = $customSlackChannel;
        } else {
            $channel = getenv('SLACK_WEBHOOK_CHANNEL');
        }

        $username = getenv('SLACK_WEBHOOK_USERNAME');

        if ($url === false || $url === '' || $channel === false || $channel === '' || $username === false || $username === '') {
            return null;
        }

        $username = $username . ' - ' . Environment::getContext();

        $logger = new Logger('slack_logger');
        $logger->pushHandler(
            new SlackWebhookHandler(
                $url,
                $channel,
                $username,
                true,
                'rotating_light',
                false,
                true,
                $handlerLevel->value
            )
        );

        return $logger;
    }
}
