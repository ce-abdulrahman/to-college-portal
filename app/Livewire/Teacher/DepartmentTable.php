<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Department;
use App\Models\System;
use App\Models\Province;
use App\Models\University;
use App\Models\College;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\DB;

class DepartmentTable extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';

    // Filters with URL Persistence
    #[Url(history: true, keep: true)]
    public $search = '';
    
    #[Url(history: true, keep: true)]
    public $system_id = null;
    
    #[Url(history: true, keep: true)]
    public $province_id = null;
    
    #[Url(history: true, keep: true)]
    public $university_id = null;
    
    #[Url(history: true, keep: true)]
    public $college_id = null;
    
    public $limit = 25;

    // Reset pagination when filters update
    public function updatedSearch() { $this->resetPage(); }
    public function updatedSystemId() { 
        $this->resetPage(); 
    }
    public function updatedProvinceId() { 
        $this->university_id = null; 
        $this->college_id = null;
        $this->resetPage(); 
    }
    public function updatedUniversityId() { 
        $this->college_id = null;
        $this->resetPage(); 
    }
    public function updatedCollegeId() { $this->resetPage(); }
    public function updatedLimit() { $this->resetPage(); }

    public function resetFilters()
    {
        $this->reset(['search', 'system_id', 'province_id', 'university_id', 'college_id', 'limit']);
        $this->system_id = null;
        $this->province_id = null;
        $this->university_id = null;
        $this->college_id = null;
    }

    public function render()
    {
        // 1. Core Query for Table
        $query = Department::with(['system', 'province', 'university', 'college'])
            ->where('status', 1);

        if ($this->system_id) {
            $query->where('system_id', $this->system_id);
        }
        
        if ($this->province_id) {
            $query->where('province_id', $this->province_id);
        }

        if ($this->university_id) {
            $query->where('university_id', $this->university_id);
        }

        if ($this->college_id) {
            $query->where('college_id', $this->college_id);
        }

        if ($this->search) {
            $search = $this->search;
            $query->where(function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('name_en', 'like', "%{$search}%")
                    ->orWhereHas('university', function($u) use ($search){
                        $u->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('college', function($c) use ($search){
                        $c->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $departments = $query->latest()->paginate($this->limit);

        // 2. Optimized Dropdown Data (Cascading Filters)
        // We want to filter dropdowns based on ALREADY selected filters to avoid 0 matches.
        
        $systems = System::where('status', 1)->get();
        
        // Provinces should ideally be filtered by System if System is selected
        $provinces = Province::where('status', 1)
            ->when($this->system_id, function($q) {
                $q->whereIn('id', function($sub) {
                    $sub->select('province_id')->from('departments')->where('system_id', $this->system_id)->where('status', 1);
                });
            })->get();
        
        $universities = University::where('status', 1)
            ->when($this->province_id, function($q) {
                $q->where('province_id', $this->province_id);
            })
            ->when($this->system_id, function($q) {
                $q->whereIn('id', function($sub) {
                    $sub->select('university_id')->from('departments')->where('system_id', $this->system_id)->where('status', 1);
                });
            })->get();

        $colleges = College::where('status', 1)
            ->when($this->university_id, function($q) {
                $q->where('university_id', $this->university_id);
            })
            ->when($this->system_id, function($q) {
                $q->whereIn('id', function($sub) {
                    $sub->select('college_id')->from('departments')->where('system_id', $this->system_id)->where('status', 1);
                });
            })->get();

        return view('livewire.teacher.department-table', [
            'departments' => $departments,
            'systems' => $systems,
            'provinces' => $provinces,
            'universities' => $universities,
            'colleges' => $colleges
        ]);
    }
}
