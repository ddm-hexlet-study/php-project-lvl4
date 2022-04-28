<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LabelController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Label::class, 'label');
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
        return view('labels.create');
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
            'name' => 'required|max:255|unique:labels',
        ], [
            'unique' => __('validation.label.unique')
        ]);
        if ($validator->fails()) {
            return redirect()->route('labels.create')
                ->withErrors($validator);
        }
        $label = new Label();
        $label->fill($request->all());
        $label->created_at = now();
        $label->save();
        flash(__('flash.label.added'))->info();
        return redirect(route('labels.index'));
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ], [
            'required' => 'Это обязательное поле',
        ]);
        if ($validator->fails()) {
            return redirect()->route('labels.create')
                ->withErrors($validator);
        }
        $label->fill($request->all());
        $label->updated_at = now();
        $label->save();
        flash(__('flash.label.edited'))->info();
        return redirect(route('labels.index'));
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
            flash(__('flash.label.failedRemoved'))->error();
            return redirect()->route('labels.index');
        }
        $label->delete();
        flash(__('flash.label.removed'))->info();
        return redirect()->route('labels.index');
    }
}
