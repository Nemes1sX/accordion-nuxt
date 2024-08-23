<?php

namespace App\Rules;

use App\Models\Task;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TaskLimitRule implements ValidationRule
{

    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $tasks = Task::where('user_id', $this->user->id)->count();

       /* if($tasks >= 5) {
            dd("User can't have more than 5 tasks");
        }*/
        if ($tasks >= 5) {
            $fail("User can't have more than 5 tasks");
        }
    }
}
