@php
    use Carbon\Carbon;
    
    // Year and month from the first date
    $firstDate = Carbon::parse($presence_list[0]);
    $year = $firstDate->year;
    $month = $firstDate->month;

    // Collection presence
    $presenceDates = collect($presence_list)->map(function ($date) {
        return Carbon::parse($date)->format('Y-m-d');
    });

    $startOfMonth = Carbon::create($year, $month, 1);
    $endOfMonth = $startOfMonth->copy()->endOfMonth();
    $startDay = $startOfMonth->startOfMonth()->dayOfWeek;
    $daysInMonth = $endOfMonth->day;
@endphp


<div class="container">
    <div class="calendar-header">
        <h5>{{ Carbon::create($year, $month, 1)->format('F Y') }}</h5>
    </div>
    <div class="calendar">
        <!-- Weekday Headers -->
        <div class="calendar-day">Min</div>
        <div class="calendar-day">Sen</div>
        <div class="calendar-day">Sel</div>
        <div class="calendar-day">Rab</div>
        <div class="calendar-day">Kam</div>
        <div class="calendar-day">Jum</div>
        <div class="calendar-day">Sab</div>

        <!-- Empty slots -->
        @for ($i = 0; $i < $startDay; $i++)
            <div class="calendar-day empty"></div>
        @endfor

        <!-- Days of the month -->
        @for ($day = 1; $day <= $daysInMonth; $day++)
            @php
                $currentDate = Carbon::create($year, $month, $day)->format('Y-m-d');
                $isHighlighted = $presenceDates->contains($currentDate);
            @endphp
            <div class="calendar-day {{ $isHighlighted ? 'highlighted-day' : '' }}">
                {{ $day }}
            </div>
        @endfor
    </div>
</div>

