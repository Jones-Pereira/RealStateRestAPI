<?php

namespace App\Constants;

class RouteActions
{
    const CRUD_ACTIONS_ADMIN = ['index', 'store', 'show', 'update', 'destroy'];

    const CRUD_ACTIONS_MANAGER = ['index', 'store', 'show', 'update'];

    const CRUD_ACTIONS_EDITOR = ['index', 'show', 'update'];

    const CRUD_ACTIONS_VIEWER = ['index', 'show'];
}
