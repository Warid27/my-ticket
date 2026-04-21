<?php

require_once 'app/core/BaseModel.php';

class VenueModel extends BaseModel
{
    protected string $table = 'venues';
    protected array $fillable = ['name', 'address', 'capacity'];
}