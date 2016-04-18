<?php

namespace App\Models;

use Cache;

/**
 * Class Role
 * Modèle gérant les opérations sur les rôles
 * @package App\Models
 */
class Role extends MainModel
{
    protected $table = 'role';

    /**
     * Retourne le nom du rôle.
     *
     * @param int $id id du rôle
     *
     * @return string le nom du role
     */
    public static function getRoleName($id = 0)
    {
        $role = new self();
        if ($role->cache->exists('role.name.'.$id)) {
            $roleName = $role->cache->get('role.name.'.$id);
        } else {
            $roleName = $role->load(['id=?', $id])->query[0]->name;
            $role->cache->set('role.name.'.$id, $roleName, 60 * 60);
        }

        return $roleName;
    }

    /**
     * Vérifie que le rôle existe.
     *
     * @param int $id L'id du rôle
     *
     * @return bool
     */
    public static function isRole($id = 0)
    {
        $role = new self();
        $role->count(['id=?', $id]);
        if ($role->query) {
            return true;
        }

        return false;
    }
}
