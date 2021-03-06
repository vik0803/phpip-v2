<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Task extends Model
{
    protected $table = 'task';
    public $timestamps = false;
    protected $hidden = ['creator', 'updated', 'updater'];
    protected $guarded = ['id', 'creator', 'updated', 'updater'];

    // use \Venturecraft\Revisionable\RevisionableTrait;
    // protected $revisionEnabled = true;
    // protected $revisionCreationsEnabled = true;
    // protected $revisionCleanup = true; //Remove old revisions (works only when used with $historyLimit)
    // protected $historyLimit = 500; //Maintain a maximum of 500 changes at any point of time, while cleaning up old revisions.

    public function info()
    {
        return $this->belongsTo('App\EventName', 'code');
    }

    public function trigger()
    {
        return $this->belongsTo('App\Event', 'trigger_id');
    }

    public static function getUsersOpenTaskCount()
    {
        $userid = Auth::user()->id;
        $role = Auth::user()->default_role;
        $selectQuery = Task::join('event as e', 'task.trigger_id', '=', 'e.id')
            ->join('matter as m', 'e.matter_id', '=', 'm.id')
            ->select(
                DB::raw('count(*) as no_of_tasks'),
                DB::raw('DATE_FORMAT(MIN(task.due_date), "%d/%m/%Y") as urgent_date'),
                DB::raw('MIN(task.due_date) as posix_urgent_date'),
                DB::raw('ifnull(task.assigned_to, m.responsible) as login')
            )
            ->where('m.dead', '=', 0)
            ->where('task.done', 0)
            ->groupby('login');

        if ($role == 'CLI') {
            $selectQuery->join('matter_actor_lnk as cli', DB::raw("ifnull(m.container_ID,m.ID)", '=', "cli.matter_ID"))
            ->where([[ 'cli.role','CLI'],['cli.actor_id', $userid]]);
        }
        return $selectQuery->get();
    }
}
