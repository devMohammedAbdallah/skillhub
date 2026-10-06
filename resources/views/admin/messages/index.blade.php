@extends('admin.layout')

@section('title', 'الرسائل')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <h1 class="h3 mb-0 page-title">الرسائل</h1>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>رسائل التواصل معنا</span>
            <span class="badge text-bg-success">{{ $messages->count() }}</span>
        </div>
        <div class="card-body p-0">
            @if ($messages->isEmpty())
                <div class="p-4 text-center text-muted">
                    لا توجد رسائل بعد.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 70px;">#</th>
                                <th scope="col">الاسم</th>
                                <th scope="col">البريد الإلكتروني</th>
                                <th scope="col">الرسالة</th>
                                <th scope="col" style="width: 140px;">التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($messages as $message)
                                <tr>
                                    <td>{{ $message->id }}</td>
                                    <td class="fw-semibold">{{ $message->name }}</td>
                                    <td>
                                        <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                                    </td>
                                    <td class="small" style="white-space: pre-wrap; max-width: 420px;">{{ $message->message }}</td>
                                    <td class="text-nowrap">{{ $message->created_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
