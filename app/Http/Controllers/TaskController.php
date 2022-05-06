<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class TaskController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Task::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters([
                AllowedFilter::exact('status_id'),
                AllowedFilter::exact('assigned_to_id'),
                AllowedFilter::exact('created_by_id')
            ])->paginate(10);
        $users = User::all()->pluck('name')->toArray();
        $statuses = TaskStatus::all()->pluck('name')->toArray();
        return view('tasks.index', compact('tasks', 'users', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all()->keyBy('id')->map(fn($item) => $item->name)->toArray();
        $statuses = TaskStatus::all()->keyBy('id')->map(fn($item) => $item->name)->toArray();
        $labels = Label::all()->keyBy('id')->map(fn($item) => $item->name)->toArray();
        $task = new Task();
        return view('tasks.create', compact('users', 'statuses', 'labels', 'task'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:255|unique:tasks',
            'description' => 'nullable',
            'status_id' => 'required',
            'assigned_to_id' => 'nullable'
        ], [
            'unique' => __('validation.task.unique')
        ]);
        $task = new Task($data);
        $task->createdBy()->associate(Auth::id());
        $task->save();
        if ($request->has('labels')) {
            $task->labels()->attach($request->input('labels'));
        }
        flash(__('flash.tasks.added'))->info();
        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        $users = User::all()->pluck('name')->toArray();
        $statuses = TaskStatus::all()->pluck('name')->toArray();
        $labels = Label::all()->pluck('name')->toArray();
        return view('tasks.edit', compact('task', 'users', 'statuses', 'labels'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'status_id' => 'required',
            'assigned_to_id' => 'nullable'
        ]);
        $task->fill($data);
        $task->save();
        if ($request->has('labels')) {
            $task->labels()->detach();
            $task->labels()->attach($request->input('labels'));
        }
        flash(__('flash.tasks.edited'))->info();
        return redirect()->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->labels()->detach();
        $task->delete();
        flash(__('flash.tasks.removed'))->info();
        return redirect()->route('tasks.index');
    }
}
