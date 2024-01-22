<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailHistroyModel extends Model
{
    use HasFactory;

    protected $table = 'sent_mail';

    public $fillable = ['*'];
}
