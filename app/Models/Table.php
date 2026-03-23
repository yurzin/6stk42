<?php

namespace App\Models;

enum Table: string
{
    case Users = 'users';
    case Photos = 'photos';
    case Videos = 'videos';
    case Notes = 'notes';
}