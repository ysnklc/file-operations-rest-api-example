<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class File
 * @package App\Model
 *
 * @property int id
 * @property string name
 * @property string mime_type
 * @property string file_path
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class File extends Model
{
    use SoftDeletes;

    protected $table = 'files';
    protected $dates = ['deleted_at'];

}
