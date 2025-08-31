@extends('layouts.admin')

@section('title', 'نتائج الامتحانات')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">نتائج الامتحانات</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#filterModal">
                            <i class="fas fa-filter"></i> تصفية النتائج
                        </button>
                        <a href="{{ route('exams.results.export') }}" class="btn btn-success">
                            <i class="fas fa-download"></i> تصدير Excel
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    {{-- Search Form --}}
                    <form method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="البحث باسم الطالب أو البريد الإلكتروني" 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="exam_id" class="form-control">
                                    <option value="">جميع الامتحانات</option>
                                    @if(isset($exams))
                                        @foreach($exams as $exam)
                                            <option value="{{ $exam->id }}" 
                                                {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                                                {{ $exam->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="result_status" class="form-control">
                                    <option value="">جميع النتائج</option>
                                    <option value="passed" {{ request('result_status') === 'passed' ? 'selected' : '' }}>
                                        نجح
                                    </option>
                                    <option value="failed" {{ request('result_status') === 'failed' ? 'selected' : '' }}>
                                        رسب
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> بحث
                                </button>
                                <a href="{{ route('exams.results') }}" class="btn btn-secondary">
                                    <i class="fas fa-refresh"></i> إعادة تعيين
                                </a>
                            </div>
                        </div>
                    </form>

                    {{-- Results Table --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم الطالب</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>رقم الهاتف</th>
                                    <th>اسم المدرسة</th>
                                    <th>التخصص</th>
                                    <th>اسم الامتحان</th>
                                    <th>الدرجة المحصولة</th>
                                    <th>الدرجة الكاملة</th>
                                    <th>النسبة المئوية</th>
                                    <th>النتيجة</th>
                                    <th>تاريخ التسليم</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($results as $result)
                                    <tr>
                                        <td>{{ $loop->iteration + ($results->currentPage() - 1) * $results->perPage() }}</td>
                                        <td>
                                            <strong>{{ $result->user->name }}</strong>
                                        </td>
                                        <td>{{ $result->user->email }}</td>
                                        <td>{{ $result->user->phone ?? 'غير محدد' }}</td>
                                        <td>{{ $result->user->school_name ?? 'غير محدد' }}</td>
                                        <td>
                                            <span class="badge badge-info">
                                                {{ $result->user->field->name ?? 'غير محدد' }}
                                            </span>
                                        </td>
                                        <td>{{ $result->exam->name }}</td>
                                        <td>
                                            <strong class="text-primary">{{ number_format($result->score, 2) }}</strong>
                                        </td>
                                        <td>{{ number_format($result->exam->total_grade, 2) }}</td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar 
                                                    @if($result->percentage >= 80) bg-success
                                                    @elseif($result->percentage >= 60) bg-warning
                                                    @else bg-danger
                                                    @endif"
                                                    style="width: {{ $result->percentage }}%;">
                                                    {{ number_format($result->percentage, 1) }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($result->score >= $result->exam->pass_grade)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check"></i> نجح
                                                </span>
                                            @else
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-times"></i> رسب
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $result->submitted_at->format('Y-m-d') }}</small><br>
                                            <small class="text-muted">{{ $result->submitted_at->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-info" 
                                                        onclick="viewAttemptDetails({{ $result->id }})">
                                                    <i class="fas fa-eye"></i> عرض
                                                </button>
                                                <a href="{{ route('exams.attempt-report', $result->id) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-file-pdf"></i> تقرير
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13" class="text-center">
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle"></i>
                                                لا توجد نتائج متاحة
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center">
                        {{ $results->appends(request()->query())->links() }}
                    </div>
                </div>

                {{-- Summary Statistics --}}
                <div class="card-footer">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $results->total() }}</h3>
                                    <p>إجمالي المحاولات</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $results->where('score', '>=', function($result) { return $result->exam->pass_grade; })->count() }}</h3>
                                    <p>نجح</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $results->where('score', '<', function($result) { return $result->exam->pass_grade; })->count() }}</h3>
                                    <p>رسب</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-times"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ number_format($results->avg('percentage'), 1) }}%</h3>
                                    <p>متوسط الدرجات</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Attempt Details Modal --}}
<div class="modal fade" id="attemptDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تفاصيل المحاولة</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="attemptDetailsContent">
                <div class="text-center">
                    <i class="fas fa-spinner fa-spin"></i> جاري التحميل...
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
function viewAttemptDetails(attemptId) {
    $('#attemptDetailsModal').modal('show');
    $('#attemptDetailsContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> جاري التحميل...</div>');
    
    $.get(`/admin/exams/attempts/${attemptId}/details`, function(data) {
        $('#attemptDetailsContent').html(data.html);
    }).fail(function() {
        $('#attemptDetailsContent').html('<div class="alert alert-danger">حدث خطأ أثناء تحميل البيانات</div>');
    });
}
</script>
@endsection