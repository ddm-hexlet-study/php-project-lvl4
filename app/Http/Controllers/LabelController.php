<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Label::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $labels = Label::all()->sort();
        return view('labels.index', compact('labels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $label = new Label();
        return view('labels.create', compact('label'));
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
            'name' => 'required|max:255|unique:labels',
            'description' => 'nullable'
        ], [
            'unique' => __('validation.label.unique')
        ]);
        $label = new Label($data);
        $label->save();
        flash(__('flash.labels.added'))->info();
        return redirect()->route('labels.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function edit(Label $label)
    {
        return view('labels.edit', compact('label'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Label $label)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable'
        ]);
        $label->fill($data);
        $label->save();
        flash(__('flash.labels.edited'))->info();
        return redirect()->route('labels.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function destroy(Label $label)
    {
        if ($label->tasks()->first() !== null) {
            flash(__('flash.labels.failedRemoved'))->error();
            return redirect()->route('labels.index');
        }
        $label->delete();
        flash(__('flash.labels.removed'))->info();
        return redirect()->route('labels.index');
    }
}
