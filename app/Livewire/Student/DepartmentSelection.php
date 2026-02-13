<?php

namespace App\Livewire\Student;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\Department;
use App\Models\ResultDep;
use App\Models\System;
use App\Models\Province;
use App\Models\University;
use App\Models\College;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DepartmentSelection extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';
    #[Url]
    public $selectedSystem = '';
    #[Url]
    public $selectedProvince = '';
    #[Url]
    public $selectedUniversity = '';
    #[Url]
    public $selectedCollege = '';

    public $sessionSelectedIds = [];
    public $originalDbIds = [];

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $student = Auth::user()->student;
        if ($student) {
            $this->sessionSelectedIds = ResultDep::where('student_id', $student->id)
                ->orderBy('rank', 'asc')
                ->pluck('department_id')
                ->toArray();
            
            $this->originalDbIds = $this->sessionSelectedIds;
        }
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['search', 'selectedSystem', 'selectedProvince', 'selectedUniversity', 'selectedCollege'])) {
            $this->resetPage();
        }
        
        // Cascading reset
        if ($propertyName === 'selectedProvince') {
            $this->selectedUniversity = '';
            $this->selectedCollege = '';
        }
        if ($propertyName === 'selectedUniversity') {
            $this->selectedCollege = '';
        }
    }

    public function addDepartment($departmentId)
    {
        $student = Auth::user()->student;
        $maxSelections = $student->all_departments ? 50 : 20;

        if (count($this->sessionSelectedIds) >= $maxSelections) {
            $this->dispatch('toast', [
                'type' => 'error', 
                'title' => 'هەڵە',
                'message' => "تۆ ناتوانیت زیاتر لە $maxSelections بەش هەڵبژێریت."
            ]);
            return;
        }

        if (!in_array($departmentId, $this->sessionSelectedIds)) {
            $this->sessionSelectedIds[] = $departmentId;
            $this->dispatch('toast', [
                'type' => 'success', 
                'title' => 'سەرکەوتوو',
                'message' => 'بەشەکە بۆ لیستی ڕێزبەندی زیاد کرا.'
            ]);
        }
    }

    public function removeDepartment($departmentId)
    {
        $this->sessionSelectedIds = array_filter($this->sessionSelectedIds, fn($id) => $id != $departmentId);
        $this->sessionSelectedIds = array_values($this->sessionSelectedIds);
        $this->dispatch('toast', [
            'type' => 'info', 
            'title' => 'ئاگاداری',
            'message' => 'بەشەکە لە لیستی کاتی سڕدراوە.'
        ]);
    }

    public function moveUp($index)
    {
        if ($index > 0) {
            $temp = $this->sessionSelectedIds[$index - 1];
            $this->sessionSelectedIds[$index - 1] = $this->sessionSelectedIds[$index];
            $this->sessionSelectedIds[$index] = $temp;
        }
    }

    public function moveDown($index)
    {
        if ($index < count($this->sessionSelectedIds) - 1) {
            $temp = $this->sessionSelectedIds[$index + 1];
            $this->sessionSelectedIds[$index + 1] = $this->sessionSelectedIds[$index];
            $this->sessionSelectedIds[$index] = $temp;
        }
    }

    public function saveChanges()
    {
        $student = Auth::user()->student;
        $user = Auth::user();

        DB::beginTransaction();
        try {
            $selectedDepartmentId = ResultDep::where('student_id', $student->id)
                ->whereNotNull('result_rank')
                ->value('department_id');

            ResultDep::where('student_id', $student->id)->delete();

            foreach ($this->sessionSelectedIds as $index => $departmentId) {
                ResultDep::create([
                    'user_id' => $user->id,
                    'student_id' => $student->id,
                    'department_id' => $departmentId,
                    'rank' => $index + 1,
                    'result_rank' => $selectedDepartmentId == $departmentId ? $index + 1 : null,
                ]);
            }

            DB::commit();
            $this->originalDbIds = $this->sessionSelectedIds;
            $this->dispatch('toast', [
                'type' => 'success', 
                'title' => 'سەرکەوتوو',
                'message' => 'هەموو ڕێزبەندییەکان بە سەرکەوتوویی پاشەکەوت کران.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('toast', [
                'type' => 'error', 
                'title' => 'هەڵە',
                'message' => 'کێشەیەک ڕوویدا لە کاتی پاشەکەوتکردن.'
            ]);
        }
    }

    public function render()
    {
        $student = Auth::user()->student;

        // Query available departments eligible for this student
        $query = Department::where('status', 1)
            ->where(function($q) use ($student) {
                $q->where('type', $student->type)
                  ->orWhere('type', 'زانستی و وێژەیی');
            })
            ->where(function($q) use ($student) {
                $q->where('sex', $student->gender)
                  ->orWhere('sex', 'هەردووکیان');
            })
            ->where('local_score', '<=', $student->mark);

        // Application filters
        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }
        if ($this->selectedSystem) {
            $query->where('system_id', $this->selectedSystem);
        }
        if ($this->selectedProvince) {
            $query->where('province_id', $this->selectedProvince);
        }
        if ($this->selectedUniversity) {
            $query->where('university_id', $this->selectedUniversity);
        }
        if ($this->selectedCollege) {
            $query->where('college_id', $this->selectedCollege);
        }

        $availableDepartments = $query->with(['university', 'system', 'province', 'college'])
            ->orderBy('local_score', 'desc')
            ->paginate(15);

        // Map session IDs to actual objects for the ranked list
        $allSelected = Department::whereIn('id', $this->sessionSelectedIds)
            ->with(['university', 'system', 'province', 'college'])
            ->get();
        
        $selectedDepartments = collect($this->sessionSelectedIds)->map(function ($id) use ($allSelected) {
            return $allSelected->firstWhere('id', $id);
        })->filter();

        // Filter data
        $systems = System::where('status', 1)->get();
        $provinces = Province::where('status', 1)->get();
        
        $universities = University::where('status', 1)
            ->when($this->selectedProvince, fn($q) => $q->where('province_id', $this->selectedProvince))
            ->when($this->selectedSystem, function($q) {
                $q->whereHas('departments', fn($dq) => $dq->where('system_id', $this->selectedSystem));
            })
            ->get();

        $colleges = College::where('status', 1)
            ->when($this->selectedUniversity, fn($q) => $q->where('university_id', $this->selectedUniversity))
            ->when($this->selectedSystem, function($q) {
                $q->whereHas('departments', fn($dq) => $dq->where('system_id', $this->selectedSystem));
            })
            ->get();

        return view('livewire.student.department-selection', [
            'availableDepartments' => $availableDepartments,
            'selectedDepartments' => $selectedDepartments,
            'systems' => $systems,
            'provinces' => $provinces,
            'universities' => $universities,
            'colleges' => $colleges,
            'maxSelections' => $student->all_departments ? 50 : 20,
            'hasUnsavedChanges' => $this->sessionSelectedIds !== $this->originalDbIds
        ]);
    }
}
