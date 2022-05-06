<?php

namespace App\Http\Controllers;

use App\Models\TaskStatus;
use Illuminate\Http\Request;

class TaskStatusController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(TaskStatus::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $statuses = TaskStatus::all()->sort();
        return view('task_statuses.index', compact('statuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task_status = new TaskStatus();
        return view('task_statuses.create', compact('task_status'));
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
            'name' => 'required|max:255|unique:task_statuses',
        ], [
            'unique' => __('validation.status.unique')
        ]);
        $status = new TaskStatus($data);
        $status->save();
        flash(__('flash.statuses.added'))->info();
        return redirect()->route('task_statuses.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TaskStatus  $taskStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(TaskStatus $taskStatus)
    {
        return view('task_statuses.edit', compact('taskStatus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TaskStatus  $taskStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TaskStatus $taskStatus)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
        ]);
        $taskStatus->fill($data);
        $taskStatus->save();
        flash(__('flash.statuses.edited'))->info();
        return redirect()->route('task_statuses.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TaskStatus  $taskStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(TaskStatus $taskStatus)
    {
        if ($taskStatus->tasks()->exists()) {
            flash(__('flash.statuses.failedRemoved'))->error();
            return redirect()->route('task_statuses.index');
        }
        $taskStatus->delete();
        flash(__('flash.statuses.removed'))->info();
        return redirect()->route('task_statuses.index');
    }
}
