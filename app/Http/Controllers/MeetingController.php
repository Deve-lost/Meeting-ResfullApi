<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Meeting;

class MeetingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index','show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $meetings = Meeting::latest()->get();

        foreach ($meetings as $meeting) {
            $meeting->view_meeting = [
                'href' => 'api/v1/meeting/' . $meeting->id,
                'method' => 'GET'
            ];
        }

        $response = [
            'msg' => 'List Meeting!',
            'meetings' => $meetings
        ];

        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate
        $request->validate([
            'title' => 'required|min:2|max:191',
            'description' => 'required',
            'time' => 'required',
            'user_id' => 'required',
        ]);

        // Request
        $title = $request->title;
        $description = $request->description;
        $time = $request->time;
        $user_id = $request->user_id;

        $meeting = new Meeting([
            'time' => $time,
            'title' => $title,
            'description' => $description
        ]);

        if ($meeting->save()) {
            $meeting->users()->attach($user_id);
            $meeting->view_meeting = [
                'href' => 'api/v1/meeting/'.$meeting->id,
                'method' => 'GET'
            ];

            $message = [
                'msg' => 'Meeting Created!',
                'meeting' => $meeting
            ];

            return response()->json($message, 201);
        }

        $response = [
            'msg' => 'Error during creation!'
        ];

        return response()->json($response, 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $meeting = Meeting::with('users')->where('id',$id)->firstOrFail();
        $meeting->view_meeting = [
            'href' => 'api/v1/meeting',
            'method' => 'GET'
        ];

        $response = [
            'msg' => 'Meeting information!',
            'meeting' => $meeting
        ];

        return response()->json($response, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate
        $request->validate([
            'title' => 'required|min:2|max:191',
            'description' => 'required',
            'time' => 'required',
            'user_id' => 'required',
        ]);

        // Request
        $title = $request->title;
        $description = $request->description;
        $time = $request->time;
        $user_id = $request->user_id;

        $meeting = Meeting::with('users')->findOrFail($id);

        if (!$meeting->users()->where('users.id', $user_id)->first()) {
            return response()->json(['msg' => 'User tidak sama, Update Not Successful!'], 401);
        }

        $meeting->time = $time;
        $meeting->title = $title;
        $meeting->description = $description;

        if (!$meeting->update()) {
            return response()->json([
                'msg' => 'Error during update!'
            ], 404);
        }

        $meeting->view_meeting = [
            'href' => 'api/v1/meeting/'.$meeting->id,
            'method' => 'GET'
        ];

        $response = [
            'msg' => 'Meeting Updated!',
            'meeting' => $meeting
        ];

        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $meeting = Meeting::findOrFail($id);
        $users = $meeting->user;
        $meeting->users()->detach();

        if (!$meeting->delete()) {
            foreach ($users as $user) {
                $meeting->user()->attach($user);
            }

            return response()->json([
                'msg' => 'Deletion Failed!'
            ], 404);
        }

        $response = [
            'msg' => 'Meeting Deleted!',
            'create' => [
                'href' => 'api/v1/meeting',
                'method' => 'POST',
                'params' => 'title, description, time'
            ],
        ];

        return response()->json($response, 200);
    }
}
