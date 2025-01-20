<?php

namespace User;

use \User\Capability;

class Role
{
    public const GUEST = 1;
    public const ADMIN = 2;
    public const CONTEST_CREATOR = 3;
    public const CONTESTANT = 4;
    private static $roles = [];

    public static function init(bool $force = false): void
    {
        if (!empty(self::$roles) || $force) {
            return;
        }
        $db = new \mc\sql\database(\config::dsn);
        $roles = $db->select(\meta\roles::__name__);
        foreach ($roles as $role) {
            self::$roles[$role[\meta\roles::ID]] = $role;
            self::$roles[$role[\meta\roles::ID]]['capabilities'] = self::capabilities($role[\meta\roles::ID]);
        }
    }

    private static function capabilities(int $roleId): array {
        $db = new \mc\sql\database(\config::dsn);
        return $db->select(
            \meta\role_capabilities::__name__, 
            [\meta\role_capabilities::CAPABILITY_ID],
            [\meta\role_capabilities::ROLE_ID => $roleId]
        );
    }

    public static function getRole(int $roleId): array {
        self::init();
        return self::$roles[$roleId];
    }

    public static function hasCapability(int $roleId, int $capabilityId): bool {
        self::init();
        return in_array($capabilityId, self::getCapabilities($roleId));
    }

    public static function getCapabilities(int $roleId): array {
        self::init();
        return self::$roles[$roleId]['capabilities'];
    }

    public static function getRoleName(int $roleId): string {
        self::init();
        return self::$roles[$roleId][\meta\roles::NAME];
    }

    public static function hasCapabilityByName(string $roleName, string $capabilityName): bool {
        $role = self::getRoleByName($roleName);
        if(empty($role)) {
            return false;
        }
        $capability = Capability::getCapabilityByName($capabilityName);
        if(empty($capability)) {
            return false;
        }
        return self::hasCapability($role[\meta\roles::ID], $capability[\meta\capabilities::ID]);
    }

    public static function getRoleByName(string $roleName): array {
        self::init();
        foreach (self::$roles as $role) {
            if ($role[\meta\roles::NAME] === $roleName) {
                return $role;
            }
        }
        return [];
    }
}
