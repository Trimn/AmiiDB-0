<?php
namespace App\Models;

use Illuminate\Support\Facades\Auth;

trait Loggable
{
    public function save(array $options = []) {
        // $dirty = $this->getDirty();
        $from = json_encode($this->getOriginal());
        $to = $this->toJson();
        $user = Auth::user();
        if($user) {
            $name = $user->name;
        }
        else {
            $name = 'SYSTEM';
        }
        Action::create([
            'user' => $name,
            'table' => $this->table,
            'from' => $from,
            'to' => $to
        ]);
        return parent::save($options);
    }

    public function delete() {
        $from = json_encode($this->getOriginal());
        $to = json_encode('');
        $user = Auth::user();
        if($user) {
            $name = $user->name;
        }
        else {
            $name = 'SYSTEM';
        }
        Action::create([
            'user' => $name,
            'table' => $this->table,
            'from' => $from,
            'to' => $to
        ]);
        return parent::delete();
    }

}

?>