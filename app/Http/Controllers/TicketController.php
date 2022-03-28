<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class TicketController extends Controller
{

    public function indexticket(Request $request){
        // var_dump($request->user()->id);
        // var_dump($request->query());
        $searchbox = $request->query('searchbox');
        if($searchbox == ''){
            $tickets = Ticket::all();
        }else{
            $tickets = Ticket::where('name','like','%'.$searchbox.'%')->get();
        }
        return view('tickets.index' , ['tickets'=>$tickets]);
    }

        /**
     * Store a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request){

        var_dump($request->input());
        $name = $request->input('name');
        $subject = $request->input('subject');
        $label = $request->input('label');
        $assignee_id = $request->input('assignee_id');
        $submitter_id = $request->user()->id;
        $priority = $request->input('priority');
        $status = $request->input('status');
        var_dump([$name,$subject,$label,$assignee_id,$priority,$status,$submitter_id]);

        $ticket = new Ticket;
        $ticket->name = $name;
        $ticket->subject = $subject;
        $ticket->label = $label;
        $ticket->assignee_id = $assignee_id;
        $ticket->priority = $priority;
        $ticket->status = $status;
        $ticket->submitter_id = $submitter_id;
        $ticket->save();



        return Redirect::route('tickets.index');
        // $name = $request->input('name');
        //
    }

    public function show($id, Request $request){
        $ticket = Ticket::where('id', $id)->with(['comments.user'])->first();
        $users = User::all();
        // var_dump($ticket->comments);
        // var_dump($book);
        //  var_dump($ticket);
        return view('tickets.show',['ticket'=>$ticket, 'users'=>$users]);

    }


    public function create(Request $request){
        // var_dump($request->user()->id);
        $users = User::all();
        return view('tickets.create',['users'=>$users]);
    }


    public function update($id, Request $request){
        // var_dump($id);
        // var_dump($request->input());
        $ticket = Ticket::find($id);

        $ticket->name = $request->input('name');
        $ticket->subject = $request->input('subject');
        $ticket->label = $request->input('label');

        if(Auth::user()->role == 'Admin'){
            $ticket->assignee_id = $request->input('assignee_id');
            $ticket->priority = $request->input('priority');
            $ticket->status = $request->input('status');
        }

        $ticket->save();

        // die();
        return Redirect::route('tickets.index');
    }

    public function destroy($id, Request $request){
        Ticket::destroy($id);
        var_dump($id);
        return Redirect::route('tickets.index');

    }

}
