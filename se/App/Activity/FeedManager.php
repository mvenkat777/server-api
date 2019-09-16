<?php

namespace Platform\App\Activity;

class FeedManager
{
    public static function getUserFeed($userId, array $type = ['all'])
    {
    }

    public static function getActivityFeed($userId)
    {
    }

    public static function getNotificationFeed($userId)
    {
    }

    // I think we should take array $entityIds and one userId
    // Coz only auth user can subscribe to multiple entity
    public static function subscribe(array $userIds, $entityId)
    {
    }

    public static function unsubscribe(array $userIds, $entityId)
    {
    }
}

