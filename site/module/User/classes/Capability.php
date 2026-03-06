<?php

namespace User;

class Capability
{
    public static $capabilities = [];

    public static function init(bool $force = false): void
    {
        if (!empty(self::$capabilities) || $force) {
            return;
        }
        self::$capabilities = \config::$db->Select(\meta\capabilities::__name__);
    }

    public static function getName(int $capabilityId): string{
        self::init();
        return self::$capabilities[$capabilityId][\meta\capabilities::NAME];
    }

    public static function getCapabilityByName(string $capabilityName): array {
        self::init();
        foreach(self::$capabilities as $capability) {
            if($capability[\meta\capabilities::NAME] == $capabilityName) {
                return $capability;
            }
        }
        return [];
    }
}
