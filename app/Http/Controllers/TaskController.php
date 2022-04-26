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
    public function __construct()
    {
        $this->authorizeResource(Task::class, 'task');
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
        $users = User::all()->keyBy('id')->map(fn($item) => $item->name)->toArray();
        $statuses = TaskStatus::all()->keyBy('id')->map(fn($item) => $item->name)->toArray();
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
        return view('tasks.create', compact('users', 'statuses', 'labels'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'status_id' => 'required',
        ], [
            'required' => 'Это обязательное поле',
        ]);
        if ($validator->fails()) {
            return redirect()->route('tasks.create')
                ->withErrors($validator);
        }
        $task = new Task();
        $task->fill($request->all());
        $task->created_at = now();
        $task->created_by_id = Auth::id();
        $task->save();
        if ($request->has('labels')) {
            $task->labels()->attach($request->input('labels'));
        }
        flash(__('flash.task.added'))->info();
        return redirect(route('tasks.index'));
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
        $users = User::all()->keyBy('id')->map(fn($item) => $item->name)->toArray();
        $statuses = TaskStatus::all()->keyBy('id')->map(fn($item) => $item->name)->toArray();
        $labels = Label::all()->keyBy('id')->map(fn($item) => $item->name)->toArray();
        return view('tasks.edit', compact('users', 'statuses', 'task', 'labels'));
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'status_id' => 'required',
        ], [
            'required' => 'Это обязательное поле',
        ]);
        if ($validator->fails()) {
            return redirect()->route('tasks.create')
                ->withErrors($validator);
        }
        $task->fill($request->all());
        $task->updated_at = now();
        $task->save();
        flash(__('flash.task.edited'))->info();
        return redirect(route('tasks.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();
        flash(__('flash.task.removed'))->info();
        return redirect(route('tasks.index'));
    }
}
