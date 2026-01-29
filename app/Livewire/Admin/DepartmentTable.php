<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Department;
use App\Models\System;
use App\Models\Province;
use App\Models\University;
use App\Models\College;

class DepartmentTable extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';

    // Filters
    public $search = '';
    public $system_id = '';
    public $province_id = '';
    public $university_id = '';
    public $college_id = '';
    public $limit = 25;

    // Reset pagination when filters update
    public function updatedSearch() { $this->resetPage(); }
    public function updatedSystemId() { $this->resetPage(); }
    public function updatedProvinceId() { 
        $this->university_id = ''; 
        $this->college_id = '';
        $this->resetPage(); 
    }
    public function updatedUniversityId() { 
        $this->college_id = '';
        $this->resetPage(); 
    }
    public function updatedCollegeId() { $this->resetPage(); }
    public function updatedLimit() { $this->resetPage(); }

    public function resetFilters()
    {
        $this->reset(['search', 'system_id', 'province_id', 'university_id', 'college_id', 'limit']);
    }

    public function render()
    {
        $query = Department::with(['system', 'province', 'university', 'college']);

        $query->when($this->system_id, function ($q) {
            $q->where('system_id', $this->system_id);
        });

        $query->when($this->province_id, function ($q) {
            $q->where('province_id', $this->province_id);
        });

        $query->when($this->university_id, function ($q) {
            $q->where('university_id', $this->university_id);
        });

        $query->when($this->college_id, function ($q) {
            $q->where('college_id', $this->college_id);
        });

        $query->when($this->search, function ($q) {
            $search = $this->search;
            $q->where(function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('name_en', 'like', "%{$search}%")
                    ->orWhereHas('university', function($u) use ($search){
                        $u->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('college', function($c) use ($search){
                        $c->where('name', 'like', "%{$search}%");
                    });
            });
        });

        $departments = $query->latest()->paginate($this->limit);

        // Fetch Dropdown Data
        // Optimization: Filter Universities based on selected Province, Colleges on University
        // This makes the dropdowns dynamic and smarter.
        
        $systems = System::all(); // usually small list
        $provinces = Province::all(); 
        
        $universities = University::when($this->province_id, function($q) {
            $q->where('province_id', $this->province_id);
        })->get();

        $colleges = College::when($this->university_id, function($q) {
            $q->where('university_id', $this->university_id);
        })->get();

        return view('livewire.admin.department-table', [
            'departments' => $departments,
            'systems' => $systems,
            'provinces' => $provinces,
            'universities' => $universities,
            'colleges' => $colleges
        ]);
    }
}
