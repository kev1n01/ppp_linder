<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Table Columns
    |--------------------------------------------------------------------------
    */

    'column.name' => 'Nombre',
    'column.guard_name' => 'Guard',
    'column.roles' => 'Roles',
    'column.permissions' => 'Permisos',
    'column.updated_at' => 'Actualizado el',

    /*
    |--------------------------------------------------------------------------
    | Form Fields
    |--------------------------------------------------------------------------
    */

    'field.name' => 'Nombre',
    'field.guard_name' => 'Guard',
    'field.permissions' => 'Permisos',
    'field.select_all.name' => 'Seleccionar todos',
    'field.select_all.message' => 'Habilitar todos los permisos actualmente <span class="text-primary font-medium">habilitados</span> para este rol',

    /*
    |--------------------------------------------------------------------------
    | Navigation & Resource
    |--------------------------------------------------------------------------
    */

    'nav.group' => 'Gestión de usuarios',
    'nav.role.label' => 'Roles',
    'nav.role.icon' => 'heroicon-o-shield-check',
    'resource.label.role' => 'Rol',
    'resource.label.roles' => 'Roles',

    /*
    |--------------------------------------------------------------------------
    | Section & Tabs
    |--------------------------------------------------------------------------
    */

    'section' => 'Entidades',
    'resources' => 'Recursos',
    'widgets' => 'Widgets',
    'pages' => 'Páginas',
    'custom' => 'Permisos personalizados',

    /*
    |--------------------------------------------------------------------------
    | Messages
    |--------------------------------------------------------------------------
    */

    'forbidden' => 'Usted no tiene permiso de acceso',

    /*
    |--------------------------------------------------------------------------
    | Resource Permissions' Labels
    |--------------------------------------------------------------------------
    */

    'resource_permission_prefixes_labels' => [
        'view' => 'Ver',
        'view_any' => 'Ver todos',
        'create' => 'Crear',
        'update' => 'Actualizar',
        'delete' => 'Eliminar uno',
        'delete_any' => 'Eliminar cualquier',
        'force_delete' => 'Forzar elminación',
        'force_delete_any' => 'Forzar eliminación de varios',
        'restore' => 'Restaurar un registro',
        'reorder' => 'Reordenar',
        'restore_any' => 'Restaurar varios registros',
        'replicate' => 'Replicar',
    ],
];
