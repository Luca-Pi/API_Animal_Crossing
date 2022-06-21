<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Places extends Model
{
    use HasFactory;
    /*            $table->id();
            $table->string('label');
            $table->binary('image');
            $table->text('description');
            $table->timestamps();*/ 
    protected $fillable = [
        'label',
        'image',
        'description',
    ];


}
