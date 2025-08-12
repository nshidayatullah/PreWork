<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pemeriksaan Awal Shift Karyawan - {{ $employee->Name }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .employee-info { background-color: #f8f9fa; padding: 10px; margin-bottom: 20px; }
        .shift-s1 { background-color: #dbeafe; color: #1e40af; padding: 2px 6px; border-radius: 3px; }
        .shift-s2 { background-color: #d1fae5; color: #059669; padding: 2px 6px; border-radius: 3px; }
        .shift-off { background-color: #fef3c7; color: #d97706; padding: 2px 6px; border-radius: 3px; }
        .bp-normal { background-color: #dcfce7; color: #166534; padding: 2px 6px; border-radius: 3px; }
        .bp-high { background-color: #fecaca; color: #dc2626; padding: 2px 6px; border-radius: 3px; }
        .today { background-color: #f0fdf4; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN KARYAWAN</h1>
        <h2>Periode: {{ $period }}</h2>
    </div>

    <div class="employee-info">
        <strong>Nama:</strong> {{ $employee->Name }} &nbsp;&nbsp;
        <strong>NRP:</strong> {{ $employee->NRP }} &nbsp;&nbsp;
        <strong>Jabatan:</strong> {{ $employee->Position }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Shift</th>
                <th>Tekanan Darah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rosters as $roster)
            @php
                $bloodPressure = $bloodPressures->get($roster->date->format('Y-m-d'));
                $isToday = \Carbon\Carbon::parse($roster->date)->isToday();
                $tanggal = \Carbon\Carbon::parse($roster->date)->locale('id')->isoFormat('DD MMMM YYYY');
            @endphp
            <tr class="{{ $isToday ? 'today' : '' }}">
                <td>{{ $tanggal }} {{ $isToday ? '(Hari Ini)' : '' }}</td>
                <td>
                    @if($roster->shift == 'shift 1')
                        <span class="shift-s1">Shift1</span>
                    @elseif($roster->shift == 'shift 2')
                        <span class="shift-s2">Shift 2</span>
                    @else
                        <span class="shift-off">{{ $roster->shift }}</span>
                    @endif
                </td>
                <td>
                    @if($bloodPressure)
                        <span class="bp-normal">{{ $bloodPressure->sistole }}/{{ $bloodPressure->diastole }}</span>
                    @else
                        @if(in_array($roster->shift, ['shift 1', 'shift 2']))
                            Belum diperiksa
                        @else
                            -
                        @endif
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top: 30px; text-align: center; font-size: 10px;">
        Laporan digenerate pada: {{ $generatedAt }}
    </p>
</body>
</html>
