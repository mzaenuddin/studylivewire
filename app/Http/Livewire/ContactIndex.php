<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

use App\Contact;


class ContactIndex extends Component
{


    public $data;
    public $statusUpdate = false;
    public $paginate= 5;
    public $search;

    use WithPagination;

    protected $listeners = [
        'contactStored' => 'handleStored',
        'contactUpdated' => 'handleUpdated'
    ];

    protected $updatesQueryString = ['search'];

    public function mount()
    {
        $this->search = request()->query('search', $this->search);
    }

    public function render()
    {
        //$this->$data = Contact::latest()->get(); 

        return view('livewire.contact-index', [
            'contacts' =>  $this->search === null ?
               Contact::latest()->paginate($this->paginate) : 
               Contact::latest()->where('name', 'like', '%' . $this->search . '%')->paginate($this->paginate)
        ]);
    }

    public function destroy($id)
    {
        if($id)
        {
            $data = Contact::find($id);
            $data->delete();
            session()->flash('message', 'was Deleted!');

        }
    }

    public function getContact($id)
    {
        $this->statusUpdate = true;
        $contact = Contact::find($id);
        $this->emit('getContact', $contact);
    }

    public function handleStored($contact)
    {
        // dd($contact);
        session()->flash('message', 'Contact'.$contact['name'].' was Stored!');

    }

    public function handleUpdated($contact)
    {
        // dd($contact);
        session()->flash('message', 'Contact'.$contact['name'].' was Update!');

    }

}
