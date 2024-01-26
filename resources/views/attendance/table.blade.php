@php
    use Carbon\Carbon;
    use Carbon\CarbonPeriod;
@endphp
<table class="table table-bordered">
    <thead>
    <tr>
        <th colspan="{{ count(CarbonPeriod::create(Carbon::parse($selectedMonth)->startOfMonth(), Carbon::parse($selectedMonth)->endOfMonth())) + 4 }}" class="text-center">
            {{ Carbon::parse($selectedMonth)->format('F Y') }} Attendance
        </th>
    </tr>
    <tr>
        <th>Employee Name</th>
        @foreach (CarbonPeriod::create(Carbon::parse($selectedMonth)->startOfMonth(), Carbon::parse($selectedMonth)->endOfMonth()) as $day)
            <th>{{ $day->format('d') }}</th>
        @endforeach
        <th>Total Present</th>
        <th>Total Absent</th>
        <th>Total Leave</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($groupedData as $employeeId => $attendanceGroup)
        <tr>
            <td>{{ $employeeNames[$employeeId] }}</td>
            @php
                $presentCount = 0;
                $absentCount = 0;
                $leaveCount = 0;
            @endphp
            @foreach (CarbonPeriod::create(Carbon::parse($selectedMonth)->startOfMonth(), Carbon::parse($selectedMonth)->endOfMonth()) as $day)
                @php
                    $attendance = $attendanceGroup->where('date', $day->format('Y-m-d'))->first();
                    if ($attendance) {
                        if ($attendance->present) {
                            $presentCount++;
                        } else {
                            if ($attendance->leave) {
                                $leaveCount++;
                            } else {
                                $absentCount++;
                            }
                        }
                    }
                @endphp
                <td>{{ $attendance ? ($attendance->present ? 'P' : ($attendance->leave ? 'L' : 'A')) : '-' }}</td>
            @endforeach
            <td>{{ $presentCount }}</td>
            <td>{{ $absentCount }}</td>
            <td>{{ $leaveCount }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
