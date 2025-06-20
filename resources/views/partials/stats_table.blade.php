{{-- resources/views/partials/stats_table.blade.php --}}
<style>
    table.table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Tajawal', sans-serif;
        font-size: 0.95rem;
    }

    table.table th,
    table.table td {
        border: 1px solid #dee2e6;
        padding: 10px 12px;
        vertical-align: middle;
        text-align: center;
    }

    table.table thead {
        background-color: #343a40;
        color: white;
    }

    table.table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    table.table tbody tr:hover {
        background-color: #e9ecef;
    }

    h2 {
        font-weight: bold;
        font-size: 1.5rem;
        color: #343a40;
    }

    .text-muted {
        color: #6c757d !important;
    }
</style>

<h2 class="mb-4 text-center">📊 جدول إحصائيات الإعارة - {{ now()->format('F Y') }}</h2>

<table class="table table-bordered text-center">
    <thead class="table-dark">
        <tr>
            <th>التاريخ</th>
                  <th>عدد الطلبات الكلي</th>
                  <th>عدد الطلبات حسب الفئة<br>( قراء ) (موظف)</th>
                    <th>الطلبات المعلقة</th>
                  <th>الطلبات المستجابة حسب الفئة</th>
                  <th>الكتب المعادة <br>( قراء ) (موظف)</th>
                  <th> الكتب المعادة  عدد  الكلي</th>
        </tr>
    </thead>
    <tbody>
        @forelse($dailyStats as $stat)
        <tr>
            <td>{{ $stat['date'] }}</td>
            <td><span >{{ $stat['total_requests'] }}</span></td>
            <td>
                <span >{{ $stat['reader_requests'] }}</span> - 
                <span c>{{ $stat['employee_requests'] }}</span>
            </td>
            <td>{{ $stat['pending_requests'] ?? 0 }}</td>

            <td>
                <span >{{ $stat['approved_readers'] }}</span> - 
                <span >{{ $stat['approved_employees'] }}</span>
            </td>
            <td>
                <span >{{ $stat['returned_readers'] }}</span> - 
                <span >{{ $stat['returned_employees'] }}</span>
            </td>
            <td><span >{{ $stat['total_returned'] }}</span></td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-muted">لا توجد بيانات للعرض</td>
        </tr>
        @endforelse
    </tbody>
</table>